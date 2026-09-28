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
        Schema::create('tracking_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_name', 64)->index();
            $table->string('event_id', 128)->nullable()->index();
            $table->string('platform', 64)->index(); // meta_capi, stape, ga4, tiktok, gtm
            $table->json('payload');
            $table->text('response')->nullable();
            $table->string('status', 32)->default('sent')->index(); // sent, failed, queued
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_logs');
    }
};
