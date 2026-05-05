<?php
class DoughType {
    private $conn;

    public $id;
    public $name;
    public $price_modifier;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        // Retourner les types de pâte codés en dur car la table n'existe pas
        return [
            [
                'id' => 1,
                'name' => 'Pâte Fine',
                'price_modifier' => 1.0
            ],
            [
                'id' => 2,
                'name' => 'Pâte Épaisse',
                'price_modifier' => 1.2
            ],
            [
                'id' => 3,
                'name' => 'Pâte à Fromage',
                'price_modifier' => 1.3
            ],
            [
                'id' => 4,
                'name' => 'Pâte Complète',
                'price_modifier' => 1.1
            ]
        ];
    }

    public function getById($id) {
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
