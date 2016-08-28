<?php

class User {

    private $id;
    private $name;
    private $email;
    private $hashedPassword;
    
    public function __construct(){
        $this -> id = -1;
        $this -> name = '';
        $this -> email = '';
        $this -> hashedPassword = '';
    }
    
    public function getId(){
        return $this -> id;
    }
    
    public function setName($name){
        if(is_string($name) && strlen(trim($name)) > 0){
            $this -> name = trim($name);
            }
        return $this;
    }
    
    public function getName(){
        return $this -> name;
    }
    
    public function setEmail($email){
        if(is_string($email) && strlen(trim($email)) >= 5){
            $this -> email = trim($email);
        }
    }
    
    public function getEmail(){
        return $this -> email;
    }
    
    public function setPassword($password){
        if(is_string($password) && strlen(trim($password)) > 5){
            $this -> hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }
    }
    
    public function saveToDB(mysqli $connection){
        if($this -> id == -1){
            $query="INSERT INTO Users (email, name, hashed_password) 
                    VALUES ('{$this -> email}', '{$this -> name}', '{$this -> hashedPassword}')";
            echo $query;
            if($connection -> query($query)){
                $this -> id = $connection -> insert_id;
                return true;
            }else{
                return false;
            }
        }else{
               
        }
    }

    
}

?>