<?php

namespace App\FrontModule\Controls;

use App\Model\PagesRepository;
use App\Forms\FormFactory;
use Nette\Application\UI;
use Nette\Mail\Message;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

class ContactControl extends UI\Control
{

	/** @var PagesRepository */
	private $pagesRepository;

	private $mailer;
	private $settings;

	/** @var FormFactory */
	private $factory;

	/**
	 * @param PagesRepository $pagesRepository
	 */
	public function __construct(PagesRepository $pagesRepository, FormFactory $factory) {
		parent::__construct();
		$this->pagesRepository = $pagesRepository;
		$this->factory = $factory;
	}

	/**
	 * @param $mailer
	 */
	public function setMailer($mailer) {
		$this->mailer = $mailer;
	}

	/**
	 * @param $settings
	 */
	public function setSettings($settings) {
		$this->settings = $settings;
	}

	public function render() {
		$this->getTemplate()->setFile(__DIR__ . '/ContactControl.latte');
		$this->getTemplate()->render();
	}

	/**
	 * @return UI\Form
	 */
	public function createComponentContactForm() {
		$form = $this->factory->create();

		$form->addText('name', 'Vaše jméno:')
				->addRule(UI\Form::FILLED, 'Zadejte jméno')
				->setAttribute('class', 'form-control input-sm');
		$form->addText('email', 'Vaše e-mailová adresa:')
				->addRule(UI\Form::EMAIL, 'Email nemá správný formát')
				->setAttribute('class', 'form-control input-sm');
		$form->addText('subject', 'Předmět:')
				->addRule(UI\Form::FILLED, 'Zadejte předmět')
				->setAttribute('class', 'form-control input-sm');

		$form->addTextArea('message', 'Zpráva:')
				->addRule(UI\Form::FILLED, 'Zadejte zprávu')
				->setAttribute('class', 'form-control input-sm');

		$form->addSubmit('process', 'Odeslat')
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
		$template = $this->createTemplate();
		$mail = new Message;

		$template->setFile(__DIR__ . '/../presenters/templates/Emails/contactForm.latte');

		$mail->addTo($this->settings['adminMail'])
				->setFrom($values['email']);

		$template->title = 'Zpráva z kontaktního formuláře';
		$template->values = $values;
		$mail->setHtmlBody($template);
		
		$this->mailer->send($mail);
		$this->flashMessage('Zpráva byla odeslána');
		$this->presenter->redirect('Pages:view', ['id' => $this->settings['thanksPage']]);
	}

}
