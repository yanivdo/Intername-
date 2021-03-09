<?php

include_once './model/Users.php';
include_once './model/Posts.php';
include_once './general/Curl.php';
class ClientController
{
    private $usersModel;
    private $postsModel;
    private $curl;
    function __construct()
    {
        $this->usersModel = new Users();
        $this->postsModel = new Posts();
        $this->curl = new Curl();
    }



    function buildView($limit = 20, $offset = 0)
    {
        $result = new stdClass();
        if (!isset($_SESSION['fetchUsers'])) {
            $this->createAddUsers(json_decode($this->curl->curlFetch("https://jsonplaceholder.typicode.com/users")));
            $_SESSION['fetchUsers'] = 1;
        }
        if (!isset($_SESSION['fetchPosts'])) {
            $this->createAddPosts(json_decode($this->curl->curlFetch("https://jsonplaceholder.typicode.com/posts")));
            $_SESSION['fetchPosts'] = 1;
        }
        $result->posts = $this->postsModel->getPosts($limit, $offset);
        $result->total = $result->posts[0]['full_count'];
        $result->avgSum = $this->getAvgPosts();
        return $result;
    }

    function createAddUsers($data)
    {
        $insertData = "";
        $added = ", ";
        foreach ($data as $key => $singleData) {
            if ($key == (sizeof($data) - 1)) {
                $added = "";
            }
            $insertData .= "('" . $singleData->name . "',
            '" . $singleData->email . "',
            '" . date("Y-m-d") . "',
            '" . date("Y-m-d") . "') " . $added . "";
        }
        $result = $this->usersModel->create($insertData);
        return $result;
    }

    function createAddPosts($data)
    {
        $insertData = "";
        $added = ", ";
        foreach ($data as $key => $singleData) {
            if ($key == (sizeof($data) - 1)) {
                $added = "";
            }
            $insertData .= "(" . $singleData->userId . ",
            '" . $singleData->title . "',
            '" . $singleData->body . "',
            '" . date("Y-m-d") . "',
            '" . date("Y-m-d") . "') " . $added . "";
        }
        $this->postsModel->create($insertData);
    }

    function addNewData($data)
    {
        $user = new stdClass();
        $post = new stdClass();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $checkUser = $this->usersModel->checkUser($user);
        if (empty($checkUser)) {
            $userId = $this->createAddUsers([$user]);
        } else {
            $userId = $checkUser[0]['id'];
        }
        $post->userId = $userId;
        $post->title = $data['title'];
        $post->body = $data['body'];
        $this->createAddPosts([$post]);
        $result = 'data added successfully';
        return $result;
    }

    function getAvgPosts()
    {
        $avgPosts = [];
        $result = [];
        $countPostsMonth = $this->postsModel->getUsersPosts();
        foreach ($countPostsMonth as $singleData) {
            if (isset($avgPosts[$singleData['user_id']]['total'])) {
                $avgPosts[$singleData['user_id']]['total'] += $singleData['total'];
            } else {
                $avgPosts[$singleData['user_id']]['total'] = 1;
            }
            if (isset($avgPosts[$singleData['user_id']]['months'][$singleData['months']])) {
                $avgPosts[$singleData['user_id']]['months'][$singleData['months']] += $singleData['total'];
            } else {
                $avgPosts[$singleData['user_id']]['months'][$singleData['months']] = $singleData['total'];
            }
            if (isset($avgPosts[$singleData['user_id']]['weeks'][$singleData['weeks']])) {
                $avgPosts[$singleData['user_id']]['weeks'][$singleData['weeks']] += $singleData['total'];
            } else {
                $avgPosts[$singleData['user_id']]['weeks'][$singleData['weeks']] = $singleData['total'];
            }
        }
        foreach ($avgPosts as $key => $singleAvgPost) {
            $monthAvg =  $singleAvgPost['total'] / sizeof($singleAvgPost['months']);
            $weekAvg = $singleAvgPost['total'] / sizeof($singleAvgPost['weeks']);
            $result[] = ['userId' => $key, 'monthAvg' => $monthAvg, 'weeksAvg' => $weekAvg];
        }
        return $result;
    }

    function searchData($data)
    {
        $result = "";
        switch ($data['searchBy']) {
            case "text":
                $result = $this->postsModel->searchByContent($data['searchText']);
                break;
            case "post id":
                $result = $this->postsModel->searchById($data['searchText']);
                break;
            case "user id":
                $result = $this->postsModel->searchByUserId($data['searchText']);
                break;
        }
        return $result;
    }
}
