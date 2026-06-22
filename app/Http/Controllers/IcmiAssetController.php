<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IcmiAssetController extends Controller
{
    private const ASSETS = [
        'theme.css' => ['assets/css/icmi-theme.css', 'text/css; charset=UTF-8'],
        'loader.js' => ['assets/js/icmi-page-loader.js', 'application/javascript; charset=UTF-8'],
        'category-card.jpg' => ['assets/images/icmi-category-card-v1.jpg', 'image/jpeg'],
        'portal-banner.jpg' => ['assets/images/icmi-portal-banner-v1.jpg', 'image/jpeg'],
        'promo-card.jpg' => ['assets/images/icmi-promo-card-v1.jpg', 'image/jpeg'],
        'trending-background.png' => ['back.png', 'image/png'],
    ];

    public function __invoke(string $asset): BinaryFileResponse
    {
        abort_unless(isset(self::ASSETS[$asset]), 404);

        [$relativePath, $mimeType] = self::ASSETS[$asset];
        $path = public_path($relativePath);

        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=604800, immutable',
        ]);
    }
}
