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

    /**
     * Base europe (database_final.sql) : prix_s, prix_m, prix_l
     * Base Tunisie (database_tunisia.sql) : prix_small, prix_medium, prix_large
     */
    private function normalizePrices(?array $row): ?array {
        if ($row === null || $row === false) {
            return null;
        }
        $hasEuro = isset($row['prix_s']) && $row['prix_s'] !== '' && $row['prix_s'] !== null;
        if (!$hasEuro && isset($row['prix_small'])) {
            $row['prix_s'] = $row['prix_small'];
            $row['prix_m'] = $row['prix_medium'] ?? null;
            $row['prix_l'] = $row['prix_large'] ?? null;
        }
        return $row;
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    private function normalizeRows(array $rows): array {
        return array_map(function ($r) {
            return $this->normalizePrices($r);
        }, $rows);
    }

    public function getAll() {
        $query = "SELECT p.*, c.nom as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.categorie_id = c.id
                  WHERE p.disponible = 1
                  ORDER BY p.nom";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $this->normalizeRows($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getById($id) {
        $query = "SELECT p.*, c.nom as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.categorie_id = c.id
                  WHERE p.id = :id AND p.disponible = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $this->normalizePrices($stmt->fetch(PDO::FETCH_ASSOC));
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

        return $this->normalizeRows($stmt->fetchAll(PDO::FETCH_ASSOC));
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

        return $this->normalizeRows($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getPriceBySize($product_id, $size) {
        $product = $this->getById($product_id);
        if (!$product || !isset($product['prix_s'])) {
            return 0;
        }
        switch ($size) {
            case 'S':
                return $product['prix_s'];
            case 'M':
                return $product['prix_m'];
            case 'L':
                return $product['prix_l'];
            default:
                return $product['prix_m'];
        }
    }
}
?>
