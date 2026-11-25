<?php

namespace Database\Seeders;

use App\Models\HostingPlan;
use Illuminate\Database\Seeder;

/**
 * Başlanğıc paketlər — qiymətləri və xüsusiyyətləri admin paneldən dəyişin.
 */
class HostingPlanSeeder extends Seeder
{
    public function run(): void
    {
        if (HostingPlan::exists()) {
            return;
        }

        $plans = [
            [
                'name_az' => 'Başlanğıc', 'name_en' => 'Starter',
                'description_az' => 'Şəxsi sayt və kiçik biznes üçün.',
                'description_en' => 'For personal and small business websites.',
                'price_monthly' => 5.00, 'price_yearly' => 50.00,
                'features_az' => "1 veb sayt\n10 GB SSD disk\n5 email hesabı\nPulsuz SSL\nHəftəlik ehtiyat nüsxə",
                'features_en' => "1 website\n10 GB SSD storage\n5 email accounts\nFree SSL\nWeekly backups",
                'is_featured' => false, 'sort_order' => 1,
            ],
            [
                'name_az' => 'Biznes', 'name_en' => 'Business',
                'description_az' => 'Böyüyən şirkətlər üçün ən populyar seçim.',
                'description_en' => 'The most popular choice for growing companies.',
                'price_monthly' => 10.00, 'price_yearly' => 100.00,
                'features_az' => "5 veb sayt\n30 GB NVMe disk\n25 email hesabı\nPulsuz SSL\nGündəlik ehtiyat nüsxə\nPulsuz sayt köçürmə",
                'features_en' => "5 websites\n30 GB NVMe storage\n25 email accounts\nFree SSL\nDaily backups\nFree website migration",
                'is_featured' => true, 'sort_order' => 2,
            ],
            [
                'name_az' => 'Pro', 'name_en' => 'Pro',
                'description_az' => 'Yüksək trafikli saytlar və onlayn mağazalar üçün.',
                'description_en' => 'For high-traffic websites and online stores.',
                'price_monthly' => 20.00, 'price_yearly' => 200.00,
                'features_az' => "Limitsiz veb sayt\n80 GB NVMe disk\nLimitsiz email hesabı\nPulsuz SSL\nGündəlik ehtiyat nüsxə\nPrioritet dəstək",
                'features_en' => "Unlimited websites\n80 GB NVMe storage\nUnlimited email accounts\nFree SSL\nDaily backups\nPriority support",
                'is_featured' => false, 'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            HostingPlan::create($plan);
        }
    }
}
