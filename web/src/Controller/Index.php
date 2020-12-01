<?php
namespace Controller;

use Framework\Controller\AbstractController;
use Controller\UserController\UserController;
use Controller\NoteController\NoteController;
use Controller\TokenController\TokenController;

class Index extends AbstractController
{

    private $tokenController;

    private $userController;

    private $noteController;


    public function index()
    {

        $token_main = (!empty($_COOKIE['token']))? $_COOKIE['token']: '';

        $params = $this -> request -> getParams();

        $this -> tokenController = new TokenController();

        $this -> userController = new UserController();

        $idUserToken = $this -> tokenController -> checkTokenUser($token_main);

        $result_auth = $this -> authorization($idUserToken, $token_main, $params);

        if (!empty($params['Log_Out'])) {

           $result_auth = $this -> output();

        }

        if(is_object($result_auth)){

           $user = $result_auth;

           $this -> noteController = new NoteController($user -> getId());

           $notes = $this -> noteController -> getListNotes();

           $resultNote = $this -> noteController -> chooseAction($params);
           
           $errors = (is_string($resultNote))? $resultNote: '';

           $notes = (is_array($notes))? $notes: [['title'=>'','text'=>'']];

           return $this -> view -> generate('notes_page/index.phtml', ['notes'=>$notes,'errors'=>$errors]);

        }
        else{
           $array_erorrs = [
            'error_validate' => 'Ошибка валидации, проверьте правильно ли заполненны поля. Поле password обязательно должно содержать минимум 6 символов, минимум 1 цифра и по 1 символу в нижнем и верхнем регистре.',
            'created_already' => 'Аккаун с таким логином уже существует',
            'error_registration' => 'Ошибка регистрации,проверьте правильно ли заполненны поля',
            'error_entered' => 'Ошибка входа, проверьте правильно ли заполненны поля'];

           $res_error = (is_string($result_auth))? $array_erorrs[$result_auth] : '';

           return $this -> view -> generate('register/index.phtml', ['errors'=> $res_error]);

        }

    }

    private function authorization($idUserToken, $token_main, $params)
    {

        if($idUserToken){

              $user = $this -> userController -> loginByToken($idUserToken);

              if(is_object($user)){

                  return $user;

              }else{

                  return false;
              }

        }
        elseif(in_array('Log_In', array_keys($params)) || in_array('Register', array_keys($params))){
            
            $result = $this -> userController -> main($params);

            if(is_object($result)){

                $idUser = $result -> getId();

                $token = $this -> tokenController -> createToken($idUser);

                setcookie('token', $token, time() + (86400 * 30), "/");

                return $result;

            }else{

                return $result; //return error string
            }
        }
        else{

            return false;

        }
    }

    private function output()
    {
 
        setcookie('token', null, -1, '/'); 

        return false;

    }
}