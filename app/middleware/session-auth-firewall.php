<?php 
    function sessionAuthFirewall($res){
        //session_start(); 
         //lee si hay una cookie sino me da una vacia
        if($res->user){
            return ;
        }
        else{
            header('Location: ' . BASE_URL . 'home');
            die();
        }
    }
    
?>