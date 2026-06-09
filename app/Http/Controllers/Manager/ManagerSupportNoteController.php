<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerSupportNoteController extends Controller
{
    public function index()
    {
        return view('manager.support-notes.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'note' => 'required|string|max:5000',
        ]);

        // Support notes could be stored in notes_internal on user or a dedicated table
        $user = \App\Models\User::findOrFail($request->user_id);
        $existing = $user->notes_internal ?? '';
        $timestamp = now()->format('Y-m-d H:i');
        $managerName = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $newNote = "[{$timestamp} - {$managerName}] {$request->note}";

        $user->update([
            'notes_internal' => $existing ? $existing . "\n---\n" . $newNote : $newNote,
        ]);

        return back()->with('success', 'Support note added.');
    }
}
