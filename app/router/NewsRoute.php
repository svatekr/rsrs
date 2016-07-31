<?php

namespace App;

use App\Model\NewsEntity;
use App\Model\NewsRepository;
use Nette\Http\IRequest;
use Nette\Application\Routers\Route;
use Nette\Application\BadRequestException;
use Nette\Application\IRouter;

class NewsRoute extends Route
{

	/** @var NewsRepository @inject */
	public $newsRepository;

	/**
	 * @return IRouter
	 * @param IRequest $httpRequest
	 */
	public function match(IRequest $httpRequest) {
		$appRequest = parent::match($httpRequest);
		if (!isset($appRequest->parameters['id']))
			return NULL;
		$id = $appRequest->parameters['id'];
		if (!is_numeric($id)) {
			$news = NULL;
			try {
				/** @var NewsEntity $news */
				$news = $this->newsRepository->getOneWhere(['url' => $id]);
			} catch (BadRequestException $exc) {
				return NULL;
			}
			$appRequest->parameters['id'] = $news->id();
		}
		return $appRequest;
	}

}
