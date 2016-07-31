<?php

namespace App\Forms;

use App\Model\PagesEntity;
use App\Model\PagesRepository;
use App\Model\GalleriesRepository;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Class EditPageFormFactory
 * @package App\Forms
 */
class EditPageFormFactory
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
	 * @param PagesEntity $page
	 * @return Form
	 */
	public function create(PagesEntity $page) {
		$form = $this->factory->create();

		$galleries = $this->galleriesRepository->getAll()
				->fetchPairs('id', 'name');

		$posibleParentsTree = $this->pagesRepository->getPosibleParentsTree($page);

		$form->addHidden('id', 'ID');
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
		$form->addSelect('parent', 'Rodič', $posibleParentsTree)
				->setAttribute('class', 'form-control input-sm select2')
				->setAttribute('placeholder', 'Rodič');
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
	 * @param $form
	 */
	public function formSubmitted(Form $form) {
		$values = $form->getValues();
		$this->page = $this->pagesRepository->get($values->id);
		$this->page->name($values->name);
		$this->page->inMenu(json_encode($values->inMenu));
		$this->page->menuTitle($values->menuTitle);
		$this->page->perex($values->perex);
		$this->page->text($values->text);
		$this->page->pictureName($values->pictureName);
		$this->page->pictureDescription($values->pictureDescription);
		$this->page->active($values->active);
		$this->page->onHomepage($values->onHomepage);
		$this->page->secret($values->secret);
		$this->page->title($values->title);
		$this->page->description($values->description);
		$this->page->keywords($values->keywords);
		$this->page->secretText($values->secretText);
		$this->page->upDate(new DateTime);
		$this->page->galleryIds(json_encode($values->galleryIds));
		$this->getUrl($values);
	}

	private function getUrl($values, $iterator = 1) {
		if ($values->url == "/")
			return $values->url;

		if ($values->url == "")
			$url = Strings::webalize($values->name) . ($iterator != 1 ? "-" . $iterator : '' );
		else
			$url = $values->url . ($iterator != 1 ? "-" . $iterator : '' );

		if (count($this->pagesRepository->getAllWhere(['url' => $url])) > 1) {
			$this->getUrl($values, ++$iterator);
		} else {
			$this->page->url($url);
			$this->pagesRepository->save($this->page);
		}
	}

}
