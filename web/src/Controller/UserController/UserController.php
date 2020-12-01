<?php
namespace Controller\UserController;

use Model\UserModel\UserModel;
use Model\Connect\Connect;

class UserController
{

   public $connect;
   
   function __construct()
   {

      $modelDB = new Connect(); 
      
      $connect = $modelDB -> connect;

      $this -> connect = new UserModel($connect, 'users'); 

   }

   public function main($params)
   {
       $type = (!empty(array_keys($params)[2]))? array_keys($params)[2]: '';
       $login = (!empty(array_values($params)[0]))? htmlspecialchars(array_values($params)[0]): '';
       $password = (!empty(array_values($params)[1]))? htmlspecialchars(array_values($params)[1]): '';

       $resultValidate = $this -> validation(['login' => $login, 'password' => $password]);
       
       if ($resultValidate) {

           return $this -> checkEnter($type, $login, $password);

       }else{

           return 'error_validate';

       }
        
   }

   private function checkEnter($type, $login, $password)
   {

       if(!empty($type) && !empty($login)){

            if($type === 'Log_In'){

               return $this -> logIn(['login'=> $login, 'password'=> $password]);

            }
            elseif($type === 'Register'){

               $result = $this -> register(['login'=> $login, 'password'=> $password]);

               if (is_object($result)) {
 
                    return $this -> logIn(['login'=> $login, 'password'=> $password]);

               }
              
            }

        }

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

    private function register($params)
    {
        $checkID = $this -> verification(['login' => $params['login']]);

        if(!$checkID){

            $newUser = $this->connect->create();

            if($newUser){

                $newUser -> setLogin($params['login']);

                $newUser -> setPassword($params['password']);

                $newUser -> save();

                return $newUser;

            }else{

                return 'error_registration';

            }

        }else{

            return 'created_already';

        }
    }

    private function logIn($params)
    {
        $checkID = $this -> verification($params);

        if($checkID){

           $user = $this->connect->getById($checkID);
       
           return $user;

        }else{

           return 'error_entered';

        }
    }
    public function logInByToken($id)
    {

        if($id){

           $user = $this->connect->getById($id);
       
           return $user;

        }else{

           return false;

        }
    }

    private function validation($values)
    {
        $rulesPass = [];

        foreach ($values as $key => $value) {

          if ($key === 'password') {

            $rulesPass['len'] = (strlen($value) >= 6)? true : false;

            $rulesPass['upcase'] = $this->validType('upcase', $value);

            $rulesPass['lowcase'] = $this->validType('lowcase', $value);

            $rulesPass['int'] = $this->validType('int', $value);


            if (in_array(false, $rulesPass)) {

                return false;
            }
          }
        }
        
        return true;

    }

    private function validType($type, $str)
    {
        $letters = (!empty($str))? str_split($str): [];

        foreach ($letters as $letter) {
          
           if ($type === 'upcase') {
             
              if (ctype_upper($letter)) {

                  return true;

              }

           }
           elseif ($type === 'lowcase') {
             
              if (ctype_lower($letter)) {

                  return true;

              }

           }
           elseif ($type === 'int') {
             
              if (ctype_digit(strval($letter))) {

                  return true;

              }

           }
        }

        return false;

    }
}
