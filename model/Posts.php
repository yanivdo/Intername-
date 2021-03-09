<?php


class Posts extends DbConnection
{

    private $conn;

    function __construct()
    {
        $this->conn = $this->connect();
    }

    function searchById($id)
    {
        $sql = "SELECT P.`id`, U.`name`, P.user_id, P.`title`, P.body, P.updated_at, P.created_at  
        FROM posts P
        LEFT JOIN users U ON(U.id = P.user_id)
        WHERE P.`id` = $id;";
        $dbResult = $this->conn->query($sql);
        $result = $this->returnDbData($dbResult);
        return $result;
    }

    function searchByUserId($userId)
    {
        $sql = "SELECT P.`id`, U.`name`, P.user_id, P.`title`, P.body, P.updated_at, P.created_at  
        FROM posts P
        LEFT JOIN users U ON(U.id = P.user_id)
        WHERE P.`user_id` = $userId;";
        $dbResult = $this->conn->query($sql);
        $result = $this->returnDbData($dbResult);
        return $result;
    }

    function searchByContent($content)
    {
        $sql = "SELECT P.`id`, U.`name`, P.user_id, P.`title`, P.body, P.updated_at, P.created_at  
        FROM posts P
        LEFT JOIN users U ON(U.id = P.user_id)
        WHERE P.`title` LIKE '%$content%' OR P.`body` LIKE '%$content%';";
        $dbResult = $this->conn->query($sql);
        $result = $this->returnDbData($dbResult);
        return $result;
    }

    function getPosts($limit, $offset)
    {
        $sql = "SELECT P.`id`, U.`name`, P.user_id, P.`title`, P.body, P.updated_at, P.created_at, COUNT(P.`id`) OVER() AS full_count
        FROM posts P
        LEFT JOIN users U ON(U.id = P.user_id)
        LIMIT $limit OFFSET $offset;";
        $dbResult = $this->conn->query($sql);
        $result = $this->returnDbData($dbResult);
        return $result;
    }
    function create($posts)
    {
        $sql = "INSERT INTO posts
        (`user_id`, `title`, `body`, `updated_at`, `created_at`)
                VALUES " . $posts . ";";
        $result = $this->conn->query($sql);
        return $result;
    }

    function getUsersPosts(){
        $sql = "SELECT user_id, COUNT(id) total, MONTH(created_at) AS months, WEEK(created_at) weeks
        FROM posts
        GROUP BY id;";
        $dbResult = $this->conn->query($sql);
        $result = $this->returnDbData($dbResult);
        return $result;
    }

    function postsModelWeek(){
        $sql = "SELECT WEEK(created_at) AS week_created, COUNT(id) AS count_result
        FROM posts
        where user_id = 1
        GROUP BY week_created;";
        $dbResult = $this->conn->query($sql);
        $result = $this->returnDbData($dbResult);
        return $result;
    }
}
