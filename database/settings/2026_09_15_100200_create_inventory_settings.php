<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('inventory.default_low_stock_threshold', 5);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('inventory.default_low_stock_threshold');
    }
};