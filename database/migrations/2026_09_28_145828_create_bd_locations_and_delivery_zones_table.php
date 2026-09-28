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
        Schema::create('bd_districts', function (Blueprint $table) {
            $table->id();
            $table->string('name_en', 64)->index();
            $table->string('name_bn', 64);
            $table->string('division', 32)->index();
            $table->boolean('is_inside_dhaka')->default(false)->index();
            $table->boolean('is_sub_dhaka')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('bd_thanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('bd_districts')->cascadeOnDelete();
            $table->string('name_en', 64)->index();
            $table->string('name_bn', 64);
            $table->string('postcode', 16)->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->decimal('charge', 12, 2)->default(60.00);
            $table->string('estimated_days', 50)->nullable()->default('1-3 days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
        Schema::dropIfExists('bd_thanas');
        Schema::dropIfExists('bd_districts');
    }
};
