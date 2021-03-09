<?php
include "./view/ClientView.php";
class Functions
{
    function getPage($location)
    {
        session_start();
        $page = new ClientView();
        switch ($location) {
            case "/":
                
                $pageData = $page->createView();
                break;
            case "/addData":
                $pageData = $page->createFormView();
                break;
        }
        return $pageData;
    }

    function ajaxToFunction($location)
    {
        $locationArray = explode('/', $location);
        $view = $locationArray[1] . 'View';
        $function = $locationArray[2] . 'Ajax';
        $ajaxCall = new $view();
        $result = $ajaxCall->$function($_POST);
        return $result;
    }
}
