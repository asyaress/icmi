<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use App\Models\Video;
use App\Support\PublicCache;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = PublicCache::remember('sitemap:xml', function (): string {
            $urls = [
                ['loc' => route('home'), 'lastmod' => now()],
                ['loc' => route('sekilas-icmi'), 'lastmod' => now()],
                ['loc' => route('berita'), 'lastmod' => now()],
                ['loc' => route('opini-tokoh'), 'lastmod' => now()],
                ['loc' => route('info-media'), 'lastmod' => now()],
                ['loc' => route('galeri'), 'lastmod' => now()],
                ['loc' => route('icmi-tv'), 'lastmod' => now()],
            ];

            $news = Post::query()->type(Post::TYPE_NEWS)->published()->get(['slug', 'updated_at']);
            foreach ($news as $post) {
                $urls[] = ['loc' => route('berita.show', $post->slug), 'lastmod' => $post->updated_at];
            }

            $opinions = Post::query()->type(Post::TYPE_OPINION)->published()->get(['slug', 'updated_at']);
            foreach ($opinions as $post) {
                $urls[] = ['loc' => route('opini-tokoh.show', $post->slug), 'lastmod' => $post->updated_at];
            }

            $mediaInfos = Post::query()->type(Post::TYPE_MEDIA_INFO)->published()->get(['slug', 'updated_at']);
            foreach ($mediaInfos as $post) {
                $urls[] = ['loc' => route('info-media.show', $post->slug), 'lastmod' => $post->updated_at];
            }

            $galleries = Gallery::query()->published()->get(['slug', 'updated_at']);
            foreach ($galleries as $gallery) {
                $urls[] = ['loc' => route('galeri.show', $gallery->slug), 'lastmod' => $gallery->updated_at];
            }

            $videos = Video::query()->published()->get(['slug', 'updated_at']);
            foreach ($videos as $video) {
                $urls[] = ['loc' => route('icmi-tv.show', $video->slug), 'lastmod' => $video->updated_at];
            }

            return view('sitemap', compact('urls'))->render();
        }, (int) config('icmi.sitemap_cache_ttl', 900));

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
