<?php

namespace App\Model;

class GalleriesRepository extends BaseRepository
{

	/** @var GalleriesMapper */
	private $mapper;

	/**
	 * GalleriesRepository constructor.
	 * @param GalleriesMapper $mapper
	 */
	public function __construct(GalleriesMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

	/**
	 * @param $item
	 * @return array
	 */
	public function getAllGalleriesWithPictures($item) {
		$galleries = [];
		$rows = $this->mapper->getAllGalleriesWithPictures($item);
		if ($rows)
			foreach ($rows as $row) {
				$galleries[$row['galleryId']]['gallery'] = ['name' => $row['galleryName'], 'description' => $row['galleryDescription']];
				$galleries[$row['galleryId']]['pictures'][] = $row;
			}
		return $galleries;
	}

}
