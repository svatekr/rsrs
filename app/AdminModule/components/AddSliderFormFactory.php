<?php

namespace App\Forms;

use App\Model\SliderEntity;
use Nette\Application\UI\Form;
use App\Model\SliderRepository;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Class AddSliderFormFactory
 * @package App\Forms
 */
class AddSliderFormFactory {

	/** @var FormFactory */
	private $factory;

	/**
	 * AddFooterFormFactory constructor.
	 * @param FormFactory $factory
	 * @param SliderRepository $sliderRepository
	 */
	public function __construct(FormFactory $factory,
	                            SliderRepository $sliderRepository) {
		$this->factory = $factory;
		$this->sliderRepository = $sliderRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();

		$form->addText('imgName', 'Obrázek')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Obrázek')
				->setAttribute('onclick', 'openKCFinder(this)');
		$form->addText('imgTitle', 'Titulek obrázku')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Titulek obrázku');
		$form->addTextArea('imgDescription', 'Popisek obrázku')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Popisek obrázku');

		$form->addSubmit('save', 'Uložit')
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
		$item = new SliderEntity;
		$item->imgName($values->imgName);
		$item->imgTitle($values->imgTitle);
		$item->imgDescription($values->imgDescription);
		$this->sliderRepository->save($item);
	}

}
