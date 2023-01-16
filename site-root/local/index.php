<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ini_set('realpath_cache_size', '4096');
ini_set('realpath_cache_ttl', '600');

require __DIR__ . '/../../vendor/autoload.php';

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

use Eightfold\Markdown\Markdown as MarkdownConverter;

use Eightfold\Amos\Site;

use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;

use Pro\Site\Templates\Page;
use Pro\Site\Templates\PageNotFound;

$psr17Factory = new Psr17Factory();

$request = (new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
))->fromGlobals();

(new SapiEmitter())->emit(
    Site::init(
        withDomain: 'http://pro.8fold:8889',
        contentIn: __DIR__ . '/../../content-root'
    )->setMarkdownConverter(
        MarkdownConverter::create()
        ->withConfig([
            'html_input' => 'allow'
        ])->defaultAttributes([
            Image::class => [
                'loading'  => 'lazy',
                'decoding' => 'async'
            ]
        ])->externalLinks([
            'open_in_new_window' => true,
            'internal_hosts'     => 'http://pro.8fold:8889'
        ])->minified()
        ->smartPunctuation()
        ->abbreviations()
        ->attributes() // for class on notices
    )->handle($request)
);
exit();
