<?php

namespace App\Services\WhiteLabel;

use App\Models\SchoolThemeSetting;

class SchoolThemeService
{
    /**
     * Get or create a default theme setting for a school.
     */
    public function getOrCreateTheme(int $schoolId): SchoolThemeSetting
    {
        $theme = SchoolThemeSetting::query()
            ->where('school_id', $schoolId)
            ->first();

        if (!$theme) {
            $theme = SchoolThemeSetting::query()->create([
                'school_id' => $schoolId,
                'theme_name' => 'Default Theme',
                'primary_color' => '#059669', // Calm Emerald
                'secondary_color' => '#060b14', // Deep Slate
                'accent_color' => '#10b981', // Emerald accent
                'text_color' => '#0f172a', // Slate-900
                'background_color' => '#f8fafc', // Slate-50
                'sidebar_style' => 'default',
                'header_style' => 'default',
                'login_layout' => 'centered',
                'card_radius' => 'md',
                'button_radius' => 'md',
                'is_active' => true,
            ]);
        }

        return $theme;
    }

    /**
     * Update school theme configurations.
     */
    public function updateTheme(int $schoolId, array $data, ?int $userId = null): SchoolThemeSetting
    {
        $theme = $this->getOrCreateTheme($schoolId);

        if ($userId) {
            $data['updated_by'] = $userId;
        }

        $theme->update($data);

        return $theme;
    }

    /**
     * Compile CSS variables representation of the school's theme settings.
     */
    public function generateCssVariables(SchoolThemeSetting $theme): string
    {
        $radiusMap = [
            'none' => '0px',
            'sm' => '0.125rem',
            'md' => '0.375rem',
            'lg' => '0.5rem',
            'xl' => '0.75rem',
            '2xl' => '1rem',
            'full' => '9999px',
        ];

        $cardRadius = $radiusMap[$theme->card_radius] ?? '0.375rem';
        $buttonRadius = $radiusMap[$theme->button_radius] ?? '0.375rem';

        // Sanitise inputs defensively (regex validated on request, but cast as string here)
        $primary = esc_css($theme->primary_color);
        $secondary = esc_css($theme->secondary_color);
        $accent = esc_css($theme->accent_color);
        $text = esc_css($theme->text_color);
        $bg = esc_css($theme->background_color);

        return "
            :root {
                --school-primary: {$primary};
                --school-secondary: {$secondary};
                --school-accent: {$accent};
                --school-text: {$text};
                --school-background: {$bg};
                --school-radius-card: {$cardRadius};
                --school-radius-button: {$buttonRadius};
            }
        ";
    }
}

/**
 * Defensive CSS sanitisation helper.
 */
if (!function_exists('esc_css')) {
    function esc_css(string $value): string
    {
        return preg_match('/^#[0-9A-Fa-f]{3,6}$/', $value) ? $value : '#2563eb';
    }
}
