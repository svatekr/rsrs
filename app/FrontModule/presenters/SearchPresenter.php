<?php

namespace App\FrontModule\Presenters;

class SearchPresenter extends BasePresenter
{

	public function startup() {
		parent::startup();
	}

	/**
	 * @param $term
	 */
	public function actionDefault($term) {
		$pages = $this->pagesRepository->search($term);
		
		$visualPaginator = $this['visualPaginator'];
		$paginator = $visualPaginator->getPaginator();
		$paginator->itemsPerPage = $this->settings['pagesPerPage'];
		$paginator->itemCount = $pages->count();
		$pages->limit($paginator->itemsPerPage);
		$pages->offset($paginator->offset);

		$this->getTemplate()->term = $term;
		$this->getTemplate()->pages = $pages->fetchAll();
		$this->getTemplate()->title = 'Výsledky hledání - ' . $term;
		$this->getTemplate()->description = 'Výsledky hledání - ' . $term;
		$this->getTemplate()->keywords = 'Výsledky hledání - ' . $term;
	}

}
