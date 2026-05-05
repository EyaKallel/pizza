<?php
require_once __DIR__ . '/../config/database.php';

class Controller {
    protected $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../app/views/" . $view . ".php";
    }
    
    public function model($model) {
        require_once __DIR__ . "/../app/models/" . $model . ".php";
        return new $model($this->db);
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}
?>
