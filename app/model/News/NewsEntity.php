<?php

namespace App\Model;

class NewsEntity extends \Nette\Object implements INewsEntity
{

   private $id;
   private $active;
   private $title;
   private $url;
   private $text;
   private $dateAdd;

    public function id($id = null) {
        if (!is_null($id))
            $this->id = $id;
        return $this->id;
    }

    public function active($active = null) {
        if (!is_null($active))
            $this->active = $active;
        return $this->active;
    }

    public function title($title = null) {
        if (!is_null($title))
            $this->title = $title;
        return $this->title;
    }

    public function url($url = null) {
        if (!is_null($url))
            $this->url = $url;
        return $this->url;
    }

    public function text($text = null) {
        if (!is_null($text))
            $this->text = $text;
        return $this->text;
    }

    public function dateAdd($dateAdd = null) {
        if (!is_null($dateAdd))
            $this->dateAdd = $dateAdd;
        return $this->dateAdd;
    }

}
