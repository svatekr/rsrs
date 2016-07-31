<?php

namespace App\Model;

/**
 * Class ISearchEntity
 * @package App\Model
 */
interface ISearchEntity
{

	public function id();

	public function term();

	public function count();

}
