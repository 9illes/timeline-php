<?php
namespace Halstack\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class MemoApiControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->match('/', 'Halstack\Controller\MemoController::apiIndexAction')
            ->bind('version');

        $controllers->match('/memos', 'Halstack\Controller\MemoController::apiGetAllAction')
            ->bind('api_memos');

        $controllers->get('/memo/{id}', 'Halstack\Controller\MemoController::getByIdAction')
            ->assert("id", "\d+")
            ->bind('api_memo');

        return $controllers;
    }
}
