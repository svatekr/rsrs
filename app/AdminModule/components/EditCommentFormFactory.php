<?php

namespace App\Forms;

use App\Model\CommentsRepository;
use App\Model\CommentsEntity;
use Nette\Application\UI\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Class EditCommentFormFactory
 * @package App\AdminModule\Presenters
 */
class EditCommentFormFactory 
{

	/** @var FormFactory */
	private $factory;
  
	public function __construct(FormFactory $factory, CommentsRepository $commentRepository) {
		$this->factory = $factory;
		$this->commentRepository = $commentRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();

		$form->addHidden('id', 'ID');
		$form->addText('author', 'Autor')
			->setAttribute('class', 'form-control input-sm');
		$form->addTextArea('text', 'Text novinky');
		$form->addCheckbox('allowed', 'Schváleno')
				->setAttribute('class', 'bootstrap');
		$form->addText('date', 'Datum vložení')
			->setAttribute('class', 'form-control input-sm');
		$form->addText('pageName', 'Ke stránce')
			->setAttribute('class', 'form-control input-sm');
		$form->addText('articleName', 'K článku')
			->setAttribute('class', 'form-control input-sm');
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
		/** @var CommentsEntity $comment */
		$comment = $this->commentRepository->get($values->id);
		$comment->allowed($values->allowed);
		$comment->text($values->text);
		$this->commentRepository->save($comment);
	}

}
