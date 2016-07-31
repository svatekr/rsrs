<?php

namespace App\Model;
 
use Dibi\Connection;

/**
 * Class FooterMapper
 * @package App\Model
 */
class FooterMapper extends BaseMapper 
{
 
	/**
	 * FooterMapper constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}
 
}
