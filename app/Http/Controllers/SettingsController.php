<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Repositories\SettingsRepository;

class SettingsController extends Controller
{
    public $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->authorizeResource(Setting::class, 'setting');
        $this->repository = $repository;
    }
    public function index()
    {
        return $this->repository->index();
    }

    public function update(Request $request, Setting $setting)
    {
        return $this->repository->update($request, $setting?->id);
    }
}
