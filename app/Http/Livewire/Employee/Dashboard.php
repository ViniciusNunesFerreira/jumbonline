<?php

namespace App\Http\Livewire\Employee;

use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Variant;
use App\Services\StalledOrderService;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Livewire\Component;

class Dashboard extends Component
{
    public $periods = 7;

    /**
     * Total de pedidos pagos há mais dias que o limite configurado sem
     * nenhum envio criado — mesma regra de OrderList (Etapa 2), reaproveitada
     * aqui sem alteração nenhuma no serviço.
     */
    public function getStalledOrdersCountProperty()
    {
        return app(StalledOrderService::class)->count();
    }

    /**
     * Variantes com controle de estoque ativo e quantidade abaixo do
     * limite (Variant::is_low_stock, o mesmo accessor já usado pelo card
     * "Estoque baixo" mais abaixo nesta página). Só os campos necessários
     * pro cálculo são selecionados, pra não carregar a variante inteira só
     * pra contar.
     */
    public function getLowStockCountProperty()
    {
        return Variant::query()
            ->where('stock_tracking', true)
            ->get(['id', 'stock_tracking', 'stock_value', 'low_stock_threshold'])
            ->filter(fn ($variant) => $variant->is_low_stock)
            ->count();
    }

    /**
     * Mesma definição de "carrinho abandonado" de AbandonedCartList (2h+
     * sem atividade, com cliente e itens, ainda não contatado), restrita
     * às últimas 24h — carrinhos abandonados há mais tempo que isso já
     * aparecem na lista completa, mas não precisam de atenção "hoje".
     */
    public function getAbandonedCartsTodayCountProperty()
    {
        return Cart::query()
            ->whereNotNull('customer_id')
            ->whereNull('contacted_at')
            ->whereHas('items')
            ->whereBetween('updated_at', [now()->subHours(24), now()->subHours(2)])
            ->count();
    }

    /**
     * Contas a pagar (não pagas) vencendo entre hoje e os próximos 7 dias.
     * Só consulta se o funcionário é admin — quem não tem acesso a Contas a
     * Pagar não precisa que essa consulta nem rode.
     */
    public function getExpensesDueSoonProperty()
    {
        if (! auth('employee')->user()?->can('admin')) {
            return null;
        }

        return Expense::query()
            ->dueBetween(today(), today()->addDays(7))
            ->selectRaw('count(*) as total_count, coalesce(sum(amount), 0) as total_amount')
            ->first();
    }

    /**
     * Contas a pagar (não pagas) com vencimento já passado.
     */
    public function getExpensesOverdueProperty()
    {
        if (! auth('employee')->user()?->can('admin')) {
            return null;
        }

        return Expense::query()
            ->overdue()
            ->selectRaw('count(*) as total_count, coalesce(sum(amount), 0) as total_amount')
            ->first();
    }

    public function getOrdersCountProperty()
    {
        return Order::query()->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])->count();
    }

    public function getDailyOrdersProperty()
    {
        $periods = new DatePeriod(Carbon::now()->subDays($this->periods)->startOfDay(), CarbonInterval::day(), Carbon::now()->endOfDay());

        $orders = Order::query()
            ->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get([
                \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'),
                \DB::raw('count(*) as orders_count'),
            ])
            ->keyBy('day')
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date);
                return $item;
            });

        return array_map(function ($datePeriod) use ($orders) {
            $date = $datePeriod->format('Y-m-d');
            return $orders->has($date) ? $orders->get($date)->orders_count : 0;
        }, iterator_to_array($periods));
    }

    public function getOrderItemsCountProperty()
    {
        return OrderItem::query()->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])->count();
    }

    public function getDailyOrderItemsProperty()
    {
        $periods = new DatePeriod(Carbon::now()->subDays($this->periods)->startOfDay(), CarbonInterval::day(), Carbon::now()->endOfDay());

        $orderItems = OrderItem::query()
            ->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get([
                \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'),
                \DB::raw('count(*) as order_items_count'),
            ])
            ->keyBy('day')
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date);
                return $item;
            });

        return array_map(function ($datePeriod) use ($orderItems) {
            $date = $datePeriod->format('Y-m-d');
            return $orderItems->has($date) ? $orderItems->get($date)->order_items_count : 0;
        }, iterator_to_array($periods));
    }

    public function getProductsCountProperty()
    {
        return Product::query()->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])->count();
    }

    public function getDailyProductsProperty()
    {
        $periods = new DatePeriod(Carbon::now()->subDays($this->periods)->startOfDay(), CarbonInterval::day(), Carbon::now()->endOfDay());

        $products = Product::query()
            ->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get([
                \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'),
                \DB::raw('count(*) as products_count'),
            ])
            ->keyBy('day')
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date);
                return $item;
            });

        return array_map(function ($datePeriod) use ($products) {
            $date = $datePeriod->format('Y-m-d');
            return $products->has($date) ? $products->get($date)->products_count : 0;
        }, iterator_to_array($periods));
    }

    public function getCustomersCountProperty()
    {
        return Customer::query()->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])->count();
    }

    public function getDailyCustomersProperty()
    {
        $periods = new DatePeriod(Carbon::now()->subDays($this->periods)->startOfDay(), CarbonInterval::day(), Carbon::now()->endOfDay());

        $customers = Customer::query()
            ->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get([
                \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'),
                \DB::raw('count(*) as customers_count'),
            ])
            ->keyBy('day')
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date);
                return $item;
            });

        return array_map(function ($datePeriod) use ($customers) {
            $date = $datePeriod->format('Y-m-d');
            return $customers->has($date) ? $customers->get($date)->customers_count : 0;
        }, iterator_to_array($periods));
    }

    /**
     * CORRIGIDO: antes somava order_items.subtotal de QUALQUER pedido
     * (carrinho abandonado, pendente, cancelado incluídos). Agora soma
     * payments.amount com status PAID — receita real recebida.
     */
    public function getDailySalesReportProperty()
    {
        $periods = new DatePeriod(Carbon::now()->subDays($this->periods)->startOfDay(), CarbonInterval::day(), Carbon::now()->endOfDay());

        $days = array_map(fn($period) => $period->format('Y-m-d'), iterator_to_array($periods));

        $sales = \DB::table('payments')
            ->where('status', PaymentStatus::PAID->name)
            ->whereBetween('created_at', [Carbon::now()->subDays($this->periods)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get([
                \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as day'),
                \DB::raw('SUM(amount) as total'),
            ])
            ->keyBy('day');

        $dailySale = array_map(function ($date) use ($sales) {
            return $sales->has($date) ? (float) $sales->get($date)->total : 0;
        }, $days);

        return ['days' => $days, 'sales' => $dailySale];
    }

    public function getRecentOrdersProperty()
    {
        return Order::query()
            ->with(['customer', 'orderItems', 'orderDiscounts'])
            ->latest()
            ->limit(5)
            ->get();
    }

    public function getTopSellingProductsProperty()
    {
        return \DB::table('order_items')
            ->crossJoin('products')
            ->whereRaw('order_items.product_id = products.id')
            ->select('products.id', 'products.name', 'products.slug')
            ->selectRaw('sum(subtotal) as total_sales')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByRaw('total_sales DESC')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.employee.dashboard')->layout('layouts.admin');
    }
}