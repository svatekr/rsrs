<?php

namespace App;

use App\Model\PagesRepository;
use Nette\Http\IRequest;
use Nette\Application\Routers\Route;
use Nette\Application\BadRequestException;

class PageRoute extends Route
{

	/** @var PagesRepository @inject */
	public $pagesRepository;

    /**
     * @return Nette\Application\IRouter
	 * @param IRequest $httpRequest
     */
	public function match(IRequest $httpRequest) {
		$appRequest = parent::match($httpRequest);
		if (!isset($appRequest->parameters['id']))
			return NULL;
		$id = $appRequest->parameters['id'];
		if (!is_numeric($id)) {
			$page = NULL;
			try {
				$page = $this->pagesRepository->getOneWhere(['url' => $id]);
			} catch (BadRequestException $exc) {
				return NULL;
			}
			$appRequest->parameters['id'] = $page->id();
		}
		return $appRequest;
	}

}
