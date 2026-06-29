<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvestorDocumentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->investorProfile;
        $documents = [];

        $path = 'investor-documents/' . $user->id;
        if (Storage::disk('local')->exists($path)) {
            $files = Storage::disk('local')->files($path);
            foreach ($files as $file) {
                $documents[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'date' => Storage::disk('local')->lastModified($file),
                ];
            }

            usort($documents, fn ($a, $b) => $b['date'] <=> $a['date']);
        }

        $countries = DB::table('countries')->orderBy('iso_3166_2')->get(['name', 'iso_3166_2']);

        return view('investor.documents.index', compact('documents', 'user', 'profile', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $user = Auth::user();
        $path = 'investor-documents/' . $user->id;
        $request->file('document')->store($path, 'local');

        return back()->with('success', 'Document uploaded successfully.');
    }
}
