<?php
namespace Halstack\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class MemoControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $app->match('/', 'Halstack\Controller\MemoController::indexAction')
            ->bind('index');

        $app->match('/create', 'Halstack\Controller\MemoController::createAction')
            ->bind('create');

        return $controllers;
    }
}
