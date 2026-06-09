<?php

namespace App\Repositories\Frontend;

use Exception;
use App\Models\Page;
use App\Models\Setting;
use App\Models\LandingPage;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Helpers\Helpers;
use Illuminate\Contracts\Debug\ExceptionHandler;


class HomeRepository extends BaseRepository
{
    function model()
    {
        return Setting::class;
    }

    public function index()
    {
        try {
            return view('frontend.home.index');


        } catch(Exception $e) {

            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
