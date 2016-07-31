<?php
 
namespace App\Model;
 
interface IUsersEntity 
{
 
	public function id();
 
	public function username();
 
	public function password();
 
	public function name();
 
	public function lastname();
 
	public function email();
 
	public function role();
 
	public function passwordHash();
 
	public function passwordHashValidity();
}
