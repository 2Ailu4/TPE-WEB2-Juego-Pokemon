<?php
require_once 'Pokemon.Model.php';
require_once 'Pokemon.View.php';

class pokemonController{
    private $pokemon_model;
    private $pokemon_view;

    public function __construct(){
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

    public function pokemonDetail($idPokemon){
        $pokemon = $this->pokemon_model->getPokemon($idPokemon);
        $this->pokemon_view->showPokemon($pokemon);
    }
}