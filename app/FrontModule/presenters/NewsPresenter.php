<?php

namespace App\FrontModule\Presenters;

use App\Model\NewsEntity;
use App\Model\NewsRepository;

class NewsPresenter extends BasePresenter
{

	/** @var NewsRepository @inject */
	public $newsRepository;

	public function startup() {
		parent::startup();
	}

	/**
	 *
	 */
	public function actionDefault() {
		$news = $this->newsRepository->getAll()->where(['active' => 1])->orderBy(['dateAdd' => 'DESC']);

		$visualPaginator = $this['visualPaginator'];
		$paginator = $visualPaginator->getPaginator();
		$paginator->itemsPerPage = 5;
		$paginator->itemCount = $news->count();
		$news->limit($paginator->itemsPerPage);
		$news->offset($paginator->offset);

		$this->getTemplate()->news = $news->fetchAll();
		$this->getTemplate()->title = "Novinky";
		$this->getTemplate()->description = "Novinky";
		$this->getTemplate()->keywords = "Novinky";
	}

	/**
	 * @param $id
	 */
	public function actionView($id) {
		/** @var NewsEntity $new */
		$new = $this->newsRepository->get($id);
		$this->getTemplate()->new = $new;
		$this->getTemplate()->title = $new->title();
		$this->getTemplate()->description = $new->title();
		$this->getTemplate()->keywords = "";
	}

}
