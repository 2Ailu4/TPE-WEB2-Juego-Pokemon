<?php
require_once 'Pokemon.Model.php';
require_once 'Pokemon.View.php';

class pokemonController{
    private $pokemon_model;
    private $pokemon_view;

    public function __construct($res=null){
        $this->pokemon_model = new pokemonModel;
         if(!isset($res)){$this->pokemon_view = new pokemonView();}
         else{$this->pokemon_view = new pokemonView($res->user);}
    }


    // .....Listado......
    public function listPokemons(){
        $pokemons = $this->pokemon_model->getPokemons();
        $pokemons_withNameTrainer = [];
        foreach($pokemons as $pokemon){ 
            $Trainer = $this->pokemon_model->getFkTrainerByPokemon($pokemon->id);
            $name_Trainer = $this->pokemon_model->getNameTrainerByFk($Trainer->FK_id_entrenador);
            
            if(!$name_Trainer){
                $pokemon->nombre_entrenador = "Es Salvaje";
            }else{
                $pokemon->nombre_entrenador = $name_Trainer->nombre_entrenador;
            }
            array_push($pokemons_withNameTrainer, $pokemon);
        }
        $this->pokemon_view->listPokemons($pokemons_withNameTrainer);
        $this->pokemon_view->return('home', "Volver");
    }


    // .....Insercion......
    public function addPokemon(){
        $trainers = $this->pokemon_model->getTrainersIdName(); 
        $this->pokemon_view->showFormInsertPokemon($trainers);
    }

    public function insertPokemon(){
        $idTrainer = $_POST['FK_id_entrenador'];
        $nroPokedex = $_POST['nro_pokedex'];
        $namePokemon = $_POST['nombre'];
        $typePokemon = $_POST['tipo'];
        $weight = $_POST['peso'];

        $nroPokedexExists = $this->pokemon_model->countNroPokedex($nroPokedex);
        if($nroPokedexExists > 0){  //si ya existe el nro_pokedex
            $pokemonInDB = $this->pokemon_model->getPokemonByNroPokedex($nroPokedex);
            if(($pokemonInDB->nombre === $namePokemon) && ($pokemonInDB->tipo === $typePokemon)){ 
                if($idTrainer === "NULL"){
                    $id_New_Pokemon = $this->pokemon_model->insertPokemon($nroPokedex, $namePokemon, $typePokemon, $weight);
                }else{
                    $id_New_Pokemon = $this->pokemon_model->insertPokemon($nroPokedex, $namePokemon, $typePokemon, $weight, $idTrainer);
                }
            }else{  //si el nombre o el tipo NO coinciden
                $this->pokemon_view->showAlert("Lo sentimos el pokemon con Numero de Pokedex: ".$nroPokedex." ya existe, pero el nombre y el tipo ingresados no coinciden con el cargado en la base de datos");
                $this->pokemon_view->showAlert("Por favor pruebe de nuevo!!");
                $this->pokemon_view->return("add-pokemon", "Volver a intentar");
            }
        }else{  //si el nro_pokedex no existe en la DB
            if($idTrainer === "NULL"){
                $id_New_Pokemon = $this->pokemon_model->insertPokemon($nroPokedex, $namePokemon, $typePokemon, $weight);
            }else{
                $id_New_Pokemon = $this->pokemon_model->insertPokemon($nroPokedex, $namePokemon, $typePokemon, $weight, $idTrainer);
            }
        }
        header('Location: ' . BASE_URL . "listPokemons");
    }


    // .....Eliminacion......
    public function releasePokemon($id_Pokemon){
        $id_Trainer = $this->pokemon_model->releasePokemon($id_Pokemon);
        $this->pokemon_view->showAlert("El Pokemon se elimino con exito");
        header('Location: ' . BASE_URL . "trainer-pokemons/" . $id_Trainer->FK_id_entrenador);
    }


    // .....Actualizacion......
    public function modifyPokemon($id_Pokemon){
        $pokemon = $this->pokemon_model->getPokemonByID($id_Pokemon);
        $name_trainer="NULL";
        if($pokemon->FK_id_entrenador !== NULL){
            $Trainer=$this->pokemon_model->getNameTrainerByFk($pokemon->FK_id_entrenador);
            $name_trainer = $Trainer->nombre_entrenador;
        }
        $datetime = date("Y-m-d H:i:s", strtotime($pokemon->fecha_captura));
        $trainers = $this->pokemon_model->getTrainersIdName();

        $this->pokemon_view->showFormUpdatePokemon($trainers, $id_Pokemon, $pokemon->nro_pokedex, $pokemon->nombre, $pokemon->tipo, $datetime, $pokemon->peso, $name_trainer);
    }

    public function updatePokemon($id_Pokemon){
        if(isset($_POST)){  
            $updateFields = $this->getUpdateFields();//Agarro todos los campos que fueron modificados, ej: ['nro_pokedex'=>'32','tipo'=>'plantita']
            $this->pokemon_model->update_Pokemon($id_Pokemon, $updateFields);
        }
        $id_Trainer = $this->pokemon_model->getFkTrainerByPokemon($id_Pokemon);
        header('Location: ' . BASE_URL . "trainer-pokemons/" . $id_Trainer->FK_id_entrenador);
    }


    private function isSet($var){
        $setted = false;
        if(isset($var) && !empty($var)) {return $setted =true;}
        return $setted;
    }
    
    private function getUpdateFields(){
        $fields=[];
        foreach ($_POST as $key => $value) {
            if ($this->isSet($value)){
                $fields[$key]=$value;   
            }
        }
        return $fields;
    }
 

}