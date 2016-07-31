<?php

namespace App\FrontModule\Presenters;

use App\FrontModule\Controls\CommentsControl;
use App\FrontModule\Controls\GalleriesControl;
use App\Model\ArticlesEntity;
use App\Model\ArticlesRepository;
use App\Model\CommentsRepository;
use App\Model\GalleriesRepository;

class ArticlesPresenter extends BasePresenter
{

	/** @var ArticlesRepository @inject */
	public $articlesRepository;

	/** @var GalleriesRepository @inject */
	public $galleriesRepository;

	/** @var CommentsRepository @inject */
	public $commentsRepository;

	/** @var ArticlesEntity */
	private $article;

	public function startup() {
		parent::startup();
	}

	public function renderDefault() {
		$articles = $this->articlesRepository->getAll()->orderBy(['date' => 'DESC']);

		$visualPaginator = $this['visualPaginator'];
		$paginator = $visualPaginator->getPaginator();
		$paginator->itemsPerPage = $this->settings['pagesPerPage'];
		$paginator->itemCount = $articles->count();
		$articles->limit($paginator->itemsPerPage);
		$articles->offset($paginator->offset);

		$this->getTemplate()->articles = $articles->fetchAll();
		$this->getTemplate()->title = 'Blog - ' . $this->settings['siteName'];
		$this->getTemplate()->description = 'Blog - ' . $this->settings['siteName'];
		$this->getTemplate()->keywords = 'Blog';
	}

	/**
	 * @param $id
	 */
	public function actionView($id) {
		$this->article = $this->articlesRepository->get($id);
		$this->getTemplate()->article = $this->article;
		$this->getTemplate()->title = ($this->article->title() > '' ? $this->article->title() : $this->article->name());
		$this->getTemplate()->description = $this->article->description() > '' ? $this->article->description() : $this->article->text();
		$this->getTemplate()->keywords = $this->article->keywords() > '' ? $this->article->keywords() : '';
	}

	/**
	 * @return CommentsControl
	 */
	public function createComponentComments() {
		$control = new CommentsControl($this->commentsRepository, $this->formFactory);
		$control->setArticleId($this->article->id());
		$control->setPageId(null);
		return $control;
	}

	/**
	 * @return GalleriesControl
	 */
	public function createComponentGalleries() {
		$control = new GalleriesControl($this->galleriesRepository);
		$control->setArticle($this->article);
		return $control;
	}

}
