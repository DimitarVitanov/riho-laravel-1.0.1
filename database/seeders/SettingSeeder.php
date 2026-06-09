<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseName = config('app.name');
        $current_year = date('Y');
        $app_url = env('APP_URL');
        $values = [
            'general' => [
                'light_logo' => '/assets/images/logo/logo.png',
                'dark_logo' => '/assets/images/logo/logo_dark.png',
                'favicon' => '/assets/images/favicon.png',
                'site_name' => $baseName,
                'footer' => "Copyright {$current_year} © {$baseName} theme by pixelstrap",
            ],
            'google_reCaptcha' => [
                'site_key' => NULL,
                'secret' => NULL,
                'status' => 1,
            ],
            'social_login' => [
                'google' => [
                    'google_client_id' => NULL,
                    'google_client_secret' => NULL,
                    'google_redirect_url' => NULL,
                    'google_login_status' => 1,
                ],

                'facebook' => [
                    'facebook_client_id' => NULL,
                    'facebook_client_secret' => NULL,
                    'facebook_redirect_url' => NULL,
                    'facebook_login_status' => 1,
                ],
            ],
        ];

        Setting::updateOrCreate(['values' => $values]);
    }
}
