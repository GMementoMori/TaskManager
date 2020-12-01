<?php
namespace Model\Connect;

use Framework\DB\DB;

class Connect
{
   
   function __construct()
   {
      $this -> connect = new DB(['host'=>'mysql',
                                 'user'=>'root',
                                 'password'=>'root',
                                 'baseName'=>'notes',
                                 'port'=>'3306']);

   }

}