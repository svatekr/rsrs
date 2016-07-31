<?php

namespace App\AdminModule\Presenters;

use App\Model\SearchRepository;
use Grido\Grid;
use PDOException;

/**
 * Class SearchPresenter
 * @package App\AdminModule\Presenters
 */
class SearchPresenter extends BasePresenter
{

	/** @var SearchRepository @inject */
	public $searchRepository;

	/**
	 * Inicializace třídních proměnných
	 */
	public function startup() {
		parent::startup();
	}

	/**
	 * @param $name
	 * @return Grid
	 * @throws \Grido\Exception
	 */
	protected function createComponentGrid($name) {
		$grid = new Grid($this, $name);
		$grid->translator->lang = 'cs';

		$fluent = $this->searchRepository->getAll();
		$grid->model = $fluent;

		$grid->addColumnText('term', 'Hledaný termín')
			->setSortable()
			->setFilterText();
		$grid->getColumn('term')->headerPrototype->style['width'] = '75%';

		$grid->addColumnNumber('count', 'Počet hledání')
			->setSortable();
		$grid->getColumn('term')->headerPrototype->style['width'] = '25%';

		$grid->setDefaultPerPage(50);
		$grid->filterRenderType = $this->filterRenderType;
		$grid->setExport();

		return $grid;
	}

}
