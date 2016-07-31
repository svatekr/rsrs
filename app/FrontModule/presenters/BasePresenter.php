<?php

namespace App\FrontModule\Presenters;

use App\FrontModule\Controls\BlogControl;
use App\FrontModule\Controls\MenuControl;
use App\FrontModule\Controls\ContactControl;
use App\FrontModule\Controls\NewsControl;
use App\FrontModule\Controls\SearchControl;
use App\Model\FooterRepository;
use Nette\Application\UI\Presenter;
use App\Model\PagesRepository;
use App\Model\NewsRepository;
use App\Model\SettingsRepository;
use App\Model\SettingsEntity;
use App\Forms\FormFactory;
use IPub\VisualPaginator\Components as VisualPaginator;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;
use Nette\Http\IRequest;


/**
 * Class BasePresenter
 * @package App\FrontModule\Presenters
 */
abstract class BasePresenter extends Presenter
{

	/** @var string @persistent */
	public $ajax = 'on';

	/** @var PagesRepository @inject */
	public $pagesRepository;

	/** @var NewsRepository @inject */
	public $newsRepository;

	/** @var FormFactory @inject */
	public $formFactory;

	/** @var SettingsRepository @inject */
	public $settingsRepository;

	/** @var FooterRepository @inject */
	public $footerRepository;

	/** @var SettingsEntity */
	protected $settings;

	/** @var IRequest */
	protected $httpRequest;

	/**
	 *
	 */
	public function startup() {
		parent::startup();
		$this->getTemplate()->addFilter('components', function ($text) {
			$presenter = $this;

			return preg_replace_callback('~\##(.*)\##~', function ($matches) use ($presenter) {
				$array = explode(", ", $matches[1]);
				$component = $array[0];
				ob_start();
				$component = $presenter->getComponent($component);
				unset($array[0]);
				if (count($array))
					$component->render($array);
				else
					$component->render();
				return ob_get_clean();
			}, $text);
		});
		$this->settings = $this->settingsRepository->getAll()->fetchPairs('field', 'value');
	}

	public function beforeRender() {
		parent::beforeRender();
		$this->getTemplate()->footer = $this->footerRepository->get(1);
		$this->getTemplate()->theme = "themes/" . $this->settings['theme'];
		$this->getTemplate()->ajax = $this->getParameter('ajax') == 'on';
		$this->getTemplate()->settings = $this->settings;
	}

	/**
	 * @return mixed
	 */
	public function formatLayoutTemplateFiles() {
		$layoutFiles = [];
		$name = $this->getName();
		$dir = dirname($_SERVER['SCRIPT_NAME']);
		$presenter = substr($name, strrpos(':' . $name, ':'));
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . $dir . '/themes/' . $this->settings['theme'] . '/' . $presenter . '/@layout.latte'))
			$layoutFiles[] = $_SERVER['DOCUMENT_ROOT'] . $dir . '/themes/' . $this->settings['theme'] . '/' . $presenter . '/@layout.latte';

		$reflection = $this->getReflection();
		while ($reflection->getName() !== 'Nette\Application\UI\Presenter') {
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . $dir . '/themes/' . $this->settings['theme'] . '/@layout.latte'))
				$layoutFiles[] = $_SERVER['DOCUMENT_ROOT'] . $dir . '/themes/' . $this->settings['theme'] . '/@layout.latte';

			$reflection = $reflection->getParentClass();
		}
		$originalLayoutFiles = parent::formatLayoutTemplateFiles();
		return array_merge($layoutFiles, $originalLayoutFiles);
	}

	/**
	 * @return mixed
	 */
	public function formatTemplateFiles() {
		$name = $this->getName();
		$templateFiles = [];
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$dir = dirname($_SERVER['SCRIPT_NAME']);

		if (file_exists($_SERVER['DOCUMENT_ROOT'] . $dir . '/themes/' . $this->settings['theme'] . '/' . $this->getView() . '.latte'))
			$templateFiles[] = $_SERVER['DOCUMENT_ROOT'] . $dir . '/themes/' . $this->settings['theme'] . '/' . $this->getView() . '.latte';
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . $dir . '/themes/' . $this->settings['theme'] . '/' . $presenter . '/' . $this->getView() . '.latte'))
			$templateFiles[] = $_SERVER['DOCUMENT_ROOT'] . $dir . '/themes/' . $this->settings['theme'] . '/' . $presenter . '/' . $this->getView() . '.latte';

		$originalTemplateFiles = parent::formatTemplateFiles();
		return array_merge($templateFiles, $originalTemplateFiles);
	}
	/**
	 * @return MenuControl
	 */
	protected function createComponentTopMenu() {
		$menu = new MenuControl($this->pagesRepository, 'topMenu');
		return $menu;
	}

	/**
	 * @return MenuControl
	 */
	protected function createComponentFooterMenu() {
		$menu = new MenuControl($this->pagesRepository, 'footerMenu');
		return $menu;
	}

	/**
	 * @return ContactControl
	 */
	protected function createComponentContactForm() {
		$control = new ContactControl($this->pagesRepository, $this->formFactory);
		$control->setSettings($this->settings);
		$control->setMailer($this->setMailer());
		return $control;
	}

	/**
	 * @return NewsControl
	 */
	public function createComponentNewsControl() {
		$control = new NewsControl($this->newsRepository);
		$control->setSettings($this->settings);
		return $control;
	}

	/**
	 * @return SearchControl
	 */
	public function createComponentSearchControl() {
		$control = new SearchControl($this->formFactory);
		return $control;
	}

	/**
	 * @return BlogControl
	 */
	public function createComponentBlogControl() {
		$control = new BlogControl($this->pagesRepository, $this->formFactory);
		$control->setSettings($this->settings);
		return $control;
	}

	/**
	 * @return SendmailMailer|SmtpMailer
	 */
	protected function setMailer() {
		if ($this->settings['useMail']) {
			/** @var SendmailMailer mailer */
			$mailer = new SendmailMailer;
		} else {
			/** @var SmtpMailer mailer */
			$mailer = new SmtpMailer([
				'host' => $this->settings['smtpHost'],
				'username' => $this->settings['smtpUsername'],
				'password' => $this->settings['smtpPassword'],
				'secure' => $this->settings['smtpSecure'],
			]);
		}
		return $mailer;
	}

	/**
	 * Create items paginator
	 * @return VisualPaginator\Control
	 */
	protected function createComponentVisualPaginator() {
		$control = new VisualPaginator\Control;
		$control->setTemplateFile('bootstrap.latte');
		$control->enableAjax();
		$that = $this;
		$control->onShowPage[] = (function () use ($that) {
			if ($that->isAjax())
				$that->redrawControl();
		});

		return $control;
	}

	protected function setSeo($params) {
		foreach($params as $key => $param)
			$this->getTemplate()->$key = $param;
	}
}
