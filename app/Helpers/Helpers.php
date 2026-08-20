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
          'favicon' => 'assets/images/logo/favicon.ico',
        ]
      ];
    }
    
    public static function getLandingPage()
    {
        return LandingPage::first()?->content ?? [];
    }

    /**
     * Convert an official country name (e.g. "Republic of Albania",
     * "Principality of Monaco") into its short common name ("Albania", "Monaco").
     * Leaves already-clean names ("Croatia") untouched.
     */
    public static function commonCountryName(?string $name): string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return '';
        }

        // Longest phrases first so more specific prefixes match before shorter ones.
        $prefixes = [
            "Democratic People's Republic of ",
            "People's Democratic Republic of ",
            "Federal Democratic Republic of ",
            "Democratic Republic of the ",
            "Democratic Republic of ",
            "Bolivarian Republic of ",
            "Co-operative Republic of ",
            "People's Republic of ",
            "Federal Republic of ",
            "Islamic Republic of ",
            "United Republic of ",
            "Arab Republic of ",
            "Republic of the ",
            "Republic of ",
            "Plurinational State of ",
            "Independent State of ",
            "Federated States of ",
            "State of ",
            "Kingdom of the ",
            "Kingdom of ",
            "Principality of ",
            "Grand Duchy of ",
            "Duchy of ",
            "Sultanate of ",
            "Commonwealth of the ",
            "Commonwealth of ",
            "Emirate of ",
        ];

        foreach ($prefixes as $prefix) {
            if (stripos($name, $prefix) === 0) {
                $short = trim(substr($name, strlen($prefix)));
                // Only strip when a sensible name remains.
                if (mb_strlen($short) > 1) {
                    return $short;
                }
            }
        }

        return $name;
    }
}