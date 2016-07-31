<?php

namespace App\Model;

/**
 * Class IArticlesEntity
 * @package App\Model
 */
interface IArticlesEntity
{

	public function id();

	public function idTheme();

	public function url();

	public function title();

	public function description();

	public function keywords();

	public function name();

	public function text();

	public function active();

	public function date();

	public function pictureName();

	public function pictureDescription();

	public function galleryIds();

}
