<?php

namespace App\Model;

interface IPagesEntity 
{

   public function id();

   public function parent();

   public function lft();

   public function rgt();

   public function level();

   public function title();

   public function name();

   public function menuTitle();

   public function url();

   public function description();

   public function keywords();

   public function perex();

   public function text();

   public function secret();

   public function secretText();

   public function lang();

   public function active();

   public function inMenu();

   public function onHomepage();

   public function date();

   public function upDate();

   public function pictureName();

   public function pictureDescription();

   public function galleryIds();

}
