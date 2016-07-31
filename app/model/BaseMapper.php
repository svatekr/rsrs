<?php

namespace App\Model;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\ForeignKeyConstraintViolationException;
use Nette\Object;
use Tracy\Debugger;
use Tracy\ILogger;
use Nette\Application\BadRequestException;

/**
 * Class BaseMapper
 * @package App\Model
 */
abstract class BaseMapper extends Object implements IMapper
{

	/** @var Connection */
	protected $db;
	protected $tableName;
	protected $primaryKey;
	protected $entityName;

	/**
	 * Class constructor
	 *
	 * @param Connection $db
	 */
	public function __construct(Connection $db) {
		$this->db = $db;
		$this->tableName = $this->getTableName();
		$this->primaryKey = $this->getPrimaryKey();
		$this->entityName = $this->getEntityName();
	}

	/**
	 * @param $id
	 * @return null|Object
	 * @throws BadRequestException
	 */
	public function get($id) {
		$row = $this->db->select('*')
				->from($this->tableName)
				->where($this->primaryKey . ' = %i', $id)
				->fetch();

		return $this->returnItemOrNull($row);
	}

	/**
	 * @param $cond
	 * @return BaseMapper
	 */
	public function getOneWhere($cond) {
		$row = $this->db->select('*')
				->from($this->tableName)
				->where('%and', $cond)
				->fetch();

		return $this->returnItemOrNull($row);
	}

	/**
	 * @return \Dibi\Fluent
	 */
	public function getAll() {
		return $this->db->select('*')
						->from($this->tableName);
	}

	/**
	 * @param $cond
	 * @return \Dibi\Fluent
	 */
	public function getAllWhere($cond) {
		return $this->db->select('*')
						->from($this->tableName)
						->where('%and', $cond);
	}

	/**
	 * @param $cond
	 * @return \Dibi\Fluent
	 */
	public function getAllWhereOr($cond) {
		return $this->db->select('*')
						->from($this->tableName)
						->where('%or', $cond);
	}

	/**
	 * @param $item
	 * @return \Dibi\Result|int
	 */
	public function save($item) {
		if ($item->{$this->primaryKey}() === NULL) {
			$data = $this->itemToArray($item);
			try {
				$this->db->insert($this->tableName, $data)->execute();
				return $this->db->insertId();
			} catch (Exception $e) {
				Debugger::log(printf("An error has occurred: %s\n", $e->getMessage()), ILogger::ERROR);
				return NULL;
			}
		} else {
			$data = $this->itemToArray($item);
			try {
				$this->db->update($this->tableName, $data)
						->where($this->primaryKey . ' = %i', $item->{$this->primaryKey}())->execute();
				return $item->{$this->primaryKey}();
			} catch (ForeignKeyConstraintViolationException $e) {
				Debugger::log($e->getMessage(), ILogger::ERROR);
				return NULL;
			}
		}
	}

	/**
	 * @param $id
	 */
	public function delete($id) {
		try {
			$this->db->delete($this->tableName)
					->where($this->primaryKey . ' = %i', $id)
					->execute();
		} catch (Exception $e) {
			Debugger::log($e->getMessage(), ILogger::ERROR);
		}
	}

	/**
	 * @param $data
	 * @return $this->entityName|null
	 */
	public function returnItemOrNull($data) {
		if ($data)
			return $this->arrayToItem($data, new $this->entityName);
		else
			return NULL;
	}

	public function begin() {
		$this->db->begin();
	}

	public function commit() {
		$this->db->commit();
	}

	public function rollback() {
		$this->db->rollback();
	}

	/**
	 * @param object $item
	 * @return array
	 */
	public function itemToArray($item) {
		$methods = get_class_methods(key(class_implements($item)));
		$array = [];
		foreach ($methods as $method)
			$array[$method] = $item->$method();
		return $array;
	}

	/**
	 * @param $data
	 * @param $item
	 * @return $this->getEntityName()
	 */
	private function arrayToItem($data, $item) {
		$methods = get_class_methods(key(class_implements($item)));
		foreach ($methods as $method)
			$item->$method($data[$method]);
		return $item;
	}

	/**
	 * @return mixed
	 */
	private function getPrimaryKey() {
		$primaryKey = $this->db->getDatabaseInfo()->getTable($this->tableName)->getPrimaryKey()->getColumns();
		return $primaryKey[0]->name;
	}

	/**
	 * @return mixed
	 */
	private function getTableName() {
		return strtolower(str_replace('Mapper', '', str_replace(__NAMESPACE__ . '\\', '', get_called_class())));
	}

	/**
	 * @return mixed
	 */
	private function getEntityName() {
		return str_replace('Mapper', 'Entity', get_called_class());
	}

}
