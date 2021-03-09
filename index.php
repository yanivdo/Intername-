<?php
include './general/DbConnection.php';
include './general/Functions.php';

$functions = new Functions();

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $content = $functions->ajaxToFunction($_SERVER['REDIRECT_URL']);
} else {
    $content = $functions->getPage($_SERVER['REQUEST_URI']);
}

echo $content;
