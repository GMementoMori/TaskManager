<?php
namespace Framework\ActiveRecord;

interface ActiveRecordInterface
{

	public function create(); //create object

	public function getById(int $id); //get new instance of self class by id from DB

	public function getList($filter,$limit); //get array rows table from DB, LIMIt - max count of item

    public function getField($title); //get field 

    public function setField($title, $value); //set value for field

    public function getFields(); //get array rows from DB

    public function save(); //save obj in DB

}