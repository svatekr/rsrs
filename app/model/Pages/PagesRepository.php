<?php

namespace App\Model;

use App\Model\PagesEntity;

class PagesRepository extends BaseRepository
{

	const UP = 'up';
	const DN = 'dn';

	/** @var PagesMapper */
	private $mapper;

	/**
	 * PagesRepository constructor.
	 * @param PagesMapper $mapper
	 */
	public function __construct(PagesMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function delete($id) {
		/** @var PagesEntity $page */
		$page = $this->get($id);

		if ($this->haveChild()) {
			$this->flashMessage('Stránku ' . (string) $page->title() .
					' nelze smazat - obsahuje podřízené stránky.', 'danger');
			$this->redirect('default');
		}

		$this->begin();
		try {
			$this->mapper->prepareBeforeDelete($page);
			$this->mapper->delete($page->id());
		} catch (Exception $ex) {
			$this->rollback();
			return false;
		}
		$this->commit();
		return true;
	}

	/**
	 * return int
	 */
	private function haveChild() {
		return $this->page->rgt() - $this->page->lft() - 1;
	}

	/**
	 * @param bool $userIsLoggedIn
	 * @return \Dibi\Fluent
	 */
	public function getFrontAll($userIsLoggedIn = false) {
		$pages = $this->mapper->getAll()
				->where(['active' => 1]);

		if (!$userIsLoggedIn)
			$pages->where(['secret' => 0]);

		return $pages;
	}

	/**
	 * @param $term
	 * @return \Dibi\Fluent
	 */
	public function search($term) {
		return $this->mapper->search($term);
	}

	/**
	 * @param $id
	 */
	public function move($id, $direction = self::DN) {
		$page = $this->get($id);
		if ($direction == self::DN)
			$neighborPage = $this->getOneWhere(['lft' => $page->rgt() + 1]);
		else
			$neighborPage = $this->getOneWhere(['rgt' => $page->lft() - 1]);
		$this->mapper->move($page, $neighborPage);
	}

	public function getPosibleParentsTree(PagesEntity $page) {
		return $this->mapper->getPosibleParentsTree($page);
	}

	/**
	 * @param $page
	 * @param $values
	 */
	public function changeParent($page, $values) {
		$newParent = $this->get($values->parent);
		$this->mapper->changeParent($page, $newParent);
	}

	public function prepareBeforeAdd($parent) {
		$this->mapper->prepareBeforeAdd($parent);
	}

	/**
	 * @param $menuType
	 * @return array
	 */
	public function getPagesInMenu($menuType) {
		return $this->mapper->getPagesInMenu($menuType);
	}

	/**
	 * @param $rowInRss
	 * @return array
	 */
	public function getRss($rowInRss) {
		return $this->mapper->getRss($rowInRss);
	}

}
