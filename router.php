<?php
require_once 'app/Entrenador/Entrenador.Controller.php';
require_once 'app/Pokemon/Pokemon.Controller.php';

define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

if(!empty($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = "home";
}


$params = explode('/', $action);

switch($params[0]){
    case "home":
        $pokemonController = new pokemonController;
        $pokemonController->homePokemon();
        break;
    case "listPokemons":
        $pokemonController = new pokemonController;
        $pokemonController->listPokemons();
        break;
    case "pokemonDetail":
        $pokemonController = new pokemonController;
        $pokemonController->pokemonDetail($params[1]);
        break;
    case "add-pokemon":
        $pokemonController = new pokemonController;
        $pokemonController->addPokemon();
        break;
    case "insert-pokemon":
        $pokemonController = new pokemonController;
        $pokemonController->insertPokemon();
        break;
    case "delete-pokemon":  
        $pokemonController = new pokemonController;
        $pokemonController->releasePokemon($params[1]);
        break;
    // case "update-pokemon":
    //     $pokemonController = new pokemonController;
    //     $pokemonController->updatePokemon();
    //     break;
    default:
        echo "404 Page Not Found";
        break;

}


