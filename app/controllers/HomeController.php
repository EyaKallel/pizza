<?php
// Appeler Database pour démarrer les sessions automatiquement
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

class HomeController extends Controller {
    
    public function index() {
        // Admin: rediriger vers le dashboard
        if ($this->isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $this->redirect('admin');
            return;
        }

        $product = $this->model('Product');
        $featured_products = $product->getFeatured();

        $this->view('home/index', [
            'featured_products' => $featured_products,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
}
?>
