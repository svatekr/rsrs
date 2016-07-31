<?php

namespace App\FrontModule\Controls;

use App\Forms\FormFactory;
use Nette\Application\UI;
use App\Model\PagesRepository;
use IPub\VisualPaginator\Components as VisualPaginator;

/**
 * Class BlogControl
 * @package App\FrontModule\Controls
 */
class BlogControl extends UI\Control
{

	/** @var PagesRepository */
	private $pagesRepository;

	/** @var FormFactory */
	private $factory;

	private $settings;

	/**
	 * @param PagesRepository $pagesRepository
	 * @param FormFactory $factory
	 */
	public function __construct(PagesRepository $pagesRepository, FormFactory $factory) {
		parent::__construct();
		$this->pagesRepository = $pagesRepository;
		$this->factory = $factory;
	}

	/**
	 * @param array $settings
	 */
	public function setSettings($settings) {
		$this->settings = $settings;
	}

	/**
	 * @param null $params
	 */
	public function render($params = NULL) {
		$blogPages = $this->pagesRepository->getFrontAll()
				->orderBy(['upDate' => 'DESC']);

		$pagesPerPage = $this->settings['pagesPerPage'];
		$template = 'BlogControl';

		if(is_array($params) && count($params)) {
			$template = $params[1];
			if(isset($params[2]))
				$pagesPerPage = $params[2];
		}

		$blogPages->limit($pagesPerPage);

		$this->getTemplate()->blogPages = $blogPages->fetchAll();
		$dir = dirname($this->getReflection()->getFileName());

		$pagesControlTemplate = $dir . '/../../../themes/' . $this->settings['theme'] . '/Homepage/'.$template.'.latte';
		if (file_exists($pagesControlTemplate))
			$this->getTemplate()->setFile($pagesControlTemplate);
		else
			$this->getTemplate()->setFile(__DIR__ . '/'.$template.'.latte');
		$this->getTemplate()->render();
	}

	/**
	 * Create items paginator
	 * @return VisualPaginator\Control
	 */
	protected function createComponentVisualPaginator() {
		$control = new VisualPaginator\Control;
		$control->setTemplateFile('bootstrap.latte');
		return $control;
	}


}