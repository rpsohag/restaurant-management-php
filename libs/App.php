<?php

require "../config/config.php";

class App{
    public $host = DB_HOST;
    public $dbname = DB_NAME;
    public $username = DB_USER;
    public $password = DB_PASSWORD;

    public $link;

    // create a constructor
    public function __construct()
    {
        $this->connect();

    }

    public function connect(){
        $this->link = new PDO("mysql:host=". $this->host. ";dbname=". $this->dbname, $this->username, $this->password);
        $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if($this->link){
            echo "Connected successfully";
        }
    }

    /**
     * select all rows from the database
     */
    public function selectAll($query){
        $rows = $this->link->query($query);
        $rows->execute();

        $allRows = $rows->fetchAll(PDO::FETCH_ASSOC);
        if($allRows){
            return $allRows;
        }else{
            return false;
        }
    }

    /**
     * select single row from the database
     */
    public function selectOne($query){
        $row = $this->link->query($query);
        $row->execute();
        $singleRow = $row->fetch(PDO::FETCH_ASSOC);
        if($singleRow){
            return $singleRow;
        } else{
            return false;
        }
    }

    /**
     * insert data into the database
     */
    public function insert($query, $arr, $path){
      if($this->validate($arr) == "empty"){
        echo "<script> alert('one or more inputs are empty');</script>";
      }else{
        $insert_record = $this->link->query($query);
        $insert_record->execute($arr);

        header("Location: " .$path."");
      }
    }

    /**
     * update data into the database
     */
    public function update($query, $arr, $path){
      if($this->validate($arr) == "empty"){
        echo "<script> alert('one or more inputs are empty');</script>";
      }else{
        $update_record = $this->link->query($query);
        $update_record->execute($arr);

        header("Location: " .$path."");
      }
    }
    
    /**
     * delete record from the database
     */
    public function delete($query, $path){
     
        $delete_record = $this->link->query($query);
        $delete_record->execute();

        header("Location: " .$path."");
      
    }

    /**
     * register data into the database
     */
    public function register($query, $arr, $path){
        if($this->validate($arr) == "empty"){
          echo "<script> alert('one or more inputs are empty');</script>";
        }else{
          $register_record = $this->link->query($query);
          $register_record->execute($arr);
  
          header("Location: " .$path."");
        }
      }


    public function login($query, $data, $path){
       
        $login_user = $this->link->query($query);
        $login_user->execute($data);
        $fetch = $login_user->fetch(PDO::FETCH_OBJ);

        if($login_user->rowCount() > 0){
            if(password_verify($data['password'], $fetch['password'])){
                header("Location: " .$path."");
            }
        }
      }

      /**
       * start session
       */
      public function startSession(){
        session_start();
      }

      public function validateSession($path){
        if(isset($_SESSION["id"])){
            header("location: ".$path."");
        }
      }

    public function validate($arr){
        if(in_array("", $arr)){
            echo "Empty array";
        }
    }

}

$app = new App();