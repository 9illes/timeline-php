<?php
namespace Halstack\Entity;

class Memo
{
    public $id;
    public $title;
    public $content;
    public $created_at;
    public $updated_at;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
