<?php

namespace App\FrontModule\Controls;

use App\Model\PagesRepository;
use Nette\Application\UI\Control;

class MenuControl extends Control
{

	/** @var PagesRepository */
	protected $pagesRepository;

	/** @var string */
	protected $menuType;

	/**
	 * MenuControl constructor.
	 * @param PagesRepository $pagesRepository
	 * @param string $menuType
	 */
	public function __construct(PagesRepository $pagesRepository, $menuType) {
		parent::__construct();
		$this->pagesRepository = $pagesRepository;
		$this->menuType = $menuType;
	}

	public function render() {
		$template = $this->getTemplate();
		if (file_exists(__DIR__ . '/' . $this->menuType . '.latte'))
			$template->setFile(__DIR__ . '/' . $this->menuType . '.latte');
		else
			$template->setFile(__DIR__ . '/topMenu.latte');
		$pagesInMenu = $this->pagesRepository->getPagesInMenu($this->menuType);
		$template->menuItems = $this->convertAdjacencyListToTree(1, $pagesInMenu, 'id', 'parent', 'children');
		$template->render();
	}

	/**
	 * @param $intParentId
	 * @param $arrRows
	 * @param $strIdField
	 * @param $strParentsIdField
	 * @param $strNameResolution
	 * @return array
	 */
	private function convertAdjacencyListToTree($intParentId, &$arrRows, $strIdField, 
			$strParentsIdField, $strNameResolution) {

		$arrChildren = [];
		for ($i = 0; $i < count($arrRows); $i++)
			if ($intParentId === $arrRows[$i][$strParentsIdField])
				$arrChildren = array_merge($arrChildren, array_splice($arrRows, $i--, 1));

		$intChildren = count($arrChildren);
		if ($intChildren != 0)
			for ($i = 0; $i < $intChildren; $i++)
				$arrChildren[$i][$strNameResolution] = $this->convertAdjacencyListToTree(
						$arrChildren[$i][$strIdField], $arrRows, $strIdField, $strParentsIdField, $strNameResolution);

		return $arrChildren;
	}

}
