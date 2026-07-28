<?php

namespace Database\Seeders;

use App\Models\TaxiReportPrompt;
use App\Models\TaxiSetting;
use Illuminate\Database\Seeder;

class TaxiReportPromptSeeder extends Seeder
{
    /**
     * DOM section id (as used in the original report HTML) => prompt key.
     */
    private const SECTION_MAP = [
        'overview' => 'HERO-003',
        'snapshot' => 'METRIC-001',
        'analysis' => 'SEC-001',
        'prices' => 'SEC-004',
        'regions' => 'SEC-008',
        'demand' => 'SEC-009',
        'supply' => 'SEC-016',
        'rental' => 'SEC-017',
        'finance' => 'SEC-014',
        'tourism' => 'SEC-011',
        'foreign-buyers' => 'SEC-019',
        'tax' => 'SEC-021',
        'short-term' => 'SEC-022',
        'risks' => 'SEC-025',
        'due-diligence' => 'SEC-023',
        'faq' => 'FAQ-001',
        'sources' => 'SRC-001',
    ];

    public function run(): void
    {
        $path = database_path('data/taxi-report-prompts.txt');

        if (!is_file($path)) {
            $this->command?->warn("Prompt map not found at {$path}");

            return;
        }

        $raw = file_get_contents($path);
        $blocks = preg_split('/^-{20,}\s*$/m', $raw) ?: [];
        $sectionByKey = array_flip(self::SECTION_MAP);
        $order = 0;

        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') {
                continue;
            }

            if (!preg_match('/^\[([A-Z]+-\d+)\]\s*(.+)$/m', $block, $header)) {
                continue;
            }

            $key = $header[1];
            $label = trim($header[2]);

            $placement = null;
            if (preg_match('/^HTML PLACEMENT:\s*(.+)$/m', $block, $pm)) {
                $placement = trim($pm[1]);
            }

            $body = preg_replace('/^\[' . preg_quote($key, '/') . '\].*$/m', '', $block);
            $body = preg_replace('/^HTML PLACEMENT:.*$/m', '', (string) $body);

            TaxiReportPrompt::updateOrCreate(
                ['key' => $key],
                [
                    'label' => $label,
                    'placement' => $placement,
                    'section_id' => $sectionByKey[$key] ?? null,
                    'prompt_text' => trim((string) $body),
                    'is_active' => true,
                    'sort_order' => $order += 10,
                ]
            );
        }

        TaxiSetting::put('auto_refresh_enabled', TaxiSetting::get('auto_refresh_enabled', '1'));
        TaxiSetting::put('refresh_interval_days', TaxiSetting::get('refresh_interval_days', '30'));
        TaxiSetting::put('ai_provider', TaxiSetting::get('ai_provider', 'openai'));
        TaxiSetting::put('translation_provider', TaxiSetting::get('translation_provider', 'gemini'));
        TaxiSetting::put('reports_per_run', TaxiSetting::get('reports_per_run', '5'));
    }
}
