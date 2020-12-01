<?php
namespace Controller\NoteController;

use Controller\NoteUserController\NoteUserController;
use Model\NoteModel\NoteModel;
use Model\Connect\Connect;

class NoteController 
{
   function __construct($user_id)
   {

      $modelDB = new Connect(); 
      
      $connect = $modelDB -> connect;

      $this -> connect = new NoteModel($connect, 'notes'); 

      $this -> user_note_controller = new NoteUserController($user_id);

   }

   public function getListNotes()
   {
      $notes = [];

   	  $ids = $this -> user_note_controller -> getNoteIds();

      if (!empty($ids)) {

   	      foreach ($ids as $id) {
   	  	
            $result = $this -> connect -> getList(['id'=>$id]);

            $notes[$id]['title'] = $result[0]['title'];

            $notes[$id]['text'] = $result[0]['text'];

   	      }
      }

   	  return $notes;
   }

   public function chooseAction($params)
   {
       $type = (!empty(array_keys($params)[0]))? array_keys($params)[0]: '';
       $id = (!empty($params['id']))? $params['id']: 0;
       $title = (!empty($params['title']))? htmlspecialchars($params['title']): '';
       $text = (!empty($params['text']))? htmlspecialchars($params['text']): '';
        
       if ($type === 'save') {

           $resultValidate = $this -> validation(['title' => $title, 'text' => $text]);
       
           if ($resultValidate) {

               return $this -> saveNote(['id'=>$id, 'title'=> $title, 'text'=> $text]);

           }else{

               return 'error_validate';

           }
        }   
        elseif ($type === 'delete') {
          
           return $this -> deleteNote($id);

        }
    }

   private function getLastId()
   {
        $result = $this -> connect -> getList(['id'=>[0,'>']]);
         
        $indx = count($result);

        $result = $result[$indx - 1]['id'];

        return $result;
    }

   private function verification($params)
   {
        $result = $this -> connect -> getList($params);

        if(!empty($result)){
  
            return $result[0]['id']; //first element of list

        }else{

            return false;
  
        }
    }

    private function saveNote($params)
    {
        $checkID = $this -> verification(['id' => $params['id']]);

        if(!$checkID){

            $newNote = $this -> connect -> create();

            if($newNote){

                $newNote -> setTitle($params['title']);

                $newNote -> setText($params['text']);

                $newNote -> save();

                $newId = $this -> getLastId();

                $this -> user_note_controller -> createRelation($newId);

                return $newNote;

            }else{
             
                return 'error_create_note';

            }

        }else{

            $note = $this->connect->getById($checkID);

            if($note){

                $note -> setTitle($params['title']);

                $note -> setText($params['text']);

                $note -> save();

                return $note;

            }else{
             
                return 'error_update_note';

            }

        }
    }
    private function deleteNote($idNote)
    {

        if(!empty($idNote)){

            $this -> connect -> delete($idNote);

            $this -> user_note_controller -> deleteRelation($idNote);

            return true;

        }else{
             
            return 'error_to_find_note';

        }
    }
    private function validation($params)
    {
        $rulesPass = [];

        foreach ($params as $key => $value) {

            if ($key === 'text') {

                $rulesPass['len'] = (mb_strlen($value) <= 200)? true : false;

            }
            elseif ($key === 'title') {

                $rulesPass['len'] = (mb_strlen($value) <= 15)? true : false;

            }

            if (in_array(false, $rulesPass)) {

                return false;
            }

        }
        
        return true;
    }
}