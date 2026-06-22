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
                ->assertSee('icmi-page-loader.js', false)
                ->assertSee('icmi-theme.css?v=', false);
        }
    }
}
