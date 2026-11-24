<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'key' => 'web_design',
                'slug_az' => 'veb-dizayn',
                'slug_en' => 'web-design',
                'icon' => 'monitor',
                'title_az' => 'Veb dizayn və hazırlanma',
                'title_en' => 'Web Design & Development',
                'excerpt_az' => 'Biznesinizi təmsil edən sürətli, mobil uyğun və SEO-ya hazır veb saytlar.',
                'excerpt_en' => 'Fast, mobile-friendly and SEO-ready websites that represent your business.',
                'body_az' => "Veb sayt şirkətinizin onlayn ofisidir. Biz korporativ saytlardan onlayn mağazalara qədər hər layihəni biznes məqsədlərinizə uyğun planlaşdırır, dizayn edir və hazırlayırıq.\n\nİş prosesimiz şəffafdır: əvvəlcə tələbləri və hədəf auditoriyanı təhlil edirik, sonra struktur və dizayn maketini sizinlə razılaşdırırıq, ardınca isə saytı müasir texnologiyalarla hazırlayıb test edirik.\n\nSayt təhvil verildikdən sonra da yanınızdayıq: texniki dəstək, yeniləmələr və məzmun idarəetməsi üzrə təlim təqdim edirik.",
                'body_en' => "Your website is your company's online office. From corporate sites to online stores, we plan, design and build every project around your business goals.\n\nOur process is transparent: we start by analysing your requirements and target audience, agree on the structure and design mock-ups with you, then build and test the site with modern technologies.\n\nWe stay with you after launch, too: technical support, updates and training on managing your content.",
                'features_az' => "Fərdi UI/UX dizayn\nMobil uyğun (responsive) quruluş\nSEO əsasları və sürət optimallaşdırması\nİdarəetmə paneli (CMS)\nÇoxdilli sayt dəstəyi\nOnlayn mağaza və ödəniş inteqrasiyası",
                'features_en' => "Custom UI/UX design\nFully responsive layout\nSEO fundamentals and speed optimisation\nContent management panel (CMS)\nMultilingual website support\nOnline store and payment integration",
                'sort_order' => 1,
            ],
            [
                'key' => 'mobile_app',
                'slug_az' => 'mobil-tetbiq',
                'slug_en' => 'mobile-apps',
                'icon' => 'smartphone',
                'title_az' => 'Mobil tətbiq hazırlanması',
                'title_en' => 'Mobile App Development',
                'excerpt_az' => 'iOS və Android üçün istifadəsi rahat, etibarlı və miqyaslana bilən mobil tətbiqlər.',
                'excerpt_en' => 'Reliable, scalable and easy-to-use mobile apps for iOS and Android.',
                'body_az' => "Müştərilərinizə cibində olan bir xidmət təqdim edin. İdeyanın təhlilindən App Store və Google Play-də yayımlanmasına qədər bütün mərhələləri biz idarə edirik.\n\nTətbiqləri həm cross-platform (Flutter, React Native), həm də native texnologiyalarla hazırlayırıq. Bu, büdcənizə və layihənin tələblərinə uyğun ən optimal yolu seçməyə imkan verir.\n\nBackend, admin panel, push bildirişlər və analitika — hamısı bir komandadan.",
                'body_en' => "Put your service in your customers' pockets. We handle every stage, from analysing the idea to publishing on the App Store and Google Play.\n\nWe build apps with both cross-platform (Flutter, React Native) and native technologies, so we can choose the best route for your budget and requirements.\n\nBackend, admin panel, push notifications and analytics: all from one team.",
                'features_az' => "iOS və Android tətbiqləri\nUI/UX prototipləşdirmə\nBackend və API hazırlanması\nPush bildirişlər və analitika\nApp Store / Google Play-də yayımlama\nTəhvildən sonra texniki dəstək",
                'features_en' => "iOS and Android apps\nUI/UX prototyping\nBackend and API development\nPush notifications and analytics\nApp Store / Google Play publishing\nPost-launch technical support",
                'sort_order' => 2,
            ],
            [
                'key' => 'hosting',
                'slug_az' => 'hosting',
                'slug_en' => 'hosting',
                'icon' => 'server',
                'title_az' => 'Veb hosting',
                'title_en' => 'Web Hosting',
                'excerpt_az' => 'SSD diskli, SSL sertifikatlı və gündəlik ehtiyat nüsxəli etibarlı hosting paketləri.',
                'excerpt_en' => 'Reliable hosting plans with SSD storage, SSL certificates and daily backups.',
                'body_az' => "Saytınızın sürəti və dayanıqlığı hostinqdən başlayır. Paketlərimiz kiçik biznes saytlarından yüksək trafikli layihələrə qədər müxtəlif ehtiyaclar üçün nəzərdə tutulub.\n\nBütün paketlərdə pulsuz SSL sertifikatı, korporativ email hesabları və idarəetmə paneli daxildir. Saytınızı başqa hostinqdən pulsuz köçürməyə kömək edirik.\n\nTexniki suallarınızla Azərbaycan dilində dəstək komandamıza müraciət edə bilərsiniz.",
                'body_en' => "Your website's speed and reliability start with hosting. Our plans cover everything from small business sites to high-traffic projects.\n\nEvery plan includes a free SSL certificate, business email accounts and a control panel. We will help you migrate your site from another host free of charge.\n\nOur support team is ready to help with any technical question.",
                'features_az' => "SSD/NVMe disk\nPulsuz SSL sertifikatı\nKorporativ email hesabları\nGündəlik ehtiyat nüsxə\nİdarəetmə paneli\nPulsuz sayt köçürmə",
                'features_en' => "SSD/NVMe storage\nFree SSL certificate\nBusiness email accounts\nDaily backups\nControl panel\nFree website migration",
                'sort_order' => 3,
            ],
            [
                'key' => 'domain',
                'slug_az' => 'domen-qeydiyyati',
                'slug_en' => 'domain-registration',
                'icon' => 'globe',
                'title_az' => 'Domen qeydiyyatı',
                'title_en' => 'Domain Registration',
                'excerpt_az' => '.az, .com, .net, .org və digər uzantılarda domen qeydiyyatı, yeniləmə və transfer.',
                'excerpt_en' => 'Register, renew and transfer .az, .com, .net, .org and other domains.',
                'body_az' => "Brendiniz üçün doğru domen adı ilk təəssüratı formalaşdırır. Bizimlə istədiyiniz domenin boş olub-olmadığını bir neçə saniyəyə yoxlaya və qeydiyyatdan keçirə bilərsiniz.\n\n.az daxil olmaqla geniş uzantı seçimi, şəffaf qiymətlər və DNS idarəetməsində köməklik təqdim edirik.\n\nMövcud domeninizi bizə transfer etmək və hosting ilə birlikdə bir yerdən idarə etmək də mümkündür.",
                'body_en' => "The right domain name shapes your brand's first impression. With us you can check whether a domain is free in seconds and register it.\n\nWe offer a wide choice of extensions including .az, transparent pricing and help with DNS management.\n\nYou can also transfer your existing domain to us and manage it in one place with your hosting.",
                'features_az' => ".az və beynəlxalq uzantılar\nAnında mövcudluq yoxlaması\nDNS idarəetməsi\nDomen transferi\nAvtomatik yeniləmə xatırlatmaları\nHosting ilə birlikdə idarəetmə",
                'features_en' => ".az and international extensions\nInstant availability check\nDNS management\nDomain transfer\nRenewal reminders\nManaged together with hosting",
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['key' => $service['key']], $service);
        }
    }
}
