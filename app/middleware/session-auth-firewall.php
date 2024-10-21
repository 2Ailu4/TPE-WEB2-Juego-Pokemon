<?php 
    function sessionAuthFirewall($res){
        if($res->user){
            return ;
        }
        else{
            header('Location: ' . BASE_URL . 'home');
            die();
        }
    }
    
?>