<?php

namespace App\Model;

use Nette\Object;

class GalleriesEntity extends Object implements IGalleriesEntity
{

   private $id;
   private $name;
   private $description;
   private $url;

    public function id($id = null) {
        if (!is_null($id))
            $this->id = $id;
        return $this->id;
    }

    public function name($name = null) {
        if (!is_null($name))
            $this->name = $name;
        return $this->name;
    }

    public function description($description = null) {
        if (!is_null($description))
            $this->description = $description;
        return $this->description;
    }

    public function url($url = null) {
        if (!is_null($url))
            $this->url = $url;
        return $this->url;
    }

}
