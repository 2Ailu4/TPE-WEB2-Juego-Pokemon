<?php
require_once 'app/Pokemon/Pokemon.Controller.php';
require_once 'app/Trainer/Trainer.Controller.php';

define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

if(!empty($_GET['action'])){
    $action = $_GET['action'];
}else{
    // $action = "home";
    $action = "trainer-list";
    
}

$params = explode('/', $action);

switch($params[0]){
    case "home":
        $trainerController = new trainerController();
        $trainerController->listTrainers();
        $pokemonController = new pokemonController();
        // $pokemonController->listPokemons();
        break;

    case "trainer-list":
        $trainerController = new trainerController();
        $trainerController->listTrainers();
        break;
    case "trainer-information":
        $trainerController = new trainerController();
        //$pokemonController = new pokemonController();        

        //$trainerPokemons = $pokemonController->trainerPokemons($params[1]); 
        $trainerController->trainerInformation($params[1]);
        break;
    case "trainer-pokemons":
        $trainerController = new trainerController();
        $trainerController->trainerPokemons($params[1]);
        break;
    default:
        echo "404 Page Not Found";
        break;

}


