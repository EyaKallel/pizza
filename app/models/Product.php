<?php
class Product {
    private $conn;
    private $table_name = "produits";

    public $id;
    public $nom;
    public $description;
    public $prix_s;
    public $prix_m;
    public $prix_l;
    public $image;
    public $categorie_id;
    public $disponible;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT p.*, c.nom as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.categorie_id = c.id
                  WHERE p.disponible = 1
                  ORDER BY p.nom";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT p.*, c.nom as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.categorie_id = c.id
                  WHERE p.id = :id AND p.disponible = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCategory($category_id) {
        $query = "SELECT p.*, c.nom as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.categorie_id = c.id
                  WHERE p.categorie_id = :category_id AND p.disponible = 1
                  ORDER BY p.nom";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeatured() {
        $query = "SELECT p.*, c.nom as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.categorie_id = c.id
                  WHERE p.disponible = 1 
                  ORDER BY RAND() 
                  LIMIT 6";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPriceBySize($product_id, $size) {
        $query = "SELECT prix_s, prix_m, prix_l 
                  FROM " . $this->table_name . " 
                  WHERE id = :id AND disponible = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $product_id);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            switch ($size) {
                case 'S': return $product['prix_s'];
                case 'M': return $product['prix_m'];
                case 'L': return $product['prix_l'];
                default: return $product['prix_m'];
            }
        }
        return 0;
    }
}
?>
