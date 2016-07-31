<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class SignUpFormFactory
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
		$form->addText('username', 'Username')
				->setRequired('Zadejte své username');

		$form->addPassword('password', "Heslo")
				->setRequired("Zadejte heslo");

		$form->addText('email', "email")
				->setRequired("Zadejte svůj email");

		$form->addText('name', "Jméno")
				->setRequired("Zadejte své křestní jméno");

		$form->addText('lastname', "Příjmení")
				->setRequired("Zadejte své příjmení");

		$form->addHidden('role', 'registered');
		$form->addSubmit('send', "Registrovat");

		return $form;
	}

}
