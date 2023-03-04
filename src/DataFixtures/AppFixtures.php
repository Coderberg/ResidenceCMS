<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Settings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (self::getData() as [$setting_name, $setting_value]) {
            $setting = new Settings();
            $setting->setSettingName($setting_name);
            $setting->setSettingValue($setting_value);
            $manager->persist($setting);
        }
        $manager->flush();
    }

    public static function getData(): array
    {
        return [
            // $data = [$setting_name, $setting_value];
            ['name', 'Site name'],
            ['title', 'Popular Listing'],
            ['meta_title', 'Site Title'],
            ['meta_description', 'Site Description'],
            ['custom_code', ''],
            ['custom_footer_text', 'All Rights Reserved.'],
            ['items_per_page', '6'],
            ['ymaps_key', ''],
            ['map_center', '27.188534, -81.128735'],
            ['map_zoom', '7'],
            ['currency_id', '1'],
            ['header_image', ''],
            ['logo_image', ''],
            ['fixed_top_navbar', '0'],
            ['show_similar_properties', '0'],
            ['show_filter_by_city', '1'],
            ['show_filter_by_deal_type', '1'],
            ['show_filter_by_category', '1'],
            ['show_filter_by_features', '0'],
            ['show_filter_by_bedrooms', '0'],
            ['show_filter_by_guests', '0'],
            ['show_language_selector', '1'],
            ['anyone_can_register', '0'],
            ['allow_html', '1'],
            ['show_bottom_bar', '1'],
        ];
    }
}
