<?php

namespace App\Model;

use Dibi\Connection;

class GalleriesMapper extends BaseMapper
{

	/**
	 * GalleriesMapper constructor.
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		parent::__construct($db);
	}

	/**
	 * @param $item
	 * @return array|null
	 */
	public function getAllGalleriesWithPictures($item) {
		$galleryIds = json_decode($item->galleryIds());
		if (count($galleryIds))
			return $this->db->select('g.name AS galleryName, g.description AS galleryDescription, p.*')
				->from($this->tableName)->as('g')
				->innerJoin('pictures')->as('p')->on('p.galleryId = g.id')
				->where('g.id IN %in', $galleryIds)
				->orderBy('g.id')
				->fetchAll();
		return null;
	}

}
