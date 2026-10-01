<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shipping.estimated_density', 0.22);
        $this->migrator->add('shipping.packaging_margin_percent', 12);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('shipping.estimated_density');
        $this->migrator->deleteIfExists('shipping.packaging_margin_percent');
    }
};