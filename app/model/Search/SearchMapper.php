<?php

namespace App\Model;
 
use Dibi\Connection;

/**
 * Class SearchMapper
 * @package App\Model
 */
class SearchMapper extends BaseMapper 
{
 
	/**
	 * SearchMapper constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}
 
}
