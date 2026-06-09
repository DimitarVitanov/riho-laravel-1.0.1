<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Country;
use App\Models\Setting;
use App\Models\Attachment;
use App\Models\LandingPage;

class Helpers
{
    public static function isUserLogin()
    {
        return auth()?->check();
    }

    public static function getCurrentUserId()
    {
      if (self::isUserLogin()) {
        return auth()?->user()?->id;
      }
    }

    public static function getCountryCode(){
      return Country::get(["calling_code", "id", "iso_3166_2", 'flag'])->unique('calling_code');
    }

    public static function getUser()
    {
        $user = User::with('roles')->latest()->take(5)->get();
        return $user;
    }

    public static function getSettings()
    {
      return Setting::pluck('values')?->first() ?? [
        'general' => [
          'site_name' => 'Villa Bit AI',
          'light_logo' => 'assets/images/logo/logo.png',
          'dark_logo' => 'assets/images/logo/logo-dark.png',
          'favicon' => 'assets/images/logo/favicon.png',
        ]
      ];
    }
    
    public static function getLandingPage()
    {
        return LandingPage::first()?->content ?? [];
    }
}