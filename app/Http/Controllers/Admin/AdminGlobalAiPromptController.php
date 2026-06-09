<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalAiPrompt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGlobalAiPromptController extends Controller
{
    public function index()
    {
        $prompts = GlobalAiPrompt::orderBy('feature_key')->get();
        return view('admin.villabit.ai-prompts.index', compact('prompts'));
    }

    public function edit(GlobalAiPrompt $prompt)
    {
        return view('admin.villabit.ai-prompts.edit', compact('prompt'));
    }

    public function update(Request $request, GlobalAiPrompt $prompt)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'prompt_text' => 'required|string',
            'ai_model_provider' => 'required|string|max:50',
            'ai_model_name' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $prompt->update([
            'label' => $request->label,
            'prompt_text' => $request->prompt_text,
            'ai_model_provider' => $request->ai_model_provider,
            'ai_model_name' => $request->ai_model_name,
            'is_active' => $request->boolean('is_active'),
            'updated_by_user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.villabit.ai-prompts.index')
            ->with('success', 'AI Prompt updated successfully.');
    }
}
