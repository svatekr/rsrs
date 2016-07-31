<?php
  
namespace App\Forms;
  
use Nette\Application\UI\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
  
/**
 * Class AddUserFormFactory
 * @package App\Forms
 */
class AddUserFormFactory 
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
  
		$roles = [
			'guest' => "Host",
			'user' => "Uživatel",
			'admin' => "Administrátor"
		];
  
		$form->addText('username', "Login")
			->setAttribute('placeholder', "Login")
			->setAttribute('class', 'form-control input-sm')
			->setRequired("Vyplňte prosím Login");
		$form->addPassword('password', 'Heslo')
			->setAttribute('placeholder', "Heslo")
			->setAttribute('class', 'form-control input-sm');
		$form->addPassword('password2', 'Heslo pro kontrolu')
			->addRule(Form::EQUAL, "Vyplňte heslo pro kontrolu", $form['password'])
			->setAttribute('placeholder', " pro kontrolu")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('name', "Jméno")
			->setAttribute('placeholder', "Jméno")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('lastname', "Příjmení")
			->setAttribute('placeholder', "Příjmení")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('email', "email")
			->setType('email')
			->addRule(Form::EMAIL, "Vyplňte email ve správném formátu")
			->setAttribute('placeholder', "Email")
			->setAttribute('class', 'form-control input-sm');
		$form->addSelect('role', "Role" . ": ", $roles)
			->setAttribute('class', 'form-control input-sm');
		$form->addSubmit('process', "Odeslat")
			->setAttribute('class', 'btn btn-primary');
  
		$form->setRenderer(new BootstrapRenderer);
  
		return $form;
	}
  
}
