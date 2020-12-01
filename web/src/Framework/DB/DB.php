<?php
namespace Framework\DB;

use PDO;

class DB {

	private $conn;
	private $host;
	private $user;
	private $password;
	private $baseName;
	private $port;
	private $Debug;
 
    function __construct($params=array()) {
		$this->conn = false;
		$this->host = (!empty($params['host']))?$params['host']:''; //hostname
		$this->user = (!empty($params['user']))?$params['user']:''; //username
		$this->password = (!empty($params['password']))?$params['password']:''; //password
		$this->baseName = (!empty($params['baseName']))?$params['baseName']:''; //name of your database
		$this->port = (!empty($params['port']))?$params['port']:'';
		$this->debug = true;
		$this->connect();
	}
 
	function __destruct() {
		$this->disconnect();
	}
	
	function connect() {
		if (!$this->conn) {
			try {
				$this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->baseName.';', $this->user, $this->password);  
			}
			catch (Exception $e) {
				die('Erreur : ' . $e->getMessage());
			}
 
			if (!$this->conn) {
				$this->status_fatal = true;
				echo 'Connection BD failed';
				die();
			} 
			else {
				$this->status_fatal = false;
			}
		}
 
		return $this->conn;
	}
 
	function disconnect() {
		if ($this->conn) {
			$this->conn = null;
		}
	}
	
	function getOne($query) {
		$result = $this->conn->prepare($query);
		$ret = $result->execute();
		if (!$ret) {
           return null;
		   
		   // die();
		}
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$reponse = $result->fetch();
		
		return $reponse;
	}
	
	function getAll($query) {
		$result = $this->conn->prepare($query);
		$ret = $result->execute();
		if (!$ret) {
           return null;
		   
		   // die();
		}
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$reponse = $result->fetchAll();
		
		return $reponse;
	}
	
	function execute($query) {
		if (!$response = $this->conn->exec($query)) {
           return null;

		   // die();
		}
		return $response;
	}
}