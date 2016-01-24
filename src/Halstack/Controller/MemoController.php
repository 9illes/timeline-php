<?php
namespace Halstack\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Halstack\Entity\MemoDao;
use Halstack\Entity\Memo;

class MemoController
{
    public function indexAction(Application $app, Request $request)
    {
        $memoDao = new MemoDao($app['db'], $app);
        $memoCollection = $memoDao->findAll();
        return $app['twig']->render('index.twig', array('memoCollection' => $memoCollection));
    }

    public function createAction(Application $app, Request $request)
    {
        $memo = new Memo;
        $memo->title = $request->get('title');
        $memo->content = $request->get('content');

        $memoDao = new MemoDao($app['db'], $app);
        $memoDao->insert($memo);

        return $app->redirect('/');
    }
}
