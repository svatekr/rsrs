<?php

namespace App\Forms;

use App\Model\PicturesRepository;
use App\Model\PicturesEntity;
use Nette\Application\UI\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Class AddPicturesFormFactory
 * @package App\Forms
 */
class AddPicturesFormFactory
{

	/** @var FormFactory */
	private $factory;
  
	public function __construct(FormFactory $factory, PicturesRepository $picturesRepository) {
		$this->factory = $factory;
		$this->picturesRepository = $picturesRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();

		$form->addText('name', 'Název')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Název');
		$form->addText('description', 'Popis')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Popis');
		$form->addText('file', 'Obrázek')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Obrázek')
			->setAttribute('onclick', 'openKCFinder(this)');
		$form->addText('order', 'Pořadí')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Pořadí');
		$form->addHidden('galleryId');
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
		if(!is_integer($values->order))
			$values->order = 1;
		/** @var PicturesEntity $pictures */
		$pictures = new PicturesEntity();
		$pictures->name($values->name);
		$pictures->description($values->description);
		$pictures->file($values->file);
		$pictures->order($values->order);
		$pictures->galleryId($values->galleryId);
		$this->picturesRepository->save($pictures);
	}

}
