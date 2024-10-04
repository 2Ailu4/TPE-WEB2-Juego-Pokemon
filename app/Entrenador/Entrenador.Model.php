<?php 

class coachModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=tpe-web2-hiese-peralta ;charset=utf8', 'root', '');
    }
    
}