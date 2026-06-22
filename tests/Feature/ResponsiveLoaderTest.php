<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponsiveLoaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_inner_page_include_branded_loader(): void
    {
        foreach ([route('home'), route('berita')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('id="icmi-page-loader"', false)
                ->assertSee('logo-icmi.png', false)
                ->assertSee('/icmi-assets/loader.js', false)
                ->assertSee('/icmi-assets/theme.css', false);
        }
    }

    public function test_deployment_safe_assets_are_served_through_laravel(): void
    {
        foreach (['theme.css', 'loader.js', 'category-card.jpg', 'portal-banner.jpg', 'promo-card.jpg'] as $asset) {
            $this->get(route('icmi-assets', ['asset' => $asset]))
                ->assertOk()
                ->assertHeader('Cache-Control');
        }

        $this->get(route('icmi-assets', ['asset' => 'not-allowed.txt']))->assertNotFound();
    }
}
