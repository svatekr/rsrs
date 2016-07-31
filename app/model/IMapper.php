<?php
 
namespace App\Model;
 
interface IMapper 
{
 
	function get($id);
 
	function getAll();
 
	function getOneWhere($cond);
 
	function getAllWhere($cond);
 
	function getAllWhereOr($cond);
 
	function itemToArray($item);
 
	function returnItemOrNull($data);
}
