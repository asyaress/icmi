<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomeSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeSettingController extends Controller
{
    public const DEFAULTS = [
        'site_name' => 'ICMI Kaltim',
        'site_tagline' => 'Website resmi ICMI Kaltim',
        'meta_default_description' => 'Portal resmi ICMI Kaltim untuk berita, opini tokoh, info media, galeri, dan ICMI TV.',
        'home_show_hero' => '1',
        'home_show_trending' => '1',
        'home_show_featured' => '1',
        'home_show_video' => '1',
        'home_show_trending_today' => '1',
        'home_show_main_posts' => '1',
        'home_hero_limit' => '3',
        'home_trending_limit' => '7',
        'home_featured_limit' => '4',
        'home_video_limit' => '5',
        'home_trending_today_limit' => '4',
        'home_main_posts_limit' => '6',
    ];

    public function edit(): View
    {
        $settings = [];
        foreach (self::DEFAULTS as $key => $default) {
            $settings[$key] = (string) Setting::get($key, $default);
        }

        return view('admin.settings.home', compact('settings'));
    }

    public function update(UpdateHomeSettingsRequest $request): RedirectResponse
    {
        $booleans = [
            'home_show_hero',
            'home_show_trending',
            'home_show_featured',
            'home_show_video',
            'home_show_trending_today',
            'home_show_main_posts',
        ];

        $fields = array_keys(self::DEFAULTS);

        foreach ($fields as $field) {
            if (in_array($field, $booleans, true)) {
                Setting::set($field, $request->boolean($field) ? '1' : '0', 'home');
                continue;
            }

            Setting::set($field, (string) $request->input($field, self::DEFAULTS[$field]), 'home');
        }

        return redirect()
            ->route('admin.settings.home.edit')
            ->with('success', 'Pengaturan homepage berhasil diupdate.');
    }
}
