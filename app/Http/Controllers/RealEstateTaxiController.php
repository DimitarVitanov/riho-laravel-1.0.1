<?php

namespace App\Http\Controllers;

use App\Models\TaxiPage;
use Illuminate\Http\Request;

class RealEstateTaxiController extends Controller
{
    public function home(Request $request)
    {
        $locale = $request->query('lang', app()->getLocale());

        $page = TaxiPage::forLocale($locale) ?? TaxiPage::forLocale('en');

        return view('realestate-taxi.home', compact('page', 'locale'));
    }
}
