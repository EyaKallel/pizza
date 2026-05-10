<?php
// Appeler Database pour démarrer les sessions automatiquement
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

class ProfileController extends Controller {
    
    public function index() {
        // $_SESSION fonctionne déjà grâce à l'appel ci-dessus
        
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
            
            // Validation du numéro de téléphone tunisien
            if (!empty($user->telephone) && !$this->validateTunisianPhone($user->telephone)) {
                echo json_encode(['error' => 'Le numéro de téléphone doit contenir exactement 8 chiffres (ex: 12345678)']);
                exit;
            }
            
            if ($user->updateProfile()) {
                $_SESSION['prenom'] = $user->prenom;
                echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès']);
            } else {
                echo json_encode(['error' => 'Erreur lors de la mise à jour du profil']);
            }
            exit;
        }
    }
    
    private function validateTunisianPhone($phone) {
        // Supprimer tous les caractères non numériques
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Vérifier que c'est exactement 8 chiffres
        return strlen($phone) === 8 && ctype_digit($phone);
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
