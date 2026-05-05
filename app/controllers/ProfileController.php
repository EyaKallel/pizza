<?php
class ProfileController extends Controller {
    
    public function index() {
        // Vérification améliorée de la session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Vérification directe au lieu de la méthode isLoggedIn()
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à votre profil";
            $this->redirect('/ProjetPizza2/index.php?url=auth/login');
            return;
        }
        
        $user = $this->model('User');
        $order = $this->model('Order');
        
        $user->id = $_SESSION['user_id'];
        $user_info = $user->getById($user->id);
        $orders = $order->getUserOrders($user->id);
        
        $this->view('profile/index', [
            'user_info' => $user_info,
            'orders' => $orders,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function update() {
        if (!$this->isLoggedIn()) {
            echo json_encode(['error' => 'Vous devez être connecté']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->model('User');
            
            $user->id = $_SESSION['user_id'];
            $user->prenom = $_POST['prenom'];
            $user->nom = $_POST['nom'];
            $user->adresse = $_POST['adresse'];
            $user->telephone = $_POST['telephone'];
            
            if ($user->updateProfile()) {
                $_SESSION['prenom'] = $user->prenom;
                echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès']);
            } else {
                echo json_encode(['error' => 'Erreur lors de la mise à jour du profil']);
            }
            exit;
        }
    }
    
    public function orderDetails($order_id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
            return;
        }
        
        $order = $this->model('Order');
        $order_details = $order->getOrderDetails($order_id);
        
        if (!$order_details || $order_details['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Commande non trouvée";
            $this->redirect('profile');
            return;
        }
        
        $order_items = $order->getOrderItems($order_id);
        
        $this->view('profile/order_details', [
            'order_details' => $order_details,
            'order_items' => $order_items,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
}
?>
