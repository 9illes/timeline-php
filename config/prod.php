<?php
use Silex\Provider\StatsdServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\SerializerServiceProvider;
use Rg\Silex\Provider\Markdown\MarkdownServiceProvider;

// configure your app for the production environment
/*
$app->register(new JDesrosiers\Silex\Provider\CorsServiceProvider(), array(
    "cors.allowOrigin" => "http://localhost:8080",
));
*/

$app->register(new HttpFragmentServiceProvider());

$app->register(new SerializerServiceProvider());

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.level' => Monolog\Logger::INFO,
    'monolog.logfile' => __DIR__.'/../var/logs/application.log',
));

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
$app['version'] = '1.0.0';

$app->register(new MarkdownServiceProvider(), array());
