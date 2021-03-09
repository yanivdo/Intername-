<?php


class DbConnection
{
    private $serverName;
    private $userName;
    private $password;
    private $dbName;

    protected function connect()
    {
        $this->serverName = "localhost";
        $this->userName = "root";
        $this->password = "";
        $this->dbName = "general_db";
        $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->dbName);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    protected function returnDbData($dbResult)
    {
        $result = [];
        if ($dbResult->num_rows > 0) {
            while ($row = $dbResult->fetch_assoc()) {
                $result[] = $row;
            }
        }
        return $result;
    }
}
