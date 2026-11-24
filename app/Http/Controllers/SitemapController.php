<?php

namespace App\Http\Controllers;

use App\Support\Seo\SitemapBuilder;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(SitemapBuilder $sitemap): Response
    {
        return $this->xml($sitemap->index());
    }

    public function pages(SitemapBuilder $sitemap): Response
    {
        return $this->xml($sitemap->pages());
    }

    public function locations(SitemapBuilder $sitemap): Response
    {
        return $this->xml($sitemap->locations());
    }

    public function pets(SitemapBuilder $sitemap, int $page): Response
    {
        abort_if($page < 1 || $page > $sitemap->petPageCount(), 404);

        return $this->xml($sitemap->pets($page));
    }

    private function xml(string $body): Response
    {
        return response($body, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
