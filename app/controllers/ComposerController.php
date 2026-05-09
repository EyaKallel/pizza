<?php
class ComposerController extends Controller {
    
    public function index() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour composer une pizza";
            $this->redirect('auth/login');
            return;
        }
        
        $pizzaSize = $this->model('PizzaSize');
        $doughType = $this->model('DoughType');
        $ingredient = $this->model('Ingredient');
        
        $sizes = $pizzaSize->getAll();
        $doughs = $doughType->getAll();
        $ingredients = $ingredient->getAll();
        
        $this->view('composer/index', [
            'sizes' => $sizes,
            'doughs' => $doughs,
            'ingredients' => $ingredients,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function calculatePrice() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $base_price = 5.00; // Prix de base
            
            $size_id = $_POST['size_id'];
            $dough_id = $_POST['dough_id'];
            $ingredient_ids = isset($_POST['ingredients']) ? $_POST['ingredients'] : [];
            
            $pizzaSize = $this->model('PizzaSize');
            $doughType = $this->model('DoughType');
            $ingredient = $this->model('Ingredient');
            
            $size = $pizzaSize->getById($size_id);
            $dough = $doughType->getById($dough_id);
            $selected_ingredients = $ingredient->getByIds($ingredient_ids);
            
            $total_price = $base_price;
            
            if ($size) {
                $total_price *= $size['price_modifier'];
            }
            
            if ($dough) {
                $total_price += $dough['price_modifier'];
            }
            
            foreach ($selected_ingredients as $ing) {
                $total_price += $ing['prix_supplementaire'];
            }
            
            echo json_encode(['price' => number_format($total_price, 2)]);
            exit;
        }
    }
    
    public function addToCart() {
        if (!$this->isLoggedIn()) {
            echo json_encode(['error' => 'Vous devez être connecté']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $size_id = $_POST['size_id'];
            $dough_id = $_POST['dough_id'];
            $ingredient_ids = isset($_POST['ingredients']) ? $_POST['ingredients'] : [];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            $pizzaSize = $this->model('PizzaSize');
            $doughType = $this->model('DoughType');
            $ingredient = $this->model('Ingredient');
            
            $size = $pizzaSize->getById($size_id);
            $dough = $doughType->getById($dough_id);
            $selected_ingredients = $ingredient->getByIds($ingredient_ids);
            
            // Calcul du prix
            $base_price = 5.00;
            $total_price = $base_price;
            
            if ($size) {
                $total_price *= $size['price_modifier'];
            }
            
            if ($dough) {
                $total_price += $dough['price_modifier'];
            }
            
            foreach ($selected_ingredients as $ing) {
                $total_price += $ing['prix_supplementaire'];
            }
            
            // Création de la pizza personnalisée
            $custom_pizza = [
                'id' => 'custom_' . uniqid(),
                'name' => 'Pizza Personnalisée',
                'price' => $total_price,
                'quantity' => $quantity,
                'image' => 'custom_pizza.jpg',
                'is_custom' => true,
                'size' => $size,
                'dough' => $dough,
                'ingredients' => $selected_ingredients
            ];
            
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $_SESSION['cart'][$custom_pizza['id']] = $custom_pizza;
            
            echo json_encode(['success' => true, 'message' => 'Pizza personnalisée ajoutée au panier']);
            exit;
        }
    }
}
?>
