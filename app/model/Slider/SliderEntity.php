<?php

namespace App\Model;

use Nette\Object;

interface ISliderEntity {

	public function id();

	public function imgName();

	public function imgTitle();

	public function imgDescription();
}

class SliderEntity extends Object implements ISliderEntity {

	private $id;
	private $imgName;
	private $imgTitle;
	private $imgDescription;

	/**
	 * @param null $id
	 * @return $this
	 */
	public function id($id = null) {
		if (is_null($id))
			return $this->id;
		$this->id = $id;
		return $this;
	}

	/**
	 * @param null $imgName
	 * @return $this
	 */
	public function imgName($imgName = null) {
		if (is_null($imgName))
			return $this->imgName;
		$this->imgName = $imgName;
		return $this;
	}

	/**
	 * @param null $imgTitle
	 * @return $this
	 */
	public function imgTitle($imgTitle = null) {
		if (is_null($imgTitle))
			return $this->imgTitle;
		$this->imgTitle = $imgTitle;
		return $this;
	}

	/**
	 * @param null $imgDescription
	 * @return $this
	 */
	public function imgDescription($imgDescription = null) {
		if (is_null($imgDescription))
			return $this->imgDescription;
		$this->imgDescription = $imgDescription;
		return $this;
	}

}
