<?php

namespace App\Model;

use Dibi\Connection;

class SliderMapper extends BaseMapper
{
	/**
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}
}
