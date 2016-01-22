<?php
namespace Halstack\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MemoController
{
    public function indexAction(Application $app, Request $request)
    {
        $memoCollection = $app['db']->fetchAll('SELECT * FROM memo ORDER BY id DESC');
        return $app['twig']->render('index.twig', array('memoCollection' => $memoCollection));
    }

    public function memoformAction(Application $app, Request $request)
    {
        return $app['twig']->render('memo_form.twig');
    }

    public function createAction(Application $app, Request $request)
    {
        $insert = 'INSERT INTO memo (content, created_at, updated_at) VALUES (?, ?, ?)';
        $now = new \DateTime;
        $app['db']->insert('memo', array(
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'created_at' => $now->format('Y-m-d H:i:s'),
            'updated_at' => $now->format('Y-m-d H:i:s'),
        ));
        return $app->redirect('/');
    }
}
