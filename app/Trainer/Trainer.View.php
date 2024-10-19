<?php
/*
    1) Se definen en el constructor, todas las rutas que utiliza el controlador para que sea mas facil rastrear y modificar las
       urls ante uno o mas cambios en el ruter, proporcionando asi mayor flexibilidad.

    2) La razon por la cual algunas de las funciones no tienen footer, es para que puedan ser reutilizadas como componentes para un layout en especifico 
*/

require_once './templates/layout/header.phtml';
// require_once './TPE-WEB2-Juego-Pokemon/templates/layout/header.phtml';
class trainerView{
    private $user;

    private $URL_home;

    private $URL_trainers;
    private $PARTIAL_URL_trainer;
    private $PARTIAL_URL_DELETE_trainer;
    private $PARTIAL_URL_UPDATE_trainer;
       
    private $PARTIAL_URL_trainer_pokemons;
    private $PARTIAL_URL_DELETE_pokemon_trainer;
    private $PARTIAL_URL_UPDATE_pokemon_trainer;

    //public function __construct($user = null){
    public function __construct($user = null){
        $this->user = $user;

        $this->URL_home = BASE_URL."home";
        //TRAINERS
        $this->URL_trainers = BASE_URL."trainer-list";
        $this->PARTIAL_URL_trainer =BASE_URL."trainer-information";
        $this->PARTIAL_URL_DELETE_trainer = BASE_URL."delete-trainer";
        $this->PARTIAL_URL_UPDATE_trainer = BASE_URL."modify-trainer";
        //POKEMONS DEL ENTRENADOR
        $this->PARTIAL_URL_trainer_pokemons =BASE_URL."trainer-pokemons";
        $this->PARTIAL_URL_DELETE_pokemon_trainer = BASE_URL."delete-pokemon";
        $this->PARTIAL_URL_UPDATE_pokemon_trainer = BASE_URL."update-pokemon";
    }
    public function return($destination, $message){
        ?> <a href="<?=BASE_URL.$destination?>"> <?=$message?> </a> <?php
    }
    public function showMessage($message){ echo($message);}

//:::::::::::::::::::::::::::::::::::::: [ COMPONENTS ] ::::::::::::::::::::::::::::::::::::::::::::::::::::::

    public function showTrainers($trainers){
        $action = $this->PARTIAL_URL_trainer;   
        require './templates/trainer/trainer-list.phtml';
    }
 
    public function showTrainerPokemon_Card($pokemon, $trainer){ 
        $url = $this->PARTIAL_URL_trainer."/".$trainer['id_entrenador'];
        require './templates/pokemon/card/pokemon-stats.phtml'; 
    }

    public function showForm_INSERT(){
        $action  = ['action_form'=>'insert-trainer'];
        $label   = $this->__get_form_labels();
        $trainer = $this->__get_trainer_placeholders();
        //$label= [];
        //$trainer=['nombre_entrenador'=>'nombre' , 'ciudad_origen'=>'ciudad' , 'nivel_entrenador'=>'nivel','cant_medallas'=>'cantidad'];
        require './templates/trainer/form/insert-update.phtml'; 
        require_once './templates/layout/footer.phtml';
    }

    public function showForm_UPDATE($trainer){
        //var_dump($action);
        //$label=[];
        // if(!$admin){
        //     $label=['id_entrenador'=>'hide', 'nivel_entrenador'=>'hide', 'cant_medallas'=>'hide'];
        // }
        $action=['action_form'=>'update-trainer/'.$trainer['id_entrenador']];
        $label = $this->__get_form_labels();
        require './templates/trainer/form/insert-update.phtml'; 
        require_once './templates/layout/footer.phtml';
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

    public function showTrainer_UPDATE($trainer){ 

        $action=['action_form'=>'update-trainer/'.$trainer['id_entrenador']];
        $label = $this->__get_form_labels();
        $template = './templates/trainer/form/insert-update.phtml'; //FORM
       
        $url = $this->__get_Profile_Links($trainer);
        $trainer_ID = "/".$trainer['id_entrenador'];
           
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
            'trainers'        => $this->URL_trainers
        ];
        if($this->user){
            $links['trainer-update']  = $this->PARTIAL_URL_UPDATE_trainer . $trainer_ID;
            $links['trainer-delete']  = $this->PARTIAL_URL_DELETE_trainer . $trainer_ID;   
        }
        
        
        return $links;
    }
    private function __get_form_labels(){
        $label = [];
        if( !($this->user) ){
            $label=['id_entrenador'=>'hide', 'nivel_entrenador'=>'hide', 'cant_medallas'=>'hide'];
        }
        return $label;
    }
    private function __get_trainer_placeholders(){
        $trainer=['nombre_entrenador'=>'nombre' , 'ciudad_origen'=>'ciudad' , 'nivel_entrenador'=>'nivel','cant_medallas'=>'cantidad'];
        // $label = [];
        // if( !($this->user) ){
        //     $label=['id_entrenador'=>'hide', 'nivel_entrenador'=>'hide', 'cant_medallas'=>'hide'];
        // }
        return $trainer;
    }
   
}