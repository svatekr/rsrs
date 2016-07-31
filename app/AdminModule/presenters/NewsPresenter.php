<?php

namespace App\AdminModule\Presenters;

use App\Model\NewsRepository;
use Grido\Grid;
use App\Forms\EditNewsFormFactory;
use App\Forms\AddNewsFormFactory;
use Nette\Utils\Html;

/**
 * Class NewsPresenter
 * @package App\AdminModule\Presenters
 */
class NewsPresenter extends BasePresenter
{

	/** @var NewsRepository @inject */
	public $newsRepository;

	/** @var EditNewsFormFactory @inject */
	public $editNewsFormFactory;

	/** @var AddNewsFormFactory @inject */
	public $addNewsFormFactory;

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
		$this->newsRepository->delete($id);
		$this->flashMessage('Novinka byla smazána.', 'success');
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @param $id
	 */
	public function handleActive($id) {
		$news = $this->newsRepository->get($id);
		$news->active(!$news->active());
		$this->newsRepository->save($news);
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
			$item = $this->newsRepository->get($id);
			$row = $this->newsRepository->itemToArray($item);
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
		$form = $this->editNewsFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Novinka byla upravena.', 'success');
			$this->redirect('default');
		};
		return $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentAddForm() {
		$form = $this->addNewsFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Novinka byla vytvořena.', 'success');
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

		$fluent = $this->newsRepository->getAll();
		$grid->model = $fluent;

		$grid->addColumnText('title', 'Titulek')
			->setSortable()
			->setFilterText();
		$grid->getColumn('title')->headerPrototype->style['width'] = '65%';

		$grid->addColumnDate('dateAdd', 'Datum')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('active', 'Aktivní')
			->setCustomRender(function ($item) {
				if ($item->active === 0) {
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

		$grid->addActionEvent('delete', '')
			->setCustomRender(function ($item) {
				$i = Html::el('i', ['class' => 'fa fa-trash']);
				$el = Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax'])
					->href($this->presenter->link("delete!", $item->id))
					->setHtml($i);
				return $el;
			});
		$grid->addActionHref('edit', '')
			->setIcon('pencil');

		$grid->setDefaultSort(['dateAdd' => 'DESC']);
		$grid->setDefaultPerPage(50);
		$grid->filterRenderType = $this->filterRenderType;
		$grid->setExport();

		return $grid;
	}

}
