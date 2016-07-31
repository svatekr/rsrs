<?php

namespace App\FrontModule\Presenters;

use App\Model\PagesEntity;
use App\Model\SliderRepository;

class HomepagePresenter extends BasePresenter
{

	/** @var  SliderRepository @inject */
	public $sliderRepository;

	public function startup() {
		parent::startup();
	}

	/**
	 * Action Default
	 */
	public function actionDefault() {
		/** @var PagesEntity $page */
		$page = $this->pagesRepository->getOneWhere(['onHomepage' => 1]);
		$this->getTemplate()->page = $page;
		$this->getTemplate()->slider = $this->sliderRepository->getAll()->fetchAll();
		$this->setSeo([
			'title' => $page->title() > '' ? $page->title() : $page->name(),
			'description' => $page->description() > '' ? $page->description() : $page->text(),
			'keywords' => $page->keywords() > '' ? $page->keywords() : '',
		]);
	}

	public function actionRss() {
		$this->getTemplate()->pages = $this->pagesRepository->getRss($this->settings['rowInRss']);
		$this->getTemplate()->title = $this->settings['siteName'];
	}

	public function actionSitemap() {
		$this->getTemplate()->pages = $this->pagesRepository->getAll();
		$this->setSeo([
			'title' => 'Mapa stránek',
			'description' => 'Mapa stránek',
			'keywords' => 'Mapa stránek',
		]);
	}

}
