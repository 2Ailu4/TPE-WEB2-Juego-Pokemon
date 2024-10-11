<?php
require_once 'Trainer.Model.php';
require_once 'Trainer.View.php';

class trainerController{
    private $trainer_model;
    private $trainer_view;

    public function __construct(){
        $this->trainer_model = new trainerModel;
        $this->trainer_view = new trainerView;
    }

    public function listTrainers(){
        $trainers = $this->trainer_model->getTrainers();
        $this->trainer_view->showTrainers($trainers);
    }

    // no la uso (preguntar si esta bien que el controlador devuelva consultas del modelo para interactuar con otros controladores)
    public function getTrainers_ID_name(){
        return $this->trainer_model->getTrainers_ID_name();
    }
    
    //muestra el perfil del entrenador
    public function trainerInformation($id_trainer){
        $trainer = $this->trainer_model->getTrainer($id_trainer);
        $pokemons = $this->trainer_model->getTrainerPokemons($id_trainer);
        $this->trainer_view->showTrainer($trainer);
    }

    //muestra galeria de pokemons de un entrenador en particular
    public function trainerPokemons($id_trainer){
        $trainer_name = $this->trainer_model->getTrainer_Name($id_trainer);
 
        $pokemons = $this->trainer_model->getTrainerPokemons($id_trainer);
       
        $trainer['id_entrenador'] = $id_trainer;
        $trainer['nombre_entrenador'] = $trainer_name['nombre_entrenador'];
      
        $this->trainer_view->showTrainerPokemons($pokemons,$trainer);
    }

}