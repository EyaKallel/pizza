<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $total_amount;
    public $status;
    public $delivery_address;
    public $phone;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET user_id=:user_id, total_amount=:total_amount, 
                      delivery_address=:delivery_address, phone=:phone";

        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
        $this->delivery_address = htmlspecialchars(strip_tags($this->delivery_address));
        $this->phone = htmlspecialchars(strip_tags($this->phone));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":delivery_address", $this->delivery_address);
        $stmt->bindParam(":phone", $this->phone);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function addOrderItems($order_id, $cart_items) {
        $query = "INSERT INTO order_items 
                  SET order_id=:order_id, product_id=:product_id, quantity=:quantity, 
                      price=:price, is_custom=:is_custom";

        foreach ($cart_items as $item) {
            $stmt = $this->conn->prepare($query);

            $product_id = $item['is_custom'] ? null : $item['id'];
            $is_custom = $item['is_custom'] ? 1 : 0;

            $stmt->bindParam(":order_id", $order_id);
            $stmt->bindParam(":product_id", $product_id);
            $stmt->bindParam(":quantity", $item['quantity']);
            $stmt->bindParam(":price", $item['price']);
            $stmt->bindParam(":is_custom", $is_custom);

            $stmt->execute();

            // Si c'est une pizza personnalisée, ajouter les ingrédients
            if ($item['is_custom'] && isset($item['ingredients'])) {
                $order_item_id = $this->conn->lastInsertId();
                $this->addCustomPizzaIngredients($order_item_id, $item['ingredients']);
            }
        }

        return true;
    }

    private function addCustomPizzaIngredients($order_item_id, $ingredients) {
        $query = "INSERT INTO custom_pizza_ingredients 
                  SET order_item_id=:order_item_id, ingredient_id=:ingredient_id";

        foreach ($ingredients as $ingredient) {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":order_item_id", $order_item_id);
            $stmt->bindParam(":ingredient_id", $ingredient['id']);
            $stmt->execute();
        }
    }

    public function getUserOrders($user_id) {
        $query = "SELECT o.*, COUNT(ci.id) as item_count 
                  FROM commandes o
                  LEFT JOIN commande_details ci ON o.id = ci.commande_id
                  WHERE o.user_id = :user_id
                  GROUP BY o.id
                  ORDER BY o.date_commande DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($order_id) {
        $query = "SELECT o.*, u.nom, u.prenom, u.email
                  FROM commandes o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE o.id = :order_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($order_id) {
        $query = "SELECT ci.*, p.nom as product_name, p.image
                  FROM commande_details ci
                  LEFT JOIN produits p ON ci.produit_id = p.id
                  WHERE ci.commande_id = :order_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($order_id, $status) {
        $query = "UPDATE commandes SET statut = :statut WHERE id = :order_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":statut", $status);
        $stmt->bindParam(":order_id", $order_id);

        return $stmt->execute();
    }
}
?>
