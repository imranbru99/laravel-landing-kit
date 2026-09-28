<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Templates Library (100+ Bangladesh shop templates)
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->string('category', 64)->index(); // fashion, footwear, beauty, health, grocery, electronics, etc.
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('tags')->nullable();
            $table->json('palette')->nullable(); // primary, secondary, accent, font_pair
            $table->string('default_language', 8)->default('bn');
            $table->json('sample_product_data')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Template Sections
        Schema::create('template_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
            $table->string('section_type', 64)->index();
            $table->integer('position')->default(0)->index();
            $table->json('content')->nullable();
            $table->json('style')->nullable();
            $table->json('responsive')->nullable();
            $table->timestamps();
        });

        // 3. Landing Pages
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('templates')->nullOnDelete();
            $table->string('title')->nullable();
            $table->string('status', 32)->default('published')->index(); // draft, published, archived
            $table->json('theme_tokens')->nullable(); // primary_color, accent_color, font_family, radius, button_style
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->json('settings')->nullable(); // sticky_cta, floating_whatsapp, countdown_timer, etc.
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Page Sections (Individual building blocks of landing pages)
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained('landing_pages')->cascadeOnDelete();
            $table->string('section_type', 64)->index();
            $table->integer('position')->default(0)->index();
            $table->boolean('is_visible')->default(true);
            $table->json('content')->nullable();
            $table->json('style')->nullable();
            $table->json('responsive')->nullable();
            $table->timestamps();
        });

        // 5. Page Revisions (Undo/redo and restore history)
        Schema::create('page_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_id')->constrained('landing_pages')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->nullable();
            $table->json('snapshot'); // full state of page and all sections
            $table->timestamps();
        });

        // 6. Saved Sections (User's reusable custom section library)
        Schema::create('saved_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('category', 64)->nullable();
            $table->string('section_type', 64)->index();
            $table->json('content');
            $table->json('style')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_sections');
        Schema::dropIfExists('page_revisions');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('landing_pages');
        Schema::dropIfExists('template_sections');
        Schema::dropIfExists('templates');
    }
};
