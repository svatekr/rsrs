<?php

namespace App\Model;

/**
 * Class NewsRepository
 * @package App\Model
 */
class NewsRepository extends BaseRepository 
{

	/** @var NewsMapper */
	private $mapper;

	/**
	 * NewsRepository constructor.
	 * @param NewsMapper $mapper
	 */
	public function __construct(NewsMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

}
