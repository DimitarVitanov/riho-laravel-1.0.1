<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiApiLog;
use Illuminate\Http\Request;

class AdminAiSettingsController extends Controller
{
    public function index()
    {
        $keys = [
            'openai'    => ['key' => config('ai.openai.api_key'),    'model' => config('ai.openai.default_model')],
            'gemini'    => ['key' => config('ai.google.api_key'),    'model' => config('ai.google.default_model')],
            'anthropic' => ['key' => config('ai.anthropic.api_key'), 'model' => config('ai.anthropic.default_model')],
            'copyscape' => [
                'key'      => config('ai.uniqueness.copyscape_api_key'),
                'username' => config('ai.uniqueness.copyscape_username'),
            ],
        ];

        $monthly  = AiApiLog::monthlySummary();
        $allTime  = AiApiLog::selectRaw('provider, SUM(api_calls_count) as total_calls, SUM(cost_estimate_usd) as total_cost')
            ->groupBy('provider')
            ->get()
            ->keyBy('provider')
            ->toArray();

        $recentLogs = AiApiLog::latest()->take(50)->get();

        return view('admin.villabit.ai-settings.index', compact('keys', 'monthly', 'allTime', 'recentLogs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'OPENAI_API_KEY'       => 'nullable|string',
            'OPENAI_DEFAULT_MODEL' => 'nullable|string',
            'GOOGLE_AI_API_KEY'    => 'nullable|string',
            'GOOGLE_AI_DEFAULT_MODEL' => 'nullable|string',
            'ANTHROPIC_API_KEY'    => 'nullable|string',
            'COPYSCAPE_API_KEY'    => 'nullable|string',
            'COPYSCAPE_USERNAME'   => 'nullable|string',
        ]);

        $toUpdate = $request->only([
            'OPENAI_API_KEY', 'OPENAI_DEFAULT_MODEL',
            'GOOGLE_AI_API_KEY', 'GOOGLE_AI_DEFAULT_MODEL',
            'ANTHROPIC_API_KEY', 'ANTHROPIC_DEFAULT_MODEL',
            'COPYSCAPE_API_KEY', 'COPYSCAPE_USERNAME',
        ]);

        $this->updateEnvFile(array_filter($toUpdate));

        return redirect()->route('admin.villabit.ai-settings.index')
            ->with('success', 'AI API keys updated. Restart the application for changes to take effect.');
    }

    private function updateEnvFile(array $data): void
    {
        $envPath = app()->environmentFilePath();
        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $value = str_contains($value, ' ') ? "\"{$value}\"" : $value;

            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
