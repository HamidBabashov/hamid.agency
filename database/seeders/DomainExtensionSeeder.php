<?php

namespace Database\Seeders;

use App\Models\DomainExtension;
use Illuminate\Database\Seeder;

/**
 * Başlanğıc uzantılar — qiymətləri admin paneldən yeniləyin.
 */
class DomainExtensionSeeder extends Seeder
{
    public function run(): void
    {
        $extensions = [
            ['extension' => 'az', 'register_price' => 40, 'renew_price' => 40, 'transfer_price' => 40, 'is_featured' => true, 'sort_order' => 1],
            ['extension' => 'com.az', 'register_price' => 40, 'renew_price' => 40, 'transfer_price' => 40, 'is_featured' => false, 'sort_order' => 2],
            ['extension' => 'com', 'register_price' => 25, 'renew_price' => 28, 'transfer_price' => 25, 'is_featured' => true, 'sort_order' => 3],
            ['extension' => 'net', 'register_price' => 28, 'renew_price' => 30, 'transfer_price' => 28, 'is_featured' => true, 'sort_order' => 4],
            ['extension' => 'org', 'register_price' => 26, 'renew_price' => 30, 'transfer_price' => 26, 'is_featured' => true, 'sort_order' => 5],
            ['extension' => 'info', 'register_price' => 10, 'renew_price' => 40, 'transfer_price' => 35, 'is_featured' => false, 'sort_order' => 6],
            ['extension' => 'io', 'register_price' => 90, 'renew_price' => 90, 'transfer_price' => 90, 'is_featured' => false, 'sort_order' => 7],
            ['extension' => 'co', 'register_price' => 50, 'renew_price' => 50, 'transfer_price' => 50, 'is_featured' => false, 'sort_order' => 8],
            ['extension' => 'online', 'register_price' => 10, 'renew_price' => 55, 'transfer_price' => 55, 'is_featured' => false, 'sort_order' => 9],
        ];

        foreach ($extensions as $extension) {
            DomainExtension::firstOrCreate(['extension' => $extension['extension']], $extension);
        }
    }
}
