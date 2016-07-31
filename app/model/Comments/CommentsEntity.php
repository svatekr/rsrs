<?php

namespace App\Model;

use Nette\Object;

class CommentsEntity extends Object implements ICommentsEntity
{

   private $id;
   private $articleId;
   private $pageId;
   private $date;
   private $author;
   private $text;
   private $allowed;

    public function id($id = null) {
        if (!is_null($id))
            $this->id = $id;
        return $this->id;
    }

    public function articleId($articleId = null) {
        if (!is_null($articleId))
            $this->articleId = $articleId;
        return $this->articleId;
    }

    public function pageId($pageId = null) {
        if (!is_null($pageId))
            $this->pageId = $pageId;
        return $this->pageId;
    }

    public function date($date = null) {
        if (!is_null($date))
            $this->date = $date;
        return $this->date;
    }

    public function author($author = null) {
        if (!is_null($author))
            $this->author = $author;
        return $this->author;
    }

    public function text($text = null) {
        if (!is_null($text))
            $this->text = $text;
        return $this->text;
    }

    public function allowed($allowed = null) {
        if (!is_null($allowed))
            $this->allowed = $allowed;
        return $this->allowed;
    }

}
