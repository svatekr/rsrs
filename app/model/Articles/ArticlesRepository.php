<?php

namespace App\Model;

/**
 * Class ArticlesRepository
 * @package App\Model
 */
class ArticlesRepository extends BaseRepository 
{

	/** @var ArticlesMapper */
	private $mapper;

	/**
	 * ArticlesRepository constructor.
	 * @param ArticlesMapper $mapper
	 */
	public function __construct(ArticlesMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

	public function getAllThemes() {
		return $this->mapper->getAllThemes();
	}

	public function newTheme($theme) {
		return $this->mapper->newTheme($theme);
	}

}
