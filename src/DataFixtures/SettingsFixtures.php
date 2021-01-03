<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Settings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class SettingsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as [$setting_name, $setting_value]) {
            $setting = new Settings();
            $setting->setSettingName($setting_name);
            $setting->setSettingValue($setting_value);
            $manager->persist($setting);
        }
        $manager->flush();
    }

    private function getData(): array
    {
        return [
            // $data = [$setting_name, $setting_value];
            ['name', 'Имоти Варна'],
            ['title', 'Популярни имоти'],
            ['meta_title', 'Имоти Варна'],
            ['meta_description', 'Site Description'],
            ['custom_code', ''],
            ['custom_footer_text', 'Всички права запазени.'],
            ['items_per_page', '6'],
            ['ymaps_key', ''],
            ['map_center', '43.2048197, 27.872869'],
            ['map_zoom', '12'],
            ['currency_id', '1'],
            ['header_image', ''],
            ['fixed_top_navbar', '1'],
            ['show_similar_properties', '0'],
            ['show_filter_by_city', '1'],
            ['show_filter_by_district', '1'],
            ['show_filter_by_neighborhood', '1'],
            ['show_filter_by_deal_type', '1'],
            ['show_filter_by_category', '1'],
            ['show_filter_by_bedrooms', '1'],
            ['show_filter_by_guests', '0'],
        ];
    }
}
