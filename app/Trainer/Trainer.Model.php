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
    
}