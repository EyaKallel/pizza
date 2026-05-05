-- Base de données Smart Pizzeria Tunisie avec prix en TND

-- Création des tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    role ENUM('client', 'admin') DEFAULT 'client',
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix_small DECIMAL(5,2) NOT NULL,
    prix_medium DECIMAL(5,2) NOT NULL,
    prix_large DECIMAL(5,2) NOT NULL,
    image VARCHAR(255),
    categorie_id INT,
    disponible TINYINT(1) DEFAULT 1,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id)
);

CREATE TABLE ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix_supplementaire DECIMAL(5,2) DEFAULT 0.00,
    disponible TINYINT(1) DEFAULT 1
);

CREATE TABLE types_pate (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix_supplementaire DECIMAL(5,2) DEFAULT 0.00
);

CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type_livraison ENUM('livraison', 'sur_place') NOT NULL DEFAULT 'livraison',
    adresse_livraison TEXT,
    telephone VARCHAR(20) NOT NULL,
    note_client TEXT,
    statut ENUM('en_attente','confirmée','en_livraison','livrée','annulée') DEFAULT 'en_attente',
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE commande_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    produit_id INT,
    taille ENUM('S', 'M', 'L') NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(5,2) NOT NULL,
    est_personnalisee TINYINT(1) DEFAULT 0,
    FOREIGN KEY (commande_id) REFERENCES commandes(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

CREATE TABLE pizza_personnalisee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    detail_id INT NOT NULL,
    type_pate_id INT NOT NULL,
    FOREIGN KEY (detail_id) REFERENCES commande_details(id),
    FOREIGN KEY (type_pate_id) REFERENCES types_pate(id)
);

CREATE TABLE pizza_personnalisee_ingredients (
    pizza_perso_id INT NOT NULL,
    ingredient_id INT NOT NULL,
    PRIMARY KEY (pizza_perso_id, ingredient_id),
    FOREIGN KEY (pizza_perso_id) REFERENCES pizza_personnalisee(id),
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id)
);

-- Insertion des données initiales avec prix en TND

-- Catégories
INSERT INTO categories (nom) VALUES 
('Pizza Classique'),
('Pizza Végétarienne'),
('Pizza Spéciale'),
('Pizza Personnalisée');

-- Ingrédients avec prix en TND (1€ ≈ 3.3 TND)
INSERT INTO ingredients (nom, prix_supplementaire) VALUES
('Mozzarella', 5.00),
('Jambon', 6.50),
('Champignons', 3.30),
('Poivrons', 2.60),
('Olives', 2.30),
('Thon', 6.50),
('Pepperoni', 8.20),
('Oignons', 1.65),
('Tomates cerises', 3.30),
('Basilic', 1.65);

-- Types de pâte avec prix en TND
INSERT INTO types_pate (nom, prix_supplementaire) VALUES
('Classique', 0.00),
('Fine', 0.00),
('Épaisse', 3.30),
('Fromage farci', 8.20);

-- Produits avec prix en TND
INSERT INTO produits (nom, description, prix_small, prix_medium, prix_large, categorie_id) VALUES
('Margherita', 'Sauce tomate, mozzarella, basilic', 12.00, 15.50, 19.00, 1),
('Reine', 'Sauce tomate, mozzarella, jambon, champignons', 15.00, 19.50, 24.00, 1),
('Pepperoni', 'Sauce tomate, mozzarella, pepperoni', 16.00, 20.50, 25.00, 1),
('Végétarienne', 'Sauce tomate, mozzarella, poivrons, champignons, olives', 14.00, 18.00, 22.00, 2),
('Quatre Fromages', 'Sauce tomate, mozzarella, chèvre, bleu, parmesan', 18.00, 23.00, 28.00, 3),
('Tunisienne', 'Sauce tomate, mozzarella, merguez, poivrons, olives', 17.00, 22.00, 27.00, 3);

-- Compte admin (email: admin@smartpizzaria.tn, mot de passe: admin123)
INSERT INTO users (nom, prenom, email, mot_de_passe, telephone, adresse, role) VALUES
('Admin', 'Smart', 'admin@smartpizzaria.tn', MD5('admin123'), '74000000', 'Sfax Centre', 'admin');

-- Compte client test (email: mohamed@gmail.com, mot de passe: client123)
INSERT INTO users (nom, prenom, email, mot_de_passe, telephone, adresse) VALUES
('Ben Ali', 'Mohamed', 'mohamed@gmail.com', MD5('client123'), '71234567', 'Avenue Habib Bourguiba, Sfax');
