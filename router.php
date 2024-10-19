<?php
require_once 'app/Pokemon/Pokemon.Controller.php';
require_once 'app/Trainer/Trainer.Controller.php';

define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

if(!empty($_GET['action'])){
    $action = $_GET['action'];
}else{
    // $action = "home";
    $action = "trainer-list";
    // $action = "register-trainer";
    
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

    case "trainer-home":
        $trainerController = new trainerController();
        //$trainerController->trainersHome();
        break;

    case "trainer-list":
        $trainerController = new trainerController();
        $trainerController->listTrainers();
        break;

    case "trainer-information":
        $trainerController = new trainerController();
        $trainerController->trainerInformation($params[1]);
        break;

    case "trainer-pokemons":
        $trainerController = new trainerController();
        $trainerController->trainerPokemons($params[1]);
        break;

    case "register-trainer":
        sessionAuthMiddleware($res);
        $trainerController = new trainerController($res);
        $trainerController->showForm_INSERT();
        break;
    case "insert-trainer":
        sessionAuthMiddleware($res);
        $trainerController = new trainerController($res);
        $trainerController->insertTrainer();
        break;

    case "modify-trainer":
        sessionAuthMiddleware($res);
        $trainerController = new trainerController($res);
        $trainerController->showForm_UPDATE($params[1]);
        break;
        
    case "update-trainer":
        sessionAuthMiddleware($res);
        $trainerController = new trainerController();
        //var_dump($params);
        $trainerController->updateTrainer($params[1]);
        break;

    case "delete-trainer":
        sessionAuthMiddleware($res);
        $trainerController = new trainerController();
        $trainerController->deleteTrainer($params[1]);
        break;

    case "close-session":
        sessionAuthMiddleware($res);
        $userController = new userController();
        $userController->closeSession();
        break;
    default:
        echo "404 Page Not Found";
        break;

}


