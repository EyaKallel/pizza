<?php
class PizzaSize {
    private $conn;

    public $id;
    public $name;
    public $price_modifier;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        // Retourner les tailles fixes car elles sont stockées comme ENUM dans la base
        return [
            [
                'id' => 'S',
                'name' => 'Small',
                'price_modifier' => 1.0
            ],
            [
                'id' => 'M', 
                'name' => 'Medium',
                'price_modifier' => 1.3
            ],
            [
                'id' => 'L',
                'name' => 'Large', 
                'price_modifier' => 1.6
            ]
        ];
    }

    public function getById($id) {
        $sizes = $this->getAll();
        
        foreach ($sizes as $size) {
            if ($size['id'] === $id) {
                return $size;
            }
        }
        
        return null;
    }
}
?>
