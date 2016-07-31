<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class SignNewPassFormFactory
{

	/** @var FormFactory */
	private $factory;

	public function __construct(FormFactory $factory) {
		$this->factory = $factory;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();
		$form->addHidden('username');
		$form->addHidden('passwordHash');
		$form->addHidden('passwordHashValidity');

		$form->addPassword('password', 'Nové heslo')
				->setRequired('Vyplňte své nové heslo');

		$form->addSubmit('send', 'Odeslat');

		return $form;
	}

}
