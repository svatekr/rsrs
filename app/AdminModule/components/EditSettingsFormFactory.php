<?php

namespace App\Forms;

use App\Model\SettingsEntity;
use App\Model\SettingsRepository;
use App\Model\PagesRepository;
use Nette\Application\UI\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use Nette\Utils\Finder;

/**
 * Class EditSettingsFormFactory
 * @package App\Forms
 */
class EditSettingsFormFactory
{

	/** @var FormFactory */
	private $factory;

	public function __construct(FormFactory $factory,
			SettingsRepository $settingsRepository,
			PagesRepository $pagesRepository) {
		$this->factory = $factory;
		$this->settingsRepository = $settingsRepository;
		$this->pagesRepository = $pagesRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();
		$pages = $this->pagesRepository->getAll()->fetchPairs('id', 'name');

		$themes = [];
		foreach(Finder::findDirectories('*')->in(__DIR__ . '/../../../themes') as $dir) {
			/** @var \SplFileInfo $dir */
			$filename = $dir->getFilename();
			$themes[$filename] = $filename;
		}

		$form->addText('siteName', "messages.settings.siteName")
			->setAttribute('placeholder', "messages.settings.siteName")
			->setAttribute('class', 'form-control input-sm')
			->setRequired("messages.settings.plsSiteName");
		$form->addText('adminMail', "messages.settings.adminMail")
			->setAttribute('placeholder', "messages.settings.adminMail")
			->setAttribute('class', 'form-control input-sm')
			->setRequired("messages.settings.plsAdminMail");
		$form->addText('companyMail', "messages.settings.companyMail")
			->setAttribute('placeholder', "messages.settings.companyMail")
			->setAttribute('class', 'form-control input-sm')
			->setRequired("messages.settings.plsCompanyMail");
		$form->addText('companyPhone', "messages.settings.companyPhone")
			->setAttribute('placeholder', "messages.settings.companyPhone")
			->setAttribute('class', 'form-control input-sm')
			->setRequired("messages.settings.plsCompanyPhone");
		$form->addText('companyAddress', "messages.settings.companyAddress")
			->setAttribute('placeholder', "messages.settings.companyAddress")
			->setAttribute('class', 'form-control input-sm')
			->setRequired("messages.settings.plsCompanyAddress");
		$form->addText('noreplyMail', "messages.settings.noreplyMail")
			->setAttribute('placeholder', "messages.settings.noreplyMail")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('motto1', "messages.settings.motto1")
			->setAttribute('placeholder', "messages.settings.motto1")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('motto2', "messages.settings.motto2")
			->setAttribute('placeholder', "messages.settings.motto2")
			->setAttribute('class', 'form-control input-sm');
		$form->addSelect('theme', "messages.settings.template", $themes)
			->setAttribute('class', 'form-control input-sm');
		$form->addText('smtpHost', "messages.settings.smtpServer")
			->setAttribute('placeholder', "messages.settings.smtpServer")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('smtpUsername', "messages.settings.smtpUsername")
			->setAttribute('placeholder', "messages.settings.smtpUsername")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('smtpPassword', "messages.settings.smtpPassword")
			->setAttribute('placeholder', "messages.settings.smtpPassword")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('smtpSecure', "messages.settings.smtpSecure")
			->setAttribute('placeholder', "messages.settings.smtpSecure")
			->setAttribute('class', 'form-control input-sm');
		$form->addCheckbox('useMail', "messages.settings.useMail")
			->setAttribute('placeholder', "messages.settings.useMail")
			->setAttribute('class', 'bootstrap');
		$form->addTextArea('ownScript', "messages.settings.footerScript")
			->setAttribute('placeholder', "messages.settings.footerScript")
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('rows', '20');
		$form->addSelect('thanksPage',  "messages.settings.thanksPage", $pages)
			->setAttribute('class', 'form-control input-sm select2');
		$form->addCheckbox('showAsBlog', "messages.settings.showAsBlog")
			->setAttribute('class', 'bootstrap');
		$form->addText('pagesPerPage', "messages.settings.pagesPerPage")
			->setAttribute('placeholder', "messages.settings.pagesPerPage")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('rowInRss', "messages.settings.rowInRss")
			->setAttribute('placeholder', "messages.settings.rowInRss")
			->setAttribute('class', 'form-control input-sm');
		$form->addText('logo', "messages.settings.logo")
			->setAttribute('placeholder', "messages.settings.logo")
			->setAttribute('onclick', 'openKCFinder(this)')
			->setAttribute('class', 'form-control input-sm');
		$form->addText('sliderInterval', "messages.settings.interval")
			->setAttribute('placeholder', "messages.settings.interval")
			->setAttribute('class', 'form-control input-sm');

		$form->addSubmit('process', "messages.settings.btnSubmit")
			->setAttribute('class', 'btn btn-primary');

		$form->setRenderer(new BootstrapRenderer);

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
		foreach($values as $id => $value) {
			/** @var SettingsEntity $item */
			$item = $this->settingsRepository->getOneWhere(['field' => $id]);
			if(is_null($item)) {
				$item = new SettingsEntity();
				$item->field($id);
			}
			$item->value($value);
			$this->settingsRepository->save($item);
		}
	}

}
