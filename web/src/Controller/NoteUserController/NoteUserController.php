<?php
namespace Controller\NoteUserController;

use Model\NoteUserModel\NoteUserModel;
use Model\Connect\Connect;

class NoteUserController 
{
   private $USER_ID;

   function __construct($user_id)
   {

      $modelDB = new Connect(); 
      
      $connect = $modelDB -> connect;

      $this -> connect = new NoteUserModel($connect, 'user_note'); 

      $this -> USER_ID = (!empty($user_id) && $user_id != 0)? $user_id : false;

   }

   public function getNoteIds()
   {

       $ids = [];

   	   $resultArray = $this -> connect -> getList(['id_user'=> $this -> USER_ID]);

       if (!empty($resultArray)) {

   	      foreach ($resultArray as $relationArr) {

   	            foreach ($relationArr as $key => $value) {
                 
                    if ($key === 'id_note') {

                      	$ids[] = $value;
                 	
                    }
                }
                
   	      }

        }

   	   return $ids;
   }

   public function createRelation($noteId)
   {
       $newrelation = $this -> connect -> create();

       $newrelation -> setId_user($this -> USER_ID);

       $newrelation -> setId_note($noteId);

       $newrelation -> save();

       return true;

   }

   public function deleteRelation($noteId)
   {
       $relation = $this -> connect -> create();

       $resultArray = $this -> connect -> getList(['id_note'=> $noteId]);

       foreach ($resultArray as $relationArr) {

            foreach ($relationArr as $key => $value) {
                 
                 if ($key === 'id') {

                  $this -> connect -> delete($value);
                  
                 }
            }
       }

       return true;

   }
}