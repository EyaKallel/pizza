<?php
class Ingredient {
    private $conn;
    private $table_name = "ingredients";

    public $id;
    public $nom;
    public $prix_supplementaire;
    public $disponible;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE disponible = 1 ORDER BY nom";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByIds($ids) {
        if (empty($ids)) {
            return [];
        }

        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $query = "SELECT * FROM " . $this->table_name . " WHERE id IN ($placeholders) AND disponible = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
