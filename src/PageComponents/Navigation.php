<?php
declare(strict_types=1);

namespace Eightfold\Site\PageComponents;

use Stringable;

// use Psr\Http\Message\RequestInterface;
// use Psr\Http\Message\UriInterface;
//
use Eightfold\HTMLBuilder\Element;

use Eightfold\Amos\SiteInterface;
use Eightfold\Amos\FileSystem\Path;

class Navigation implements Stringable
{
    public static function create(SiteInterface $site, Path $requestPath): self
    {
        return new self($site, $requestPath);
    }

    final private function __construct(
        private readonly SiteInterface $site,
        private readonly Path $requestPath
    ) {
    }

    public function site(): SiteInterface
    {
        return $this->site;
    }

    public function __toString(): string
    {
        $links = [
            '/ Home',
            '/legal/ Legal'
        ];

        $l = [];
        $requestPath = $this->requestPath->toStringWithTrailingSlash();
        foreach ($links as $link) {
            list($href, $title) = explode(' ', $link, 2);

            $a = Element::a(
                $title
            )->props('href ' . $href);
            if ($requestPath === '/' and $href === $requestPath) {
                $a = Element::a(
                    $title
                )->props(
                    'href ' . $href,
                    'class current',
                    'aria-current true'
                );

            } elseif (
                $href !== '/' and
                str_starts_with($requestPath, $href)
            ) {
                $a = Element::a(
                    $title
                )->props(
                    'href ' . $href,
                    'class current',
                    'aria-current true'
                );

            }

            $l[] = Element::li($a);
        }

        return (string) Element::nav(
            Element::ul(...$l)->props('class col-' . count($links))
        )->props('is main-nav', 'aria-label primary navigation');
    }
}
