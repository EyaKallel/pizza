<?php
class CheckoutController extends Controller {
    
    public function index() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour passer une commande";
            $this->redirect('auth/login');
            return;
        }
        
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION['error'] = "Votre panier est vide";
            $this->redirect('cart');
            return;
        }
        
        $user = $this->model('User');
        $user->id = $_SESSION['user_id'];
        $user_info = $user->getById($user->id);
        
        $cart_items = $_SESSION['cart'];
        $total = 0;
        
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        $this->view('checkout/index', [
            'cart_items' => $cart_items,
            'total' => $total,
            'user_info' => $user_info,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function process() {
        header('Content-Type: application/json; charset=utf-8');

        if (!$this->isLoggedIn()) {
            echo json_encode(['error' => 'Vous devez être connecté']);
            exit;
        }

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            echo json_encode(['error' => 'Panier vide']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $order = $this->model('Order');

        $order->user_id = $_SESSION['user_id'];
        $order->delivery_address = $_POST['delivery_address'] ?? '';
        $order->phone = $_POST['phone'] ?? '';
        $order->note_client = $_POST['instructions'] ?? '';

        $type = $_POST['type_livraison'] ?? 'livraison';
        $order->type_livraison = in_array($type, ['livraison', 'sur_place'], true) ? $type : 'livraison';

        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += (float) $item['price'] * (int) $item['quantity'];
        }
        
        // Frais de livraison: 3.50€ pour livraison, 0 pour retrait sur place
        $delivery_fee = ($type === 'livraison') ? 3.50 : 0.00;
        $order->frais_livraison = $delivery_fee;
        $order->total_amount = $subtotal + $delivery_fee;

        if ($order->create()) {
            if (!$order->addOrderItems($order->id, $_SESSION['cart'])) {
                echo json_encode(['error' => 'Erreur lors de l\'enregistrement des articles']);
                exit;
            }

            unset($_SESSION['cart']);

            echo json_encode([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Commande passée avec succès',
            ]);
            exit;
        }

        echo json_encode(['error' => 'Erreur lors de la création de la commande']);
        exit;
        } catch (Exception $e) {
            error_log('Checkout error: ' . $e->getMessage());
            echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
            exit;
        }
    }
    
    public function success($order_id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
            return;
        }
        
        $order = $this->model('Order');
        $order_details = $order->getOrderDetails($order_id);
        
        if (!$order_details || $order_details['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Commande non trouvée";
            $this->redirect('home');
            return;
        }
        
        $order_items = $order->getOrderItems($order_id);
        
        $this->view('checkout/success', [
            'order_details' => $order_details,
            'order_items' => $order_items,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
}
?>
