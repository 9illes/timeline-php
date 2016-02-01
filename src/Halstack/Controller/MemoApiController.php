<?php
namespace Halstack\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Nocarrier\Hal;

use Halstack\Entity\MemoDao;
use Halstack\Entity\Memo;

use Halstack\Resource\MemoHal;

class MemoApiController
{
    public function indexAction(Application $app, Request $request)
    {

        $memosHal = new Hal(
            $request->getRequestUri(),
            array(
                'welcome' => "Memos Api",
                'hint_1' => "The API talk only json",
                'hint_2' => ""
            )
        );

        $memosHal->addLink('curies', $request->getRequestUri().'rels/{rel}', array('name' => 'm', 'templated' => true));
        $memosHal->addLink('m:memos', '/api/memos');

        return new Response(
            $memosHal->asJson(),
            200,
            array("Content-Type" => $app['request']->getMimeType('json')
        ));
    }

    public function getAllAction(Application $app, Request $request)
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

        return new Response(
            $memosHal->asJson(),
            200,
            array("Content-Type" => $app['request']->getMimeType('json')
        ));
    }

    public function getOneAction(Application $app, Request $request, $id)
    {
        $memoDao = new MemoDao($app['db'], $app);
        $memo = $memoDao->find($id);

        if (empty($memo)) {
            return $this->notFoundAction($app, $request);
        }

        $memoHal = new MemoHal($memo);

        return new Response(
            $memoHal->asJson(),
            $request->get('created') ? 201 : 200,
            array("Content-Type" => $app['request']->getMimeType('json')
        ));
    }

    public function createAction(Application $app, Request $request)
    {
        // TODO use validator

        $memo = new Memo;
        $memo->title = $request->get('title');
        $memo->content = $request->get('content');

        $memoDao = new MemoDao($app['db'], $app);
        $memo = $memoDao->insert($memo);

        $subRequest = Request::create($app['url_generator']
            ->generate('api_memo', array('id' => $memo->getId(), 'created' => true)),
            'GET');
        return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    public function notFoundAction(Application $app, Request $request)
    {
        $response = new Hal(
            $request->getRequestUri(),
            array(
                "http status code" => 404,
                "http status description" => "Not Found",
                "message" => "document does not exist"
            )
        );

        return new Response(
            $response->asJson(),
            404,
            array("Content-Type" => $app['request']->getMimeType('json')
        ));
    }

}
