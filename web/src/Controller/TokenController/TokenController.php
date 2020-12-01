<?php
namespace Controller\TokenController;

use Model\TokenModel\TokenModel;
use Model\Connect\Connect;

class TokenController
{

   function __construct()
   {

      $modelDB = new Connect(); 
      
      $connect = $modelDB -> connect;

      $this -> connect = new TokenModel($connect, 'user_token'); 

   }
   public function checkToken($params) // return token id
   {
        $result = $this -> connect -> getList($params);

        if(!empty($result)){
  
            return $result[0]['id']; //first element of list

        }else{

            return false;
  
        }
   }
   public function checkTokenUser($token) //return user id
   {
        $result = $this -> connect -> getList(['token'=>$token]);

        if(!empty($result)){
  
            return $result[0]['id_user']; //first element of list

        }else{

            return false;
  
        }
   }
   public function createToken($idUser)
   {
        $random_token = $this -> getRandomWord();

        $checkUserTokens = $this -> checkToken(['id_user'=>$idUser]); //check user for token

        if($checkUserTokens){

           $token = $this->connect->getById($checkUserTokens);
       
           $token -> setToken($random_token);

           $token -> save();

        }else{

           $token = $this->connect->create();
       
           $token -> setId_user($idUser);

           $token -> setToken($random_token);

           $token -> save();

        }

        return $random_token;

   }
   private function getRandomWord($len = 10) {

      $word = array_merge(
      	                 array_merge(range('a', 'z'),[rand(999,999999)]),
      	                 array_merge([rand(999,999999)],range('A', 'Z'))
      	                );

      shuffle($word);

      return substr(implode($word), 0, $len);
   }
}