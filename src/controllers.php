<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Halstack\Provider\MemoController;
use Halstack\Provider\MemoApiController;

use Halstack\Provider\MemoControllerProvider;
use Halstack\Provider\MemoApiControllerProvider;

/* Errors */

$app->error(function (\Exception $e, $code) use ($app) {
    return;

    if ($app['debug']) {
        return;
    }

    // 404.twig, or 40x.twig, or 4xx.twig, or error.twig
    $templates = array(
        './errors/'.$code.'.twig',
        './errors/'.substr($code, 0, 2).'x.twig',
        './errors/'.substr($code, 0, 1).'xx.twig',
        './errors/default.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

/*
 * Mounts
 */

$app->mount('/api', new MemoApiControllerProvider);
$app->mount('/', new MemoControllerProvider);
