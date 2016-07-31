<?php

namespace App\Model;

use Dibi\Connection;

class PagesMapper extends BaseMapper
{

	/**
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}

	/**
	 * @param PagesEntity $page
	 * @param PagesEntity $neighborPage
	 */
	public function move(PagesEntity $page, PagesEntity $neighborPage) {
		$differentPage = (integer) $page->rgt() - (integer) $page->lft() + 1;
		$differentNeighbor = (integer) $neighborPage->rgt() - (integer) $neighborPage->lft() + 1;
		$min_lft = min($page->lft(), $neighborPage->lft());
		$max_rgt = max($page->rgt(), $neighborPage->rgt());
		switch ($page->lft() < $neighborPage->lft()) {
			case true:
				$lft = $page->lft();
				$rgt = $page->rgt();
				break;
			case false:
			default:
				$lft = $neighborPage->lft();
				$rgt = $neighborPage->rgt();
				$differentPage = $differentNeighbor;
				$differentNeighbor = $differentPage;
				break;
		}

		$sql = "UPDATE pages
	        SET lft = lft + IF(@subtree := lft >= " . $lft . " AND rgt <= " . $rgt . ", $differentNeighbor, 
	                IF(lft >= " . $min_lft . ", -$differentPage, 0)),
	            rgt = rgt + IF(@subtree, $differentNeighbor, IF(rgt <= " . $max_rgt . ", -$differentPage, 0))
	        WHERE rgt >= " . $min_lft . " AND lft <= " . $max_rgt . "";

		$this->db->query($sql);
	}

	/**
	 * @param PagesEntity $page
	 * @param PagesEntity $newParent
	 */
	public function changeParent(PagesEntity $page, PagesEntity $newParent) {
		$different = $page->rgt() - $page->lft() + 1;
		$lft = $newParent->rgt();
		$level = $newParent->level() + 1;
		if ($lft > $page->lft())
			$lft -= $different;

		$min_lft = min($lft, $page->lft());
		$max_rgt = max($lft + $different - 1, $page->rgt());
		$move = $lft - $page->lft();
		if ($lft > $page->lft())
			$different = -$different;

		$sql = "UPDATE $this->tableName
	        SET level = level + IF(@subtree := lft >= " . $page->lft() . " 
	                AND rgt <= " . $page->rgt() . ", " . ($level - $page->level()) . ", 0),
	            parent = IF(id = " . $page->id() . ", " . $newParent->id() . ", parent),
	            lft = lft + IF(@subtree, $move, IF(lft >= $min_lft, $different, 0)),
	            rgt = rgt + IF(@subtree, $move, IF(rgt <= $max_rgt, $different, 0))
	        WHERE rgt >= $min_lft AND lft <= $max_rgt ";

		$this->db->query($sql);
	}

	/**
	 * @param PagesEntity $page
	 */
	public function prepareBeforeAdd(PagesEntity $page) {
		$sql = "UPDATE $this->tableName
	        SET 
	        lft = lft + IF(lft > " . $page->rgt() . ", 2, 0),
	        rgt = rgt + IF(rgt >= " . $page->rgt() . ", 2, 0)
	         ";
		$this->db->query($sql);
	}

	/**
	 * @param PagesEntity $page
	 */
	public function prepareBeforeDelete(PagesEntity $page) {
		$sql = "UPDATE $this->tableName
	        SET 
	        lft = lft - IF(lft > " . $page->rgt() . ", 2, 0),
	        rgt = rgt - IF(rgt > " . $page->rgt() . ", 2, 0)
	         ";
		$this->db->query($sql);
	}

	/**
	 * @param PagesEntity $page
	 * @return array
	 */
	public function getPosibleParentsTree(PagesEntity $page) {
		return $this->db->select('id, CONCAT(REPEAT(\'- \', level), name) as name')
						->from($this->tableName)
						->where('lft < %i OR rgt > %i', $page->lft(), $page->rgt())
						->orderBy('lft')
						->fetchPairs();
	}

	/**
	 * @param $menuType
	 * @return array
	 */
	public function getPagesInMenu($menuType) {
		return $this->db->select('id, url, name, IF(LENGTH(menuTitle) > 0, menuTitle, name) as menuTitle, '
								. 'parent, level')
						->from($this->tableName)
						->where('inMenu LIKE %~like~', $menuType)
						->where('active = %i', 1)
						->groupBy('inMenu, url')
						->orderBy('lft')
						->fetchAll();
	}

	/**
	 * @param $term
	 * @return \Dibi\Fluent
	 */
	public function search($term) {
		return $this->db->select('*')
						->from($this->tableName)
						->where("MATCH (name,title,perex,text) AGAINST ('*' %s '*' IN BOOLEAN MODE)", $term)
						->and('active = %i', 1);
	}

	/**
	 * @param $rowInRss
	 * @return \Dibi\Fluent
	 */
	public function getRss($rowInRss) {
		return $this->db->select('*')
						->from($this->tableName)
						->orderBy(['upDate' => 'DESC'])
						->limit($rowInRss);
	}

}
