<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Services\Est8ads\PanelData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, PanelData $panelData): View
    {
        return view('est8ads.user.dashboard', [
            'est8adsData' => $panelData->forUser($request->user()),
        ]);
    }
}
