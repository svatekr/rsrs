<?php

namespace App\AdminModule\Presenters;

use App\Model\ArticlesEntity;
use App\Model\ArticlesRepository;
use App\Forms\EditArticleFormFactory;
use App\Forms\AddArticleFormFactory;
use Dibi;
use Grido\Grid;
use Nette\Application\UI\Form;
use Nette\Utils\Html;
use PDOException;

/**
 * Class ArticlesPresenter
 * @package App\AdminModule\Presenters
 */
class ArticlesPresenter extends BasePresenter
{

	/** @var EditArticleFormFactory @inject */
	public $editArticleFormFactory;

	/** @var AddArticleFormFactory @inject */
	public $addArticleFormFactory;

	/** @var ArticlesRepository @inject */
	public $articlesRepository;

	/** @var ArticlesEntity */
	private $article;

	public function startup() {
		parent::startup();
	}

	/**
	 * @param int $id
	 */
	public function renderEdit($id = 0) {
		/** @var Form $form */
		$form = $this['editForm'];
		if (!$form->isSubmitted()) {
			$item = $this->articlesRepository->get($id);
			$row = $this->articlesRepository->itemToArray($item);
			if (!$row)
				throw new PDOException('Záznam nenalezen');
			$row['galleryIds'] = json_decode($row['galleryIds']);
			$form->setDefaults($row);
			$this->getTemplate()->picture = $item->pictureName();
		}
	}

	public function actionEdit($id = null) {
		$this->article = $this->articlesRepository->get($id);
	}

	/**
	 * @param $id
	 */
	public function handleActive($id) {
		$this->article = $this->articlesRepository->get($id);
		$this->article->active(!$this->article->active());
		$this->articlesRepository->save($this->article);
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @param $id
	 */
	public function handleDelete($id) {
		$this->articlesRepository->delete($id);
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @return Form
	 */
	protected function createComponentEditForm() {
		$form = $this->editArticleFormFactory->create($this->article);
		$form->onSuccess[] = function (Form $form) {
			$this->presenter->flashMessage('Článek byl uložen.', 'success');
			if (isset($form->submitted->name) && $form->submitted->name == 'saveandstay')
				$this->redirect('edit', $form->values->id);

			$this->redirect('default');
		};
		return $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentAddForm() {
		$form = $this->addArticleFormFactory->create();
		$form->onSuccess[] = function (Form $form) {
			$this->presenter->flashMessage('Článek byl uložen.', 'success');
			if (isset($form->submitted->name) && $form->submitted->name == 'saveandstay')
				$this->redirect('edit', Dibi::getInsertId());

			$this->redirect('default');
		};
		return $form;
	}

	/**
	 * @param $name
	 * @return Grid
	 */
	protected function createComponentGrid($name) {
		$grid = new Grid($this, $name);
		$grid->translator->lang = 'cs';

		$fluent = $this->articlesRepository->getAll();

		$articles = [];
		foreach ($fluent as $articleRow)
			$articles[$articleRow->id] = $articleRow;

		if (isset($grid->model))
			$grid->model = $fluent;

		$grid->addColumnNumber('id', 'ID');
		$header = $grid->getColumn('id')->headerPrototype;
		$header->style['width'] = '0.5%';

		$grid->addColumnText('name', 'Název')
			->setFilterText()
			->setSuggestion();
		$grid->getColumn('name')->headerPrototype->style['width'] = '40%';

		$grid->addColumnText('active', 'Aktivní')
			->setCustomRender(function ($item) {
				if ($item->active == 0) {
					$i = Html::el('i', ['class' => 'glyphicon glyphicon-thumbs-down']);
					$el = Html::el('a', ['class' => 'btn btn-danger btn-xs btn-mini ajax'])
						->href($this->presenter->link("active!", $item->id))
						->setHtml($i);
				} else {
					$i = Html::el('i', ['class' => 'glyphicon glyphicon-thumbs-up']);
					$el = Html::el('a', ['class' => 'btn btn-success btn-xs btn-mini ajax'])
						->href($this->presenter->link("active!", $item->id))
						->setHtml($i);
				}
				return $el;
			})
			->cellPrototype->class[] = 'center';

		$grid->addActionHref('edit', '')
			->setIcon('pencil');

		$grid->addActionEvent('delete', '')
			->setCustomRender(function ($item) {
				$i = Html::el('i', ['class' => 'fa fa-trash']);
				$el = Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax', 'data-grido-confirm' => "Opravdu chcete tuto položku odstranit?"])
					->href($this->presenter->link("delete!", $item->id))
					->setHtml($i);
				return $el;
			});

		$grid->setDefaultSort([
			'date' => 'DESC',
		]);
		$grid->setRememberState(true);
		$grid->setPrimaryKey('id');
		$grid->setDefaultPerPage(50);

		$grid->filterRenderType = $this->filterRenderType;
		$grid->setExport();

		return $grid;
	}

}
