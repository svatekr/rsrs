<?php

namespace App\Forms;

use App\Model\FooterEntity;
use App\Model\FooterRepository;
use Nette\Application\UI\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Class EditFooterFormFactory
 * @property  footerRepository
 * @package App\Forms
 */
class EditFooterFormFactory
{

	/** @var FormFactory */
	private $factory;

	/**
	 * EditFooterFormFactory constructor.
	 * @param FormFactory $factory
	 * @param FooterRepository $footerRepository
	 */
	public function __construct(FormFactory $factory,
	                            FooterRepository $footerRepository) {
		$this->factory = $factory;
		$this->footerRepository = $footerRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();
		$form->addHidden('id', 'ID');
		$form->addTextArea('text', 'Obsah patičky')
			->setAttribute('placeholder', 'Obsah patičky')
			->setAttribute('class', 'form-control input-sm');
		$form->addSubmit('process', 'Odeslat')
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
		/** @var FooterEntity $item */
		$item = $this->footerRepository->get($values->id);
		$item->text($values->text);
		$this->footerRepository->save($item);
	}

}
