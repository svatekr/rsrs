<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class SignRememberFormFactory
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
		$form->addText('email', 'Váš email')
				->addCondition(Form::FILLED)
				->addRule(Form::EMAIL, 'Vložte validní email');

		$form->addText('username', 'Uživatelské jméno')
				->addConditionOn($form['email'], ~Form::FILLED, TRUE)
				->addRule(Form::FILLED, 'Vložte buď email, nebo uživatelské jméno');

		$form->addSubmit('send', 'Odeslat');

		return $form;
	}

}
