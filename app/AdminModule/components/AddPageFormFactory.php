<?php

namespace App\Forms;

use App\Model\GalleriesRepository;
use App\Model\PagesEntity;
use App\Model\PagesRepository;
use Nette\Application\UI\Form;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
use Nette\Utils\Strings;
use Nette\Utils\DateTime;

/**
 * Class AddPageFormFactory
 * @package App\Forms
 */
class AddPageFormFactory
{

	/** @var FormFactory */
	private $factory;

	/** @var PagesEntity */
	private $page;

	/** @var PagesRepository */
	private $pagesRepository;

	/** @var GalleriesRepository */
	private $galleriesRepository;

	public function __construct(FormFactory $factory,
	                            PagesRepository $pagesRepository,
	                            GalleriesRepository $galleriesRepository) {
		$this->factory = $factory;
		$this->pagesRepository = $pagesRepository;
		$this->galleriesRepository = $galleriesRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();

		$galleries = $this->galleriesRepository->getAll()
			->fetchPairs('id', 'name');

		$form->addHidden('parent');
		$form->addText('name', 'Název')
				->setAttribute('placeholder', 'Název stránky')
				->setAttribute('class', 'form-control input-sm')
				->setRequired('Zadejte prosím Název');
		$form->addText('url', 'URL')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'URL stránky');
		$form->addCheckbox('onHomepage', 'Na úvodní stranu')
				->setAttribute('class', 'bootstrap');
		$form->addCheckboxList('inMenu', 'Položka menu:', [
					'topMenu' => 'Horní menu',
					'footerMenu' => 'Menu v patičce',
					'sideMenu' => 'Postranní menu',
					'otherMenu' => 'Jiné menu',
				])
				->setAttribute('class', 'bootstrap');
		$form->addCheckbox('active', 'Aktivní')
				->setAttribute('class', 'bootstrap');
		$form->addCheckbox('secret', 'Pro přihlášené')
				->setAttribute('class', 'bootstrap');
		$form->addText('menuTitle', 'Název do menu')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Název v menu');
		$form->addText('title', 'Titulek')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Titulek v prohlížeči');
		$form->addText('description', 'Description')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Popisek');
		$form->addText('keywords', 'Keywords')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Klíčová slova');
		$form->addTextArea('perex', 'Perex')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Perex');

		$form->addTextArea('text', 'Text')
				->setAttribute('placeholder', 'Text stránky');
		$form->addTextArea('secretText', 'Text po přihlášení')
				->setAttribute('placeholder', 'Text pro přihlášené uživatele');
		$form->addText('pictureName', 'Obrázek')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Připojený obrázek')
				->setAttribute('onclick', 'openKCFinder(this)');
		$form->addText('pictureDescription', 'Popisek obrázku')
				->setAttribute('class', 'form-control input-sm')
				->setAttribute('placeholder', 'Popisek obrázku');
		$form->addMultiSelect('galleryIds', 'Svázané galerie', $galleries)
			->setAttribute('class', 'form-control input-sm select2')
			->setAttribute('placeholder', 'Galerie');

		$form->addSubmit('save', 'Uložit')
				->setAttribute('class', 'btn btn-primary');
		$form->addSubmit('saveandstay', 'Uložit a zůstat')
				->setAttribute('class', 'btn btn-default');

		$form->setRenderer(new BootstrapRenderer);
		$form->getElementPrototype()->class('form-horizontal');

		$form->onSuccess[] = function (Form $form) {
			$this->formSubmitted($form);
		};
		return $form;
	}

	/**
	 * @param Form $form
	 * @param $form->values
	 */
	private function formSubmitted(Form $form) {
		/** @var PagesEntity $pageParent */
		$pageParent = $this->pagesRepository->get($form->values->parent);
		$this->pagesRepository->begin();

		$this->pagesRepository->prepareBeforeAdd($pageParent);

		$this->page = new PagesEntity;
		$this->page->level($pageParent->level() + 1);
		$this->page->lft($pageParent->rgt());
		$this->page->rgt($pageParent->rgt() + 1);
		$this->page->parent($pageParent->id());
		$this->page->date(new DateTime);
		$this->page->upDate(new DateTime);
		$this->page->name($form->values->name);
		$this->page->inMenu(json_encode($form->values->inMenu));
		$this->page->menuTitle($form->values->menuTitle);
		$this->page->perex($form->values->perex);
		$this->page->text($form->values->text);
		$this->page->pictureName($form->values->pictureName);
		$this->page->pictureDescription($form->values->pictureDescription);
		$this->page->active($form->values->active);
		$this->page->onHomepage($form->values->onHomepage);
		$this->page->secret($form->values->secret);
		$this->page->title($form->values->title);
		$this->page->description($form->values->description);
		$this->page->keywords($form->values->keywords);
		$this->page->secretText($form->values->secretText);
		$this->page->galleryIds(json_encode($form->values->galleryIds));
		$this->page->upDate(new DateTime);

		$this->getUrl($form->values);

		$this->pagesRepository->commit();
	}

	private function getUrl($values, $iterator = 1) {
		if ($values->url == "/")
			return $values->url;

		if ($values->url == "")
			$url = Strings::webalize($values->name) . ($iterator != 1 ? "-" . $iterator : '' );
		else
			$url = $values->url . ($iterator != 1 ? "-" . $iterator : '' );

		if (count($this->pagesRepository->getAllWhere(['url' => $url]))) {
			$this->getUrl($values, ++$iterator);
		} else {
			$this->page->url($url);
			$this->pagesRepository->save($this->page);
		}
	}

}
