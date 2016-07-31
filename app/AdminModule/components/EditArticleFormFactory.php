<?php

namespace App\Forms;

use App\Model\ArticlesEntity;
use App\Model\ArticlesRepository;
use App\Model\GalleriesRepository;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Class EditArticleFormFactory
 * @package App\Forms
 */
class EditArticleFormFactory
{

	/** @var FormFactory */
	private $factory;

	/** @var ArticlesEntity */
	private $article;

	/** @var ArticlesRepository */
	private $articlesRepository;

	/** @var GalleriesRepository */
	private $galleriesRepository;

	/**
	 * EditArticleFormFactory constructor.
	 * @param FormFactory $factory
	 * @param ArticlesRepository $articlesRepository
	 * @param GalleriesRepository $galleriesRepository
	 */
	public function __construct(FormFactory $factory,
	                            ArticlesRepository $articlesRepository,
	                            GalleriesRepository $galleriesRepository) {
		$this->factory = $factory;
		$this->articlesRepository = $articlesRepository;
		$this->galleriesRepository = $galleriesRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();

		$galleries = $this->galleriesRepository->getAll()
			->fetchPairs('id', 'name');

		$themes = $this->articlesRepository->getAllThemes();

		$form->addHidden('id', 'ID');
		$form->addText('name', 'Název')
			->setAttribute('placeholder', 'Název článku')
			->setAttribute('class', 'form-control input-sm')
			->setRequired('Zadejte prosím Název článku');
		$form->addText('url', 'URL')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'URL stránky');
		$form->addCheckbox('active', 'Aktivní')
			->setAttribute('class', 'bootstrap');
		$form->addText('title', 'Titulek')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Titulek v prohlížeči');
		$form->addText('description', 'Description')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Popisek');
		$form->addText('keywords', 'Keywords')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Klíčová slova');
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
		$form->addSelect('idTheme', 'Téma článku', $themes)
			->setAttribute('class', 'form-control input-sm select2')
			->setAttribute('placeholder', 'Téma článku');
		$form->addText('theme', 'Nové téma')
			->setAttribute('class', 'form-control input-sm')
			->setAttribute('placeholder', 'Nové téma');
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
		if($values->theme > '')
			$values->idTheme = $this->articlesRepository->newTheme($values->theme);

		$this->article = $this->articlesRepository->get($values->id);
		$this->article->name($values->name);
		$this->article->text($values->text);
		$this->article->pictureName($values->pictureName);
		$this->article->pictureDescription($values->pictureDescription);
		$this->article->active($values->active);
		$this->article->title($values->title);
		$this->article->description($values->description);
		$this->article->keywords($values->keywords);
		$this->article->galleryIds(json_encode($values->galleryIds));
		$this->article->idTheme($values->idTheme);
		$this->getUrl($values);
	}

	private function getUrl($values, $iterator = 1) {
		if ($values->url == "/")
			return $values->url;

		if ($values->url == "")
			$url = Strings::webalize($values->name) . ($iterator != 1 ? "-" . $iterator : '');
		else
			$url = $values->url . ($iterator != 1 ? "-" . $iterator : '');

		if (count($this->articlesRepository->getAllWhere(['url' => $url])) > 1) {
			$this->getUrl($values, ++$iterator);
		} else {
			$this->article->url($url);
			$this->articlesRepository->save($this->article);
		}
	}

}
