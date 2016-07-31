<?php

namespace App\Model;

interface ICommentsEntity
{

	public function id();

	public function articleId();

	public function pageId();

	public function date();

	public function author();

	public function text();

	public function allowed();

}
