<?php
 
namespace App\Model;
 
/**
 * Class UsersRepository
 * @package App\Model
 */
class UsersRepository extends BaseRepository 
{
 
	/** @var UsersMapper */
	private $mapper;
 
	/**
	 * UsersRepository constructor.
	 * @param UsersMapper $mapper
	 */
	public function __construct(UsersMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}
 
}
