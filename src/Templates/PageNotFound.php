<?php
declare(strict_types=1);

namespace Eightfold\Site\Templates;

use Stringable;

use Eightfold\Markdown\Markdown;

use Eightfold\Amos\SiteInterface;
use Eightfold\Amos\FileSystem\Path;
use Eightfold\Amos\FileSystem\Filename;
use Eightfold\Amos\PlainText\PrivateFile;
use Eightfold\Amos\ObjectsFromJson\PrivateObject;

use Eightfold\Site\Documents\Main;

class PageNotFound implements Stringable
{
    private Markdown $converter;

    private Path $requestPath;

    public static function create(SiteInterface $site): self
    {
        return new self($site);
    }

    final private function __construct(private readonly SiteInterface $site)
    {
    }

    private function site(): SiteInterface
    {
        return $this->site;
    }

    public function withRequestPath(Path $requestPath): self
    {
        $this->requestPath = $requestPath;
        return $this;
    }

    public function requestPath(): Path
    {
        return $this->requestPath;
    }

    public function withConverter(Markdown $converter): self
    {
        $this->converter = $converter;
        return $this;
    }

    private function converter(): Markdown
    {
        return $this->converter;
    }

    public function __toString(): string
    {
        $meta = PrivateObject::inRoot(
            $this->site()->contentRoot(),
            Filename::fromString('meta.json'),
            Path::fromString('/errors/404')
        );

        $content = PrivateFile::inRoot(
            $this->site()->contentRoot(),
            Filename::fromString('content.md'),
            Path::fromString('/errors/404')
        );

        if ($meta->notFound() or $content->notFound()) {
            return '404: Page not found.';
        }

        return (string) Main::create($this->site(), $this->requestPath())
            ->setPageTitle($meta->title())
            ->setBody(
                $this->converter()->convert($content->toString())
            );
    }
}
