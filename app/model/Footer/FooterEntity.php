<?php

namespace App\Model;

/**
 * Class FooterEntity
 * @package App\Model
 */
class FooterEntity extends \Nette\Object implements IFooterEntity
{

	private $id;
	private $text;

	public function id($id = null) {
		if (!is_null($id))
			$this->id = $id;
		return $this->id;
	}

	public function text($text = null) {
		if (!is_null($text))
			$this->text = $text;
		return $this->text;
	}

}
