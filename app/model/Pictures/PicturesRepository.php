<?php

namespace App\Model;

/**
 * Class PicturesRepository
 * @package App\Model
 */
class PicturesRepository extends BaseRepository 
{

	/** @var PicturesMapper */
	private $mapper;

	/**
	 * PicturesRepository constructor.
	 * @param PicturesMapper $mapper
	 */
	public function __construct(PicturesMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

}
