<?php

namespace App\AdminModule\Presenters;

use App\Model\GalleriesRepository;
use App\Model\PicturesRepository;
use Grido\Grid;
use App\Forms\EditGalleriesFormFactory;
use App\Forms\AddGalleriesFormFactory;
use App\Forms\EditPicturesFormFactory;
use App\Forms\AddPicturesFormFactory;
use Nette\Utils\Html;

/**
 * Class GalleriesPresenter
 * @package App\AdminModule\Presenters
 */
class GalleriesPresenter extends BasePresenter
{

	/** @var GalleriesRepository @inject */
	public $galleriesRepository;

	/** @var PicturesRepository @inject */
	public $picturesRepository;

	/** @var EditGalleriesFormFactory @inject */
	public $editGalleriesFormFactory;

	/** @var AddGalleriesFormFactory @inject */
	public $addGalleriesFormFactory;

	/** @var EditPicturesFormFactory @inject */
	public $editPicturesFormFactory;

	/** @var AddPicturesFormFactory @inject */
	public $addPicturesFormFactory;

	/**
	 * Inicializace třídních proměnných
	 */
	public function startup() {
		parent::startup();
	}

	/**
	 * @param int $id
	 */
	public function renderEdit($id = 0) {
		/** @var Form $form */
		$form = $this['editForm'];
		$this->getTemplate()->id = $id;
		if (!$form->isSubmitted()) {
			$item = $this->galleriesRepository->get($id);
			$row = $this->galleriesRepository->itemToArray($item);
			if (!$row)
				throw new PDOException('Záznam nenalezen');
			$form->setDefaults($row);
		}
	}

	/**
	 * @param int $id
	 */
	public function renderAddPicture($id = 0) {
		/** @var Form $form */
		$form = $this['addPictureForm'];
		if (!$form->isSubmitted()) {
			$row['galleryId'] = $id;
			$form->setDefaults($row);
		}
	}

	/**
	 * @param int $id
	 */
	public function renderEditPicture($id = 0) {
		/** @var Form $form */
		$form = $this['editPictureForm'];
		if (!$form->isSubmitted()) {
			$item = $this->picturesRepository->get($id);
			$row = $this->picturesRepository->itemToArray($item);
			if (!$row)
				throw new PDOException('Záznam nenalezen');
			$form->setDefaults($row);
		}
	}

	/**
	 * @param $id
	 */
	public function handleDelete($id) {
		$this->galleriesRepository->delete($id);
		$this->flashMessage('Galerie byla smazána.', 'success');
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @param $id
	 */
	public function handleDeletePicture($id) {
		$picture = $this->picturesRepository->get($id);
		$this->picturesRepository->delete($id);
		$this->flashMessage('Obrázek byl odstraněn z galerie.', 'success');
		$this->redirect('Galleries:edit', $picture->galleryId());
	}

	/**
	 * @return Form
	 */
	protected function createComponentEditForm() {
		$form = $this->editGalleriesFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Galerie byla upravena.', 'success');
			$this->redirect('default');
		};
		return $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentAddForm() {
		$form = $this->addGalleriesFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Galerie byla vytvořena.', 'success');
			$this->redirect('default');
		};
		return $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentEditPictureForm() {
		$form = $this->editPicturesFormFactory->create();
		$form->onSuccess[] = function ($form) {
			$this->flashMessage('Obrázek byl upraven.', 'success');
			$this->redirect('Galleries:edit', $form->values->galleryId);
		};
		return $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentAddPictureForm($id) {
		$form = $this->addPicturesFormFactory->create();
		$form->onSuccess[] = function ($form) {
			$this->flashMessage('Obrázek byl vložen.', 'success');
			$this->redirect('Galleries:edit', $form->values->galleryId);
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

		$fluent = $this->galleriesRepository->getAll();
		$grid->model = $fluent;

		$grid->addColumnText('id', 'ID')
			->setSortable();
		$grid->getColumn('id')->headerPrototype->style['width'] = '1%';

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();
		$grid->getColumn('name')->headerPrototype->style['width'] = '25%';

		$grid->addColumnText('description', 'Popis')
			->setSortable()
			->setFilterText();
		$grid->getColumn('description')->headerPrototype->style['width'] = '40%';

		$grid->addActionHref('edit', '')
			->setIcon('pencil');

		$grid->addActionEvent('delete', '')
			->setCustomRender(function ($item) {
				$i = Html::el('i', ['class' => 'fa fa-trash']);
				$el = Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax'])
					->href($this->presenter->link("delete!", $item->id))
					->setHtml($i);
				return $el;
			});

		$grid->setDefaultPerPage(50);
		$grid->filterRenderType = $this->filterRenderType;
		$grid->setExport();

		return $grid;
	}

	/**
	 * @param $name
	 * @return Grid
	 * @throws \Grido\Exception
	 */
	protected function createComponentGridPictures($name) {
		$grid = new Grid($this, $name);
		$grid->translator->lang = 'cs';

		$fluent = $this->picturesRepository->getAllWhere(['galleryId' => $this->getParameter('id')]);
		$grid->model = $fluent;

		$grid->addColumnText('id', 'ID')
			->setSortable();
		$grid->getColumn('id')->headerPrototype->style['width'] = '1%';

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();
		$grid->getColumn('name')->headerPrototype->style['width'] = '25%';

		$grid->addColumnText('file', 'Soubor')
			->setSortable()
			->setFilterText();
		$grid->getColumn('file')->headerPrototype->style['width'] = '40%';

		$grid->addActionHref('editPicture', '')
			->setIcon('pencil');

		$grid->addActionEvent('deletePicture', '')
			->setCustomRender(function ($item) {
				$i = Html::el('i', ['class' => 'fa fa-trash']);
				$el = Html::el('a', ['class' => 'btn btn-default btn-xs btn-mini ajax'])
					->href($this->presenter->link("deletePicture!", $item->id))
					->setHtml($i);
				return $el;
			});

		$grid->setDefaultPerPage(50);
		$grid->filterRenderType = $this->filterRenderType;
		$grid->setExport();

		return $grid;
	}

}
