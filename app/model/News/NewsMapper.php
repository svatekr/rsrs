<?php

namespace App\Model;
 
use Dibi\Connection;

/**
 * Class NewsMapper
 * @package App\Model
 */
class NewsMapper extends BaseMapper 
{
 
	/**
	 * NewsMapper constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}
 
}
