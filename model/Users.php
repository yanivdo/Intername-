
<?php

class Users extends DbConnection{

    private $conn;

    function __construct(){
        $this->conn = $this->connect();
    }

    function getUsers(){
        $sql = "SELECT `name`,`email`, `updated_at`, `created_at` 
        FROM users;";
        $dbResult = $this->conn->query($sql);
        $result = $this->returnDbData($dbResult);
        return $result; 
    }

    function create($users){
        $sql = "INSERT INTO users
        (`name`, `email`, `updated_at`, `created_at`)
                VALUES ".$users.";";
        $this->conn->query($sql);
        $last_id = $this->conn->insert_id;
        return $last_id; 
    }

    function checkUser($user){
        $sql = "SELECT `id`, `name`,`email`, `updated_at`, `created_at` 
        FROM users
		WHERE `name` = '".$user->name."' AND `email` = '".$user->email."';";
        $dbResult = $this->conn->query($sql);
        $result = $this->returnDbData($dbResult);
        return $result; 
    }
}