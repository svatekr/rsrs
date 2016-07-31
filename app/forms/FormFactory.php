<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Mail\SmtpMailer;

class FormFactory
{

	/**
	 * @return Form
	 */
	public function create() {
		return new Form;
	}

	/**
	 * @return SmtpMailer
	 */
	public function setMailer() {
		/** @var SmtpMailer mailer */
		$mailer = new SmtpMailer([
			'host' => 'smtpServer',
			'username' => 'smtpUsername',
			'password' => 'smtpPassword',
			'secure' => 'smtpSecure',
		]);
		return $mailer;
	}

}
