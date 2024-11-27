<?php 
require_once './config/config.php';
class trainerModel{
    protected $db;

    public function __construct(){
        
        $this->db = new PDO(
            "mysql:host=".MYSQL_HOST .";charset=utf8", MYSQL_USER, MYSQL_PASS);
        if ($this->db) {
            $this->createDatabase();
            $this->db->exec("USE " . MYSQL_DB);
            $this->_deploy();
        }
    }

    function createDatabase() {
            $query = "CREATE DATABASE IF NOT EXISTS " . MYSQL_DB . " DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
            $this->db->exec($query);
        }

    private function _deploy() {
        $query = $this->db->query('SHOW TABLES');
        $tables = $query->fetchAll();
        if (count($tables) == 0) { 
            $sqlFile = './tpe-web2-hiese-peralta.sql';
            $sql = file_get_contents($sqlFile);
            
            // Arreglo para separar en consultas
            $queries = explode(';', $sql);
            foreach ($queries as $query) {
                $query = trim($query); // quitamos espacios en blanco al inicio y fin             
                if (!empty($query)) {
                    $this->db->query($query);
                }
            }
        }
    }
    
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
        $query = $this->db->prepare('SELECT nombre_entrenador, imagen FROM entrenadorpokemon WHERE id_entrenador = :id_entrenador');
        $query->execute([":id_entrenador"=>$id_trainer]);

        $trainer = $query->fetch(PDO::FETCH_ASSOC);
        return $trainer;
    }

    
    public function getTrainerPokemons($id_trainer){
        $query = $this->db->prepare('SELECT id, nro_pokedex, nombre, tipo, fecha_captura, peso , imagen_pokemon FROM pokemon JOIN entrenadorpokemon on (FK_id_entrenador =id_entrenador) WHERE id_entrenador = :id_entrenador');
        $query->execute([':id_entrenador'=>$id_trainer]);

        $trainer = $query->fetchAll(PDO::FETCH_ASSOC);
        return $trainer;
    }
    
    
    public function insertTrainer($trainer = [],$imagen=null){
        $pathImg=null;
         
        if ($imagen){
            $pathImg = $this->uploadImage($imagen);
            $trainer['imagen']=$pathImg;
        }   
        $query = $this->db->prepare('INSERT INTO entrenadorpokemon(nombre_entrenador, ciudad_origen, nivel_entrenador, cant_medallas, imagen) 
                                            VALUES (?, ?, ?, ?, ?)');
        $query -> execute([$trainer['nombre_entrenador'], $trainer['ciudad_origen'], $trainer['nivel_entrenador'], $trainer['cant_medallas'],$trainer['imagen']]);
         
        $trainerID = $this->db->lastInsertId();
        return $trainerID;
    }

    public function deleteTrainer($trainerID){
        $hasPokemons = $this->getTrainerPokemons($trainerID);
        if ($hasPokemons) {$this->releasePokemons($trainerID);} 
        
        $query = $this->db->prepare('DELETE FROM entrenadorpokemon WHERE id_entrenador=?');
        $query->execute([$trainerID]);
    }

    public function updateTrainer($updateFields, $imagen = NULL){
        $pathImg=null;
         
        if ($imagen){
            $pathImg = $this->uploadImage($imagen);
            $updateFields['imagen']=$pathImg;
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