<?php
namespace Halstack\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Nocarrier\Hal;

use Halstack\Entity\MemoDao;
use Halstack\Entity\Memo;

use Halstack\Resource\MemoHal;

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

    /* API */

    public function apiIndexAction(Application $app, Request $request)
    {





    }

    public function apiGetAllAction(Application $app, Request $request)
    {
        $memoDao = new MemoDao($app['db'], $app);
        $memoCollection = $memoDao->findAll();

        $halMemoCollection = array();
        foreach($memoCollection as $memo) {
            $halMemo = new MemoHal($memo);
            $halMemoCollection[] = $halMemo->asArray(true);
        }

        $memosHal = new Hal(
            $request->getRequestUri(),
            array('m:memos' =>$halMemoCollection));

        $acceptToFormat = array('application/json' => 'json');
        $accept = $request->headers->get('Accept');
        $format = isset($acceptToFormat[$accept]) ? $acceptToFormat[$accept] : 'json'; // haha

        return new Response(
            $memosHal->asJson(),
            200,
            array("Content-Type" => $app['request']->getMimeType($format)
        ));
    }

    public function getByIdAction(Application $app, Request $request, $id)
    {
        $memoDao = new MemoDao($app['db'], $app);
        $memoCollection = $memoDao->find($id);

        if (empty($memoCollection)) {
            return $this->notFoundAction($app, $request);
        }

        $memo = array_shift($memoCollection);

        $memoHal = new MemoHal($memo);


        $acceptToFormat = array('application/json' => 'json');
        $accept = $request->headers->get('Accept');
        $format = isset($acceptToFormat[$accept]) ? $acceptToFormat[$accept] : 'json'; // haha

        return new Response(
            $memoHal->asJson(),
            200,
            array("Content-Type" => $app['request']->getMimeType($format)
        ));
    }

    public function notFoundAction(Application $app, Request $request)
    {
        $acceptToFormat = array('application/json' => 'json');
        $accept = $request->headers->get('Accept');
        $format = isset($acceptToFormat[$accept]) ? $acceptToFormat[$accept] : 'json'; // haha

        $response = new Hal(
            $request->getRequestUri(),
            array(
                "http status code" => 404,
                "http status description" => "Not Found",
                "message" => "document does not exist"
            )
        );

        return new Response(
            /*$app['serializer']->serialize($response, $format),*/
            $response->asJson(),
            404,
            array("Content-Type" => $app['request']->getMimeType($format)
        ));
    }

}
