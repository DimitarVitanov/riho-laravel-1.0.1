<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Services\Est8ads\PanelData;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function index(PanelData $panelData): View
    {
        return view('est8ads.admin.dashboard', [
            'est8adsData' => $panelData->forAdmin(),
        ]);
    }
}
