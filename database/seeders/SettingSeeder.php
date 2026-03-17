<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name', 'value' => 'Wash Hub', 'group' => 'general', 'type' => 'text', 'description' => 'Nama aplikasi/toko'],
            ['key' => 'app_tagline', 'value' => 'Professional Car Wash Service', 'group' => 'general', 'type' => 'text', 'description' => 'Tagline'],
            ['key' => 'app_phone', 'value' => '0231-123456', 'group' => 'general', 'type' => 'text', 'description' => 'Nomor telepon'],
            ['key' => 'app_address', 'value' => 'Jl. Siliwangi No. 123, Cirebon', 'group' => 'general', 'type' => 'textarea', 'description' => 'Alamat toko'],
            ['key' => 'app_email', 'value' => 'info@washhub.id', 'group' => 'general', 'type' => 'text', 'description' => 'Email'],

            // Receipt / Thermal Print
            ['key' => 'receipt_header', 'value' => 'WASH HUB', 'group' => 'receipt', 'type' => 'text', 'description' => 'Header struk'],
            ['key' => 'receipt_footer', 'value' => 'Terima kasih telah menggunakan layanan kami!', 'group' => 'receipt', 'type' => 'textarea', 'description' => 'Footer struk'],
            ['key' => 'receipt_show_points', 'value' => '1', 'group' => 'receipt', 'type' => 'boolean', 'description' => 'Tampilkan poin di struk'],

            // Loyalty
            ['key' => 'loyalty_washes_required', 'value' => '10', 'group' => 'loyalty', 'type' => 'number', 'description' => 'Jumlah cuci untuk 1 reward'],
            ['key' => 'loyalty_reward_type', 'value' => 'free_wash', 'group' => 'loyalty', 'type' => 'text', 'description' => 'Tipe reward'],

            // Tax
            ['key' => 'tax_enabled', 'value' => '0', 'group' => 'tax', 'type' => 'boolean', 'description' => 'Aktifkan pajak'],
            ['key' => 'tax_percent', 'value' => '0', 'group' => 'tax', 'type' => 'number', 'description' => 'Persentase pajak'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}