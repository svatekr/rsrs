<?php

namespace App\Model;

class UsersEntity extends \Nette\Object implements IUsersEntity
{

	private $id;
	private $username;
	private $password;
	private $name;
	private $lastname;
	private $email;
	private $role;
	private $passwordHash;
	private $passwordHashValidity;

	public function id($id = null) {
		if (!is_null($id))
			$this->id = $id;
		return $this->id;
	}

	public function username($username = null) {
		if (!is_null($username))
			$this->username = $username;
		return $this->username;
	}

	public function password($password = null) {
		if (!is_null($password))
			$this->password = $password;
		return $this->password;
	}

	public function name($name = null) {
		if (!is_null($name))
			$this->name = $name;
		return $this->name;
	}

	public function lastname($lastname = null) {
		if (!is_null($lastname))
			$this->lastname = $lastname;
		return $this->lastname;
	}

	public function email($email = null) {
		if (!is_null($email))
			$this->email = $email;
		return $this->email;
	}

	public function role($role = null) {
		if (!is_null($role))
			$this->role = $role;
		return $this->role;
	}

	public function passwordHash($passwordHash = null) {
		if (!is_null($passwordHash))
			$this->passwordHash = $passwordHash;
		return $this->passwordHash;
	}

	public function passwordHashValidity($passwordHashValidity = null) {
		if (!is_null($passwordHashValidity))
			$this->passwordHashValidity = $passwordHashValidity;
		return $this->passwordHashValidity;
	}

}