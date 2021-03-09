<?php

include_once './controller/ClientController.php';
include_once './view/view.php';
class ClientView extends View
{
    private $userController;
    function __construct()
    {
        $this->ClientController = new ClientController();
    }

    function createView()
    {
        $data = $this->ClientController->buildView();
        $innerPage = $this->createTablePage($data->posts);
        $viewSum = $this->createPostsSum($data->avgSum);
        $page = $this->createTemplate('./template/posts/index.html', [
            'innerPage' => $innerPage,
            'postSum' => $viewSum ?? ''
        ]);
        $finalPage = $this->attachToIndex($page);
        return $finalPage;
    }

    function createFormView(){
        $page = $this->createTemplate('./template/form/index.html', []);
        $finalPage = $this->attachToIndex($page);
        return $finalPage;
    }

    function addPostAjax($data){
        $result = $this->ClientController->addNewData($data);
        return $result;
    }

    function searchAjax($data){
        $searchData = $this->ClientController->searchData($data);
        $innerPage = $this->createTablePage($searchData);
        return $innerPage;
    }   
    
    function createTablePage($data){
        $innerPage = '';
        foreach ($data as $singleData) {
            $body = strlen($singleData['body']) > 100 ? substr($singleData['body'],0,100)."..." : $singleData['body'];
            $innerPage .= $this->createTemplate(
                './template/posts/innerPage.html',
                [
                    'id' => $singleData['id'],
                    'user' => $singleData['name'],
                    'userId' => $singleData['user_id'],
                    'title' => $singleData['title'],
                    'body' => $body,
                    'updated_at' => $singleData['updated_at'],
                    'created_at' => $singleData['created_at'] ?? '',
                ]
            );
        }
        return $innerPage;
    }

    function createPostsSum($postsSum){
        $innerPage = '';
        foreach($postsSum as $singlePostSum){
            $innerPage .= $this->createTemplate(
                './template/avg/innerPage.html',
                [
                    'user_id' => $singlePostSum['userId'],
                    'monthAvg' => $singlePostSum['monthAvg'],
                    'weekAvg' => $singlePostSum['weeksAvg'],
                ]
            );
        }
        $result = $this->createTemplate(
            './template/avg/index.html',
            [
                'innerPage' => $innerPage,
            ]
        );
        return $result;
    }
}
