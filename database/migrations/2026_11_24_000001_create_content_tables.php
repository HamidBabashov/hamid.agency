<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // web_design, mobile_app, hosting, domain
            $table->string('slug_az')->unique();
            $table->string('slug_en')->unique();
            $table->string('icon')->default('code');
            $table->string('title_az');
            $table->string('title_en');
            $table->string('excerpt_az', 500)->nullable();
            $table->string('excerpt_en', 500)->nullable();
            $table->text('body_az')->nullable();
            $table->text('body_en')->nullable();
            $table->text('features_az')->nullable(); // hər sətir bir xüsusiyyət
            $table->text('features_en')->nullable();
            $table->string('meta_title_az')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->string('meta_description_az', 300)->nullable();
            $table->string('meta_description_en', 300)->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('hosting_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name_az');
            $table->string('name_en');
            $table->string('description_az', 500)->nullable();
            $table->string('description_en', 500)->nullable();
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_yearly', 10, 2); // illik ümumi məbləğ
            $table->text('features_az')->nullable();
            $table->text('features_en')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('domain_extensions', function (Blueprint $table) {
            $table->id();
            $table->string('extension')->unique(); // "az", "com" (nöqtəsiz)
            $table->decimal('register_price', 10, 2);
            $table->decimal('renew_price', 10, 2);
            $table->decimal('transfer_price', 10, 2)->nullable();
            $table->boolean('is_featured')->default(false); // axtarışda avtomatik yoxlanılır
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_az');
            $table->string('name_en');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title_az');
            $table->string('title_en');
            $table->string('summary_az', 500)->nullable();
            $table->string('summary_en', 500)->nullable();
            $table->string('client')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('company')->nullable();
            $table->string('position_az')->nullable();
            $table->string('position_en')->nullable();
            $table->text('content_az');
            $table->text('content_en');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('photo')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('service_type'); // web_design, mobile_app, hosting, domain
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->json('details')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new')->index();
            $table->text('admin_note')->nullable();
            $table->string('locale', 5)->default('az');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('service_type')->nullable();
            $table->text('message');
            $table->string('status')->default('new')->index();
            $table->string('locale', 5)->default('az');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_categories');
        Schema::dropIfExists('domain_extensions');
        Schema::dropIfExists('hosting_plans');
        Schema::dropIfExists('services');
    }
};
