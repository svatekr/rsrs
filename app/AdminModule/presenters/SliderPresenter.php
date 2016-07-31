<?php

namespace App\AdminModule\Presenters;

use App\Model\SliderEntity;
use App\Model\SliderRepository;
use Grido\Grid;
use Nette\Application\UI\Form;
use PDOException;
use Nette\Utils\Html;
use App\Forms\EditSliderFormFactory;
use App\Forms\AddSliderFormFactory;

/**
 * Class SliderPresenter
 * @package App\AdminModule\Presenters
 */
class SliderPresenter extends BasePresenter {

	/** @var SliderRepository @inject */
	public $sliderRepository;

	/** @var EditSliderFormFactory @inject */
	public $editSliderFormFactory;

	/** @var AddSliderFormFactory @inject */
	public $addSliderFormFactory;
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
		if (!$form->isSubmitted()) {
			/** @var SliderEntity $item */
			$item = $this->sliderRepository->get($id);
			$row = $this->sliderRepository->itemToArray($item);
			if (!$row) {
				throw new PDOException('Záznam nenalezen');
			}
			$form->setDefaults($row);
			$this->getTemplate()->picture = $item->imgName();

		}
	}

	/**
	 * @param $id
	 */
	public function handleDelete($id) {
		$this->sliderRepository->delete($id);
		$this->flashMessage('Položka slideru byla smazána.', 'success');
		if (!$this->isAjax())
			$this->redirect('default');
		$this->redrawControl();
	}

	/**
	 * @return mixed
	 */
	protected function createComponentEditForm() {
		$form = $this->editSliderFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Položka slideru byla upravena.', 'success');
			$this->redirect('default');
		};
		return $form;
	}

	/**
	 * @return Form
	 */
	protected function createComponentAddForm() {
		$form = $this->addSliderFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Položka slideru byla vytvořena.', 'success');
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

		$fluent = $this->sliderRepository->getAll();
		$grid->model = $fluent;

		$grid->addColumnText('imgName', 'Obrázek')->setCustomRender(function ($item) {
			$img = Html::el('img')->src($item->imgName)->width('200');
			return $img;
		});

		$grid->addColumnText('imgTitle', 'Titulek')
			->setSortable()
			->setFilterText();
		$grid->getColumn('imgTitle')->headerPrototype->style['width'] = '20%';

		$grid->addColumnText('imgDescription', 'Popisek')
			->setSortable()
			->setFilterText();
		$grid->getColumn('imgDescription')->headerPrototype->style['width'] = '40%';

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

}
