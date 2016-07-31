<?php

namespace App\Model;

/**
 * Class FooterRepository
 * @package App\Model
 */
class FooterRepository extends BaseRepository 
{

	/** @var FooterMapper */
	private $mapper;

	/**
	 * FooterRepository constructor.
	 * @param FooterMapper $mapper
	 */
	public function __construct(FooterMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

}
