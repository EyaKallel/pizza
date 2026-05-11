<?php
class Order {
    private $conn;
    private $table_commandes = 'commandes';
    private $table_details = 'commande_details';

    public $id;
    public $user_id;
    public $total_amount;
    public $frais_livraison;
    public $type_livraison;
    public $delivery_address;
    public $phone;
    public $note_client;

    /** @var array<string, bool>|null */
    private static $hasColCommandes = null;
    /** @var array<string, bool>|null */
    private static $hasColDetails = null;

    public function __construct($db) {
        $this->conn = $db;
    }

    //verifie si les colonnes existent
    private function loadColumnCache(): void {
        if (self::$hasColCommandes !== null) {
            return;
        }
        //stocker les colonnes
        self::$hasColCommandes = [];
        self::$hasColDetails = [];
        $stmt = $this->conn->query('SHOW COLUMNS FROM `' . $this->table_commandes . '`');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            self::$hasColCommandes[$row['Field']] = true;
        }
        $stmt = $this->conn->query('SHOW COLUMNS FROM `' . $this->table_details . '`');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            self::$hasColDetails[$row['Field']] = true;
        }
    }
    
    private function commandesHas(string $col): bool {
        $this->loadColumnCache();
        return !empty(self::$hasColCommandes[$col]);
    }

    private function detailsHas(string $col): bool {
        $this->loadColumnCache();
        return !empty(self::$hasColDetails[$col]);
    }

    /**
     * Taille S/M/L à partir du panier (menu ou composer).
     *
     * @param array<string, mixed> $item
     */
    //determiner la taille (small, medium, large) d'un item du panier en fonction de ses propriétés
    private function cartItemTaille(array $item): string {
        if (!empty($item['size']) && is_string($item['size']) && preg_match('/^[SML]$/', $item['size'])) {
            return $item['size'];
        }
        if (!empty($item['size']) && is_array($item['size'])) {
            if (!empty($item['size']['code']) && preg_match('/^[SML]$/', (string) $item['size']['code'])) {
                return (string) $item['size']['code'];
            }
            $name = isset($item['size']['name']) ? strtolower((string) $item['size']['name']) : '';
            if (strpos($name, 'petit') !== false || strpos($name, 'small') !== false) {
                return 'S';
            }
            if (strpos($name, 'grand') !== false || strpos($name, 'large') !== false) {
                return 'L';
            }
        }
        return 'M';
    }

    //creer commandee
    //bind value matestanesh execute besh yakraha , tkra  whdha
    public function create() {
        $this->loadColumnCache();

        $type = $this->type_livraison ?? 'livraison';
        if (!in_array($type, ['livraison', 'sur_place'], true)) {
            $type = 'livraison';
        }

        $fields = ['user_id', 'type_livraison', 'adresse_livraison', 'telephone', 'note_client'];
        $placeholders = [':user_id', ':type_livraison', ':adresse_livraison', ':telephone', ':note_client'];

        if ($this->commandesHas('total')) {
            $fields[] = 'total';
            $placeholders[] = ':total';
        }
        if ($this->commandesHas('frais_livraison')) {
            $fields[] = 'frais_livraison';
            $placeholders[] = ':frais_livraison';
        }

        $sql = 'INSERT INTO `' . $this->table_commandes . '` (`' . implode('`,`', $fields) . '`) VALUES (' . implode(',', $placeholders) . ')';
        $stmt = $this->conn->prepare($sql);

        $userId = (int) $this->user_id;
        $addr = htmlspecialchars(strip_tags((string) $this->delivery_address), ENT_QUOTES, 'UTF-8');
        $tel = htmlspecialchars(strip_tags((string) $this->phone), ENT_QUOTES, 'UTF-8');
        $note = htmlspecialchars(strip_tags((string) ($this->note_client ?? '')), ENT_QUOTES, 'UTF-8');
        $total = (float) ($this->total_amount ?? 0);
        $frais = (float) ($this->frais_livraison ?? 0);

        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':type_livraison', $type);
        $stmt->bindValue(':adresse_livraison', $addr);
        $stmt->bindValue(':telephone', $tel);
        $stmt->bindValue(':note_client', $note);
        if ($this->commandesHas('total')) {
            $stmt->bindValue(':total', $total);
        }
        if ($this->commandesHas('frais_livraison')) {
            $stmt->bindValue(':frais_livraison', $frais);
        }

        if ($stmt->execute()) {
            $this->id = (int) $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * @param array<string|int, array<string, mixed>> $cart_items
     */
    //ajouter les produits d'une commande dans table commande detail
    public function addOrderItems($order_id, $cart_items) {
        $this->loadColumnCache();
        $hasPrix = $this->detailsHas('prix_unitaire');

        foreach ($cart_items as $item) {
            $isCustom = !empty($item['is_custom']);
            $productId = $isCustom ? null : (int) $item['id'];
            $taille = $this->cartItemTaille($item);
            $quantite = (int) ($item['quantity'] ?? 1);
            $prixUnit = isset($item['price']) ? (float) $item['price'] : 0.0;

            if ($hasPrix) {
                $sql = 'INSERT INTO `' . $this->table_details . '` 
                    (`commande_id`, `produit_id`, `taille`, `quantite`, `prix_unitaire`, `est_personnalisee`)
                    VALUES (:commande_id, :produit_id, :taille, :quantite, :prix_unitaire, :est_personnalisee)';
            } else {
                $sql = 'INSERT INTO `' . $this->table_details . '` 
                    (`commande_id`, `produit_id`, `taille`, `quantite`, `est_personnalisee`)
                    VALUES (:commande_id, :produit_id, :taille, :quantite, :est_personnalisee)';
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':commande_id', (int) $order_id, PDO::PARAM_INT);
            $stmt->bindValue(':produit_id', $productId, $productId ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindValue(':taille', $taille);
            $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);
            $stmt->bindValue(':est_personnalisee', $isCustom ? 1 : 0, PDO::PARAM_INT);
            if ($hasPrix) {
                $stmt->bindValue(':prix_unitaire', $prixUnit);
            }

            if (!$stmt->execute()) {
                return false;
            }
        }

        return true;
    }

    //les commandes d'un users
    public function getUserOrders($user_id) {
        $this->loadColumnCache();
        $t = $this->table_details;
        $c = $this->table_commandes;

        $subCount = "(SELECT COUNT(*) FROM `{$t}` x WHERE x.commande_id = o.id)";
        if ($this->detailsHas('prix_unitaire')) {
            $subSum = "(SELECT COALESCE(SUM(x.quantite * x.prix_unitaire), 0) FROM `{$t}` x WHERE x.commande_id = o.id)";
        } else {
            $subSum = '0';
        }

        if ($this->commandesHas('total')) {
            $query = "SELECT o.*, {$subCount} AS item_count,
                COALESCE(NULLIF(o.total, 0), {$subSum}) AS total
                FROM `{$c}` o
                WHERE o.user_id = :user_id
                ORDER BY o.date_commande DESC";
        } else {
            $query = "SELECT o.*, {$subCount} AS item_count,
                {$subSum} AS total
                FROM `{$c}` o
                WHERE o.user_id = :user_id
                ORDER BY o.date_commande DESC";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //les détails d'une commande (avec une fetch)
    public function getOrderDetails($order_id) {
        $query = "SELECT o.*, u.nom, u.prenom, u.email
                  FROM {$this->table_commandes} o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE o.id = :order_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }

        $row['created_at'] = $row['date_commande'] ?? null;
        $row['delivery_address'] = $row['adresse_livraison'] ?? '';
        $row['phone'] = $row['telephone'] ?? '';
        $statut = $row['statut'] ?? 'en_attente';
        $row['status'] = $this->statutToStatusClass($statut);
        $row['statut'] = $statut;

        if (array_key_exists('total', $row) && $row['total'] !== null && $row['total'] !== '') {
            $row['total_amount'] = (float) $row['total'];
        } else {
            $row['total_amount'] = $this->sumOrderTotal((int) $order_id);
        }
        
        // Delivery fee
        if (array_key_exists('frais_livraison', $row) && $row['frais_livraison'] !== null && $row['frais_livraison'] !== '') {
            $row['delivery_fee'] = (float) $row['frais_livraison'];
        } else {
            $row['delivery_fee'] = 0.00;
        }

        return $row;
    }

    private function sumOrderTotal(int $order_id): float {
        if (!$this->detailsHas('prix_unitaire')) {
            return 0.0;
        }
        $q = "SELECT COALESCE(SUM(quantite * prix_unitaire), 0) AS t FROM {$this->table_details} WHERE commande_id = :id";
        $stmt = $this->conn->prepare($q);
        $stmt->bindValue(':id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) ($r['t'] ?? 0);
    }

    private function statutToStatusClass(string $statut): string {
        $map = [
            'en_attente' => 'pending',
            'confirmée' => 'preparing',
            'en_livraison' => 'preparing',
            'livrée' => 'delivered',
            'annulée' => 'cancelled',
            'en_cours' => 'preparing',
            'prete' => 'ready',
        ];
        return $map[$statut] ?? 'pending';
    }

    public function getOrderItems($order_id) {
        $query = "SELECT ci.*, p.nom AS product_name, p.image
                  FROM {$this->table_details} ci
                  LEFT JOIN produits p ON ci.produit_id = p.id
                  WHERE ci.commande_id = :order_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $row['quantity'] = (int) ($row['quantite'] ?? 1);
            if (isset($row['prix_unitaire'])) {
                $row['price'] = (float) $row['prix_unitaire'];
            } else {
                $row['price'] = 0.0;
            }
            $row['is_custom'] = !empty($row['est_personnalisee']);
        }
        unset($row);

        return $rows;
    }

    public function updateStatus($order_id, $status) {
        $query = "UPDATE {$this->table_commandes} SET statut = :statut WHERE id = :order_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $status);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
