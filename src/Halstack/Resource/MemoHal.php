<?php
namespace Halstack\Resource;

use Nocarrier\Hal;

use Halstack\Entity\Memo;

class MemoHal
{

    private $hrefPattern = '/api/memo/%d';
    private $resource = null;
    private $memo = null;

    public function __construct(Memo $memo)
    {
        $this->memo = $memo;

        $this->resource = new Hal(
            $this->getHref(),
            $this->asArray()
        );
    }

    public function getHref()
    {
        return sprintf($this->hrefPattern, $this->memo->getId());
    }

    public function asArray($href = false)
    {
        $arr = array(
            '_id' => $this->memo->getId(),
            'title' => $this->memo->getTitle(),
            'content' => $this->memo->getContent(),
            '_created_at' => $this->memo->getCreatedAt(),
        );

        if ($href) {
            $arr['href'] = $this->getHref();
            $arr['templated'] = false;
        }

        return $arr;
    }

    public function asJson()
    {
        return $this->resource->asJson();
    }
}
