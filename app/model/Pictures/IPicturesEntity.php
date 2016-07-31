<?php

namespace App\Model;

/**
 * Class IPicturesEntity
 * @package App\Model
 */
interface IPicturesEntity
{

	public function id();

	public function name();

	public function description();

	public function file();

	public function order();

	public function galleryId();

}
