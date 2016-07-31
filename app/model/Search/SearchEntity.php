<?php

namespace App\Model;

/**
 * Class SearchEntity
 * @package App\Model
 */
class SearchEntity extends \Nette\Object implements ISearchEntity
{

	private $id;
	private $term;
	private $count;

	public function id($id = null) {
		if (!is_null($id))
			$this->id = $id;
		return $this->id;
	}

	public function term($term = null) {
		if (!is_null($term))
			$this->term = $term;
		return $this->term;
	}

	public function count($count = null) {
		if (!is_null($count))
			$this->count = $count;
		return $this->count;
	}

}
