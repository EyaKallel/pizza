<?php
// Appeler Database pour démarrer les sessions automatiquement
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

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
        $ingredient = $this->model('Ingredient');
        $ingredients = $ingredient->getAllAdmin();

        $this->view('admin/ingredients', [
            'ingredients' => $ingredients,
            'user_logged_in' => $this->isLoggedIn()
        ]);
    }

    public function addIngredient() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $nom = trim($_POST['nom'] ?? '');
                $prix_supplementaire = isset($_POST['prix_supplementaire']) ? (float)$_POST['prix_supplementaire'] : 0.00;
                $disponible = isset($_POST['disponible']) ? (int)$_POST['disponible'] : 1;

                if ($nom === '') {
                    throw new Exception("Le nom de l'ingrédient est requis");
                }

                $database = new Database();
                $db = $database->getConnection();

                $query = "INSERT INTO ingredients (nom, prix_supplementaire, disponible)
                          VALUES (:nom, :prix_supplementaire, :disponible)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prix_supplementaire', $prix_supplementaire);
                $stmt->bindParam(':disponible', $disponible);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Ingrédient ajouté avec succès']);
                } else {
                    echo json_encode(['success' => false, 'error' => "Erreur lors de l'ajout de l'ingrédient"]);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Erreur: ' . $e->getMessage()]);
            }
        }
        exit;
    }

    public function updateIngredient() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $ingredient_id = isset($_POST['ingredient_id']) ? (int)$_POST['ingredient_id'] : 0;
                $nom = trim($_POST['nom'] ?? '');
                $prix_supplementaire = isset($_POST['prix_supplementaire']) ? (float)$_POST['prix_supplementaire'] : 0.00;
                $disponible = isset($_POST['disponible']) ? (int)$_POST['disponible'] : 1;

                if ($ingredient_id <= 0) {
                    throw new Exception("ID ingrédient invalide");
                }
                if ($nom === '') {
                    throw new Exception("Le nom de l'ingrédient est requis");
                }

                $database = new Database();
                $db = $database->getConnection();

                $query = "UPDATE ingredients
                          SET nom = :nom,
                              prix_supplementaire = :prix_supplementaire,
                              disponible = :disponible
                          WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prix_supplementaire', $prix_supplementaire);
                $stmt->bindParam(':disponible', $disponible);
                $stmt->bindParam(':id', $ingredient_id);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Ingrédient mis à jour avec succès']);
                } else {
                    echo json_encode(['success' => false, 'error' => "Erreur lors de la mise à jour de l'ingrédient"]);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Erreur: ' . $e->getMessage()]);
            }
        }
        exit;
    }

    public function deleteIngredient() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $ingredient_id = isset($_POST['ingredient_id']) ? (int)$_POST['ingredient_id'] : 0;
                if ($ingredient_id <= 0) {
                    throw new Exception("ID ingrédient invalide");
                }

                $database = new Database();
                $db = $database->getConnection();

                // Dépendances potentielles (selon la version de BD du projet)
                $tablesToClean = ['custom_pizza_ingredients', 'pizza_personnalisee_ingredients'];
                foreach ($tablesToClean as $tableName) {
                    $checkQuery = "SELECT COUNT(*) AS cnt
                                   FROM information_schema.tables
                                   WHERE table_schema = DATABASE() AND table_name = :table_name";
                    $checkStmt = $db->prepare($checkQuery);
                    $checkStmt->bindParam(':table_name', $tableName);
                    $checkStmt->execute();
                    $exists = (int)$checkStmt->fetch(PDO::FETCH_ASSOC)['cnt'] > 0;

                    if ($exists) {
                        // Nettoyage des lignes liées à l'ingrédient
                        $delQuery = "DELETE FROM {$tableName} WHERE ingredient_id = :ingredient_id";
                        $delStmt = $db->prepare($delQuery);
                        $delStmt->bindParam(':ingredient_id', $ingredient_id);
                        $delStmt->execute();
                    }
                }

                $query = "DELETE FROM ingredients WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $ingredient_id);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Ingrédient supprimé avec succès']);
                } else {
                    echo json_encode(['success' => false, 'error' => "Erreur lors de la suppression de l'ingrédient"]);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Erreur: ' . $e->getMessage()]);
            }
        }
        exit;
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
        $query = "SELECT o.*, u.nom, u.prenom, u.email, u.telephone, u.adresse
                  FROM commandes o
                  LEFT JOIN users u ON o.user_id = u.id
                  ORDER BY o.date_commande DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer les détails complets pour chaque commande
        foreach ($orders as &$order) {
            // Calculer le total
            if (!isset($order['total']) || $order['total'] === null) {
                $order['total'] = $this->calculateOrderTotal($order['id']);
            }
            
            // Récupérer les détails des produits
            $order['details'] = $this->getOrderDetails($order['id']);
            
            // Formater le type de livraison
            $order['type_livraison_texte'] = $this->formatDeliveryType($order['type_livraison']);
            
            // Formater le statut
            $order['statut_texte'] = $this->formatOrderStatus($order['statut']);
        }
        
        return $orders;
    }
    
    private function getOrderDetails($order_id) {
        $query = "SELECT cd.*, p.nom as produit_nom, p.image as produit_image,
                          CASE 
                              WHEN cd.est_personnalisee = 1 THEN 'Pizza Personnalisée'
                              ELSE p.nom
                          END as affichage_nom
                  FROM commande_details cd
                  LEFT JOIN produits p ON cd.produit_id = p.id
                  WHERE cd.commande_id = :order_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function formatDeliveryType($type) {
        $types = [
            'livraison' => 'Livraison',
            'sur_place' => 'Sur place'
        ];
        return $types[$type] ?? $type;
    }
    
    private function formatOrderStatus($status) {
        $statuses = [
            'en_attente' => 'En attente',
            'confirmée' => 'Confirmée',
            'en_livraison' => 'En livraison',
            'livrée' => 'Livrée',
            'annulée' => 'Annulée'
        ];
        return $statuses[$status] ?? $status;
    }
    
    private function calculateOrderTotal($order_id) {
        try {
            // Vérifier d'abord si la colonne prix_unitaire existe
            $checkQuery = "SHOW COLUMNS FROM commande_details LIKE 'prix_unitaire'";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->execute();
            $columnExists = $checkStmt->rowCount() > 0;
            
            if ($columnExists) {
                // Utiliser prix_unitaire si la colonne existe
                $query = "SELECT cd.prix_unitaire, cd.quantite
                          FROM commande_details cd
                          WHERE cd.commande_id = :order_id";
                
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->execute();
                
                $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $total = 0;
                
                foreach ($details as $detail) {
                    $total += $detail['prix_unitaire'] * $detail['quantite'];
                }
                
                return $total;
            } else {
                // Calculer le total à partir des produits si prix_unitaire n'existe pas
                $query = "SELECT cd.produit_id, cd.taille, cd.quantite, p.prix_small, p.prix_medium, p.prix_large
                          FROM commande_details cd
                          LEFT JOIN produits p ON cd.produit_id = p.id
                          WHERE cd.commande_id = :order_id";
                
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->execute();
                
                $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $total = 0;
                
                foreach ($details as $detail) {
                    $price = 0;
                    switch ($detail['taille']) {
                        case 'S':
                            $price = $detail['prix_small'];
                            break;
                        case 'M':
                            $price = $detail['prix_medium'];
                            break;
                        case 'L':
                            $price = $detail['prix_large'];
                            break;
                        default:
                            $price = $detail['prix_small'];
                    }
                    $total += $price * $detail['quantite'];
                }
                
                return $total;
            }
        } catch (Exception $e) {
            // En cas d'erreur, retourner 0 pour éviter le crash
            return 0;
        }
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
    $query = "SELECT u.id, u.nom, u.prenom, u.email, u.telephone, u.adresse,
                     u.role, u.date_inscription,
                     COALESCE(u.blocked, 0) as blocked,
                     COUNT(c.id) as order_count
              FROM users u
              LEFT JOIN commandes c ON c.user_id = u.id
              GROUP BY u.id
              ORDER BY u.date_inscription DESC";
    
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

private function getUserStats() {
    $stats = [];

    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'client'");
    $stmt->execute();
    $stats['total_clients'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE blocked = 1");
    $stmt->execute();
    $stats['blocked_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users 
                                 WHERE MONTH(date_inscription) = MONTH(CURRENT_DATE) 
                                 AND YEAR(date_inscription) = YEAR(CURRENT_DATE)");
    $stmt->execute();
    $stats['new_users_this_month'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    return $stats;
}
}
?>
