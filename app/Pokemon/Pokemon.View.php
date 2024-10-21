<?php
class pokemonView{
    private $user = NULL; 

    public function __construct($userr = NULL){
        $this->user = $userr;
    }


    public function showAlert($alert) {
        echo "<h1>$alert</h1>";
    }

    public function return($destination, $message){
        ?> <a href="<?=BASE_URL.$destination?>"> <?=$message?> </a> <?php
    }

    public function listPokemons($pokemons){ 
        $imgPokeball = "./images/pokeball.png";
        $template='./templates/pokemon/pokemon-list.phtml';
        require 'templates/layout/index.phtml';
        
    }

    private function hideFormLabels(){
        $labels= ['fecha_captura'=> 'No mostrar fecha de captura'];
        return $labels;
    }

    public function showFormInsertPokemon($trainers){
        $label = $this->hideFormLabels();   //seteo la fecha para que no se imprima este campo del form
        $options = ['action_form'=>'insert-pokemon', 
                    'select-id'=>'trainer_name', 
                    'label-data'=>'Nombre del Entenador', 
                    'select-name'=>'trainer',
                    'message'=>'¿¿Esta seguro que quiere insertar el nuevo Pokemon??'];
                    
        $name_trainer =null;
        $field = [  'number-pokedex-pokemon'=>'pokedex', 
                    'name-pokemon'=>'Nombre', 
                    'type-pokemon'=>'Tipo/s', 
                    'weight-pokemon'=>'Peso en kg'];
        $template= './templates/pokemon/forms/info-pokemon.phtml';
        $imgPokeball = "./images/pokeball.png";
        $imgHeaderLayoutForm = "./images/header-layout.png";
        $imgFooterLayoutForm = "./images/footer-layout.png";
        require_once 'templates/layout/index.phtml';
    }
    
     
    public function showFormUpdatePokemon($trainers, $id_Pokemon, $nro_pokedex, $name_pokemon, $type_pokemon, $capture_date_pokemon, $weight_pokemon, $name_trainer){
        $label =[];
        $options = ['action_form'=>'update-pokemon/'.$id_Pokemon, 
                    'select-id'=>'trainer_name', 
                    'label-data' =>'Nombre del Entenador', 
                    'select-name'=> 'trainerUpdate',
                    'message'=>'Si acepta se modificara el pokemon: '.$name_pokemon];

        $field = [  'number-pokedex-pokemon'=>$nro_pokedex, 
                    'name-pokemon'=>$name_pokemon, 
                    'type-pokemon'=>$type_pokemon, 
                    'capture-date-pokemon'=>$capture_date_pokemon, 
                    'weight-pokemon'=>$weight_pokemon];
        $template = './templates/pokemon/forms/info-pokemon.phtml';
        $imgPokeball = "../images/pokeball.png";
        $imgHeaderLayoutForm = "../images/header-layout.png";
        $imgFooterLayoutForm = "../images/footer-layout.png";
        require_once 'templates/layout/index.phtml';
    }

}
