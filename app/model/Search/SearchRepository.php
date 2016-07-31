<?php

namespace App\Model;

/**
 * Class SearchRepository
 * @package App\Model
 */
class SearchRepository extends BaseRepository 
{

	/** @var SearchMapper */
	private $mapper;

	/**
	 * SearchRepository constructor.
	 * @param SearchMapper $mapper
	 */
	public function __construct(SearchMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

}
