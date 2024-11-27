<?php 
require_once './config/config.php';
class userModel{
    private $db;


    public function __construct(){
        
        $this->db = new PDO(
            "mysql:host=".MYSQL_HOST .";charset=utf8", MYSQL_USER, MYSQL_PASS);
        if ($this->db) {
            $this->createDatabase();
            $this->db->exec("USE " . MYSQL_DB);
            $this->_deploy();
        }
    }

    function createDatabase() {
            $query = "CREATE DATABASE IF NOT EXISTS " . MYSQL_DB . " DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
            $this->db->exec($query);
        }

    private function _deploy() {
        $query = $this->db->query('SHOW TABLES');
        $tables = $query->fetchAll();
        if (count($tables) == 0) { 
            $sqlFile = './tpe-web2-hiese-peralta.sql';
            $sql = file_get_contents($sqlFile);
            
            // Arreglo para separar en consultas
            $queries = explode(';', $sql);
            foreach ($queries as $query) {
                $query = trim($query); // quitamos espacios en blanco al inicio y fin             
                if (!empty($query)) {
                    $this->db->query($query);
                }
            }
        }
    }
    public function getUserByUserNameOrMail($user){
        $query = $this->db->prepare('SELECT * FROM admin_user WHERE (email= :user OR nombre_usuario = :user)');
        $query->execute([':user'=>$user]);

        $user = $query->fetch(PDO::FETCH_OBJ);

        return $user;
    }

}