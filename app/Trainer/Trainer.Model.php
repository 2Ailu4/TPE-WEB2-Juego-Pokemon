<?php 

class trainerModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=tpe-web2-hiese-peralta;charset=utf8', 'root', '');
    }

    // getTrainers($id_trainer=null, $name_trainer=null);
    public function getTrainers_ID_name(){
        $query = $this->db->prepare('SELECT id_entrenador,nombre_entrenador FROM entrenadorpokemon');
        $query->execute();

        $trainers = $query->fetchAll(PDO::FETCH_OBJ);
        return $trainers;
    }
    public function getTrainers(){
        $query = $this->db->prepare('SELECT * FROM entrenadorpokemon');
        $query->execute();

        $trainers = $query->fetchAll(PDO::FETCH_ASSOC);
        return $trainers;
    }
    public function getTrainer($id_trainer){
        $query = $this->db->prepare('SELECT * FROM entrenadorpokemon WHERE id_entrenador = :id_entrenador');
        $query->execute([":id_entrenador"=>$id_trainer]);

        $trainer = $query->fetch(PDO::FETCH_ASSOC);

        return $trainer;
    }

    public function getTrainer_ID_name($id_trainer){
        $query = $this->db->prepare('SELECT id_entrenador as id ,nombre_entrenador as nombre FROM entrenadorpokemon WHERE id_entrenador = :id_entrenador');
        $query->execute([":id_entrenador"=>$id_trainer]);

        $trainer = $query->fetch(PDO::FETCH_ASSOC);
         
        return $trainer;
    }
    public function getTrainer_Name($id_trainer){
        $query = $this->db->prepare('SELECT nombre_entrenador FROM entrenadorpokemon WHERE id_entrenador = :id_entrenador');
        $query->execute([":id_entrenador"=>$id_trainer]);

        $trainer = $query->fetch(PDO::FETCH_ASSOC);
        return $trainer;
    }

    // public function getTrainerPokemons($id_trainer){
    //     $query = $this->db->prepare('SELECT nro_pokedex, nombre, tipo, fecha_captura, peso FROM pokemon WHERE FK_id_entrenador = :FK_id_entrenador');
    //     $query->execute([':FK_id_entrenador'=>$id_trainer]);

    //     $trainer = $query->fetchAll(PDO::FETCH_ASSOC);
    //     return $trainer;
    // }
    public function getTrainerPokemons($id_trainer){
        $query = $this->db->prepare('SELECT id, nro_pokedex, nombre, tipo, fecha_captura, peso FROM pokemon JOIN entrenadorpokemon on (FK_id_entrenador =id_entrenador) WHERE id_entrenador = :id_entrenador');
        $query->execute([':id_entrenador'=>$id_trainer]);

        $trainer = $query->fetchAll(PDO::FETCH_ASSOC);
        return $trainer;
    }
    
    
    public function insertTrainer($trainer = []){
        var_dump($trainer);
        $query = $this->db->prepare('INSERT INTO entrenadorpokemon(nombre_entrenador, ciudad_origen, nivel_entrenador, cant_medallas) 
                                            VALUES (?, ?, ?, ?)');
        $query -> execute([$trainer['nombre_entrenador'], $trainer['ciudad_origen'], $trainer['nivel_entrenador'], $trainer['cant_medallas']]);
        $trainerID = $this->db->lastInsertId();
        return $trainerID;
    }

    public function deleteTrainer($trainerID){
        $hasPokemons = $this->getTrainerPokemons($trainerID);
        var_dump($hasPokemons);
        //if (isset($hasPokemons)) {$this->releasePokemons($trainerID);}
        if ($hasPokemons) {$this->releasePokemons($trainerID);} // creo q va este 
        
        $query = $this->db->prepare('DELETE FROM entrenadorpokemon WHERE id_entrenador=?');
        $query->execute([$trainerID]);
    }

    public function updateTrainer($updateFields, $imagen = NULL){
        $pathImg=null;
         
        if ($imagen){
            $pathImg = $this->uploadImage($imagen);
            //$pathImg = $this->uploadImage($imagen); // creo la ruta a la imagen y guardo la imagen con el nombre de la misma y un id 
            $updateFields['imagen']=$pathImg;
            ?><br><?php
            var_dump("esta bien params ? ",$updateFields);
        }   
        $update='';
        $params=[];
        $num_of_fields = count($updateFields);
        $i=0;
        foreach ($updateFields as $key => $value) {
                $associate=':'.$key;
                $update .= $key . ' = '.$associate;
                $params[$associate] = $value;
                $i++;
                if($i< $num_of_fields)
                    $update.=', ';
        }
        $query = $this->db->prepare("UPDATE entrenadorpokemon
                                        SET  $update 
                                      WHERE id_entrenador = :id_entrenador");
        $query->execute($params); 
    }

    public function releasePokemon($trainerID , $pokemonID ){
            // Libera un Pokemon en especifico
        $query = $this->db->prepare('UPDATE pokemon
                                        SET FK_id_entrenador = ?
                                        WHERE id = ? AND id_entrenador = ?');
        $query->execute([$pokemonID, $trainerID, NULL]);
    }

//:::::::::::::::::::::::::::::::::::::: [ PRIVADAS ] :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    
    private function releasePokemons($trainerID){
        // busco todos los pokemons que tienen por FK a ese entrenador
        // funciona como un : for(pokemon.FK_pokemon == id_entrenador("1")) -->  releasePokemon (FK_id_entrenador = NULL )
        $query = $this->db->prepare('UPDATE pokemon
                                        SET FK_id_entrenador = ?
                                        WHERE FK_id_entrenador = ?');
        $query->execute([$trainerID, NULL]);
    }

    private function add_existent_keys($keys_arr, $arr){
        $arr_to_insert = [];
        foreach($keys_arr as $key_arr){
            if(array_key_exists($key_arr, $arr)){
                $arr_to_insert[$key_arr] = $arr[$key_arr];
            }
        }
        return $arr_to_insert;
    }

    private function uploadImage($imgTemp, $relativePath = "images/trainers/", $nombre_entrenador = null) {
        // carga la imagen a la ruta 
        if (!isset($nombre_entrenador)) {
            $nombre_entrenador = pathinfo($_FILES['input_name']['name'], PATHINFO_FILENAME);
        }  
        // Genera un ID: (para q no se repitan los nombres, en caso de que dos usuarios carguen una img con mismo nombre)
        $uniqueId = uniqid();
        
        // Get extensión del archivo
        $extension = strtolower(pathinfo($_FILES['input_name']['name'], PATHINFO_EXTENSION));
        
        // Ruta + nombre del archivo 
        $filePath = $relativePath . $nombre_entrenador . "_" . $uniqueId . "." . $extension;

        // Intenta mover el archivo a la ubicación deseada
        if (!move_uploaded_file($imgTemp, $filePath)) {
            throw new Exception("Error al mover el archivo subido.");
        }
        
        // Para depuración
        ?><br><?php
        var_dump('filepath:  ', $filePath);
        ?><br><?php
        var_dump('temporal:  ', $nombre_entrenador);
        ?><br><?php
        var_dump('pathinfo:  ', pathinfo($nombre_entrenador, PATHINFO_EXTENSION));
    
        return $filePath;
    } 

    private function uploadImagePokemon($imgTemp, $nombre_pokemon, $update = false) {
 
        $extension = strtolower(pathinfo($nombre_pokemon, PATHINFO_EXTENSION));
        $filePath = "images/pokemons/" . $nombre_pokemon . "." . $extension;
        
        if($update){
            // elimina la imagen de pokemon 
            $this->deleteImage($filePath); 
            // setea la nueva imagen para dicho pokemon
            if (!move_uploaded_file($imgTemp, $filePath)) {
                throw new Exception("Error al mover el archivo subido.");
            }
        }

        // Para depuración
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