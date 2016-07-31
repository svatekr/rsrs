<?php
  
namespace App\AdminModule\Presenters;
  
use App\Model\SettingsRepository;
use App\Forms\EditSettingsFormFactory;
use Nette\Application\UI\Form;

/**
 * Class SettingsPresenter
 * @package App\AdminModule\Presenters
 */
class SettingsPresenter extends BasePresenter
{

	/** @var SettingsRepository @inject */
	public $settingsRepository;
 
	/** @var EditSettingsFormFactory @inject */
	public $editSettingsFormFactory;

	/**
	 * Inicializace třídních proměnných
	 */
	public function startup() {
		parent::startup();
	}

	public function renderEdit() {
		/** @var Form $form */
		$form = $this['editForm'];
		if (!$form->isSubmitted()) {
			$rows = $this->settingsRepository->getAll()->fetchPairs('field', 'value');
			$form->setDefaults($rows);

			if(isset($rows['logo']) && $rows['logo'] > '')
				$this->getTemplate()->logo = $rows['logo'];
		}
	}

	/**
	 * @return Form
	 */
	protected function createComponentEditForm() {
		$form = $this->editSettingsFormFactory->create();
		$form->setTranslator($this->translator);
		$form->onSuccess[] = function () {
			$this->flashMessage('Změny byly uloženy', 'success');
			$this->redirect('this');
		};
		return $form;
	}

}
