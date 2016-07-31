<?php

namespace App\FrontModule\Controls;

use App\Model\ArticlesEntity;
use App\Model\GalleriesRepository;
use App\Model\PagesEntity;
use Nette\Application\UI;

class GalleriesControl extends UI\Control
{

	/** @var GalleriesRepository */
	private $galleriesRepository;

	/** @var PagesEntity */
	private $page;

	/** @var ArticlesEntity */
	private $article;

	/**
	 * GalleriesControl constructor.
	 * @param GalleriesRepository $galleriesRepository
	 */
	public function __construct(GalleriesRepository $galleriesRepository) {
		parent::__construct();
		$this->galleriesRepository = $galleriesRepository;
	}

	/**
	 * @param $page
	 */
	public function setPage($page) {
		$this->page = $page;
	}

	/**
	 * @param $article
	 */
	public function setArticle($article) {
		$this->article = $article;
	}

	/**
	 *
	 */
	public function render() {
		if (!is_null($this->page))
			$this->getTemplate()->galleries = $this->galleriesRepository->getAllGalleriesWithPictures($this->page);
		else
			$this->getTemplate()->galleries = $this->galleriesRepository->getAllGalleriesWithPictures($this->article);
		$this->getTemplate()->setFile(__DIR__ . '/GalleriesControl.latte');
		$this->getTemplate()->render();
	}

}
