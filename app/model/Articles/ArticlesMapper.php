<?php

namespace App\Model;

use Dibi\Connection;

/**
 * Class ArticlesMapper
 * @package App\Model
 */
class ArticlesMapper extends BaseMapper
{

	/**
	 * ArticlesMapper constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}

	public function getAllThemes() {
		return $this->db->select('*')
			->from('articlethemes')
			->orderBy('name')
			->fetchPairs('id', 'name');
	}

	public function newTheme($theme) {
		$row = $this->db->select('id')
			->from('articlethemes')
			->where('name = %s', $theme)
			->fetch();
		if ($row === false) {
			$this->db->insert('articlethemes', ['name' => $theme])->execute();
			return $this->db->insertId;
		} else {
			return $row->id;
		}
	}
}
