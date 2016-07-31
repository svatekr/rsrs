<?php

namespace App\AdminModule\Presenters;

use App\Forms\EditFooterFormFactory;
use App\Model\FooterRepository;
use Nette\Application\UI\Form;
use PDOException;

/**
 * Class FooterPresenter
 * @package App\AdminModule\Presenters
 */
class FooterPresenter extends BasePresenter
{

	/** @var FooterRepository @inject */
	public $footerRepository;

	/** @var EditFooterFormFactory @inject */
	public $editFooterFormFactory;

	/**
	 * Inicializace třídních proměnných
	 */
	public function startup() {
		parent::startup();
	}

	/**
	 * @param int $id
	 */
	public function renderEdit($id = 1) {
		/** @var Form $form */
		$form = $this['editForm'];
		if (!$form->isSubmitted()) {
			$item = $this->footerRepository->get($id);
			$row = $this->footerRepository->itemToArray($item);
			if (!$row) {
				throw new PDOException('Záznam nenalezen');
			}
			$form->setDefaults($row);
		}
	}

	/**
	 * @return Form
	 */
	protected function createComponentEditForm() {
		$form = $this->editFooterFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Změny byly uloženy', 'success');
			$this->redirect('this');
		};
		return $form;
	}

}
