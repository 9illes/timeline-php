<?php
date_default_timezone_set('Europe/Paris');

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__.'/../var/db/app.db',
        'charset' => 'utf8',
    ),
));

$app->register(new TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

/* DB init */

$schema = "CREATE TABLE IF NOT EXISTS memo (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, title VARCHAR(128) NOT NULL, content TEXT NOT NULL, created_at DATETIME, updated_at DATETIME);";
$statement = $app['db']->prepare($schema);
$statement->execute();

/* CORS */

$app->before(function (Request $request) {
	// Accepting a JSON Request Body
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
    // Accepting CORS
    header('Access-Control-Allow-Origin: *');
});

return $app;
