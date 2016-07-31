<?php

namespace App\AdminModule\Presenters;

use App\Model\PagesEntity;
use App\Model\PagesRepository;
use App\Forms\EditPageFormFactory;
use App\Forms\AddPageFormFactory;
use App\Model\GalleriesRepository;
use Dibi;
use Grido\Grid;
use Nette\Application\UI\Form;
use Nette\Utils\Html;
use PDOException;
use Nette\Utils\DateTime;

/**
 * Class PagesPresenter
 * @package App\AdminModule\Presenters
 */
class PagesPresenter extends BasePresenter
{

	/** @var EditPageFormFactory @inject */
	public $editPageFormFactory;

	/** @var AddPageFormFactory @inject */
	public $addPageFormFactory;

	/** @var PagesRepository @inject */
	public $pagesRepository;

	/** @var  GalleriesRepository @inject */
	public $galleriesRepository;

	/** @var PagesEntity */
	private $page;

	const UP = 'up';
	const DN = 'dn';

	/**
	 * Inicializace třídních proměnných
	 */
	public function startup() {
		parent::startup();
		$pages = $this->pagesRepository->getAll()->fetch();
		if ($pages === FALSE) {
			$page = new PagesEntity;
			$page->level(0);
			$page->lft(1);
			$page->rgt(2);
			$page->parent(0);
			$page->active(1);
			$page->date(new DateTime);
			$page->upDate(new DateTime);
			$page->name('root');
			$page->menuTitle('root');
			$page->title('root');
			$page->url('/');
			$this->pagesRepository->save($page);
		}
	}

	/**
	 * @param int $id
	 */
	public function renderEdit($id = 0) {
		/** @var Form $form */
		$form = $this['editForm'];
		if (!$form->isSubmitted()) {
			$item = $this->pagesRepository->get($id);
			$row = $this->pagesRepository->itemToArray($item);
			if (!$row)
				throw new PDOException('Záznam nenalezen');
			$row['inMenu'] = json_decode($row['inMenu'], true);
			$row['galleryIds'] = json_decode($row['galleryIds'], true);
			$form->setDefaults($row);
			$this->getTemplate()->picture = $item->pictureName();
		}
	}

	public function actionEdit($id = NULL) {
		$this->page = $this->pagesRepository->get($id);
	}

	/**
	 * @param int $id
	 */
	public function renderAdd($id = 1) {
		/** @var Form $form */
		$form = $this['addForm'];
		$row = ['parent' => $id];
		$form->setDefaults($row);
	}

	/**
	 * @param $id
	 * @param string $direction
	 */
	public function handleMove($id, $direction = self::DN) {
		$this->pagesRepository->move($id, $direction);
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @param $id
	 */
	public function handleActive($id) {
		$this->page = $this->pagesRepository->get($id);
		$this->page->active(!$this->page->active());
		$this->pagesRepository->save($this->page);
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @param $id
	 */
	public function handleInTopMenu($id) {
		$this->page = $this->pagesRepository->get($id);
		$items = json_decode($this->page->inMenu(), true);
		$key = array_search('topMenu', $items);

		if (is_numeric($key))
			unset($items[$key]);
		else
			array_push($items, 'topMenu');

		$this->page->inMenu(json_encode($items));
		$this->pagesRepository->save($this->page);

		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @param $id
	 */
	public function handleDelete($id) {
		$this->pagesRepository->delete($id);
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @return Form
	 */
	protected function createComponentEditForm() {
		$form = $this->editPageFormFactory->create($this->page);
		$form->onSuccess[] = function (Form $form) {
			$this->presenter->flashMessage('Stránka byla uložena.', 'success');
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
		$form = $this->addPageFormFactory->create();
		$form->onSuccess[] = function (Form $form) {

			$this->presenter->flashMessage('Stránka byla uložena.', 'success');
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

		$fluent = $this->pagesRepository->getAll();

		$pages = [];
		foreach ($fluent as $pageRow)
			$pages[$pageRow->id] = $pageRow;

		if (isset($grid->model))
			$grid->model = $fluent;

		$grid->addColumnNumber('id', 'ID');
		$header = $grid->getColumn('id')->headerPrototype;
		$header->style['width'] = '0.5%';

		$grid->addColumnText('name', 'Název')
				->setFilterText()
				->setSuggestion();
		$grid->getColumn('name')->headerPrototype->style['width'] = '80%';

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

		$grid->addColumnText('inMenu', 'Top menu')
						->setCustomRender(function ($item) {
							$menuItems = (array) json_decode($item->inMenu);
							if (!in_array('topMenu', $menuItems)) {
								$i = Html::el('i', ['class' => 'glyphicon glyphicon-thumbs-down']);
								$el = Html::el('a', ['class' => 'btn btn-danger btn-xs btn-mini ajax'])
										->href($this->presenter->link("inTopMenu!", $item->id))
										->setHtml($i);
							} else {
								$i = Html::el('i', ['class' => 'glyphicon glyphicon-thumbs-up']);
								$el = Html::el('a', ['class' => 'btn btn-success btn-xs btn-mini ajax'])
										->href($this->presenter->link("inTopMenu!", $item->id))
										->setHtml($i);
							}
							return $el;
						})
				->cellPrototype->class[] = 'center';

		$grid->addActionHref('edit', '')
				->setIcon('pencil');

		$grid->addActionEvent('delete', '')
				->setCustomRender(function ($item) {
					if ($item->parent === 0 || $item->rgt - $item->lft > 1) {
						$i = Html::el('i', ['class' => 'fa']);
						$el = Html::el('span', ['class' => 'btn btn-xs btn-mini'])->setHtml($i);
					} else {
						$i = Html::el('i', ['class' => 'fa fa-trash']);
						$el = Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax', 'data-grido-confirm' => "Opravdu chcete tuto položku odstranit?"])
								->href($this->presenter->link("delete!", $item->id))
								->setHtml($i);
					}
					return $el;
				});

		$grid->addActionEvent('moveup', '')
				->setCustomRender(function ($item) use ($pages) {
					$first = $item->parent == 0 || ($item->lft - 1) == $pages[$item->parent]['lft'];

					if ($first) {
						$i = Html::el('i', ['class' => 'fa']);
						$el = Html::el('span', ['class' => 'btn btn-xs btn-mini'])->setHtml($i);
					} else {
						$i = Html::el('i', ['class' => 'fa fa-arrow-up']);
						$el = Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax'])
								->href($this->presenter->link("move!", $item->id, self::UP))
								->setHtml($i);
					}
					return $el;
				});

		$grid->addActionEvent('movedn', '')
				->setCustomRender(function ($item) use ($pages) {
					$last = $item->parent == 0 || ($item->rgt + 1) == $pages[$item->parent]['rgt'];

					if ($last) {
						$i = Html::el('i', ['class' => 'fa']);
						$el = Html::el('span', ['class' => 'btn btn-xs btn-mini'])->setHtml($i);
					} else {
						$i = Html::el('i', ['class' => 'fa fa-arrow-down']);
						$el = Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax'])
								->href($this->presenter->link("move!", $item->id, self::DN))
								->setHtml($i);
					}
					return $el;
				});

		$grid->addActionHref('add', '')
				->setIcon('plus');

		$grid->setDefaultSort([
			'lft' => 'ASC',
		]);
		$grid->setRememberState(TRUE);
		$grid->setPrimaryKey('id');
		$grid->setDefaultPerPage(50);
		$grid->setRowCallback(function ($item, $tr) {
			$tr->class[] = "level_{$item->level}";
			return $tr;
		});

		$grid->filterRenderType = $this->filterRenderType;
		$grid->setExport();

		return $grid;
	}

}
