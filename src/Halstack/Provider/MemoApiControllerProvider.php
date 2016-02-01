<?php
namespace Halstack\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class MemoApiControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'Halstack\Controller\MemoApiController::indexAction')
            ->bind('api_welcome');

        $controllers->get('/memos', 'Halstack\Controller\MemoApiController::getAllAction')
            ->bind('api_memos');

        $controllers->post('/memos', 'Halstack\Controller\MemoApiController::createAction')
            ->bind('api_create_memo');

        $controllers->get('/memo/{id}', 'Halstack\Controller\MemoApiController::getOneAction')
            ->assert("id", "\d+")
            ->bind('api_memo');

        return $controllers;
    }
}
