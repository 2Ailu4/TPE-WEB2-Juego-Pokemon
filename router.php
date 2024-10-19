<?php
require_once 'libs/response.php';
require_once 'app/middleware/session-auth-middleware.php';
require_once 'app/user/User.Controller.php';
require_once 'app/Pokemon/Pokemon.Controller.php';
require_once 'app/Trainer/Trainer.Controller.php';

define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

if(!empty($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = "home";
}

$res = new Response;
$params = explode('/', $action);

switch($params[0]){
    case "home":    
        $controller = new userController();
        $controller->showHome();
        break;
    case "listPokemons":
        $pokemonController = new pokemonController();
        $pokemonController->listPokemons();
        break;
    case "add-pokemon":
        sessionAuthMiddleware($res);
        $pokemonController = new pokemonController($res);
        $pokemonController->addPokemon();
        break;
    case "insert-pokemon":
        sessionAuthMiddleware($res);
        $pokemonController = new pokemonController($res);
        $pokemonController->insertPokemon();
        break;
    case "delete-pokemon":  
        sessionAuthMiddleware($res);
        $pokemonController = new pokemonController($res);
        $pokemonController->releasePokemon($params[1]);
        break;
    case "modify-pokemon":
        sessionAuthMiddleware($res);
        $pokemonController = new pokemonController($res);
        $pokemonController->modifyPokemon($params[1]);
        break;
    case "update-pokemon":
        sessionAuthMiddleware($res);
        $pokemonController = new pokemonController($res);
        $pokemonController->updatePokemon($params[1]);
        break;
    case "login":
        $controller = new userController();
        $controller->login();
        break;
    case "showUpdateItemsCategories":
        $controller = new userController();
        $controller->showUpdateItemsCategories();
        break;

// ------------------------------------------
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


