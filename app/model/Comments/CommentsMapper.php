<?php

namespace App\Model;

use Dibi\Connection;

/**
 * Class CommentsMapper
 * @package App\Model
 */
class CommentsMapper extends BaseMapper
{

	/**
	 * CommentsMapper constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}

	/**
	 * @param integer $articleId
	 * @return Dibi\Row | false
	 */
	public function getPage($articleId) {
		return $this->db->select('name')->from('pages')->where(['id' => $articleId])->fetch();
	}

}
