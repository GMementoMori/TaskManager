<?php
namespace Framework\ActiveRecord;

use Framework\ActiveRecord\ActiveRecordInterface;

class ActiveRecord implements ActiveRecordInterface
{
	  protected $connect;
 
    protected $arFieldsValues;

    public $tableName;

	function __construct($connect, $tableName)
	{
		$this -> connect = $connect;

		$this -> tableName = $tableName;

	}

	function __call($title, $arguments)
	{

        $typeMethod = substr($title, 0, 3); 

        if($typeMethod == 'set'){

           $titleRow = implode(explode('set', $title));

           $result = $this -> setField(mb_strtolower($titleRow), $arguments[0]);

        }
        elseif($typeMethod == 'get') {

           $titleRow = implode(explode('get', $title));
           
           $result = $this -> getField(mb_strtolower($titleRow));
        }
        
        return $result;
	}

	function create()
  {

        if(empty($this->id)){

		    $instance = new self($this->connect, $this->tableName);

		    $instance -> newItem = true;

            return $instance;

        }else{

        	return null;
        
        }
  }

	function getById(int $id)
	{
		
		    $rows = $this -> connect -> getOne('SELECT * FROM `'.$this->tableName.'` WHERE `id` = '.$id);

        if(!empty($rows['id']) && (empty($this->newItem) || $this->newItem == false)){

		    $instance = new self($this->connect, $this->tableName);

		    $instance -> id = $id;

		    $instance -> arFieldsValues = $this -> connect -> getOne('SELECT * FROM 
		    	                                                     `'.$this->tableName.'` 
		    	                                                  WHERE 
		    	                                                     `id` = '.$id);

            return $instance;

        }else{

        	return null;
        
        }
	}

  function delete(int $id)
  {
    
       return $this -> connect -> execute('DELETE FROM `'.$this->tableName.'` WHERE `id` = '.$id);

  }
  
  function getList($filter,$limit='')
  {
        $filterQuery = 'SELECT * FROM `' . $this -> tableName . '` WHERE ';
      
        $keyWords = ['maxCount'];

        foreach($filter as $rowName => $filterValues){

           if($rowName != array_keys($filter)[0]){

               $filterQuery .= ' AND ';
           
           }
           if(is_array($filterValues)){

               $filterQuery .= "`" . $rowName . "` " . $filterValues[1] . " '" . $filterValues[0] . "'";

           }else{

               $filterQuery .= "`" . $rowName . "` = '" . $filterValues . "'";

           }
        }

        if(!empty($limit)){
           $filterQuery .= ' LIMIT ' . $limit . ';';
        }

        $this -> itemsList = $this -> connect -> getAll($filterQuery);

        return $this -> itemsList;
  }
  
  function getField($title)
  {
       
        return (!empty($result = $this -> arFieldsValues[$title]))? $result : false;
  }

  function setField($title, $value)
  {
     
        $this -> arFieldsValues[$title] = $value;

        return true;
  }

  function getFields()
  {
        return $this -> arFieldsValues;
  }

  function save()
  {
        
        if(!empty($this->id) && (empty($this->newItem) || $this->newItem == true)){

          foreach ($this -> arFieldsValues as $fieldTitle => $fieldValue) {
          	
          	$field_value[] = "`".$fieldTitle."` = '".$fieldValue."'";

          }

          $valueUpdate = implode(',',$field_value);

    	    $query = 'UPDATE `'.$this -> tableName.'` SET '.$valueUpdate.' WHERE `id` = '.$this -> id;
        
        }else{

          foreach ($this -> arFieldsValues as $fieldTitle => $fieldValue) {
            
            $field_values[] = "'".$fieldValue."'";
            
            $field_keys[] = "`".$fieldTitle."`";

          }

          $query = 'INSERT INTO `'.$this -> tableName.'` 
                           ('.implode(',',$field_keys).') 
                    VALUES 
                           ('.implode(',',$field_values).')';

        }

        return $this -> connect -> execute($query);

  }
}