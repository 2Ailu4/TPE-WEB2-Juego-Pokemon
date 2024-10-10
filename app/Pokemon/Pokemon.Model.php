<?php 

class pokemonModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=tpe-web2-hiese-peralta;charset=utf8', 'root', '');
    }
    
    public function getPokemons(){
            $query = $this->db->prepare('SELECT nro_pokedex, nombre, tipo, 
                                                MAX(fecha_captura) AS fecha_captura,
                                                MAX(peso) AS peso
                                        FROM pokemon 
                                        GROUP BY nro_pokedex');
            $query->execute();   
        
            $pokemons = $query->fetchAll(PDO::FETCH_OBJ);
            return $pokemons;
        }

    // public function getPokemons(){
    //     $query = $this->db->prepare('SELECT DISTINCT (nro_pokedex), nombre  FROM pokemon');
    //     $query->execute();   
    
    //     $pokemons = $query->fetchAll(PDO::FETCH_OBJ);
    //     return $pokemons;
    // }
    
    public function getPokemon($id){
        $query = $this->db->prepare('SELECT * FROM pokemon WHERE nro_pokedex=?');
        $query->execute([$id]);

        $pokemon = $query->fetch(PDO::FETCH_OBJ);
        return $pokemon;
    }

    public function getNroPokedexTypeByName($namePokemon){
        $query = $this->db->prepare('SELECT nro_pokedex,tipo FROM pokemon WHERE nombre=?');
        $query->execute([$namePokemon]);

        $pokemon = $query->fetch(PDO::FETCH_OBJ);
        return $pokemon;
    }

    // CONSULTAR SI LO DEBE TENER MARIAN ------------------------------------------------------------
    public function getTrainers_ID_name(){
        $query = $this->db->prepare('SELECT id_entrenador,nombre_entrenador FROM entrenadorpokemon');
        $query->execute();

        $trainers = $query->fetchAll(PDO::FETCH_OBJ);
        return $trainers;
    }
    // ---------------------------------------------------------------------------------------------

    public function insertPokemon($nro_pokedex, $nombre, $tipo, $peso, $entrenador){
        $query = $this->db->prepare('INSERT INTO pokemon(nro_pokedex, nombre, tipo, peso, FK_id_entrenador) 
                                            VALUES (?, ?, ?, ?, ?)');
        $query->execute([$nro_pokedex, $nombre, $tipo, $peso, $entrenador]);

        $id = $this->db->lastInsertId();
        return $id;
    }

    public function releasePokemon($id_pokemon){
        $query = $this->db->prepare('SELECT FK_id_entrenador FROM pokemon WHERE id=?');
        $query->execute([$id_pokemon]);
        $trainer = $query->fetch(PDO::FETCH_OBJ);

        $query = $this->db->prepare('DELETE FROM pokemon WHERE id=?');
        $query->execute([$id_pokemon]);

        return $trainer;
    }

}