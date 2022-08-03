<?php
declare(strict_types=1);

namespace Pro\Site\Templates;

use Eightfold\XMLBuilder\Contracts\Buildable;

use Psr\Http\Message\RequestInterface;

use Eightfold\HTMLBuilder\Document;
use Eightfold\HTMLBuilder\Element;

use Eightfold\Amos\Site;

use Eightfold\Amos\PageComponents\PageTitle;

class Page implements Buildable
{
    public static function create(Site $site): self
    {
        return new self($site);
    }

    final private function __construct(private Site $site)
    {
    }

    public function site(): Site
    {
        return $this->site;
    }

    public function build(): string
    {
        return Document::create(
            PageTitle::create($this->site())->build()
        )->body(
            Element::p('hello')
        )->build();
    }

    public function __toString(): string
    {
        return $this->build();
    }
}
