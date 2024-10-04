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

}