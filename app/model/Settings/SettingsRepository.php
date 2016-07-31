<?php

namespace App\Model;

/**
 * Class SettingsRepository
 * @package App\Model
 */
class SettingsRepository extends BaseRepository 
{

	/** @var SettingsMapper */
	private $mapper;

	/**
	 * SettingsRepository constructor.
	 * @param SettingsMapper $mapper
	 */
	public function __construct(SettingsMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

}
