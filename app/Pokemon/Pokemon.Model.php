<?php 
// require_once '../../config/config.php';
require_once './config/config.php';
class pokemonModel{
    protected $db;

    public function __construct(){
        
        $this->db = new PDO(
            "mysql:host=".MYSQL_HOST .
            ";dbname=".MYSQL_DB.";charset=utf8", 
            MYSQL_USER, MYSQL_PASS);
        $this->_deploy();
            
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
    
    // .....Listado......

    public function getPokemons(){
        $query = $this->db->prepare('SELECT * FROM pokemon ORDER BY nro_pokedex');
        $query->execute();   
    
        $pokemons = $query->fetchAll(PDO::FETCH_OBJ);
        return $pokemons;
    }

    public function getPokemonByID($id_pokemon){
        $query = $this->db->prepare('SELECT * FROM pokemon WHERE id=?');
        $query->execute([$id_pokemon]);

        $pokemon = $query->fetch(PDO::FETCH_OBJ);
        return $pokemon;
    }
    
    public function getPokemonByNroPokedex($nroPokedex){
        $query = $this->db->prepare('SELECT nombre, tipo, imagen_pokemon FROM pokemon WHERE nro_pokedex=?');
        $query->execute([$nroPokedex]);

        $pokemon = $query->fetch(PDO::FETCH_OBJ);
        return $pokemon;
    }

    public function getPokemonByName($name){
        $query = $this->db->prepare('SELECT nro_pokedex, tipo FROM pokemon WHERE nombre LIKE ?');
        $query->execute([$name]);

        $pokemon = $query->fetch(PDO::FETCH_OBJ);
        return $pokemon;
    }

    
    public function getNameTrainerByFk($fk_trainer){// CONSULTAR SI LO DEBE TENER MARIAN*********************
        $query = $this->db->prepare('SELECT nombre_entrenador FROM entrenadorpokemon WHERE id_entrenador=?');
        $query->execute([$fk_trainer]);

        $trainer = $query->fetch(PDO::FETCH_OBJ);
        return $trainer;
    }


    // .....Insercion......
    public function getTrainersIdName(){
        $query = $this->db->prepare('SELECT id_entrenador,nombre_entrenador FROM entrenadorpokemon');
        $query->execute();

        $trainers = $query->fetchAll(PDO::FETCH_OBJ);
        return $trainers;
    }// ------------------------------------------------------------------------------------------
    
    public function countNamePokemon($name){
        $query = $this->db->prepare('SELECT COUNT(*) FROM pokemon WHERE nombre= ?');
        $query->execute([$name]);
        return $query->fetchColumn();
    }


    public function countNroPokedex($nro_Pokedex){
        $query = $this->db->prepare('SELECT COUNT(*) FROM pokemon WHERE nro_pokedex= ?');
        $query->execute([$nro_Pokedex]);
        return $query->fetchColumn();
    }
    


    public function insertPokemon($nro_pokedex, $nombre, $tipo, $peso, $imgTemp, $entrenador=null, $pathImg_exists = null){
        $pathImg = $pathImg_exists; 
        // carga de imagen
        if(!isset($pathImg_exists)){
            $pathImg = $this->uploadImage($imgTemp,$nombre);
        }
        
        $query = $this->db->prepare('INSERT INTO pokemon(nro_pokedex, nombre, tipo, peso, FK_id_entrenador, imagen_pokemon) 
                                            VALUES (?, ?, ?, ?, ?, ?)');
        $query->execute([$nro_pokedex, $nombre, $tipo, $peso, $entrenador,$pathImg]);

        $id = $this->db->lastInsertId();
        return $id;
    }

    // .....Eliminacion......
    public function getFkTrainerByPokemon($id_Pokemon){
        $query = $this->db->prepare('SELECT FK_id_entrenador FROM pokemon WHERE id=?');
        $query->execute([$id_Pokemon]);
        $trainer = $query->fetch(PDO::FETCH_OBJ);
    
        return $trainer;
    }
    public function getFkTrainerByPokemon_DELETE($id_Pokemon){
        $query = $this->db->prepare('SELECT FK_id_entrenador FROM pokemon WHERE id=?');
        $query->execute([$id_Pokemon]);
        $trainer = $query->fetch(PDO::FETCH_ASSOC);
        return $trainer;
    }
    
    public function releasePokemon($id_Pokemon){
        $query = $this->db->prepare('DELETE FROM pokemon WHERE id=?');
        $query->execute([$id_Pokemon]);
        return $this->getFkTrainerByPokemon($id_Pokemon);
    }
    
    private function uploadImage($imgTemp, $nombre_pokemon, $update = false, $relativePath = "images/pokemons/") {
 
        $extension = strtolower(pathinfo($_FILES['input_name']['name'], PATHINFO_EXTENSION));
        //var_dump('extention:  ', $extension);
        ?><br><?php
        $filePath = $relativePath . $nombre_pokemon . "." . $extension;
        
        if($update){// elimina la imagen de pokemon 
            $this->deleteImage($filePath); 
}
        // setea la nueva imagen para dicho pokemon
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



    // .....Actualizacion......
    public function getNroPokedex($id_Pokemon){
        $query = $this->db->prepare('SELECT nro_pokedex FROM pokemon WHERE id=?');
        $query->execute([$id_Pokemon]);
        $pokedex = $query->fetch(PDO::FETCH_OBJ);
        return $pokedex;
    }

    private function existsInDB($var){
        $setted = false;
        if(isset($var) && !empty($var)) {return $setted =true;}
        return $setted;
    }


    public function update_Pokemon($id_Pokemon, $updateFields){
        $pokemonByName=NULL;
        $pokemonByNroPokedex=NULL;

        $pokemon = $this->getPokemonByID($id_Pokemon);
        $nro_pokedex_for_update_Type = "";

        $nombre_modificado = false;
        $nro_Pokedex_modificado = false;
        
        if(array_key_exists('nombre',$updateFields)){ 
            $pokemonByName = $this->getPokemonByName($updateFields['nombre']);  //nro,tipo
            $nombre_modificado=true;
        }
        if(array_key_exists('nro_pokedex',$updateFields)){ 
            $pokemonByNroPokedex = $this->getPokemonByNroPokedex($updateFields['nro_pokedex']);
            $nro_Pokedex_modificado=true;
        }
        
        if($nro_Pokedex_modificado && $nombre_modificado){
        
            if ($this->existsInDB($pokemonByName) && $this->existsInDB($pokemonByNroPokedex)){  //existen ambos en la db
                if($updateFields['nombre'] === $pokemonByNroPokedex->nombre){  // verifico que coincidan el nombre y el nombre del nro de pokedex (se puede consultar x nro_pok)
                    $this->updateNroNameType_By_ID($id_Pokemon, $updateFields['nro_pokedex'], $updateFields['nombre'], $pokemonByNroPokedex->tipo); //(WHERE id)
                    $nro_pokedex_for_update_Type = $updateFields['nro_pokedex'];
                }
                else{  //existen el nro y el nombre pero estos no coinciden
                    echo "No es posible actualizar el nombre del pokemon ".$updateFields['nombre'].", ya que el nombre asociado a Nro_Pokedex: ".$updateFields['nro_pokedex']." cuenta con otro nombre";
                }
            }else{//ninguno de los dos existe en la db
                if(!($this->existsInDB($pokemonByName)) && !($this->existsInDB($pokemonByNroPokedex))){  
                    $this->updateNroNameType_By_ID($id_Pokemon, $updateFields['nro_pokedex'], $updateFields['nombre'], $pokemon->tipo);  //(WHERE id)
                    $nro_pokedex_for_update_Type = $updateFields['nro_pokedex'];
                }
                else{  //nro_pokedex existe en la db pero el nombre no
                    if(!$this->existsInDB($pokemonByName) && $this->existsInDB($pokemonByNroPokedex)){    
                        $this->updateNroNameType_By_ID($id_Pokemon, $updateFields['nro_pokedex'], $pokemonByNroPokedex->nombre, $pokemonByNroPokedex->tipo); //(WHERE id)
                        $this->updateName_BY_nro_Pokedex($updateFields['nombre'], $updateFields['nro_pokedex']);
                        $nro_pokedex_for_update_Type = $updateFields['nro_pokedex'];
                    }
                    else{  //nombre existe en la db pero nro_pokedex no
                        $this->updateNroNameType_By_ID($id_Pokemon, $pokemonByName->nro_pokedex, $updateFields['nombre'], $pokemonByName->tipo); //(WHERE id)
                        $this->update_Nro_Pokedex($updateFields['nro_pokedex'], $pokemonByName->nro_pokedex);
                        $nro_pokedex_for_update_Type = $updateFields['nro_pokedex'];
                    }
                }                
            }
        }else{
            if($nro_Pokedex_modificado){
                if($this->existsInDB($pokemonByNroPokedex)){   //existe en la db
                    $this->updateNroNameType_By_ID($id_Pokemon, $updateFields['nro_pokedex'], $pokemonByNroPokedex->nombre, $pokemonByNroPokedex->tipo);  //(WHERE id)
                    $nro_pokedex_for_update_Type = $updateFields['nro_pokedex'];
                }
                else{  //no existe en la db
                    $this->update_Nro_Pokedex($updateFields['nro_pokedex'], $pokemon->nro_pokedex);
                    $nro_pokedex_for_update_Type = $updateFields['nro_pokedex'];
                }
            }
            if($nombre_modificado){
                if($this->existsInDB($pokemonByName)){  //existe en la db
                    $this->updateNroNameType_By_ID($id_Pokemon, $pokemonByName->nro_pokedex, $updateFields['nombre'], $pokemonByName->tipo); //(WHERE id)
                    $nro_pokedex_for_update_Type = $pokemonByName->nro_pokedex;
                }
                else{  //no existe en la db
                    $this->updateName_BY_nro_Pokedex($updateFields['nombre'], $pokemon->nro_pokedex);
                    $nro_pokedex_for_update_Type = $pokemon->nro_pokedex;
                }
            }
        }

        if(array_key_exists('tipo',$updateFields)){ 
            $this->updateType_BY_nro_Pokedex($nro_pokedex_for_update_Type, $updateFields['tipo']);
        }
        // carga de imagen
        $update_attributes=[];
        $whereParams="id = :id ";
        $pathImg=null;
        $imgTemp = $_FILES['input_name']['tmp_name']; 
        
        if(!empty($imgTemp)){
            var_dump("dasdsadsa", $imgTemp);
            $pathImg = $this->uploadImage($imgTemp,$pokemon->nombre);
            $updateFields['imagen_pokemon'] = $pathImg;
            $update_attributes= $this->add_existent_keys(['peso','fecha_captura', 'imagen_pokemon', 'FK_id_entrenador'],$updateFields);
        }else{
            $update_attributes= $this->add_existent_keys(['peso','fecha_captura','FK_id_entrenador'],$updateFields);
        }

        if (isset($update_attributes['FK_id_entrenador']) && $update_attributes['FK_id_entrenador'] === "NULL") { $update_attributes['FK_id_entrenador']= NULL;}
        
        $this->update_BY_ASSOC_Array($id_Pokemon, $update_attributes, $whereParams);

    }

    private function generate_update_params(array $field){
        $SET_params='';
        $ASSOC_Params_array=[];
        $num_of_fields = count($field);
        $i=0;
        foreach ($field as $key => $value) {
                $associate=':'.$key;
                $SET_params .= $key . ' = '.$associate; //id = :id

                $ASSOC_Params_array[$associate] = $value;   //[':id'=>value, ...]
                $i++;
                if($i< $num_of_fields)
                    $SET_params.=', ';
        }
        return ['ASSOC_ARRAY'=>$ASSOC_Params_array,'SET_params'=>$SET_params];
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

    private function updateType_BY_nro_Pokedex($nro_Pokedex, $type){ 
        $updateParams ="tipo = :new_tipo";
        $whereParams = "nro_pokedex = :old_nro_pokedex";

        $ASSOC_Array=[':old_nro_pokedex'=>$nro_Pokedex,':new_tipo'=>$type];

        $this->update($updateParams, $whereParams, $ASSOC_Array);
    }

    private function updateNroNameType_By_ID($id_Pokemon, $nro_Pokedex, $name, $type){
        $updateParams ="nro_pokedex = :new_nro_pokedex,    
                        nombre = :new_nombre,              
                        tipo = :new_tipo ";             
        $whereParams = "id = :id";

        $ASSOC_Array=[':id'=>intval($id_Pokemon), ':new_nro_pokedex'=>$nro_Pokedex,':new_nombre'=>$name,':new_tipo'=>$type];

        $this->update($updateParams, $whereParams, $ASSOC_Array);
       
    }

    private function update_BY_ASSOC_Array($id_Pokemon, array $ASSOC_UPD_params, $whereParams){
        $fields = $this->generate_update_params($ASSOC_UPD_params); 
        $ASSOC_Array = $fields['ASSOC_ARRAY'];
        $ASSOC_Array[':id']= intval($id_Pokemon);   
        $this->update($fields['SET_params'], $whereParams, $ASSOC_Array);
    }

    private function updateName_BY_nro_Pokedex($name, $nro_Pokedex){
        $updateParams ="nombre = :new_name";
        $whereParams = "nro_pokedex = :current_nro_pokedex";

        $ASSOC_Array=[':current_nro_pokedex'=>$nro_Pokedex,':new_name'=>$name];

        $this->update($updateParams, $whereParams, $ASSOC_Array);
    }

    private function update_Nro_Pokedex($new_nro_Pokedex, $old_nro_Pokedex){
        $updateParams ="nro_pokedex = :new_nro_pokedex";
        $whereParams = "nro_pokedex = :old_nro_pokedex";

        $ASSOC_Array=[':old_nro_pokedex'=>$old_nro_Pokedex,':new_nro_pokedex'=>$new_nro_Pokedex];

        $this->update($updateParams, $whereParams, $ASSOC_Array);
    }

    private function update($updateParams, $whereParams, $ASSOC_Array){
        $query = $this->db->prepare("UPDATE pokemon
                                        SET  $updateParams 
                                        WHERE $whereParams");                           
        $query->execute($ASSOC_Array); 
    }
}