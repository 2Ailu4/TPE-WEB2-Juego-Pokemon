<?php
require_once './templates/layout/header.phtml';
class pokemonView{
    
    public function homeView(){
        ?> <a href="listPokemons">Ver todos los Pokemons</a> <?php
    }

    public function return($destination){
        ?> <a href="<?=BASE_URL.$destination?>">Volver</a> <?php
    }

    public function listPokemons($pokemons){ ?>
        <h1>Los pokemons que se puede encontrar son:</h1><?php
        foreach($pokemons as $pokemon){ 
            require './templates/pokemon-list.phtml';
        }
        $this->return("home");
    }

    public function showPokemon($pokemon){ 
        require_once './templates/pokemon-detail.phtml';
        $this->return("listPokemons");
    }

    public function showFormInsertPokemon($pokemons, $trainers){
        require_once './templates/forms/insert-pokemon.phtml';
    }

}
//require_once './templates/layout/footer.phtml';