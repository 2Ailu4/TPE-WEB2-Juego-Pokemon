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

    public function insertPokemon($nro_pokedex, $nombre, $tipo, $peso, $entrenador,$imgTemp){
        
        $imgPath = NULL; 
        // carga de imagen
        if ($imgTemp){
            $pathImg = $this->uploadImage($imgTemp,$nombre);
            $updateFields['imagen']=$imgPath;
        } 

                        ?><br><?php
                        var_dump("CHECK params : ",$updateFields);

        $query = $this->db->prepare('INSERT INTO pokemon(nro_pokedex, nombre, tipo, peso, FK_id_entrenador, imagen) 
                                            VALUES (?, ?, ?, ?, ?)');
        $query->execute([$nro_pokedex, $nombre, $tipo, $peso, $entrenador,$imgPath]);

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
//:::::::::::::::::::::::::::::::::::::: [ PRIVADAS ] :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    
    private function uploadImage($imgTemp, $nombre_pokemon, $update = false, $relativePath = "images/pokemons/"   ) {
 
        $extension = strtolower(pathinfo($nombre_pokemon, PATHINFO_EXTENSION));
        $filePath = $relativePath . $nombre_pokemon . "." . $extension;
        
        if($update){
            // elimina la imagen de pokemon 
            $this->deleteImage($filePath); 
            // setea la nueva imagen para dicho pokemon
            if (!move_uploaded_file($imgTemp, $filePath)) {
                throw new Exception("Error al mover el archivo subido.");
            }
        }

        // Para depurar
        ?><br><?php
        var_dump('filepath:  ', $filePath);
        ?><br><?php
        var_dump('temporal:  ', $nombre_pokemon);
        ?><br><?php
        var_dump('pathinfo:  ', pathinfo($nombre_pokemon, PATHINFO_EXTENSION));
    
        return $filePath;
    }
 
    private function deleteImage($filePath) {
        if (file_exists($filePath)) {
            // Intenta eliminar el archivo
            if (unlink($filePath)) {
                return "Imagen eliminada con éxito.";
            } else {
                throw new Exception("Error al eliminar la imagen.");
            }
        } else {
            throw new Exception("El archivo no existe: " . $filePath);
        }
    }

}