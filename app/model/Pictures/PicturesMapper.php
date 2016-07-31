<?php

namespace App\Model;
 
use Dibi\Connection;

/**
 * Class PicturesMapper
 * @package App\Model
 */
class PicturesMapper extends BaseMapper 
{
 
	/**
	 * PicturesMapper constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}
 
}
