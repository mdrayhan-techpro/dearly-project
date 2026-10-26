<?php

namespace Database\Seeders;

use App\Models\PostageStamp;
use App\Models\StationeryTheme;
use App\Models\WaxSeal;
use Illuminate\Database\Seeder;

class StationerySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Stationery Themes
        $themes = [
            [
                'slug' => 'classic-ivory',
                'name' => 'Classic Ivory',
                'background_css' => '#FAF6EE',
                'text_color' => '#342A25',
                'border_style' => '1px solid #E5D8C3',
                'order_index' => 1,
            ],
            [
                'slug' => 'old-library',
                'name' => 'Old Library',
                'background_css' => '#F4EBD9',
                'text_color' => '#2A201C',
                'border_style' => '2px solid #D7C4A5',
                'order_index' => 2,
            ],
            [
                'slug' => 'vintage-rose',
                'name' => 'Vintage Rose',
                'background_css' => '#FAF0EF',
                'text_color' => '#482B30',
                'border_style' => '1px dashed #D9B3B8',
                'order_index' => 3,
            ],
            [
                'slug' => 'forest-letter',
                'name' => 'Forest Letter',
                'background_css' => '#F0F5F1',
                'text_color' => '#1C2E24',
                'border_style' => '1px solid #C1D4C7',
                'order_index' => 4,
            ],
            [
                'slug' => 'blue-airmail',
                'name' => 'Blue Airmail',
                'background_css' => '#F0F4F8',
                'text_color' => '#1E303D',
                'border_style' => '3px double #9BB2C4',
                'order_index' => 5,
            ],
            [
                'slug' => 'midnight-correspondence',
                'name' => 'Midnight Correspondence',
                'background_css' => '#2B303A',
                'text_color' => '#F2EFEB',
                'border_style' => '1px solid #4B5263',
                'order_index' => 6,
            ],
            [
                'slug' => 'botanical-garden',
                'name' => 'Botanical Garden',
                'background_css' => '#F3F6F1',
                'text_color' => '#273629',
                'border_style' => '1px dashed #A9C2A4',
                'order_index' => 7,
            ],
            [
                'slug' => 'minimal-parchment',
                'name' => 'Minimal Parchment',
                'background_css' => '#FAF9F6',
                'text_color' => '#2D2A28',
                'border_style' => '1px solid #E0DCD6',
                'order_index' => 8,
            ],
        ];

        foreach ($themes as $theme) {
            StationeryTheme::updateOrCreate(['slug' => $theme['slug']], $theme);
        }

        // 2. Postage Stamps
        $stamps = [
            [
                'slug' => 'first-letter',
                'name' => 'First Letter Quill',
                'theme_category' => 'nostalgia',
                'image_svg_path' => 'stamps/quill.svg',
                'order_index' => 1,
            ],
            [
                'slug' => 'midnight-blue',
                'name' => 'Midnight Moon & Stars',
                'theme_category' => 'celestial',
                'image_svg_path' => 'stamps/moon.svg',
                'order_index' => 2,
            ],
            [
                'slug' => 'little-wildflower',
                'name' => 'Little Wildflower',
                'theme_category' => 'botanical',
                'image_svg_path' => 'stamps/flower.svg',
                'order_index' => 3,
            ],
            [
                'slug' => 'golden-friendship',
                'name' => 'Golden Friendship Laurel',
                'theme_category' => 'heritage',
                'image_svg_path' => 'stamps/laurel.svg',
                'order_index' => 4,
            ],
            [
                'slug' => 'airmail-memories',
                'name' => '1982 Airmail Wings',
                'theme_category' => 'postal',
                'image_svg_path' => 'stamps/airplane.svg',
                'order_index' => 5,
            ],
            [
                'slug' => 'autumn-leaves',
                'name' => 'Amber Autumn Leaf',
                'theme_category' => 'seasonal',
                'image_svg_path' => 'stamps/leaf.svg',
                'order_index' => 6,
            ],
            [
                'slug' => 'old-library-tome',
                'name' => 'The Reader’s Hardcover',
                'theme_category' => 'literature',
                'image_svg_path' => 'stamps/book.svg',
                'order_index' => 7,
            ],
            [
                'slug' => 'post-office-crest',
                'name' => 'Dearly Post Office Crest',
                'theme_category' => 'heritage',
                'image_svg_path' => 'stamps/crest.svg',
                'order_index' => 8,
            ],
        ];

        foreach ($stamps as $stamp) {
            PostageStamp::updateOrCreate(['slug' => $stamp['slug']], $stamp);
        }

        // 3. Wax Seals
        $seals = [
            [
                'slug' => 'postal-burgundy',
                'name' => 'Royal Postal Burgundy',
                'color_hex' => '#783C46',
                'symbol_svg' => 'D',
            ],
            [
                'slug' => 'antique-brass',
                'name' => 'Muted Antique Brass',
                'color_hex' => '#B79A61',
                'symbol_svg' => '★',
            ],
            [
                'slug' => 'forest-moss',
                'name' => 'Deep Forest Moss',
                'color_hex' => '#3B5745',
                'symbol_svg' => '✤',
            ],
            [
                'slug' => 'midnight-indigo',
                'name' => 'Midnight Ocean Indigo',
                'color_hex' => '#293C4E',
                'symbol_svg' => '☾',
            ],
            [
                'slug' => 'rose-terracotta',
                'name' => 'Warm Rose Terracotta',
                'color_hex' => '#A35C58',
                'symbol_svg' => '❦',
            ],
        ];

        foreach ($seals as $seal) {
            WaxSeal::updateOrCreate(['slug' => $seal['slug']], $seal);
        }
    }
}
