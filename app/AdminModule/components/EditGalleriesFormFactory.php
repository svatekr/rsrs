<?php

namespace App\Forms;

use App\Model\GalleriesRepository;
use App\Model\GalleriesEntity;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Class EditGalleriesFormFactory
 * @package App\Forms
 */
class EditGalleriesFormFactory
{

	/** @var FormFactory */
	private $factory;
  
	/** @var GalleriesRepository */
	private $galleriesRepository;
  
	public function __construct(FormFactory $factory, GalleriesRepository $galleriesRepository) {
		$this->factory = $factory;
		$this->galleriesRepository = $galleriesRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();

		$form->addHidden('id', 'ID');
		$form->addText('name', 'Název')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Název');
		$form->addText('description', 'Popis')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Popis');
		$form->addSubmit('save', 'Odeslat')
			->setAttribute('class', 'btn btn-primary');

		$form->setRenderer(new BootstrapRenderer);
		$form->getElementPrototype()->class('form-horizontal');

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
		/** @var GalleriesEntity $galleries */
		$galleries = $this->galleriesRepository->get($values->id);
		$galleries->name($values->name);
		$galleries->description($values->description);
		$galleries->url(Strings::webalize($values->name));
		$this->galleriesRepository->save($galleries);
	}

}
