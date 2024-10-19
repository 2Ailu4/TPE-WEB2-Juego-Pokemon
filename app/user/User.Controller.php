<?php 
require_once 'User.model.php';
require_once 'User.View.php';

class userController{
    private $user_model;
    private $user_view;

    public function __construct(){
        $this->user_model = new userModel;
        $this->user_view = new authView;
    }

    public function showHome(){
        $this->user_view->showHome();
    }

    public function showUpdateItemsCategories(){
        $this->user_view->showUpdateItemsCategories();
    }

    public function login(){
        if(!isset($_POST['user']) || empty($_POST['user'])){
            return $this->user_view->showAlertLogin('Debe ingresar un nombre de usuario o email para ingresar');
        }
        if(!isset($_POST['password']) || empty($_POST['password'])){
            return $this->user_view->showAlertLogin('Falta completar la contraseña');
        }

        $user = $_POST['user'];
        $password = $_POST['password'];
        $userFromDB = $this->user_model->getUserByUserNameOrMail($user);

        //verifica si el password que nos dio el usuario coincida con el hash
        if($userFromDB && password_verify($password, $userFromDB->contraseña)){
            session_start();
            $_SESSION['ID_USER'] = $userFromDB->id;
            $_SESSION['NAME_USER'] = $userFromDB->nombre_usuario;
            $_SESSION['EMIAL_USER'] = $userFromDB->email;
            $_SESSION['LAST_ACTIVITY'] = time();

            header('Location: ' . BASE_URL . 'showUpdateItemsCategories');
        }else{
            return $this->user_view->showAlertLogin('Credenciaes incorrectas');
        }
        
    }
}