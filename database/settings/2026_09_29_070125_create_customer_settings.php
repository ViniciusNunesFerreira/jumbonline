<?php


use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('customer.frequent_customer_min_orders', 3);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('customer.frequent_customer_min_orders');
    }
};