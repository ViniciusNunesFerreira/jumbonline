<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('order.stalled_order_days_threshold', 2);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('order.stalled_order_days_threshold');
    }
};