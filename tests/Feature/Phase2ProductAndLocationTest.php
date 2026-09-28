<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\DeliveryZone;
use App\Models\District;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Models\ProductVariant;
use App\Models\Thana;

test('bangladesh locations and delivery zones are seeded with 64 districts', function () {
    expect(District::count())->toBe(64)
        ->and(Thana::count())->toBeGreaterThan(150);

    $dhaka = District::where('name_en', 'Dhaka')->first();
    expect($dhaka)->not->toBeNull()
        ->and($dhaka->is_inside_dhaka)->toBeTrue()
        ->and($dhaka->division)->toBe('Dhaka')
        ->and($dhaka->thanas->count())->toBeGreaterThan(15);

    expect(DeliveryZone::count())->toBe(3)
        ->and(DeliveryZone::where('code', 'inside_dhaka')->value('charge'))->toBe('60.00')
        ->and(DeliveryZone::where('code', 'outside_dhaka')->value('charge'))->toBe('120.00');
});

test('category auto-generates universal slug and supports hierarchy', function () {
    $parent = Category::create([
        'name' => 'Fashion & Apparel',
        'is_active' => true,
    ]);

    expect($parent->slug)->toBe('fashion-apparel');

    $child = Category::create([
        'name' => 'Men Punjabi & Pajama',
        'parent_id' => $parent->id,
        'is_active' => true,
    ]);

    expect($child->slug)->toBe('men-punjabi-pajama')
        ->and($child->parent->id)->toBe($parent->id)
        ->and($parent->children->first()->id)->toBe($child->id);
});

test('product auto-generates universal slug from name and calculates effective price', function () {
    $product = Product::create([
        'name' => 'Premium Silk Panjabi 2026',
        'sku' => 'PANJABI-001',
        'regular_price' => 2500,
        'sale_price' => 1950,
        'track_stock' => true,
        'stock_quantity' => 25,
        'status' => 'active',
    ]);

    expect($product->slug)->toBe('premium-silk-panjabi-2026')
        ->and($product->effective_price)->toBe(1950.0)
        ->and($product->discount_percent)->toBe(22)
        ->and($product->isInStock())->toBeTrue();
});

test('product supports bengali title slug generation', function () {
    $product = Product::create([
        'name' => 'খাঁটি সুন্দরবনের মধু ১ কেজি',
        'sku' => 'HONEY-001',
        'regular_price' => 1200,
        'status' => 'active',
    ]);

    // Universal slug preserves unicode cleanly or converts
    expect($product->slug)->not->toBeEmpty()
        ->and($product->name)->toBe('খাঁটি সুন্দরবনের মধু ১ কেজি');
});

test('product can have variants and bundle offers', function () {
    $product = Product::create([
        'name' => 'Cotton Polo T-Shirt',
        'sku' => 'POLO-001',
        'regular_price' => 850,
        'status' => 'active',
    ]);

    $variantM = ProductVariant::create([
        'product_id' => $product->id,
        'name' => 'Size M - Navy Blue',
        'sku' => 'POLO-M-NVY',
        'price' => 850,
        'stock_quantity' => 15,
        'is_active' => true,
    ]);

    $variantL = ProductVariant::create([
        'product_id' => $product->id,
        'name' => 'Size L - Black',
        'sku' => 'POLO-L-BLK',
        'price' => 850,
        'stock_quantity' => 20,
        'is_active' => true,
    ]);

    $offer = ProductOffer::create([
        'product_id' => $product->id,
        'type' => 'quantity_tier',
        'name' => 'Buy 2 Get 150 Tk Discount',
        'discount_type' => 'fixed',
        'discount_amount' => 150,
        'min_quantity' => 2,
        'is_active' => true,
    ]);

    expect($product->variants->count())->toBe(2)
        ->and($product->offers->count())->toBe(1)
        ->and($product->offers->first()->discount_amount)->toBe('150.00');
});
