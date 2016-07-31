<?php
  
namespace App\Forms;
  
use Nette\Application\UI\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
  
/**
 * Class EditUserFormFactory
 * @package App\Forms
 */
class EditUserFormFactory 
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
  
		$form->addHidden('id', 'ID');
		$form->addText('username', "messages.users.login")
			->setAttribute('placeholder', "messages.users.loginPlaceholder")
			->setAttribute('class', 'form-control input-sm')
			->setRequired("messages.users.plsLogin");
		$form->addPassword('password', '')
			->setAttribute('placeholder', "messages.users.passwordPlaceholder")
			->setAttribute('class', 'form-control input-sm');
		$form->addPassword('password2', '')
			->addRule(Form::EQUAL, "messages.users.equalPassword", $form['password'])
			->setAttribute('placeholder', "messages.users.password2Placeholder")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('name', "messages.users.name")
			->setAttribute('placeholder', "messages.users.namePlaceholder")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('lastname', "messages.users.lastName")
			->setAttribute('placeholder', "messages.users.lastNamePlaceholder")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('email', "messages.users.email")
			->setType('email')
			->addRule(Form::EMAIL, "messages.users.validEmail")
			->setAttribute('placeholder', "messages.users.emailPlaceholder")
			->setAttribute('class', 'form-control input-sm');
		$form->addSelect('role', "messages.users.validEmail" . ": ", $roles)
			->setAttribute('class', 'form-control input-sm');
		$form->addSubmit('process', "messages.users.btnSubmit")
			->setAttribute('class', 'btn btn-primary');  
		$form->setRenderer(new BootstrapRenderer);
  
		return $form;
	}
  
}