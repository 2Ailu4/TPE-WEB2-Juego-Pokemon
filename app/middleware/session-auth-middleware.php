<?php 

    function sessionAuthMiddleware($res){
        session_start(); 
           //lee si hay una cookie sino me da una vacia
        if(isset($_SESSION['ID_USER'])){
            $res->user = new stdClass; 
            $res->user->id = $_SESSION['ID_USER'];
            $res->user->nombre_usuario = $_SESSION['NAME_USER'];
            $res->user->email = $_SESSION['EMIAL_USER'];
            return ;
        }
         
    }
     
?>