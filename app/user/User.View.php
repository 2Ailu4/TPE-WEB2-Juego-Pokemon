<?php 

class authView{
    public $user = null;
    public function __construct($user=null){
        $this->user = $user;
    }
    public function showAlertLogin($error = ''){ ?>
        <h1 class="fw-bolder"><mark> <?php echo $error ?> </mark></h1>; <?php
    }

    public function showUpdateItemsCategories(){
        $imgPokeball = "./images/pokeball.png";
        $template = './templates/pokemon/update-items-categories.phtml';
        require_once 'templates/layout/index.phtml';
    }

    public function showHome(){
        $mainClass = "container";
        $imgPokeball = "./images/pokeball.png";
        $template = './templates/home.phtml';
        require_once 'templates/layout/index.phtml';
    }

}
