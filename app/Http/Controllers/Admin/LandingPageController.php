<?php

namespace App\Http\Controllers\Admin;

use App\Models\LandingPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\LandingPageRepository;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $repository;

    public function __construct(LandingPageRepository $repository)
    {
        $this->authorizeResource(LandingPage::class, 'landingPage');
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->repository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LandingPage $landingPage)
    {
        return $this->repository->update($request, $landingPage->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
