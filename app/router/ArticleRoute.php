<?php

namespace App;

use App\Model\ArticlesRepository;
use Nette\Http\IRequest;
use Nette\Application\Routers\Route;
use Nette\Application\BadRequestException;

class ArticleRoute extends Route
{

	/** @var ArticlesRepository @inject */
	public $articlesRepository;

	/**
	 * @param IRequest $httpRequest
	 * @return \Nette\Application\Request|NULL
	 */
	public function match(IRequest $httpRequest) {
		$appRequest = parent::match($httpRequest);
		if (!isset($appRequest->parameters['id']))
			return NULL;
		$id = $appRequest->parameters['id'];
		if (!is_numeric($id)) {
			$article = NULL;
			try {
				$article = $this->articlesRepository->getOneWhere(['url' => $id]);
			} catch (BadRequestException $exc) {
				return NULL;
			}
			$appRequest->parameters['id'] = $article->id();
		}
		return $appRequest;
	}

}
