<?php

namespace App\AdminModule\Presenters;

use App\Forms\EditCommentFormFactory;
use App\Model\ArticlesEntity;
use App\Model\CommentsEntity;
use App\Model\CommentsRepository;
use App\Model\PagesEntity;
use App\Model\PagesRepository;
use App\Model\ArticlesRepository;
use Grido\Grid;
use Nette\Application\UI\Form;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use PDOException;

/**
 * Class CommentsPresenter
 * @package App\AdminModule\Presenters
 */
class CommentsPresenter extends BasePresenter
{

	/** @var CommentsRepository @inject */
	public $commentsRepository;

	/** @var PagesRepository @inject */
	public $pagesRepository;

	/** @var ArticlesRepository @inject */
	public $articlesRepository;

	/** @var EditCommentFormFactory @inject */
	public $editCommentFormFactory;
	/**
	 * Inicializace třídních proměnných
	 */
	public function startup() {
		parent::startup();
	}

	/**
	 * @param $id
	 */
	public function handleDelete($id) {
		$this->commentsRepository->delete($id);
		$this->flashMessage('Komentář byl smazán.', 'success');
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @param $id
	 */
	public function handleAllowed($id) {
		$comment = $this->commentsRepository->get($id);
		$comment->allowed(!$comment->allowed());
		$this->commentsRepository->save($comment);
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @param int $id
	 */
	public function renderEdit($id = 0) {
		/** @var Form $form */
		$form = $this['editForm'];
		if (!$form->isSubmitted()) {
			/** @var CommentsEntity $item */
			$item = $this->commentsRepository->get($id);
			$row = $this->commentsRepository->itemToArray($item);
			if (!$row) {
				throw new PDOException('Záznam nenalezen');
			}
			if (is_null($item->pageId())) {
				$row['pageName'] = '';
			} else {
				/** @var PagesEntity $page */
				$page = $this->pagesRepository->get($item->pageId());
				$row['pageName'] = $page->name();
			}
			if (is_null($item->articleId())) {
				$row['articleName'] = '';
			} else {
				/** @var ArticlesEntity $aricle */
				$aricle = $this->articlesRepository->get($item->articleId());
				$row['articleName'] = $aricle->name();
			}
			$form->setDefaults($row);
		}
	}

	/**
	 * @return Form
	 */
	protected function createComponentEditForm() {
		$form = $this->editCommentFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Komentář byl upraven.', 'success');
			$this->redirect('default');
		};
		return $form;
	}

	/**
	 * @param $name
	 * @return Grid
	 * @throws \Grido\Exception
	 */
	protected function createComponentGrid($name) {
		$grid = new Grid($this, $name);
		$grid->translator->lang = 'cs';

		$fluent = $this->commentsRepository->getAll();
		$grid->model = $fluent;

		$grid->addColumnText('author', 'Autor')
			->setSortable()
			->setFilterText();

		$grid->addColumnDate('date', 'Datum')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('allowed', 'Schváleno')
			->setCustomRender(function ($item) {
				if ($item['allowed'] === 0) {
					$i = Html::el('i', ['class' => 'glyphicon glyphicon-thumbs-down']);
					$el = Html::el('a', ['class' => 'btn btn-danger btn-xs btn-mini ajax'])
						->href($this->presenter->link("allowed!", $item['id']))
						->setHtml($i);
				} else {
					$i = Html::el('i', ['class' => 'glyphicon glyphicon-thumbs-up']);
					$el = Html::el('a', ['class' => 'btn btn-success btn-xs btn-mini ajax'])
						->href($this->presenter->link("allowed!", $item['id']))
						->setHtml($i);
				}
				return $el;
			})
			->cellPrototype->class[] = 'center';

		$grid->addColumnText('text', 'Text')
			->setCustomRender(function ($item) {
				$el = Html::el('span')->setText(Strings::substring($item['text'], 0, 80));
				return $el;
			});
		$grid->getColumn('text')->headerPrototype->style['width'] = '90%';

		$grid->addActionHref('edit', '')
			->setIcon('pencil');

		$grid->addActionEvent('delete', '')
			->setCustomRender(function ($item) {
				$i = Html::el('i', ['class' => 'fa fa-trash']);
				$el = Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax'])
					->href($this->presenter->link("delete!", $item['id']))
					->setHtml($i);
				return $el;
			});

		$grid->setDefaultSort(['date' => 'DESC']);
		$grid->setDefaultPerPage(50);
		$grid->filterRenderType = $this->filterRenderType;
		$grid->setExport();

		return $grid;
	}

}
