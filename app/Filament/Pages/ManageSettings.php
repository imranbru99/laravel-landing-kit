<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Services\SettingService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings & System';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Store & System Settings';

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(SettingService $settingService): void
    {
        $this->form->fill($settingService->all());
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Settings')
                ->tabs([
                    Tabs\Tab::make('General')
                        ->icon('heroicon-o-building-storefront')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('site_name')
                                    ->label('Store / Brand Name')
                                    ->required(),
                                TextInput::make('site_tagline')
                                    ->label('Store Tagline'),
                                TextInput::make('currency')
                                    ->label('Currency Code')
                                    ->default('BDT'),
                                TextInput::make('currency_symbol')
                                    ->label('Currency Symbol')
                                    ->default('৳'),
                                Toggle::make('use_bangla_numerals')
                                    ->label('Use Bangla Numerals (১২৩৪৫৬৭৮৯০)')
                                    ->helperText('Display prices and quantities in Bangla numerals on public landing pages')
                                    ->default(true),
                                Select::make('default_language')
                                    ->label('Default Language')
                                    ->options([
                                        'bn' => 'বাংলা (Bangla)',
                                        'en' => 'English',
                                    ])
                                    ->default('bn'),
                                TextInput::make('primary_phone')
                                    ->label('Customer Care Phone')
                                    ->tel(),
                                TextInput::make('whatsapp_number')
                                    ->label('WhatsApp Number')
                                    ->tel(),
                                TextInput::make('support_email')
                                    ->label('Support Email')
                                    ->email(),
                            ]),
                        ]),

                    Tabs\Tab::make('Checkout')
                        ->icon('heroicon-o-shopping-bag')
                        ->schema([
                            Section::make('Payment Methods')->schema([
                                Toggle::make('enable_cod')
                                    ->label('Enable Cash on Delivery (COD)')
                                    ->default(true),
                                Grid::make(2)->schema([
                                    Toggle::make('enable_bkash_manual')
                                        ->label('Enable Manual bKash Payment'),
                                    TextInput::make('bkash_manual_number')
                                        ->label('bKash Number & Account Type')
                                        ->placeholder('e.g. 017XXXXXXXX (Personal)'),
                                    Toggle::make('enable_nagad_manual')
                                        ->label('Enable Manual Nagad Payment'),
                                    TextInput::make('nagad_manual_number')
                                        ->label('Nagad Number & Account Type')
                                        ->placeholder('e.g. 018XXXXXXXX (Personal)'),
                                ]),
                            ]),
                            Section::make('Delivery & Shipping Charges')->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('default_inside_dhaka_charge')
                                        ->label('Inside Dhaka Delivery (৳)')
                                        ->numeric()
                                        ->default(60),
                                    TextInput::make('default_outside_dhaka_charge')
                                        ->label('Outside Dhaka Delivery (৳)')
                                        ->numeric()
                                        ->default(120),
                                    TextInput::make('free_delivery_threshold')
                                        ->label('Free Delivery Threshold (৳, 0 = disabled)')
                                        ->numeric()
                                        ->default(0),
                                ]),
                            ]),
                            Section::make('Checkout Protection & UX')->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('duplicate_order_window_minutes')
                                        ->label('Duplicate Order Window (Minutes)')
                                        ->helperText('Flag duplicate orders from same phone & product within this time')
                                        ->numeric()
                                        ->default(10),
                                    Toggle::make('mask_guest_lookup')
                                        ->label('Mask Returning Customer Data')
                                        ->helperText('Protect customer privacy when auto-filling returning phone numbers')
                                        ->default(true),
                                    Toggle::make('order_notes_enabled')
                                        ->label('Allow Customer Order Notes')
                                        ->default(true),
                                ]),
                                Textarea::make('success_page_message')
                                    ->label('Custom Order Success Message')
                                    ->rows(3),
                            ]),
                        ]),

                    Tabs\Tab::make('Order Lifecycle')
                        ->icon('heroicon-o-arrow-path')
                        ->schema([
                            Grid::make(3)->schema([
                                TextInput::make('order_number_prefix')
                                    ->label('Order Number Prefix')
                                    ->default('ORD'),
                                Select::make('stock_reduction_rule')
                                    ->label('Stock Decrement Trigger')
                                    ->options([
                                        'on_order' => 'Immediately on customer order submit',
                                        'on_confirm' => 'When status changes to Confirmed (Recommended)',
                                    ])
                                    ->default('on_confirm'),
                                Select::make('default_order_status')
                                    ->label('Default New Order Status')
                                    ->options([
                                        'pending' => 'Pending (Default)',
                                        'processing' => 'Processing',
                                    ])
                                    ->default('pending'),
                            ]),
                        ]),

                    Tabs\Tab::make('Courier & Logistics')
                        ->icon('heroicon-o-truck')
                        ->schema([
                            Select::make('default_courier')
                                ->label('Default Courier Driver')
                                ->options([
                                    'manual' => 'Manual / In-House Courier',
                                    'steadfast' => 'Steadfast Courier',
                                    'pathao' => 'Pathao Courier',
                                    'redx' => 'RedX Courier',
                                ])
                                ->default('manual'),
                            Section::make('Steadfast Courier')->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('steadfast_api_key')
                                        ->label('Steadfast API Key')
                                        ->password(),
                                    TextInput::make('steadfast_secret_key')
                                        ->label('Steadfast Secret Key')
                                        ->password(),
                                ]),
                            ])->collapsed(),
                            Section::make('Pathao Courier')->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('pathao_client_id')->label('Client ID'),
                                    TextInput::make('pathao_client_secret')->label('Client Secret')->password(),
                                    TextInput::make('pathao_username')->label('Username'),
                                    TextInput::make('pathao_password')->label('Password')->password(),
                                ]),
                            ])->collapsed(),
                            Section::make('RedX Courier')->schema([
                                TextInput::make('redx_api_token')->label('RedX API Token')->password(),
                            ])->collapsed(),
                        ]),

                    Tabs\Tab::make('Tracking & Marketing')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            Section::make('Google Tag Manager & Stape')->schema([
                                Toggle::make('gtm_enabled')->label('Enable GTM'),
                                Grid::make(2)->schema([
                                    TextInput::make('gtm_container_id')
                                        ->label('GTM Container ID')
                                        ->placeholder('GTM-XXXXXXX'),
                                    TextInput::make('gtm_custom_domain')
                                        ->label('Custom Loader / Stape Web Domain')
                                        ->placeholder('e.g. metrics.amarshopbd.com'),
                                ]),
                                Toggle::make('stape_enabled')->label('Enable Stape Server-Side Gateway'),
                                TextInput::make('stape_domain')
                                    ->label('Stape Server Container URL')
                                    ->placeholder('https://ss.amarshopbd.com'),
                            ]),
                            Section::make('Meta Pixel & Conversions API (CAPI)')->schema([
                                Toggle::make('meta_pixel_enabled')->label('Enable Meta Pixel (Browser)'),
                                TextInput::make('meta_pixel_id')->label('Meta Pixel ID')->placeholder('123456789012345'),
                                Toggle::make('meta_capi_enabled')->label('Enable Meta Conversions API (Server-Side CAPI)'),
                                TextInput::make('meta_capi_token')->label('Meta CAPI Access Token')->password(),
                                TextInput::make('meta_test_code')->label('Meta Test Event Code')->placeholder('TEST12345'),
                            ]),
                            Section::make('Google Analytics 4 & TikTok Pixel')->schema([
                                Toggle::make('ga4_enabled')->label('Enable GA4'),
                                TextInput::make('ga4_measurement_id')->label('GA4 Measurement ID')->placeholder('G-XXXXXXXXXX'),
                                Toggle::make('tiktok_pixel_enabled')->label('Enable TikTok Pixel'),
                                TextInput::make('tiktok_pixel_id')->label('TikTok Pixel ID')->placeholder('C1234567890'),
                            ]),
                        ]),

                    Tabs\Tab::make('AI Assistant')
                        ->icon('heroicon-o-sparkles')
                        ->schema([
                            Select::make('ai_provider')
                                ->label('AI Provider')
                                ->options([
                                    'gemini' => 'Google Gemini (Recommended)',
                                    'openai' => 'OpenAI (GPT-4o / GPT-4.1)',
                                    'anthropic' => 'Anthropic Claude',
                                ])
                                ->default('gemini'),
                            TextInput::make('ai_model')
                                ->label('Default Model')
                                ->default('gemini-2.5-flash'),
                            TextInput::make('ai_gemini_key')
                                ->label('Gemini API Key')
                                ->password()
                                ->helperText('Can be left blank to use GEMINI_API_KEY from .env'),
                            TextInput::make('ai_openai_key')
                                ->label('OpenAI API Key')
                                ->password()
                                ->helperText('Can be left blank to use OPENAI_API_KEY from .env'),
                        ]),

                    Tabs\Tab::make('System & Tools')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->schema([
                            Section::make('System Optimization')->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('sys_php_ver')
                                        ->label('PHP Version')
                                        ->default(PHP_VERSION)
                                        ->disabled(),
                                    TextInput::make('sys_laravel_ver')
                                        ->label('Laravel Version')
                                        ->default(app()->version())
                                        ->disabled(),
                                    TextInput::make('sys_db_conn')
                                        ->label('Database Driver')
                                        ->default(config('database.default'))
                                        ->disabled(),
                                ]),
                            ]),
                        ]),
                ]),
        ];
    }

    public function submit(SettingService $settingService): void
    {
        $formData = $this->form->getState();

        $encryptedFields = [
            'meta_capi_token',
            'ai_gemini_key',
            'ai_openai_key',
            'steadfast_api_key',
            'steadfast_secret_key',
            'pathao_client_secret',
            'pathao_password',
            'redx_api_token',
        ];

        foreach ($formData as $key => $value) {
            $isEncrypted = in_array($key, $encryptedFields, true);
            $group = $this->determineGroup($key);
            $settingService->set($key, $value, $group, null, $isEncrypted);
        }

        Notification::make()
            ->title('Settings Saved')
            ->body('Store and integration settings have been updated and cached successfully.')
            ->success()
            ->send();
    }

    public function clearSystemCache(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        app(SettingService::class)->clearCache();

        Notification::make()
            ->title('Cache Cleared')
            ->body('Application cache, view cache, and settings cache have been cleared.')
            ->success()
            ->send();
    }

    private function determineGroup(string $key): string
    {
        return match (true) {
            str_starts_with($key, 'gtm_'),
            str_starts_with($key, 'meta_'),
            str_starts_with($key, 'stape_'),
            str_starts_with($key, 'ga4_'),
            str_starts_with($key, 'tiktok_') => 'tracking',
            str_starts_with($key, 'enable_'),
            str_starts_with($key, 'default_inside_'),
            str_starts_with($key, 'default_outside_'),
            str_starts_with($key, 'free_delivery_'),
            str_starts_with($key, 'duplicate_'),
            str_starts_with($key, 'mask_'),
            str_starts_with($key, 'order_notes_'),
            str_starts_with($key, 'success_page_') => 'checkout',
            str_starts_with($key, 'order_number_'),
            str_starts_with($key, 'stock_reduction_'),
            str_starts_with($key, 'default_order_') => 'order',
            str_starts_with($key, 'steadfast_'),
            str_starts_with($key, 'pathao_'),
            str_starts_with($key, 'redx_'),
            str_starts_with($key, 'default_courier') => 'courier',
            str_starts_with($key, 'ai_') => 'ai',
            default => 'general',
        };
    }
}
