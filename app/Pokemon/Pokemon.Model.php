<?php 

class pokemonModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=tpe-web2-hiese-peralta;charset=utf8', 'root', '');
    }
    
    public function getPokemons(){
        $query = $this->db->prepare('SELECT * FROM pokemon');
        $query->execute();   
    
        $pokemons = $query->fetchAll(PDO::FETCH_OBJ);
        return $pokemons;
    }

    public function getPokemon($id){
        $query = $this->db->prepare('SELECT * FROM pokemon WHERE nro_pokedex=?');
        $query->execute([$id]);

        $pokemon = $query->fetch(PDO::FETCH_OBJ);
        return $pokemon;
    }

}