<?php
class MenuController extends Controller {
    
    public function index() {
        $product = $this->model('Product');
        $category = $this->model('Category');
        
        $products = $product->getAll();
        $categories = $category->getAll();
        
        $products_by_category = [];
        foreach ($categories as $cat) {
            $products_by_category[$cat['id']] = $product->getByCategory($cat['id']);
        }
        
        $this->view('menu/index', [
            'products' => $products,
            'categories' => $categories,
            'products_by_category' => $products_by_category,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function addToCart() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour ajouter au panier";
            $this->redirect('auth/login');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $size = isset($_POST['size']) ? $_POST['size'] : 'M';
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $cart_key = $product_id . '_' . $size;
            
            if (isset($_SESSION['cart'][$cart_key])) {
                $_SESSION['cart'][$cart_key]['quantity'] += $quantity;
            } else {
                $product = $this->model('Product');
                $product_info = $product->getById($product_id);
                
                if ($product_info) {
                    $price = $product->getPriceBySize($product_id, $size);
                    
                    $_SESSION['cart'][$cart_key] = [
                        'id' => $product_info['id'],
                        'name' => $product_info['nom'],
                        'price' => $price,
                        'quantity' => $quantity,
                        'size' => $size,
                        'image' => $product_info['image'],
                        'is_custom' => false
                    ];
                }
            }
            
            $_SESSION['success'] = "Produit ajouté au panier";
            $this->redirect('menu');
        }
    }
}
?>
