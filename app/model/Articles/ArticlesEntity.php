<?php

namespace App\Model;

use Nette\Object;

/**
 * Class ArticlesEntity
 * @package App\Model
 */
class ArticlesEntity extends Object implements IArticlesEntity
{

	private $id;
	private $idTheme;
	private $url;
	private $title;
	private $description;
	private $keywords;
	private $name;
	private $text;
	private $active;
	private $date;
	private $pictureName;
	private $pictureDescription;
	private $galleryIds;

	public function id($id = null) {
		if (!is_null($id))
			$this->id = $id;
		return $this->id;
	}

	public function idTheme($idTheme = null) {
		if (!is_null($idTheme))
			$this->idTheme = $idTheme;
		return $this->idTheme;
	}

	public function url($url = null) {
		if (!is_null($url))
			$this->url = $url;
		return $this->url;
	}

	public function title($title = null) {
		if (!is_null($title))
			$this->title = $title;
		return $this->title;
	}

	public function description($description = null) {
		if (!is_null($description))
			$this->description = $description;
		return $this->description;
	}

	public function keywords($keywords = null) {
		if (!is_null($keywords))
			$this->keywords = $keywords;
		return $this->keywords;
	}

	public function name($name = null) {
		if (!is_null($name))
			$this->name = $name;
		return $this->name;
	}

	public function text($text = null) {
		if (!is_null($text))
			$this->text = $text;
		return $this->text;
	}

	public function active($active = null) {
		if (!is_null($active))
			$this->active = $active;
		return $this->active;
	}

	public function date($date = null) {
		if (!is_null($date))
			$this->date = $date;
		return $this->date;
	}

	public function pictureName($pictureName = null) {
		if (!is_null($pictureName))
			$this->pictureName = $pictureName;
		return $this->pictureName;
	}

	public function pictureDescription($pictureDescription = null) {
		if (!is_null($pictureDescription))
			$this->pictureDescription = $pictureDescription;
		return $this->pictureDescription;
	}

	public function galleryIds($galleryIds = null) {
		if (!is_null($galleryIds))
			$this->galleryIds = $galleryIds;
		return $this->galleryIds;
	}

}
