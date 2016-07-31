<?php

namespace App\Model;

class SliderRepository extends BaseRepository {

	/** @var SliderMapper */
	private $mapper;

	public function __construct(SliderMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}
}
