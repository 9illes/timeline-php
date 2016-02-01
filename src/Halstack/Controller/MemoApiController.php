<?php
namespace Halstack\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
                'hint_1' => "",
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
            200,
            array("Content-Type" => $app['request']->getMimeType('json')
        ));
    }

    public function createAction(Application $app, Request $request)
    {
        $memo = new Memo;
        $memo->title = $request->get('title');
        $memo->content = $request->get('content');

        $memoDao = new MemoDao($app['db'], $app);
        $memo = $memoDao->insert($memo);
        $memo = $memoDao->find($memo->getId());

        $memoHal = new MemoHal($memo);

        return new Response(
            $memoHal->asJson(),
            201,
            array("Content-Type" => $app['request']->getMimeType('json')
        ));
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
