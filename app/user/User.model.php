<?php 

class userModel{
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=tpe-web2-hiese-peralta;charset=utf8', 'root', '');
    }

    public function getUserByUserNameOrMail($user){
        $query = $this->db->prepare('SELECT * FROM admin_user WHERE (email= :user OR nombre_usuario = :user)');
        $query->execute([':user'=>$user]);

        $user = $query->fetch(PDO::FETCH_OBJ);

        return $user;
    }

}