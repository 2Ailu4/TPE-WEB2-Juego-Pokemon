<?php
require_once 'Entrenador.Model.php';
require_once 'Entrenador.View.php';

class coachController{
    private $coach_model;
    private $coach_view;

    public function __construct(){
        $this->coach_model = new coachModel;
        $this->coach_view = new coachView;
    }

}