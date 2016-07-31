<?php

namespace App\Model;

interface INewsEntity 
{

   public function id();

   public function active();

   public function title();

   public function url();

   public function text();

   public function dateAdd();

}
