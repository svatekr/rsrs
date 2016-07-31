<?php

namespace App\FrontModule\Presenters;

use App\Model\UsersEntity;
use Nette;
use App\Forms;
use App\Model\UsersRepository;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Latte\Engine;
use Nette\Utils\DateTime;
use Nette\Bridges\ApplicationLatte\UIMacros;

class SignPresenter extends BasePresenter
{

	/** @persistent */
	public $backlink = '';

	/** @var Forms\SignInFormFactory @inject */
	public $signInFactory;

	/** @var Forms\SignUpFormFactory @inject */
	public $signUpFactory;

	/** @var Forms\SignRememberFormFactory @inject */
	public $signRememberFactory;

	/** @var Forms\SignNewPassFormFactory @inject */
	public $signNewPassFactory;

	/** @var UsersRepository @inject */
	public $usersRepository;

	/**
	 * @param $username
	 * @param $token
	 */
	public function renderNewPass($username, $token) {
		/** @var UsersEntity $user */
		$user = $this->usersRepository->getOneWhere(['username' => $username, 'passwordHash' => $token]);

		if (is_null($user)) {
			$this->flashMessage('Nepodařilo se najít uživatelský účet pro obnovu hesla', 'error');
			$this->redirect('in');
		}

		$now = new DateTime();
		if ($user->passwordHashValidity() < $now) {
			$user->passwordHash('');
			$user->passwordHashValidity('');
			$this->usersRepository->save($user);
			$this->presenter->flashMessage('Vypršela platnost tokenu. Požádejte prosím o nové heslo znovu. Platnost žádosti je 1 den.', 'error');
			$this->presenter->redirect('remember');
		}

		/** @var Form $form */
		$form = $this['newPassForm'];
		if (!$form->isSubmitted()) {
			$row = [
				'username' => $user->username(),
				'passwordHash' => $user->passwordHash(),
				'passwordHashValidity' => $user->passwordHashValidity()
			];
			$form->setDefaults($row);
		}
	}

	public function actionOut() {
		$this->getUser()->logout();
		$this->presenter->redirect('Homepage:default');
	}

	/**
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm() {
		$form = $this->signInFactory->create();
		$form->onSuccess[] = function (Form $form) {
			try {
				$this->user->setExpiration($form->values->remember ? '14 days' : '20 minutes');
				$this->getUser()->login($form->values->username, $form->values->password);
			} catch (AuthenticationException $e) {
				$form->addError($e->getMessage());
			}
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		};
		return $form;
	}

	/**
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignRememberForm() {
		$form = $this->signRememberFactory->create();
		$form->onSuccess[] = function (Form $form) {
			$values = $form->getValues();
			/** @var UsersEntity $user */
			$user = null;
			if ($values->username > '')
				$user = $this->usersRepository->getOneWhere(['username' => $values->username]);

			if (is_null($user) && $values->email > '')
				$user = $this->usersRepository->getOneWhere(['email' => $values->email]);

			if (is_null($user)) {
				$this->flashMessage('Neznámý uživatel', 'error');
				$this->redirect('this');
			}

			$now = new DateTime();
			$validity = new DateTime('+1 day');
			$user->passwordHash(Passwords::hash($now . $user->email()));
			$user->passwordHashValidity($validity);
			$this->usersRepository->save($user);

			$message = new Message;

			$params = [
				'username' => $user->username(),
				'token' => $user->passwordHash(),
				'_presenter' => $this,
				'_control' => $this,
			];
			$latte = new Engine();
			UIMacros::install($latte->getCompiler());
			$message->setFrom($this->settings['adminMail'])
				->addTo($user->email())
				->setSubject('Zapomenuté heslo')
				->setHtmlBody($latte->renderToString(__DIR__ . '/templates/Sign/rememberMail.latte', $params));

			$mailer = $this->setMailer();
			$mailer->send($message);

			$this->flashMessage('Zkontrolujte si prosím svou mailovou schránku', 'success');
			$this->redirect('Sign:in');
		};
		return $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentNewPassForm() {
		$form = $this->signNewPassFactory->create();
		$form->onSuccess[] = function (Form $form) {
			$values = $form->getValues();
			/** @var UsersEntity $user */
			$user = $this->usersRepository->getOneWhere(
				[
					'username' => $values->username,
					'passwordHash' => $values->passwordHash,
				]
			);
			if (is_null($user)) {
				$this->presenter->flashMessage('Neznámý uživatel', 'error');
				$this->presenter->redirect('in');
			}
			$user->passwordHash('');
			$user->passwordHashValidity('');
			$user->password(Passwords::hash($values->password));
			$this->usersRepository->save($user);
			$this->presenter->flashMessage('Heslo bylo změněno', 'success');
			$this->redirect('in');
		};
		return $form;
	}

	/**
	 * Sign-up form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignUpForm() {
		$form = $this->signUpFactory->create();
		$form->onSuccess[] = function (Form $form) {
			$values = $form->getValues();
			$userByUserName = $this->usersRepository->getOneWhere(['username' => $values->username]);
			$error = 0;

			if (!is_null($userByUserName)) {
				$this->presenter->flashMessage("Uživatel s tímto username již existuje", 'error');
				$error = 1;
			}

			$userByEmail = $this->usersRepository->getOneWhere(['email' => $values->email]);

			if (!is_null($userByEmail)) {
				$this->presenter->flashMessage("Uživatel s tímto emailem již existuje", 'error');
				$error = 1;
			}

			if ($error == 0) {
				$user = new UsersEntity();

				if (trim($values->password) != '') {
					$user->password(Passwords::hash($values->password));
				}
				$user->username($values->username);
				$user->name($values->name);
				$user->lastname($values->lastname);
				$user->email($values->email);
				$user->role($values->role);
				$this->usersRepository->save($user);
				$this->getUser()->login($values->username, $values->password);
				$this->flashMessage("Registrace proběhla v pořádku", 'success');
				$this->presenter->redirect('Homepage:default');
			}
		};
		return $form;
	}

}
