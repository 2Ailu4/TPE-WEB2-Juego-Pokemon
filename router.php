<?php
require_once 'app/Controller.php';

define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

if(!empty($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = "home";
}

$params = explode('/', $action);

switch($params[0]){
    case "home":

        break;

    default:
        echo "404 Page Not Found";
        break;

}


