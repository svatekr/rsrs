<?php

namespace App\Forms;

use App\Model\NewsRepository;
use App\Model\NewsEntity;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;
use Nette\Utils\DateTime;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Class EditNewsFormFactory
 * @package App\AdminModule\Presenters
 */
class EditNewsFormFactory 
{

	/** @var FormFactory */
	private $factory;
  
	public function __construct(FormFactory $factory, NewsRepository $newsRepository) {
		$this->factory = $factory;
		$this->newsRepository = $newsRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();

		$form->addHidden('id', 'ID');
		$form->addText('title', 'Titulek')
			->setAttribute('class', 'form-control input-sm');
		$form->addText('url', 'URL')
			->setAttribute('class', 'form-control input-sm');
		$form->addTextArea('text', 'Text novinky');
		$form->addCheckbox('active', 'Aktivní')
				->setAttribute('class', 'bootstrap');
		$form->addSubmit('save', 'Odeslat')
			->setAttribute('class', 'btn btn-primary');

		$form->setRenderer(new BootstrapRenderer);
		$form->getElementPrototype()->class('form-horizontal');

		$form->onSuccess[] = function (Form $form) {
			$this->formSucceeded($form);
		};
		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function formSucceeded(Form $form) {
		$values = $form->getValues();
		/** @var NewsEntity $news */
		$news = $this->newsRepository->get($values->id);
		$news->title($values->title);
		$news->active($values->active);
		$news->text($values->text);
		$news->url(strlen($values->url) > 0 ? Strings::webalize($values->url) : Strings::webalize($values->title));
		$news->dateAdd(new DateTime);
		$this->newsRepository->save($news);
	}

}
