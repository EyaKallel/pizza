<?php
class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Vérifier si l'utilisateur est admin
        if (!$this->isLoggedIn() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = "Accès non autorisé - Réservé aux administrateurs";
            $this->redirect('home');
            exit;
        }
    }
    
    public function index() {
        // Rediriger vers les commandes au lieu du tableau de bord
        $this->redirect('admin/orders');
    }
    
    public function orders() {
        $order = $this->model('Order');
        $orders = $this->getAllOrders();
        
        $this->view('admin/orders', [
            'orders' => $orders,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function products() {
        $product = $this->model('Product');
        $category = $this->model('Category');
        
        $products = $this->getAllProducts();
        $categories = $category->getAll();
        
        $this->view('admin/products', [
            'products' => $products,
            'categories' => $categories,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function ingredients() {
        $this->view('admin/ingredients', [
            'ingredients' => [],
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function users() {
        $users = $this->getAllUsers();
        $stats = $this->getUserStats();
        
        $this->view('admin/users', [
            'users' => $users,
            'stats' => $stats,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }
    
    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = $_POST['order_id'];
            $status = $_POST['status'];
            
            // Mettre à jour le statut dans la base de données
            $database = new Database();
            $db = $database->getConnection();
            
            $query = "UPDATE commandes SET statut = :statut WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':statut', $status);
            $stmt->bindParam(':id', $order_id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Statut mis à jour']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour']);
            }
        }
        exit;
    }
    
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Gérer l'upload d'image
                $imageName = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $uploadDir = __DIR__ . '/../../public/images/';
                    $imageName = time() . '_' . basename($_FILES['image']['name']);
                    $uploadFile = $uploadDir . $imageName;
                    
                    // Vérifier le type de fichier
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $fileType = mime_content_type($_FILES['image']['tmp_name']);
                    
                    if (in_array($fileType, $allowedTypes) && $_FILES['image']['size'] < 5242880) { // 5MB max
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                            // Image uploadée avec succès
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'upload de l\'image']);
                            exit;
                        }
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Type de fichier non valide ou taille trop grande']);
                        exit;
                    }
                }
                
                // Insérer le produit dans la base de données
                $database = new Database();
                $db = $database->getConnection();
                
                $query = "INSERT INTO produits (nom, description, prix_s, prix_m, prix_l, image, categorie_id) 
                          VALUES (:nom, :description, :prix_s, :prix_m, :prix_l, :image, :categorie_id)";
                
                $stmt = $db->prepare($query);
                
                $stmt->bindParam(':nom', $_POST['nom']);
                $stmt->bindParam(':description', $_POST['description']);
                $stmt->bindParam(':prix_s', $_POST['prix_s']);
                $stmt->bindParam(':prix_m', $_POST['prix_m']);
                $stmt->bindParam(':prix_l', $_POST['prix_l']);
                $stmt->bindParam(':image', $imageName);
                $stmt->bindParam(':categorie_id', $_POST['categorie_id']);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Produit ajouté avec succès']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'ajout du produit']);
                }
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Erreur: ' . $e->getMessage()]);
            }
        }
        exit;
    }
    
    public function updateProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $product_id = $_POST['product_id'];
                
                // Gérer l'upload d'image si une nouvelle image est fournie
                $imageName = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $uploadDir = __DIR__ . '/../../public/images/';
                    $imageName = time() . '_' . basename($_FILES['image']['name']);
                    $uploadFile = $uploadDir . $imageName;
                    
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $fileType = mime_content_type($_FILES['image']['tmp_name']);
                    
                    if (in_array($fileType, $allowedTypes) && $_FILES['image']['size'] < 5242880) {
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                            // Image uploadée avec succès
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'upload de l\'image']);
                            exit;
                        }
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Type de fichier non valide ou taille trop grande']);
                        exit;
                    }
                }
                
                $database = new Database();
                $db = $database->getConnection();
                
                // Mettre à jour le produit
                if ($imageName) {
                    $query = "UPDATE produits SET nom = :nom, description = :description, prix_s = :prix_s, prix_m = :prix_m, prix_l = :prix_l, image = :image, categorie_id = :categorie_id WHERE id = :id";
                } else {
                    $query = "UPDATE produits SET nom = :nom, description = :description, prix_s = :prix_s, prix_m = :prix_m, prix_l = :prix_l, categorie_id = :categorie_id WHERE id = :id";
                }
                
                $stmt = $db->prepare($query);
                
                $stmt->bindParam(':nom', $_POST['nom']);
                $stmt->bindParam(':description', $_POST['description']);
                $stmt->bindParam(':prix_s', $_POST['prix_s']);
                $stmt->bindParam(':prix_m', $_POST['prix_m']);
                $stmt->bindParam(':prix_l', $_POST['prix_l']);
                $stmt->bindParam(':categorie_id', $_POST['categorie_id']);
                $stmt->bindParam(':id', $product_id);
                
                if ($imageName) {
                    $stmt->bindParam(':image', $imageName);
                }
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Produit mis à jour avec succès']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour du produit']);
                }
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Erreur: ' . $e->getMessage()]);
            }
        }
        exit;
    }
    
    public function toggleProductAvailability() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $disponible = $_POST['disponible'];
            
            $database = new Database();
            $db = $database->getConnection();
            
            $query = "UPDATE produits SET disponible = :disponible WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':disponible', $disponible);
            $stmt->bindParam(':id', $product_id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Disponibilité mise à jour']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour de la disponibilité']);
            }
        }
        exit;
    }
    
    public function deleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            
            $database = new Database();
            $db = $database->getConnection();
            
            $query = "DELETE FROM produits WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $product_id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Produit supprimé avec succès']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression']);
            }
        }
        exit;
    }
    
    private function getRecentOrders() {
        $query = "SELECT o.*, u.nom, u.prenom 
                  FROM commandes o
                  LEFT JOIN users u ON o.user_id = u.id
                  ORDER BY o.date_commande DESC
                  LIMIT 5";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getAllOrders() {
        $query = "SELECT o.*, u.nom, u.prenom, u.email
                  FROM commandes o
                  LEFT JOIN users u ON o.user_id = u.id
                  ORDER BY o.date_commande DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getAllProducts() {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT p.*, c.nom as category_name
                  FROM produits p
                  LEFT JOIN categories c ON p.categorie_id = c.id
                  ORDER BY p.nom";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getStats() {
        $stats = [];
        
        // Total des commandes
        $query = "SELECT COUNT(*) as total FROM commandes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_orders'] = $result['total'];
        
        // Total des produits
        $query = "SELECT COUNT(*) as total FROM produits";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_products'] = $result['total'];
        
        // Total des utilisateurs
        $query = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_users'] = $result['total'];
        
        // Commandes en attente
        $query = "SELECT COUNT(*) as pending FROM commandes WHERE statut = 'en_attente'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['pending_orders'] = $result['pending'];
        
        return $stats;
    }
    
    private function getAllUsers() {
        $query = "SELECT id, nom, prenom, email, telephone, role, date_creation 
                  FROM users 
                  ORDER BY date_creation DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getUserStats() {
        $stats = [];
        
        // Total des utilisateurs
        $query = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_users'] = $result['total'];
        
        // Total des clients
        $query = "SELECT COUNT(*) as total FROM users WHERE role = 'client'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_clients'] = $result['total'];
        
        // Nouveaux utilisateurs ce mois
        $query = "SELECT COUNT(*) as total FROM users 
                  WHERE MONTH(date_creation) = MONTH(CURRENT_DATE) 
                  AND YEAR(date_creation) = YEAR(CURRENT_DATE)";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['new_users_this_month'] = $result['total'];
        
        return $stats;
    }
}
?>
