<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CapitalCall;
use Illuminate\Http\Request;

class AdminCapitalCallController extends Controller
{
    public function index()
    {
        $capitalCalls = CapitalCall::with(['project', 'investorUser'])
            ->latest()
            ->paginate(20);

        return view('admin.villabit.capital-calls.index', compact('capitalCalls'));
    }

    public function show(CapitalCall $capitalCall)
    {
        $capitalCall->load('project', 'investorUser', 'investment');
        return view('admin.villabit.capital-calls.show', compact('capitalCall'));
    }

    public function markPaid(Request $request, CapitalCall $capitalCall)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $capitalCall->update([
            'status' => 'paid',
            'paid_amount' => $request->paid_amount,
            'paid_at' => now(),
            'payment_reference' => $request->payment_reference,
        ]);

        return back()->with('success', 'Capital call marked as paid.');
    }
}
