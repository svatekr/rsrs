<?php

namespace App\Model;

class PagesEntity extends \Nette\Object implements IPagesEntity
{

   private $id;
   private $parent;
   private $lft;
   private $rgt;
   private $level;
   private $title;
   private $name;
   private $menuTitle;
   private $url;
   private $description;
   private $keywords;
   private $perex;
   private $text;
   private $secret;
   private $secretText;
   private $lang;
   private $active;
   private $inMenu;
   private $onHomepage;
   private $date;
   private $upDate;
   private $pictureName;
   private $pictureDescription;
   private $galleryIds;

    public function id($id = null) {
        if (!is_null($id))
            $this->id = $id;
        return $this->id;
    }

    public function parent($parent = null) {
        if (!is_null($parent))
            $this->parent = $parent;
        return $this->parent;
    }

    public function lft($lft = null) {
        if (!is_null($lft))
            $this->lft = $lft;
        return $this->lft;
    }

    public function rgt($rgt = null) {
        if (!is_null($rgt))
            $this->rgt = $rgt;
        return $this->rgt;
    }

    public function level($level = null) {
        if (!is_null($level))
            $this->level = $level;
        return $this->level;
    }

    public function title($title = null) {
        if (!is_null($title))
            $this->title = $title;
        return $this->title;
    }

    public function name($name = null) {
        if (!is_null($name))
            $this->name = $name;
        return $this->name;
    }

    public function menuTitle($menuTitle = null) {
        if (!is_null($menuTitle))
            $this->menuTitle = $menuTitle;
        return $this->menuTitle;
    }

    public function url($url = null) {
        if (!is_null($url))
            $this->url = $url;
        return $this->url;
    }

    public function description($description = null) {
        if (!is_null($description))
            $this->description = $description;
        return $this->description;
    }

    public function keywords($keywords = null) {
        if (!is_null($keywords))
            $this->keywords = $keywords;
        return $this->keywords;
    }

    public function perex($perex = null) {
        if (!is_null($perex))
            $this->perex = $perex;
        return $this->perex;
    }

    public function text($text = null) {
        if (!is_null($text))
            $this->text = $text;
        return $this->text;
    }

    public function secret($secret = null) {
        if (!is_null($secret))
            $this->secret = $secret;
        return $this->secret;
    }

    public function secretText($secretText = null) {
        if (!is_null($secretText))
            $this->secretText = $secretText;
        return $this->secretText;
    }

    public function lang($lang = null) {
        if (!is_null($lang))
            $this->lang = $lang;
        return $this->lang;
    }

    public function active($active = null) {
        if (!is_null($active))
            $this->active = $active;
        return $this->active;
    }

    public function inMenu($inMenu = null) {
        if (!is_null($inMenu))
            $this->inMenu = $inMenu;
        return $this->inMenu;
    }

    public function onHomepage($onHomepage = null) {
        if (!is_null($onHomepage))
            $this->onHomepage = $onHomepage;
        return $this->onHomepage;
    }

    public function date($date = null) {
        if (!is_null($date))
            $this->date = $date;
        return $this->date;
    }

    public function upDate($upDate = null) {
        if (!is_null($upDate))
            $this->upDate = $upDate;
        return $this->upDate;
    }

    public function pictureName($pictureName = null) {
        if (!is_null($pictureName))
            $this->pictureName = $pictureName;
        return $this->pictureName;
    }

    public function pictureDescription($pictureDescription = null) {
        if (!is_null($pictureDescription))
            $this->pictureDescription = $pictureDescription;
        return $this->pictureDescription;
    }

    public function galleryIds($galleryIds = null) {
        if (!is_null($galleryIds))
            $this->galleryIds = $galleryIds;
        return $this->galleryIds;
    }

}
