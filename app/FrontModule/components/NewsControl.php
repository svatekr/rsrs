<?php

namespace App\FrontModule\Controls;

use Nette\Application\UI;
use App\Model\NewsRepository;

class NewsControl extends UI\Control
{

	/** @var NewsRepository */
	private $newsRepository;

	private $settings;

	/**
	 * @param NewsRepository $newsRepository
	 */
	public function __construct(NewsRepository $newsRepository) {
		parent::__construct();
		$this->newsRepository = $newsRepository;
	}

	/**
	 * @param $settings
	 */
	public function setSettings($settings) {
		$this->settings = $settings;
	}

	/**
	 * @param null $params
	 */
	public function render() {
		$news = $this->newsRepository->getAllWhere(['active' => 1])
				->orderBy('dateAdd DESC')
				->limit($this->settings['pagesPerPage']);
		$this->getTemplate()->news = $news;
		$this->getTemplate()->setFile(__DIR__ . '/NewsControl.latte');
		$this->getTemplate()->render();
	}

}
