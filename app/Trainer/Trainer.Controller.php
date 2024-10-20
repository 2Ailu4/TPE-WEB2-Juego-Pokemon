<?php
require_once 'Trainer.Model.php';
require_once 'Trainer.View.php';

class trainerController{
    private $trainer_model;
    private $trainer_view;

    public function __construct($res=null){
        $this->trainer_model = new trainerModel;
        if(!isset($res)){$this->trainer_view = new trainerView();}
         else{$this->trainer_view = new trainerView($res->user);}
       
    }

    public function listTrainers(){
        $trainers = $this->trainer_model->getTrainers();
        $this->trainer_view->showTrainers($trainers);
    }

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
        $trainer = $this->trainer_model->getTrainer($id_trainer);
        $pokemons = $this->trainer_model->getTrainerPokemons($id_trainer);
      
        $this->trainer_view->showTrainerPokemons($pokemons,$trainer);
    }

    private function isSet($var){
        $setted = false;
        if(isset($var) && !empty($var)) {return $setted =true;}
        return $setted;
    }

    private function getFormFields($update = false){
        $fields=[];
        foreach ($_POST as $key => $value) {
            if($update){
                if ($this->isSet($value)){
                    $fields[$key]=$value;
                }
            }else $fields[$key]=$value;
            
        }
        return $fields;
    }
     
    public function showForm_INSERT(){
        $this->trainer_view->showForm_INSERT();
        
    }

    public function insertTrainer(){
        $imgTemp=NULL;
        $updateFields = $this->getFormFields();
        if(isSet($updateFields)) { 
            if ($this->imageUploaded()) {
                $imgTemp = $_FILES['input_name']['tmp_name'];             
                $this->trainer_model->insertTrainer($updateFields,$imgTemp);               
            }else
                $this->trainer_model->insertTrainer($updateFields);           
        }else{
            $this->trainer_view->showMessage('Debes modificar al menos un campo para poder actualizar tu [perfil / al entrenador: ]');
        }        
        header('Location: ' . BASE_URL . "trainer-list" );
    }

    public function showForm_UPDATE($trainerID){
        $trainer = $this->trainer_model->getTrainer($trainerID);
        $this->trainer_view->showForm_UPDATE($trainer);
    }

    public function updateTrainer($trainerID){
        $imgTemp=NULL;
        $updateFields = $this->getFormFields(true);
        if(isSet($updateFields)) {
            $updateFields['id_entrenador'] = $trainerID;
            if ($this->imageUploaded()) {
                $imgTemp = $_FILES['input_name']['tmp_name'];                
                $this->trainer_model->updateTrainer($updateFields,$imgTemp); 
            }else
                $this->trainer_model->updateTrainer($updateFields);
        }else{
            $this->trainer_view->showMessage('Debes modificar al menos un campo para poder actualizar tu [perfil / al entrenador: ]');
        }
        header('Location: ' . BASE_URL . "trainer-information/".$trainerID );         
    }
    
    public function deleteTrainer($trainerID){
        $this->trainer_model->deleteTrainer($trainerID);
        $this->trainer_view->showMessage('Tu usuario ah sido eliminado exitosamente');
        $this->trainer_view->return("trainer-list/" ,"Lista de Entrenadores");
        header('Location: ' . BASE_URL . "trainer-list");
    }

    private function imageUploaded(){
        return $_FILES['input_name']['type'] == "image/jpg"
            || $_FILES['input_name']['type'] == "image/jpeg" 
            || $_FILES['input_name']['type'] == "image/png";     
    }


}