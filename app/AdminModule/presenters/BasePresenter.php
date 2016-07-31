<?php
 
namespace App\AdminModule\Presenters;
 
use Grido\Components\Filters\Filter;
use Nette\Application\UI\Presenter;
use Nette\Security\User;
 
/**
 * Class BasePresenter
 * @package App\AdminModule\Presenters
 */
abstract class BasePresenter extends Presenter 
{
 
	/** @persistent */
	public $locale;
	 
	/** @persistent */
	public $ajax = "on";
	 
	/** @var \Kdyby\Translation\Translator @inject */
	public $translator;
	
	/** @var string @persistent */
	public $filterRenderType = Filter::RENDER_INNER;
 
    /** @persistent */
    public $backlink = '';

	/**
	 *
	 */
	protected function startup() {
		parent::startup();
		if (!$this->getUser()->isLoggedIn()) {
			if ($this->getUser()->logoutReason === User::INACTIVITY) {
				$this->flashMessage($this->translator->translate('messages.msg.inactive'));
			} else {
				$this->flashMessage($this->translator->translate('messages.msg.please_login'));
			}
			$this->redirect(':Front:Sign:in', ['backlink' => $this->storeRequest()]);
		}
	 
		if (!$this->getUser()->isInRole('admin')) {
			$this->flashMessage($this->translator->translate('messages.msg.not_allowed'));
			$this->redirect(':Front:Sign:in', ['backlink' => $this->storeRequest()]);
		}
	}
 
}