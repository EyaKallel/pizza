<?php
class DoughType
{
    private $conn;

    public $id;
    public $name;
    public $price_modifier;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        try {
            $query = "SELECT id, nom as name, prix_supplementaire as price_modifier FROM types_pate ORDER BY nom";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getById($id)
    {
        $allDoughTypes = $this->getAll();

        foreach ($allDoughTypes as $doughType) {
            if ($doughType['id'] == $id) {
                return $doughType;
            }
        }

        return null;
    }
}
?>