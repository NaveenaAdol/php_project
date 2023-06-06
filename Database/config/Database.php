<?php

require_once("config/config.php");
 
 class Database{

    private $host= DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname= DB_NAME;

    private $connection;
    private $error;
    private $stmt;
    private $dbconnected = false;

public function __construct() {
  $dsn = 'mysql:host=' .$this->host .';dbname' .$this->dbname;
  $options= array(
     PDO::ATTR_PERSISTENT => true ,
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  );

try {
    $this->connection=new PDO($dsn,$this->user,$this->pass,$options);
} catch(PDOException $e) {
    $this->error=$e->getMessage(). PHP_EOL;
}
}
public function getError() {
   return $this->error; 
}
public function isConnected() {
    return $this->dbconnected;
}
// prepare statement with query
public function query($query) {
    $this->stmt=$this->connection->prepare($query);
}
// execute the prepare statement
public function execute() {
    return $this->stmt->execute();
}

public function resultset() {
    $this->execute();
    return $this->stmt->fetchALL(PDO::FETCH_OBJ);
}
public function rowCount() {
    return $this->stmt->rowCount();
 }
 //get single record as object
 public function single() {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
 }
 public function bind($param,$value,$type= null) {
    if (is_null($type)) {
    switch(true) {
     case is_int($value):
         $type = PDO::PARAM_INT;
         break;
     case is_bool($value):
         $type = PDO::PARAM_BOOL;
         break;
     case is_null($value):
         $type = PDO::PARAM_NULL;
         break;
     default:
         $type = PDO::PARAM_STR;
     }
    }
     $this->stmt->bindValue($param,$value,$type);
}
 }