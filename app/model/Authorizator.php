<?php
 
namespace App\Model;
 
use Nette\Object;
use Nette\Security as NS;
 
/**
 * Users Authorizator.
 */
class MyAuthorizator extends Object implements NS\IAuthorizator 
{
 
	/**
	 * @var NS\Permission
	 */
	private $acl;
 
	public function __construct() {
		$this->acl = new NS\Permission();
		$this->acl->addRole('guest');
		$this->acl->addRole('user', 'registered');
		$this->acl->addRole('admin', 'user');
 
		$this->acl->addResource('backend');
		$this->acl->addResource('users');
 
		$this->acl->allow('user', array('backend'), array('view'));
		$this->acl->allow('admin');
	}
 
	function isAllowed($role, $resource, $privilege) {
		return $this->acl->isAllowed($role, $resource, $privilege);
	}
 
}