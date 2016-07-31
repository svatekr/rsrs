<?php

namespace App\Model;

use Nette\Object;

/**
 * Class PicturesEntity
 * @package App\Model
 */
class PicturesEntity extends Object implements IPicturesEntity
{

	private $id;
	private $name;
	private $description;
	private $file;
	private $order;
	private $galleryId;

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

	public function file($file = null) {
		if (!is_null($file))
			$this->file = $file;
		return $this->file;
	}

	public function order($order = null) {
		if (!is_null($order))
			$this->order = $order;
		return $this->order;
	}

	public function galleryId($galleryId = null) {
		if (!is_null($galleryId))
			$this->galleryId = $galleryId;
		return $this->galleryId;
	}

}
