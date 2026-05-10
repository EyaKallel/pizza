<?php
// Appeler Database pour démarrer les sessions automatiquement
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

class CartController extends Controller {
    
    public function index() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour voir votre panier";
            $this->redirect('auth/login');
            return;
        }
        
        $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $total = 0;
        
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        $this->view('cart/index', [
            'cart_items' => $cart_items,
            'total' => $total,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function update() {
        if (!$this->isLoggedIn()) {
            echo json_encode(['error' => 'Vous devez être connecté']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item_id = $_POST['item_id'];
            $quantity = (int)$_POST['quantity'];
            
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$item_id]);
            } else {
                if (isset($_SESSION['cart'][$item_id])) {
                    $_SESSION['cart'][$item_id]['quantity'] = $quantity;
                }
            }
            
            // Recalculer le total
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            
            echo json_encode([
                'success' => true,
                'total' => number_format($total, 2),
                'item_count' => count($_SESSION['cart'])
            ]);
            exit;
        }
    }
    
    public function remove() {
        if (!$this->isLoggedIn()) {
            echo json_encode(['error' => 'Vous devez être connecté']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item_id = $_POST['item_id'];
            
            if (isset($_SESSION['cart'][$item_id])) {
                unset($_SESSION['cart'][$item_id]);
            }
            
            // Recalculer le total
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            
            echo json_encode([
                'success' => true,
                'total' => number_format($total, 2),
                'item_count' => count($_SESSION['cart'])
            ]);
            exit;
        }
    }
    
    public function clear() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
            return;
        }
        
        unset($_SESSION['cart']);
        $_SESSION['success'] = "Panier vidé avec succès";
        $this->redirect('cart');
    }
}
?>
