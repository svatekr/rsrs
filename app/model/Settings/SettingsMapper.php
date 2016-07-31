<?php

namespace App\Model;
 
use Dibi\Connection;

/**
 * Class SettingsMapper
 * @package App\Model
 */
class SettingsMapper extends BaseMapper 
{
 
	/**
	 * SettingsMapper constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}
 
}
