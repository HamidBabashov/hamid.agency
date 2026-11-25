<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

/**
 * Yalnız kateqoriyalar yaradılır. Layihələri admin paneldən real işlərinizlə əlavə edin.
 */
class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'websites', 'name_az' => 'Veb saytlar', 'name_en' => 'Websites', 'sort_order' => 1],
            ['slug' => 'ecommerce', 'name_az' => 'E-ticarət', 'name_en' => 'E-commerce', 'sort_order' => 2],
            ['slug' => 'mobile-apps', 'name_az' => 'Mobil tətbiqlər', 'name_en' => 'Mobile apps', 'sort_order' => 3],
            ['slug' => 'branding', 'name_az' => 'Brendinq', 'name_en' => 'Branding', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            ProjectCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
