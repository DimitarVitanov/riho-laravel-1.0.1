<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminManagerController extends Controller
{
    public function index()
    {
        $managers = User::where('role', 'manager')
            ->with('managerProfile')
            ->latest()
            ->paginate(20);

        return view('admin.villabit.managers.index', compact('managers'));
    }

    public function show(User $user)
    {
        $user->load('managerProfile');
        return view('admin.villabit.managers.show', compact('user'));
    }
}
