<?php
 
namespace App\Model;
 
use Nette\Object;
 
/**
 * Class BaseRepository
 * @package App\Model
 */
abstract class BaseRepository extends Object 
{
 
	/** @var BaseMapper */
	private $mapper;
 
	/**
	 * BaseRepository constructor.
	 * @param BaseMapper $mapper
	 */
	public function __construct(BaseMapper $mapper) {
		$this->mapper = $mapper;
	}

	/**
	 * @param $id
	 * @return null|Object
	 */
	public function get($id) {
		return $this->mapper->get($id);
	}

	/**
	 * @param array $cond
	 * @return BaseMapper
	 */
	public function getOneWhere(array $cond) {
		return $this->mapper->getOneWhere($cond);
	}
 
	/**
	 * @return \Dibi\Fluent
	 */
	public function getAll() {
		return $this->mapper->getAll();
	}
 
	/**
	 * @param array $cond
	 * @return \Dibi\Fluent
	 */
	public function getAllWhere(array $cond) {
		return $this->mapper->getAllWhere($cond);
	}
 
	/**
	 * @param array $cond
	 * @return \Dibi\Fluent
	 */
	public function getAllWhereOr(array $cond) {
		return $this->mapper->getAllWhereOr($cond);
	}
 
	/**
	 * @param object $item
	 * @return array|null
	 */
	public function save($item) {
		return $this->mapper->save($item);
	}
 
	/**
	 * @param $id
	 */
	public function delete($id) {
		$this->mapper->delete($id);
	}
 
	public function begin() {
		$this->mapper->begin();
	}
 
	public function commit() {
		$this->mapper->commit();
	}
 
	public function rollback() {
		$this->mapper->rollback();
	}
 
	/**
	 * @param $item
	 * @return array
	 */
	public function itemToArray($item) {
		return $this->mapper->itemToArray($item);
	}
 
}