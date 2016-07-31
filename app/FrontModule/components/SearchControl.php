<?php

namespace App\FrontModule\Controls;

use App\Forms\FormFactory;
use Nette\Application\UI;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

class SearchControl extends UI\Control
{

	/** @var FormFactory */
	private $factory;

	/**
	 * @param FormFactory $factory
	 */
	public function __construct(FormFactory $factory) {
		parent::__construct();
		$this->factory = $factory;
	}

	public function render() {
		$this->getTemplate()->setFile(__DIR__ . '/SearchControl.latte');
		$this->getTemplate()->render();
	}

	/**
	 * @return UI\Form
	 */
	protected function createComponentSearchForm() {
		$form = $this->factory->create();
		$form->addText('term', 'hledat')
				->setRequired("Zadejte hledaný termín")
				->setAttribute('class', 'form-control input-sm');
		$form->addButton('process', "Hledat")
				->setAttribute('onclick', 'this.form.submit();')
				->setAttribute('class', 'btn btn-primary');

		$form->setRenderer(new BootstrapRenderer);
		$form->getElementPrototype()->class('form-horizontal');
		$form->onSuccess[] = callback($this, 'processForm');

		return $form;
	}

	/**
	 * @param UI\Form $form
	 */
	public function processForm(UI\Form $form) {
		$values = $form->getValues();
		$this->presenter->redirect('Search:default', ['term' => $values->term]);
	}

}
