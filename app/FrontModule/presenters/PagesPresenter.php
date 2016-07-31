<?php

namespace App\FrontModule\Presenters;

use App\FrontModule\Controls\CommentsControl;
use App\FrontModule\Controls\GalleriesControl;
use App\Model\CommentsRepository;
use App\Model\GalleriesRepository;
use App\Model\PagesEntity;

class PagesPresenter extends BasePresenter
{

	/** @var GalleriesRepository @inject */
	public $galleriesRepository;

	/** @var CommentsRepository @inject */
	public $commentsRepository;

	/** @var PagesEntity */
	private $page;

	public function startup() {
		parent::startup();
	}

	/**
	 * @param $id
	 */
	public function actionView($id) {
		$this->page = $this->pagesRepository->get($id);
		if ($this->page->secret() == 1 && !$this->user->isLoggedIn()) {
			$this->flashMessage('Ke stránce ' . (string) $this->page->name() . ' nemáte oprávnění', 'danger');
			$this->redirect('Homepage:default');
		}

		$this->getTemplate()->page = $this->page;
		$this->getTemplate()->title = ($this->page->title() > '' ? $this->page->title() : $this->page->name());
		$this->getTemplate()->description = $this->page->description() > '' ? $this->page->description() : $this->page->text();
		$this->getTemplate()->keywords = $this->page->keywords() > '' ? $this->page->keywords() : '';
	}

	/**
	 * @return CommentsControl
	 */
	public function createComponentComments() {
		$control = new CommentsControl($this->commentsRepository, $this->formFactory);
		$control->setPageId($this->page->id());
		$control->setArticleId(null);
		return $control;
	}

	/**
	 * @return GalleriesControl
	 */
	public function createComponentGalleries() {
		$control = new GalleriesControl($this->galleriesRepository);
		$control->setPage($this->page);
		$control->setArticle(null);
		return $control;
	}

}
