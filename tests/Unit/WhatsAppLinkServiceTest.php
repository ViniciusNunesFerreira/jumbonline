<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Order;
use App\Services\WhatsAppLinkService;
use Tests\TestCase;

/**
 * Cobre WhatsAppLinkService, que gera o link wa.me com mensagem
 * pré-preenchida para os botões de WhatsApp em Cliente e Pedido. Não acessa
 * banco de dados: Customer e Order são construídos em memória, com o
 * telefone hidratado via setRawAttributes() — do jeito que o Eloquent
 * povoa um model a partir de uma linha real do banco, o que faz o cast
 * E164PhoneNumberCast funcionar normalmente na leitura.
 */
class WhatsAppLinkServiceTest extends TestCase
{
    private WhatsAppLinkService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new WhatsAppLinkService();
    }

    private function makeCustomer(?string $rawPhone, string $name = 'Maria Silva', ?int $id = 1): Customer
    {
        $customer = new Customer();
        $customer->setRawAttributes([
            'id' => $id,
            'name' => $name,
            'phone' => $rawPhone,
        ], true);

        return $customer;
    }

    public function test_customer_link_uses_only_digits_and_the_first_name(): void
    {
        $customer = $this->makeCustomer('+5515999998888', 'Maria Silva');

        $url = $this->service->forCustomer($customer);

        $this->assertStringStartsWith('https://wa.me/5515999998888?text=', $url);
        $this->assertStringContainsString('Olá Maria, aqui é da Jumbonline.', urldecode($url));
    }

    public function test_customer_without_phone_returns_no_link(): void
    {
        $customer = $this->makeCustomer(null);

        $this->assertNull($this->service->forCustomer($customer));
    }

    public function test_customer_with_invalid_phone_data_returns_no_link_instead_of_throwing(): void
    {
        $customer = $this->makeCustomer('not-a-phone-number');

        $this->assertNull($this->service->forCustomer($customer));
    }

    public function test_single_word_name_is_used_whole_as_the_first_name(): void
    {
        $customer = $this->makeCustomer('+5515999998888', 'Madonna');

        $url = $this->service->forCustomer($customer);

        $this->assertStringContainsString('Olá Madonna,', urldecode($url));
    }

    public function test_order_link_mentions_the_order_id(): void
    {
        $customer = $this->makeCustomer('+5515999998888', 'João Souza');

        $order = new Order();
        $order->forceFill(['id' => 4821]);
        $order->setRelation('customer', $customer);

        $url = $this->service->forOrder($order);

        $decoded = urldecode($url);
        $this->assertStringContainsString('João', $decoded);
        $this->assertStringContainsString('pedido #4821', $decoded);
    }

    public function test_order_without_loaded_customer_relation_returns_no_link(): void
    {
        $order = new Order();
        $order->forceFill(['id' => 1]);

        $this->assertNull($this->service->forOrder($order));
    }

    public function test_order_with_customer_relation_explicitly_null_returns_no_link(): void
    {
        $order = new Order();
        $order->forceFill(['id' => 1]);
        $order->setRelation('customer', null);

        $this->assertNull($this->service->forOrder($order));
    }
}