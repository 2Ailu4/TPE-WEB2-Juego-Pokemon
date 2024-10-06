<?php

class pokemonView{
    
    public function homeView(){
        ?> <a href="listPokemons">Ver todos los Pokemons</a> <?php
    }

    public function return($destination){
        ?> <a href="<?=BASE_URL.$destination?>">Volver</a> <?php
    }

    public function listPokemons($pokemons){ ?> 
        <h1>Los pokemons que se puede encontrar son:</h1>
        <ol class="list-group list-group-numbered"> <?php
            foreach($pokemons as $pokemon){ ?>
                <li class="list-group-item"> <?=$pokemon->nombre?>
                    <a href="pokemonDetail/<?=$pokemon->nro_pokedex?>">Ver</a> 
                </li> <?php 
            } ?>
        </ol> <?php 
        $this->return("home");
    }

    public function showPokemon($pokemon){ 
        require_once './header.phtml';?>
        <div class="card">
        <div class="card-body">
            <h5 class="card-title"> <?=$pokemon->nombre?> </h5>
            <p class="card-text"> <?=$pokemon->tipo?> </p>
            <p class="card-text"> <?=$pokemon->fecha_captura?> </p>
            <p class="card-text"> <?=$pokemon->peso?> </p>
            <!-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> -->
        </div>
        <img src="https://github.com/2Ailu4/TPE-WEB2-Juego-Pokemon/blob/main/images/<?=$pokemon->nombre?>.jpg?raw=true" class="card-img-bottom" alt="...">
        </div> <?php

        $this->return("listPokemons");
    }

}