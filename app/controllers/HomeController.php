<?php
class HomeController extends Controller {
    
    public function index() {
        $product = $this->model('Product');
        $featured_products = $product->getFeatured();
        
        $this->view('home/index', [
            'featured_products' => $featured_products,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
}
?>
