<?php

namespace App\FrontModule\Controls;

use App\Model\CommentsEntity;
use App\Model\CommentsRepository;
use Nette\Application\UI;
use Nette\Utils\DateTime;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use App\Forms\FormFactory;

class CommentsControl extends UI\Control
{

	/** @var CommentsRepository */
	private $commentsRepository;

	/** @var int */
	private $pageId;

	/** @var int */
	private $articleId;

	/** @var FormFactory */
	private $factory;

	/**
	 * @param CommentsRepository $commentsRepository
	 * @param FormFactory $factory
	 */
	public function __construct(CommentsRepository $commentsRepository, FormFactory $factory) {
		parent::__construct();
		$this->commentsRepository = $commentsRepository;
		$this->factory = $factory;
	}

	/**
	 * @param $pageId
	 */
	public function setPageId($pageId) {
		$this->pageId = $pageId;
	}

	/**
	 * @param $articleId
	 */
	public function setArticleId($articleId) {
		$this->articleId = $articleId;
	}

	/**
	 *
	 */
	public function render() {
		if (!is_null($this->pageId))
			$this->getTemplate()->comments = $this->commentsRepository->getAllWhere(['pageId' => $this->pageId,
				'allowed' => 1]);
		else
			$this->getTemplate()->comments = $this->commentsRepository->getAllWhere(['articleId' => $this->articleId,
				'allowed' => 1]);
		$this->getTemplate()->setFile(__DIR__ . '/CommentsControl.latte');
		$this->getTemplate()->render();
	}

	/**
	 * @param UI\Form $form
	 */
	public function processForm(UI\Form $form) {
		$values = $form->getValues();
		$comment = new CommentsEntity();
		$comment->pageId($this->pageId);
		$comment->articleId($this->articleId);
		$comment->author($values->name);
		$comment->text($values->text);
		$comment->date(new DateTime());
		$comment->allowed(0);
		$this->commentsRepository->save($comment);
		$this->flashMessage("Děkujeme za názor. Váš komentář byl odeslán a po schválení bude zobrazen", 'success');
		if (!$this->presenter->isAjax())
			$this->redirect('this');
		$this->redrawControl();
	}

	/**
	 * @return UI\Form
	 */
	protected function createComponentAddCommentForm() {
		$form = $this->factory->create();
		$form->addText('name', 'Vaše jméno')
			->setRequired("Vložte prosím vaše jméno")
			->setAttribute('class', 'form-control input-sm');
		$form->addTextArea('text', "Text komentáře")
			->setRequired("Vložte prosím text komentáře")
			->setAttribute('class', 'form-control input-sm');
		$form->addSubmit('process', "Odeslat ke schválení")
			->setAttribute('class', 'btn btn-primary');

		$form->setRenderer(new BootstrapRenderer);
		$form->getElementPrototype()->class('form-horizontal ajax');

		$form->onSuccess[] = callback($this, 'processForm');
		return $form;
	}

}
