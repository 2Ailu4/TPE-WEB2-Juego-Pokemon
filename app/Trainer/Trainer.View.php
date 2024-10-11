<?php
/*
    1) Se definen en el contructor, todas las rutas que utiliza el controlador para que sea mas facil rastrear y modificar las
       urls ante uno o mas cambios en el ruter, proporcionando asi mayor flexibilidad.

    2) La razon por la cual algunas de las funciones no tienen footer, es para que puedan ser reutilizadas como componentes para un layout en especifico 
*/

require_once './templates/layout/header.phtml';
class trainerView{
    private $URL_home;

    private $URL_trainers;
    private $PARTIAL_URL_trainer;
    private $PARTIAL_URL_DELETE_trainer;
    private $PARTIAL_URL_UPDATE_trainer;
       
    private $PARTIAL_URL_trainer_pokemons;
    private $PARTIAL_URL_DELETE_pokemon_trainer;
    private $PARTIAL_URL_UPDATE_pokemon_trainer;

    public function __construct(){
        $this->URL_home = BASE_URL."home";
        //TRAINERS
        $this->URL_trainers = BASE_URL."trainer-list";
        $this->PARTIAL_URL_trainer =BASE_URL."trainer-information";
        $this->PARTIAL_URL_DELETE_trainer = BASE_URL."delete-trainer";
        $this->PARTIAL_URL_UPDATE_trainer = BASE_URL."update-trainer";
        //POKEMONS DEL ENTRENADOR
        $this->PARTIAL_URL_trainer_pokemons =BASE_URL."trainer-pokemons";
        $this->PARTIAL_URL_DELETE_pokemon_trainer = BASE_URL."delete-pokemon";
        $this->PARTIAL_URL_UPDATE_pokemon_trainer = BASE_URL."update-pokemon";
    }

//:::::::::::::::::::::::::::::::::::::: [ COMPONENTS ] ::::::::::::::::::::::::::::::::::::::::::::::::::::::

    public function showTrainers($trainers){
        $action = $this->PARTIAL_URL_trainer;   
        require './templates/trainer/trainer-list.phtml';
    }
 
    public function showTrainerPokemon_Card($pokemon, $trainer){ 
        $url = $this->PARTIAL_URL_trainer."/".$trainer['id_entrenador'];
        require './templates/pokemon/card/pokemon-stats.phtml'; 
    }

//:::::::::::::::::::::::::::::::::::::: [ LAYOUTS ] ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: 

    public function showTrainerPokemons($pokemons, $trainer=[]){
    
        $set_free_pokemon = $this->PARTIAL_URL_DELETE_pokemon_trainer;
        $update_pokemon = $this->PARTIAL_URL_UPDATE_pokemon_trainer;
        $template = './templates/pokemon/gallery/pokemon-stats.phtml';
        $url = $this->__get_Profile_Links($trainer);
            require_once './templates/trainer/show-profile.phtml';  

        require_once './templates/layout/footer.phtml';
    }
   
    public function showTrainer($trainer){ 
        $url = $this->__get_Profile_Links($trainer);
        $trainer_ID = "/".$trainer['id_entrenador'];
        
        $template = './templates/trainer/card/profile.phtml';  //Tarjeta con informacion del entrenador
        require_once './templates/trainer/show-profile.phtml'; // Perfil del entrenador  

        require_once './templates/layout/footer.phtml';
    }

//:::::::::::::::::::::::::::::::::::::: [ PRIVADAS ] :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

    // genera todos los links necesarios para mostrar el perfil del entrenador
    private function __get_Profile_Links($trainer){
        $trainer_ID = "/".$trainer['id_entrenador'];
        $links= [
            'trainer-link'    => $this->PARTIAL_URL_trainer          . $trainer_ID,
            'trainer-pokemons'=> $this->PARTIAL_URL_trainer_pokemons . $trainer_ID,
            'trainer-update'  => $this->PARTIAL_URL_UPDATE_trainer   . $trainer_ID,
            'trainer-delete'  => $this->PARTIAL_URL_DELETE_trainer   . $trainer_ID,
            'trainers'        => $this->URL_trainers
        ];
        return $links;
    }
}