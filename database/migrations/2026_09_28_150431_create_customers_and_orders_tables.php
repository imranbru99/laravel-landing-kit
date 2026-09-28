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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->unique()->index();
            $table->string('name')->index();
            $table->string('email')->nullable();
            $table->text('default_address')->nullable();
            $table->foreignId('district_id')->nullable()->constrained('bd_districts')->nullOnDelete();
            $table->foreignId('thana_id')->nullable()->constrained('bd_thanas')->nullOnDelete();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->integer('delivered_orders_count')->default(0);
            $table->integer('returned_orders_count')->default(0);
            $table->integer('cancelled_orders_count')->default(0);
            $table->decimal('success_rate', 5, 2)->default(100.00);
            $table->boolean('is_blocked')->default(false)->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address');
            $table->foreignId('district_id')->nullable()->constrained('bd_districts')->nullOnDelete();
            $table->foreignId('thana_id')->nullable()->constrained('bd_thanas')->nullOnDelete();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 64)->unique()->index();
            $table->string('order_token', 64)->unique()->index(); // signed/unguessable public token
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            
            // Customer snapshots at time of order
            $table->string('customer_name');
            $table->string('customer_phone', 20)->index();
            $table->text('customer_address');
            $table->foreignId('district_id')->nullable()->constrained('bd_districts')->nullOnDelete();
            $table->foreignId('thana_id')->nullable()->constrained('bd_thanas')->nullOnDelete();
            $table->foreignId('delivery_zone_id')->nullable()->constrained('delivery_zones')->nullOnDelete();

            $table->string('status', 32)->default('pending')->index();
            
            // Financials
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('delivery_charge', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);

            // Payment
            $table->string('payment_method', 32)->default('cod'); // cod, bkash_manual, nagad_manual
            $table->string('payment_status', 32)->default('unpaid'); // unpaid, paid, refunded
            $table->string('payment_trx_id', 64)->nullable();
            $table->string('payment_sender_number', 20)->nullable();

            // Notes
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();

            // Courier
            $table->string('courier_driver', 32)->nullable()->default('manual'); // manual, steadfast, pathao, redx
            $table->string('courier_tracking_id')->nullable()->index();
            $table->string('courier_consignment_id')->nullable()->index();
            $table->string('courier_status', 64)->nullable();
            $table->timestamp('courier_sent_at')->nullable();

            // Fraud / Attribution / Analytics
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('utm_source', 64)->nullable();
            $table->string('utm_medium', 64)->nullable();
            $table->string('utm_campaign', 64)->nullable();
            $table->string('utm_content', 64)->nullable();
            $table->string('utm_term', 64)->nullable();
            $table->string('fbclid', 128)->nullable();
            $table->string('gclid', 128)->nullable();
            $table->string('ttclid', 128)->nullable();
            $table->integer('fraud_score')->default(0);
            $table->json('fraud_flags')->nullable();

            // Event tracking deduplication guard
            $table->timestamp('purchase_tracked_at')->nullable();
            $table->boolean('stock_decremented')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('total_price', 12, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 32)->nullable();
            $table->string('to_status', 32);
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note');
            $table->boolean('is_customer_visible')->default(false);
            $table->timestamps();
        });

        Schema::create('incomplete_orders', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index();
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('district_id')->nullable()->constrained('bd_districts')->nullOnDelete();
            $table->foreignId('thana_id')->nullable()->constrained('bd_thanas')->nullOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->string('status', 32)->default('new')->index(); // new, called, converted, dropped
            $table->foreignId('converted_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('utm_source', 64)->nullable();
            $table->string('utm_medium', 64)->nullable();
            $table->string('utm_campaign', 64)->nullable();
            $table->timestamps();
        });

        Schema::create('blocklists', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16); // phone, ip
            $table->string('value', 64)->unique();
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocklists');
        Schema::dropIfExists('incomplete_orders');
        Schema::dropIfExists('order_notes');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('customers');
    }
};
