<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Widgets\LatestOrdersWidget;
use App\Filament\Widgets\OrderStatusChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Models\User;
use Database\Seeders\DemoStoreSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class Phase11FinalVerificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(SettingSeeder::class);
        $this->seed(DemoStoreSeeder::class);
    }

    public function test_llk_install_command_executes_cleanly(): void
    {
        $exitCode = Artisan::call('llk:install', ['--force' => true]);
        $this->assertSame(0, $exitCode);
    }

    public function test_dynamic_sitemap_renders_published_products(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $this->assertStringContainsString('<urlset', $response->getContent());
        $this->assertStringContainsString('premium-semi-fitted-eid-panjabi', $response->getContent());
    }

    public function test_robots_txt_renders_with_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->assertStringContainsString('User-agent: *', $response->getContent());
        $this->assertStringContainsString('Disallow: /admin', $response->getContent());
        $this->assertStringContainsString('Sitemap:', $response->getContent());
    }

    public function test_schema_org_json_ld_is_rendered_on_product_landing_page(): void
    {
        $response = $this->get('/premium-semi-fitted-eid-panjabi');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/ld+json', $response->getContent());
        $this->assertStringContainsString('"@type": "Product"', $response->getContent());
        $this->assertStringContainsString('"priceCurrency": "BDT"', $response->getContent());
        $this->assertStringContainsString('1850', $response->getContent());
    }

    public function test_demo_store_landing_page_renders_with_configured_sections(): void
    {
        $response = $this->get('/premium-semi-fitted-eid-panjabi');

        $response->assertStatus(200);
        $this->assertStringContainsString('১০০% পিওর কটন প্রিমিয়াম ডিজাইনার পাঞ্জাবি', $response->getContent());
        $this->assertStringContainsString('আমাদের পাঞ্জাবির বিশেষত্ব', $response->getContent());
        $this->assertStringContainsString('গ্রাহকরা যা বলছেন', $response->getContent());
        $this->assertStringContainsString('ক্যাশ অন ডেলিভারিতে অর্ডার করতে তথ্য দিন', $response->getContent());
    }

    public function test_filament_dashboard_widgets_render_properly(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@amaronline.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('Super Admin');

        $this->actingAs($admin);

        Livewire::test(StatsOverviewWidget::class)
            ->assertSuccessful();

        Livewire::test(OrderStatusChartWidget::class)
            ->assertSuccessful();

        Livewire::test(LatestOrdersWidget::class)
            ->assertSuccessful();
    }
}
