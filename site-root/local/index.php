<?php // testing CD
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ini_set('realpath_cache_size', '4096');
ini_set('realpath_cache_ttl', '600');

require __DIR__ . '/../../vendor/autoload.php';

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Response;

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

use Eightfold\Markdown\Markdown;

use Eightfold\Amos\SiteWithRequest;
use Eightfold\Amos\FileSystem\Directories\Root as ContentRoot;
use Eightfold\Amos\FileSystem\Path;

use Eightfold\Site\Documents\Sitemap;

use Eightfold\Site\Templates\PageNotFound;
use Eightfold\Site\Templates\Page;

/** Partials **/
use Eightfold\Site\Partials\DateBlock;

$psr17Factory = new Psr17Factory();

$request = (new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
))->fromGlobals();

$site = SiteWithRequest::init(
    ContentRoot::fromString(__DIR__ . '/../../content-root'),
    $request
);

$emitter = new SapiEmitter();

if ($site === false) {
    $error500 = file_get_contents(__DIR__ . '/error-500.html');
    if ($error500 === false) {
        $error500 = 'Server error.';
    }

    $response = new Response(500, body: $error500);

    $emitter->emit($response);
    exit();
}

$path = Path::fromString(
    $request->getUri()->getPath()
);

if (str_ends_with($path->toString(), 'sitemap.xml')) {
    $response = new Sitemap();

    $emitter->emit($response($site));
    exit();
}

$converter = Markdown::create()
    ->withConfig([
        'html_input' => 'allow'
    ])->defaultAttributes([
        Image::class => [
            'loading'  => 'lazy',
            'decoding' => 'async'
        ]
    ])->externalLinks([
        'open_in_new_window' => true,
        'internal_hosts'     => $site->domain()->toString()
    ])->accessibleHeadingPermalinks([
        'min_heading_level' => 2,
        'max_heading_level' => 3,
        'symbol'            => '＃'
    ])->minified()
    ->smartPunctuation()
    ->descriptionLists()
    ->tables()
    ->attributes() // for class on notices
    ->abbreviations()
    ->partials([
        'partials' => [
            'dateblock'        => DateBlock::class,
    //         'next-previous'    => NextPrevious::class,
    //         'article-list'     => ArticleList::class,
    //         'paycheck-loglist' => PaycheckLogList::class,
    //         'original'         => OriginalContentNotice::class,
    //         'data'             => Data::class,
    //         'fi-experiments'   => FiExperiments::class,
    //         'full-nav'         => FullNav::class,
    //         'health-loglist'   => HealthLogList::class
        ],
        'extras' => [
            'meta'         => $site->publicMeta($path),
    //         'site'         => $site,
    //         'request_path' => $path->toString()
        ]
    ]);

if ($site->hasPublicMeta($path) === false) {
    $response = new Response(
        404,
        body: (string) PageNotFound::create($site)
            ->withConverter($converter)
            ->withRequestPath($path)
    );

    $emitter->emit($response);
    exit();
}

$body = (string) Page::create($site)
    ->withConverter($converter)
    ->withRequestPath($path);

$response = new Response(200, body: $body);

$emitter->emit($response);
exit();
