<?php
class HomeController extends Controller {
    
    public function index() {
        // Si l'utilisateur est déjà connecté, le rediriger selon son rôle
        if ($this->isLoggedIn()) {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $this->redirect('admin');
            } else {
                $this->redirect('menu');
            }
            return;
        }
        
        // Sinon, afficher la page de login comme page d'accueil
        $this->view('auth/login');
    }
}
?>
