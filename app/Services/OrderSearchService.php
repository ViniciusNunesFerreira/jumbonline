<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Busca ampliada de pedidos (painel administrativo).
 *
 * Interpreta o termo digitado pelo atendente e decide automaticamente
 * por qual caminho buscar:
 *
 *  - "#1234"                       → número exato do pedido
 *  - "1234" (até 7 dígitos puros)  → número do pedido (comportamento legado)
 *                                    OU final do telefone do cliente (4+ dígitos)
 *  - "(15) 99999-8888", "15999998888", "+55 15 ..." → telefone do cliente
 *  - "AB123456789BR" (ou prefixo "AB1234...")      → código de rastreio
 *  - qualquer outro texto          → nome do cliente (todas as palavras) ou e-mail
 *
 * Premissas do schema (verificadas no código atual):
 *  - customers.phone é gravado em E.164 ("+5515999998888") via E164PhoneNumberCast;
 *    registros legados com máscara também são encontrados (a comparação ignora formatação).
 *  - Collation utf8mb4_unicode_ci: LIKE já é insensível a caixa e acentos no MySQL.
 *  - orders.customer_email cobre pedidos sem cliente vinculado (ex.: balcão).
 *
 * Todas as buscas em tabelas relacionadas são feitas via subquery "whereIn"
 * (semi-join), sem JOIN — não altera as colunas selecionadas de orders,
 * não duplica linhas e preserva o comportamento de paginação e do WithBulkActions.
 */
class OrderSearchService
{
    public const TYPE_ORDER_ID = 'order_id';

    public const TYPE_NUMERIC = 'numeric';

    public const TYPE_PHONE = 'phone';

    public const TYPE_TRACKING = 'tracking';

    public const TYPE_TEXT = 'text';

    /**
     * Quantidade mínima de dígitos para tratar um número como (parte de) telefone.
     * Abaixo disso a busca casaria com praticamente toda a base de clientes.
     */
    private const MIN_PHONE_DIGITS = 4;

    /**
     * Números puros com até esta quantidade de dígitos são tratados primeiro como
     * número de pedido (os IDs começam em 1001), com fallback para final de telefone.
     */
    private const MAX_ORDER_ID_DIGITS = 7;

    /**
     * Expressão SQL que remove a formatação comum de telefone (+, espaço, -, parênteses, ponto).
     */
    private const PHONE_DIGITS_SQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone, '+', ''), ' ', ''), '-', ''), '(', ''), ')', ''), '.', '')";

    /**
     * Interpreta o termo de busca.
     *
     * @return array{type: string, value: string, tokens: array<int, string>, exact: bool, label: string, icon: string}|null
     */
    public function interpret(?string $raw): ?array
    {
        $term = trim(preg_replace('/\s+/u', ' ', (string) $raw));

        if ($term === '') {
            return null;
        }

        // 1) "#1234" → número exato do pedido
        if (preg_match('/^#\s*(\d+)$/', $term, $matches)) {
            return $this->result(self::TYPE_ORDER_ID, $matches[1], exact: true);
        }

        // 2) Código de rastreio (padrão Correios: 2 letras + 9 dígitos + 2 letras)
        $compact = strtoupper(str_replace([' ', '-', '.'], '', $term));

        if (preg_match('/^[A-Z]{2}\d{9}[A-Z]{2}$/', $compact)) {
            return $this->result(self::TYPE_TRACKING, $compact, exact: true);
        }

        if (preg_match('/^[A-Z]{2}\d{3,9}[A-Z]{0,2}$/', $compact)) {
            return $this->result(self::TYPE_TRACKING, $compact, exact: false);
        }

        // 3) Numérico / telefone
        if (preg_match('/^[\d\s().+\-]+$/', $term)) {
            $digits = preg_replace('/\D/', '', $term);

            if ($digits === '') {
                return null;
            }

            $hasPhoneFormatting = (bool) preg_match('/[()+\-\s]/', $term);

            if (! $hasPhoneFormatting && strlen($digits) <= self::MAX_ORDER_ID_DIGITS) {
                return $this->result(self::TYPE_NUMERIC, $digits);
            }

            // Remove prefixo de operadora/tronco ("0 15 9999...") que não existe no E.164
            $phoneDigits = ltrim($digits, '0');

            if (strlen($phoneDigits) >= self::MIN_PHONE_DIGITS) {
                return $this->result(self::TYPE_PHONE, $phoneDigits);
            }

            return $this->result(self::TYPE_NUMERIC, $digits);
        }

        // 4) Texto livre → nome (todas as palavras, em qualquer ordem) ou e-mail
        $tokens = array_values(array_filter(
            explode(' ', $term),
            fn (string $token) => $token !== ''
        ));

        return $this->result(self::TYPE_TEXT, $term, tokens: $tokens);
    }

    /**
     * Aplica a busca na query de pedidos. Não faz nada se o termo for vazio.
     */
    public function apply(Builder $query, ?string $raw): Builder
    {
        $term = $this->interpret($raw);

        if (! $term) {
            return $query;
        }

        $idColumn = $query->qualifyColumn('id');
        $customerColumn = $query->qualifyColumn('customer_id');
        $value = $term['value'];

        switch ($term['type']) {
            case self::TYPE_ORDER_ID:
                $query->where($idColumn, (int) $value);
                break;

            case self::TYPE_NUMERIC:
                $query->where(function (Builder $q) use ($idColumn, $customerColumn, $value) {
                    // Mantém o comportamento legado da tela (id LIKE %termo%)
                    $q->where($idColumn, 'like', '%' . $this->escapeLike($value) . '%');

                    if (strlen($value) >= self::MIN_PHONE_DIGITS) {
                        $q->orWhereIn($customerColumn, $this->customerIdsByPhone($value, suffixOnly: true));
                    }
                });

                // Relevância: pedido com número exato aparece primeiro
                $query->orderByRaw("CASE WHEN {$idColumn} = ? THEN 0 ELSE 1 END", [(int) $value]);
                break;

            case self::TYPE_PHONE:
                $query->whereIn($customerColumn, $this->customerIdsByPhone($value));
                break;

            case self::TYPE_TRACKING:
                $query->whereIn($idColumn, $this->orderIdsByTracking($value, $term['exact']));
                break;

            case self::TYPE_TEXT:
                $emailColumn = $query->qualifyColumn('customer_email');
                $like = '%' . $this->escapeLike($value) . '%';

                $query->where(function (Builder $q) use ($customerColumn, $emailColumn, $term, $like) {
                    $q->whereIn($customerColumn, $this->customerIdsByNameOrEmail($term['tokens'], $term['value']))
                        ->orWhere($emailColumn, 'like', $like);
                });
                break;
        }

        return $query;
    }

    /**
     * Retorna os rótulos de "encontrado por" de um pedido já carregado.
     * Usa somente relações já carregadas (lazy loading estrito está habilitado).
     *
     * @return array<int, string>
     */
    public function matchReasons(Order $order, ?string $raw): array
    {
        $term = $this->interpret($raw);

        if (! $term) {
            return [];
        }

        $customer = $order->relationLoaded('customer') ? $order->customer : null;
        $value = $term['value'];
        $reasons = [];

        switch ($term['type']) {
            case self::TYPE_ORDER_ID:
                $reasons[] = __('Nº do pedido');
                break;

            case self::TYPE_NUMERIC:
                if (str_contains((string) $order->id, $value)) {
                    $reasons[] = __('Nº do pedido');
                }

                if (strlen($value) >= self::MIN_PHONE_DIGITS && str_ends_with($this->customerPhoneDigits($customer), $value)) {
                    $reasons[] = __('Final do telefone');
                }
                break;

            case self::TYPE_PHONE:
                if ($value !== '' && str_contains($this->customerPhoneDigits($customer), $value)) {
                    $reasons[] = __('Telefone');
                }
                break;

            case self::TYPE_TRACKING:
                if ($order->relationLoaded('shipments')) {
                    $found = $order->shipments->contains(function ($shipment) use ($term, $value) {
                        $tracking = strtoupper((string) $shipment->tracking_number);

                        return $term['exact'] ? $tracking === $value : str_starts_with($tracking, $value);
                    });

                    if ($found) {
                        $reasons[] = __('Rastreio');
                    }
                }
                break;

            case self::TYPE_TEXT:
                if ($customer && $this->containsAllTokens((string) $customer->name, $term['tokens'])) {
                    $reasons[] = __('Nome');
                }

                $needle = $this->normalize($value);
                $emails = array_filter([$customer?->email, $order->customer_email]);

                foreach ($emails as $email) {
                    if (str_contains($this->normalize((string) $email), $needle)) {
                        $reasons[] = __('E-mail');
                        break;
                    }
                }
                break;
        }

        return array_values(array_unique($reasons));
    }

    /**
     * Formata o telefone do cliente para exibição, sem nunca lançar exceção
     * (números legados inválidos no banco não podem quebrar a listagem).
     */
    public function displayPhone(?Customer $customer): ?string
    {
        if (! $customer) {
            return null;
        }

        $raw = $customer->getRawOriginal('phone');

        if (! $raw) {
            return null;
        }

        try {
            $phone = $customer->phone;

            return $phone ? $phone->formatNational() : $raw;
        } catch (\Throwable $e) {
            return $raw;
        }
    }

    /**
     * @return array{type: string, value: string, tokens: array<int, string>, exact: bool, label: string, icon: string}
     */
    private function result(string $type, string $value, bool $exact = false, array $tokens = []): array
    {
        [$label, $icon] = match ($type) {
            self::TYPE_ORDER_ID => [__('Nº do pedido'), 'heroicon-m-hashtag'],
            self::TYPE_NUMERIC => strlen($value) >= self::MIN_PHONE_DIGITS
                ? [__('Nº do pedido ou final do telefone'), 'heroicon-m-hashtag']
                : [__('Nº do pedido'), 'heroicon-m-hashtag'],
            self::TYPE_PHONE => [__('Telefone do cliente'), 'heroicon-m-phone'],
            self::TYPE_TRACKING => [$exact ? __('Código de rastreio') : __('Início do código de rastreio'), 'heroicon-m-truck'],
            default => str_contains($value, '@')
                ? [__('E-mail do cliente'), 'heroicon-m-at-symbol']
                : [__('Nome ou e-mail do cliente'), 'heroicon-m-user'],
        };

        return [
            'type' => $type,
            'value' => $value,
            'tokens' => $tokens,
            'exact' => $exact,
            'label' => $label,
            'icon' => $icon,
        ];
    }

    /**
     * Busca por dígitos contíguos do telefone.
     *
     * O cadastro atual grava em E.164 ("+5515999998888"), mas registros legados
     * anteriores ao E164PhoneNumberCast podem conter máscara ("(15) 99999-8888").
     * Por isso a comparação é feita sobre o telefone sem formatação. Como o LIKE
     * já começa com curinga, nenhum índice seria usado de qualquer forma — o
     * REPLACE não piora o plano de execução.
     */
    private function customerIdsByPhone(string $digits, bool $suffixOnly = false): Builder
    {
        $escaped = $this->escapeLike($digits);

        return Customer::query()
            ->select('id')
            ->whereNotNull('phone')
            ->whereRaw(
                self::PHONE_DIGITS_SQL . ' like ?',
                [$suffixOnly ? "%{$escaped}" : "%{$escaped}%"]
            );
    }

    private function customerIdsByNameOrEmail(array $tokens, string $term): Builder
    {
        return Customer::query()
            ->select('id')
            ->where(function (Builder $q) use ($tokens, $term) {
                $q->where(function (Builder $nameQuery) use ($tokens) {
                    foreach ($tokens as $token) {
                        $nameQuery->where('name', 'like', '%' . $this->escapeLike($token) . '%');
                    }
                })->orWhere('email', 'like', '%' . $this->escapeLike($term) . '%');
            });
    }

    private function orderIdsByTracking(string $tracking, bool $exact): Builder
    {
        return Shipment::query()
            ->select('order_id')
            ->when(
                $exact,
                fn (Builder $q) => $q->where('tracking_number', $tracking),
                // Prefixo (sem % à esquerda) permite uso do índice em tracking_number
                fn (Builder $q) => $q->where('tracking_number', 'like', $this->escapeLike($tracking) . '%')
            );
    }

    private function customerPhoneDigits(?Customer $customer): string
    {
        if (! $customer) {
            return '';
        }

        return preg_replace('/\D/', '', (string) $customer->getRawOriginal('phone'));
    }

    private function containsAllTokens(string $haystack, array $tokens): bool
    {
        if (! $tokens) {
            return false;
        }

        $normalized = $this->normalize($haystack);

        foreach ($tokens as $token) {
            if (! str_contains($normalized, $this->normalize($token))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Normalização equivalente à collation utf8mb4_unicode_ci (caixa e acentos),
     * usada apenas para montar os rótulos de "encontrado por" na interface.
     */
    private function normalize(string $value): string
    {
        return Str::lower(Str::ascii($value));
    }

    /**
     * Escapa curingas do LIKE (% e _) digitados pelo usuário.
     */
    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}