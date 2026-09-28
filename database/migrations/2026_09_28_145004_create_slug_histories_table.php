<?php

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
        Schema::create('slug_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('sluggable');
            $table->string('slug')->index();
            $table->string('field')->default('slug');
            $table->timestamps();

            $table->unique(['sluggable_type', 'slug', 'field'], 'slug_histories_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slug_histories');
    }
};
