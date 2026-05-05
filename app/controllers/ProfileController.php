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
            $user->first_name = $_POST['first_name'];
            $user->last_name = $_POST['last_name'];
            $user->address = $_POST['address'];
            $user->phone = $_POST['phone'];
            
            if ($user->updateProfile()) {
                $_SESSION['first_name'] = $user->first_name;
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
