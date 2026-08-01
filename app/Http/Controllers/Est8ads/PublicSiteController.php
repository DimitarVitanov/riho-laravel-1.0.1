<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PublicSiteController extends Controller
{
    public function index(): View
    {
        return view('est8ads.public.index');
    }

    public function contact(): View
    {
        return view('est8ads.public.contact');
    }

    public function privacy(): View
    {
        return view('est8ads.public.privacy');
    }

    public function terms(): View
    {
        return view('est8ads.public.terms');
    }
}
