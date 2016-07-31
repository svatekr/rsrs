<?php

namespace App;

use App\Model\ArticlesRepository;
use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;
use App\Model\PagesRepository;
use App\Model\NewsRepository;

/**
 * Class RouterFactory
 * @package App
 */
class RouterFactory {
	
	/** @var PagesRepository */
	private $pagesRepository;

	/** @var ArticlesRepository */
	private $articlesRepository;

	/** @var NewsRepository */
	private $newsRepository;

	public function __construct(PagesRepository $pagesRepository, ArticlesRepository $articlesRepository, NewsRepository $newsRepository) {
		$this->pagesRepository = $pagesRepository;
		$this->articlesRepository = $articlesRepository;
		$this->newsRepository = $newsRepository;
	}
	
	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter() {
		$router = new RouteList();
		
		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('[<locale=cs cs|en>/]admin/<presenter>/<action>', 'Pages:default');

		$router[] = $frontRouter = new RouteList('Front');
		$frontRouter[] = new Route("rss.xml", 'Homepage:rss');
		$frontRouter[] = new Route("sitemap.xml", 'Homepage:sitemap');
		$frontRouter[] = new Route('[<locale=cs cs|en>/]blog', 'Articles:default');

		$frontRouter[] = new PageRoute('[<locale=cs cs|en>/][stranky/]<id>', array(
			'id' => array(
				Route::FILTER_IN => function ($id) {
					if(is_numeric($id)) {
						return $id;
					} else {
						$page = $this->pagesRepository->getOneWhere(['url' => $id]);
						if($page === NULL)
							return NULL;
						return $page->id();
					}
				},
				Route::FILTER_OUT => function ($id) {
					if(!is_numeric($id)) {
						return $id;
					} else {
						$page = $this->pagesRepository->get($id);
						return $page->url();
					}
				}
			),
			'presenter' => 'Pages',
			'action' => 'view'
		));

		$frontRouter[] = new ArticleRoute('[<locale=cs cs|en>/]blog/<id>', array(
			'id' => array(
				Route::FILTER_IN => function ($id) {
					if(is_numeric($id)) {
						return $id;
					} else {
						$article = $this->articlesRepository->getOneWhere(['url' => $id]);
						if($article === NULL)
							return NULL;
						return $article->id();
					}
				},
				Route::FILTER_OUT => function ($id) {
					if(!is_numeric($id)) {
						return $id;
					} else {
						$article = $this->articlesRepository->get($id);
						return $article->url();
					}
				}
			),
			'presenter' => [
				Route::VALUE => 'Articles',
				Route::FILTER_TABLE => [
					'blog' => 'Articles',
				],
			],
			'action' => 'view'
		));

		$frontRouter[] = new NewsRoute('[<locale=cs cs|en>/][novinky/]<id>', array(
			'id' => array(
				Route::FILTER_IN => function ($id) {
					if(is_numeric($id)) {
						return $id;
					} else {
						$page = $this->newsRepository->getOneWhere(['url' => $id]);
						if($page === NULL)
							return NULL;
						return $page->id();
					}
				},
				Route::FILTER_OUT => function ($id) {
					if(!is_numeric($id)) {
						return $id;
					} else {
						$page = $this->newsRepository->get($id);
						return $page->url();
					}
				}
			),
			'presenter' => 'News',
			'action' => 'view'
		));
		$frontRouter[] = new Route('[<locale=cs cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');
		$frontRouter[] = new Route('[<locale=cs cs|en>/]index.php', 'Homepage:default', Route::ONE_WAY);
		return $router;
	}

}
