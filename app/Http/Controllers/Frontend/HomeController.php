<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Frontend\HomeRepository;

class HomeController extends Controller
{
    public $repository;

    public function __construct(HomeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->index();
    }
}
