<?php

namespace Tests\Unit;

use App\Services\OrderSearchService;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Cobre a interpretação do termo da busca ampliada de pedidos.
 * Não acessa banco de dados.
 */
class OrderSearchServiceTest extends TestCase
{
    private OrderSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new OrderSearchService();
    }

    public function test_empty_terms_are_ignored(): void
    {
        $this->assertNull($this->service->interpret(''));
        $this->assertNull($this->service->interpret('   '));
        $this->assertNull($this->service->interpret(null));
        $this->assertNull($this->service->interpret('()'));
    }

    public static function termProvider(): array
    {
        return [
            'número exato com #' => ['#1234', OrderSearchService::TYPE_ORDER_ID, '1234', true],
            'número exato com # e espaço' => ['# 1234', OrderSearchService::TYPE_ORDER_ID, '1234', true],
            'número curto (legado)' => ['12', OrderSearchService::TYPE_NUMERIC, '12', false],
            'número de 4 dígitos' => ['1234', OrderSearchService::TYPE_NUMERIC, '1234', false],
            'telefone só dígitos' => ['15999998888', OrderSearchService::TYPE_PHONE, '15999998888', false],
            'telefone com máscara' => ['(15) 99999-8888', OrderSearchService::TYPE_PHONE, '15999998888', false],
            'telefone com DDI' => ['+55 15 99999-8888', OrderSearchService::TYPE_PHONE, '5515999998888', false],
            'telefone parcial com hífen' => ['9999-8888', OrderSearchService::TYPE_PHONE, '99998888', false],
            'telefone com zero de tronco' => ['015 99999 8888', OrderSearchService::TYPE_PHONE, '15999998888', false],
            'rastreio completo' => ['AB123456789BR', OrderSearchService::TYPE_TRACKING, 'AB123456789BR', true],
            'rastreio minúsculo com espaços' => ['ab 123 456 789 br', OrderSearchService::TYPE_TRACKING, 'AB123456789BR', true],
            'início do rastreio' => ['AB1234', OrderSearchService::TYPE_TRACKING, 'AB1234', false],
            'nome' => ['Maria  da Silva', OrderSearchService::TYPE_TEXT, 'Maria da Silva', false],
            'e-mail' => ['joao@gmail.com', OrderSearchService::TYPE_TEXT, 'joao@gmail.com', false],
            'nome com acento' => ['José', OrderSearchService::TYPE_TEXT, 'José', false],
            'alfanumérico curto não é rastreio' => ['ab12', OrderSearchService::TYPE_TEXT, 'ab12', false],
        ];
    }

    #[DataProvider('termProvider')]
    public function test_interprets_terms(string $input, string $type, string $value, bool $exact): void
    {
        $result = $this->service->interpret($input);

        $this->assertNotNull($result);
        $this->assertSame($type, $result['type']);
        $this->assertSame($value, $result['value']);
        $this->assertSame($exact, $result['exact']);
        $this->assertNotEmpty($result['label']);
        $this->assertStringStartsWith('heroicon-m-', $result['icon']);
    }

    public function test_text_terms_are_split_into_tokens(): void
    {
        $result = $this->service->interpret('  Maria   da Silva ');

        $this->assertSame(['Maria', 'da', 'Silva'], $result['tokens']);
    }
}