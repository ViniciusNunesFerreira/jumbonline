<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('brand.slogan', 'Modernize Your Home, Simplify Your Day with Our Appliances and Gadgets.');
        $this->migrator->add('brand.short_description', 'Discover a vast selection of top-notch home appliances and electronic gadgets. Elevate your living with innovative, energy-efficient solutions.');
        $this->migrator->add('brand.logo_path', '');
        $this->migrator->add('brand.favicon_path', '');
        $this->migrator->add('brand.cover_path', '');
        $this->migrator->add('brand.social_links', [
            [
                'name' => 'Facebook',
                'url' => '',
                'url_placeholder' => 'https://facebook.com/cartify',
            ],
            [
                'name' => 'Twitter',
                'url' => '',
                'url_placeholder' => 'https://twitter.com/cartify',
            ],
            [
                'name' => 'Pinterest',
                'url' => '',
                'url_placeholder' => 'https://pinterest.com/cartify',
            ],
            [
                'name' => 'Instagram',
                'url' => '',
                'url_placeholder' => 'https://instagram.com/cartify',
            ],
            [
                'name' => 'TikTok',
                'url' => '',
                'url_placeholder' => 'https://tiktok.com/@cartify',
            ],
            [
                'name' => 'Tumblr',
                'url' => '',
                'url_placeholder' => 'https://cartify.tumblr.com',
            ],
            [
                'name' => 'Snapchat',
                'url' => '',
                'url_placeholder' => 'https://snapchat.com/add/cartify',
            ],
            [
                'name' => 'YouTube',
                'url' => '',
                'url_placeholder' => 'https://youtube.com/c/cartify',
            ],
            [
                'name' => 'Vimeo',
                'url' => '',
                'url_placeholder' => 'https://vimeo.com/cartify',
            ],
        ]);
    }

    public function down()
    {
        $this->migrator->deleteIfExists('brand.slogan');
        $this->migrator->deleteIfExists('brand.short_description');
        $this->migrator->deleteIfExists('brand.logo_path');
        $this->migrator->deleteIfExists('brand.favicon_path');
        $this->migrator->deleteIfExists('brand.cover_path');
        $this->migrator->deleteIfExists('brand.socials');
    }
};
