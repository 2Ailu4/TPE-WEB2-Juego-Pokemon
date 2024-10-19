<?php
require_once 'Pokemon.Model.php';
require_once 'Pokemon.View.php';

class pokemonController{
    private $pokemon_model;
    private $pokemon_view;

    public function __construct($res){
        if(!isset($res)){$this->pokemon_view = new pokemonView();}
         else{$this->pokemon_view = new pokemonView($res->user);}
        $this->pokemon_model = new pokemonModel;
        $this->pokemon_view = new pokemonView;
    }

    public function homePokemon(){  //solo para probar las funcionalidades
        $this->pokemon_view->homeView();
    }


    public function listPokemons(){
        $pokemons = $this->pokemon_model->getPokemons();
        $this->pokemon_view->listPokemons($pokemons);
    }

    public function pokemonDetail($nro_Pokedex_Pokemon){
        $pokemon = $this->pokemon_model->getPokemon($nro_Pokedex_Pokemon);
        $this->pokemon_view->showPokemon($pokemon);
    }

    public function addPokemon(){
        $trainers = $this->pokemon_model->getTrainers_ID_name(); //return id_entrenador y nombre_entrenador //?????????????????
        $pokemons = $this->pokemon_model->getPokemons(); //return nro_pokedex y nombre

        $this->pokemon_view->showFormInsertPokemon($pokemons, $trainers);
    }

    public function insertPokemon(){
        $idTrainer = $_POST['trainer'];
        $namePokemon = $_POST['pokemon'];
        $pokemon = $this->pokemon_model->getNroPokedexTypeByName($namePokemon);
        $weight = $_POST['weightPokemon'];

        $pokemon_existent =  'verificar en db si existe ';
        if (!$pokemon_existent ){
            if ($this->imageUploaded()) {
                $imgTemp = $_FILES['input_name']['tmp_name']; 
            }else { 
                $this->pokemon_view->showMessage('Error: Es necesario ingresar una imagen para agregar nuevas especies de Pokemons');
                die(); 
            } 
        }else{$imgTemp = $pokemon_existent -> imagen; }// agarro la imagen de el pokemon}
        
        // toda la logica ACTUALIZADA de insertar  
        
        $id_New_Pokemon = $this->pokemon_model->insertPokemon($pokemon->nro_pokedex, $namePokemon, $pokemon->tipo, $weight, $idTrainer, $imgTemp);
        return $id_New_Pokemon;
    }

    public function releasePokemon($id_Pokemon){
        $id_Trainer = $this->pokemon_model->releasePokemon($id_Pokemon);
        header('Location: ' . BASE_URL . "trainer-pokemons/" . $id_Trainer->FK_id_entrenador);
    }

    private function imageUploaded(){
        return $_FILES['input_name']['type'] == "image/jpg"
            || $_FILES['input_name']['type'] == "image/jpeg" 
            || $_FILES['input_name']['type'] == "image/png";      
    }
        
}