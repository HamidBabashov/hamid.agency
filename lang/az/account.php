<?php

return [
    'nav' => [
        'login' => 'Daxil ol',
        'register' => 'Qeydiyyat',
        'account' => 'Hesabım',
        'orders' => 'Sifarişlərim',
        'profile' => 'Profil',
        'logout' => 'Çıxış',
        'admin' => 'Admin panel',
        'menu' => 'Hesab menyusu',
    ],

    'login' => [
        'title' => 'Hesabınıza daxil olun',
        'lead' => 'Sifarişlərinizi izləyin və yeni sifarişi bir neçə kliklə verin.',
        'remember' => 'Məni xatırla',
        'forgot' => 'Şifrəni unutmusunuz?',
        'submit' => 'Daxil ol',
        'no_account' => 'Hesabınız yoxdur?',
        'create_account' => 'Qeydiyyatdan keçin',
    ],

    'register' => [
        'title' => 'Hesab yaradın',
        'lead' => 'Qeydiyyatdan keçin — sifarişlərinizin statusunu bir yerdən izləyin.',
        'terms' => 'İstifadə şərtləri və məxfilik siyasəti ilə razıyam',
        'submit' => 'Qeydiyyatdan keç',
        'has_account' => 'Artıq hesabınız var?',
        'login' => 'Daxil olun',
        'password_hint' => 'Ən azı 8 simvol, hərf və rəqəm.',
        'benefits' => [
            'Bütün sifarişləriniz bir yerdə',
            'Status dəyişiklikləri barədə email bildirişi',
            'Növbəti sifarişlərdə formanı yenidən doldurmağa ehtiyac yoxdur',
        ],
    ],

    'forgot' => [
        'title' => 'Şifrəni bərpa edin',
        'lead' => 'Email ünvanınızı yazın — şifrəni yeniləmək üçün link göndərək.',
        'submit' => 'Bərpa linkini göndər',
        'sent' => 'Bu email ilə hesab varsa, şifrə bərpası linki göndərildi. Poçt qutunuzu (və spam qovluğunu) yoxlayın.',
        'back' => 'Girişə qayıt',
    ],

    'reset' => [
        'title' => 'Yeni şifrə təyin edin',
        'submit' => 'Şifrəni yenilə',
    ],

    'reset_mail' => [
        'subject' => 'Şifrənin bərpası — Hamid Agency',
        'greeting' => 'Salam, :name!',
        'intro' => 'Hesabınız üçün şifrə bərpası sorğusu aldıq.',
        'action' => 'Şifrəni yenilə',
        'expire' => 'Bu link :count dəqiqə ərzində etibarlıdır.',
        'outro' => 'Əgər bu sorğunu siz göndərməmisinizsə, heç nə etməyinizə ehtiyac yoxdur.',
    ],

    'dashboard' => [
        'title' => 'Hesabım',
        'welcome' => 'Xoş gəlmisiniz, :name',
        'lead' => 'Burada sifarişlərinizin vəziyyətini izləyə və yeni sifariş verə bilərsiniz.',
        'new_order' => 'Yeni sifariş',
        'orders' => 'Sifarişlərim',
        'empty_title' => 'Hələ sifarişiniz yoxdur',
        'empty_text' => 'Veb sayt, mobil tətbiq, hosting və ya domen — ilk sifarişinizi verin.',
        'total' => 'Ümumi',
    ],

    'orders' => [
        'number' => 'Sifariş №:number',
        'service' => 'Xidmət',
        'date' => 'Tarix',
        'status' => 'Status',
        'details' => 'Sifariş detalları',
        'message' => 'Qeydləriniz',
        'contact' => 'Əlaqə məlumatları',
        'back' => 'Bütün sifarişlər',
        'view' => 'Bax',
        'progress' => 'Sifarişin gedişatı',
        'help' => 'Sualınız var? Bizə yazın və sifariş nömrəsini qeyd edin.',
    ],

    'statuses' => [
        'new' => 'Qəbul edildi',
        'contacted' => 'İcradadır',
        'completed' => 'Tamamlandı',
    ],

    'status_hints' => [
        'new' => 'Sorğunuz bizə çatıb. Tezliklə baxıb sizinlə əlaqə saxlayacağıq.',
        'contacted' => 'Sizinlə əlaqə saxlanılıb və sifarişiniz üzərində işləyirik.',
        'completed' => 'Sifarişiniz tamamlanıb. Bizi seçdiyiniz üçün təşəkkür edirik!',
    ],

    'profile' => [
        'title' => 'Profil',
        'details' => 'Şəxsi məlumatlar',
        'password' => 'Şifrəni dəyiş',
        'save' => 'Yadda saxla',
        'update_password' => 'Şifrəni yenilə',
        'language_hint' => 'Emaillər bu dildə göndəriləcək.',
    ],

    'quote' => [
        'guest_title' => 'Sifarişlərinizi izləmək istəyirsiniz?',
        'guest_text' => 'Hesab yaradın və ya daxil olun — sifarişin statusunu istənilən vaxt görə bilərsiniz.',
        'logged_in' => 'Sifariş :email hesabınıza əlavə olunacaq.',
    ],

    'flash' => [
        'registered' => 'Xoş gəlmisiniz! Hesabınız yaradıldı.',
        'profile_updated' => 'Profiliniz yeniləndi.',
        'password_updated' => 'Şifrəniz dəyişdirildi.',
        'password_reset' => 'Şifrəniz yeniləndi və hesabınıza daxil oldunuz.',
    ],

    'mail' => [
        'received' => [
            'subject' => 'Sifarişiniz qəbul edildi — №:number',
            'heading' => 'Təşəkkür edirik, :name!',
            'text' => '":service" üzrə sorğunuz (№ :number) qəbul edildi. Tezliklə sizinlə əlaqə saxlayacağıq.',
        ],
        'status' => [
            'subject' => 'Sifarişinizin statusu yeniləndi — №:number',
            'heading' => 'Sifariş №:number',
            'text' => 'Yeni status: **:status**',
        ],
        'view_order' => 'Sifarişə bax',
        'signature' => 'Hörmətlə, Hamid Agency komandası',
    ],

    'attributes' => [
        'name' => 'ad, soyad',
        'email' => 'email',
        'phone' => 'telefon',
        'company' => 'şirkət',
        'password' => 'şifrə',
        'current_password' => 'cari şifrə',
        'terms' => 'şərtlər',
        'locale' => 'dil',
    ],

    'fields' => [
        'password' => 'Şifrə',
        'password_confirmation' => 'Şifrənin təkrarı',
        'current_password' => 'Cari şifrə',
        'new_password' => 'Yeni şifrə',
        'language' => 'Dil',
    ],
];
