# Smart Pizzeria - E-commerce PHP MVC

Un site e-commerce pour la vente de pizzas avec une fonctionnalité unique de "Composer votre pizza".

## 📋 Description du projet

Smart Pizzeria est une application web complète de vente de pizzas en ligne développée en PHP avec une architecture MVC (Modèle-Vue-Contrôleur) et PDO pour la gestion de base de données.

### 🌟 Fonctionnalités principales

#### Front Office (Client)
- **Authentification obligatoire** : Inscription et connexion des utilisateurs
- **Page d'accueil** : Présentation de la pizzeria avec boutons "Commander" et "Composer votre pizza"
- **Menu dynamique** : Affichage des pizzas depuis la base de données MySQL avec catégories
- **Composer votre pizza** (BONUS) : 
  - Choix de la taille (S/M/L)
  - Sélection des ingrédients avec prix automatique
  - Choix du type de pâte
  - Calcul du prix en temps réel
- **Panier** : Gestion du panier avec sessions PHP
- **Commande** : Processus de validation avec adresse et téléphone
- **Profil utilisateur** : Modification des informations et historique des commandes

#### Back Office (Administration)
- **Tableau de bord** : Statistiques des commandes et chiffre d'affaires
- **Gestion des commandes** : Modification du statut des commandes
- **Gestion des produits** : Ajout, modification et suppression de pizzas
- **Accès restreint** : Réservé à l'administrateur

## 🛠️ Stack technique

- **Backend** : PHP 7.4+
- **Architecture** : MVC (Modèle-Vue-Contrôleur)
- **Base de données** : MySQL avec PDO
- **Frontend** : HTML5, CSS3, JavaScript
- **Sessions** : Gestion des sessions PHP pour l'authentification et le panier

## 📁 Structure du projet

```
ProjetPizza2/
├── app/
│   ├── controllers/     # Contrôleurs MVC
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── MenuController.php
│   │   ├── ComposerController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── ProfileController.php
│   │   └── AdminController.php
│   ├── models/         # Modèles MVC
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── PizzaSize.php
│   │   ├── DoughType.php
│   │   ├── Ingredient.php
│   │   └── Order.php
│   └── views/          # Vues MVC
│       ├── auth/
│       ├── home/
│       ├── menu/
│       ├── composer/
│       ├── cart/
│       ├── checkout/
│       ├── profile/
│       └── admin/
├── config/
│   └── database.php    # Configuration de la base de données
├── core/
│   ├── Controller.php  # Contrôleur de base
│   └── App.php        # Gestionnaire d'application
├── public/
│   ├── css/
│   │   ├── style.css  # Styles principaux
│   │   └── admin.css  # Styles administration
│   ├── js/            # Fichiers JavaScript
│   └── images/        # Images des produits
├── database_setup.sql # Script de création de la base de données
├── index.php         # Point d'entrée de l'application
└── README.md         # Ce fichier
```

## 🚀 Installation et configuration

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache ou Nginx)
- XAMPP (pour développement local)

### Étapes d'installation

1. **Cloner ou télécharger le projet**
   ```bash
   cd /xampp/htdocs/
   # Placer le dossier ProjetPizza2 ici
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin
   - Créer une nouvelle base de données nommée `smart_pizzeria`
   - Importer le fichier `database_setup.sql`

3. **Configurer la base de données**
   - Ouvrir le fichier `config/database.php`
   - Vérifier les paramètres de connexion :
   ```php
   private $host = 'localhost';
   private $db_name = 'smart_pizzeria';
   private $username = 'root';
   private $password = '';
   ```

4. **Configurer le serveur web**
   - Démarrer Apache et MySQL via XAMPP
   - Naviguer vers `http://localhost/ProjetPizza2/`

5. **Créer le compte administrateur**
   - S'inscrire sur le site avec le premier compte (ID = 1)
   - Ce compte aura automatiquement accès à l'administration

### Configuration des URL réécrites (optionnel)

Pour des URLs plus propres, créer un fichier `.htaccess` à la racine :

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## 👤 Utilisation

### Client
1. **Créer un compte** : Inscription avec email, nom, prénom
2. **Se connecter** : Accès au menu et fonctionnalités
3. **Parcourir le menu** : Voir les pizzas par catégorie
4. **Composer une pizza** : Personnaliser sa pizza
5. **Gérer le panier** : Ajouter/modifier/supprimer des articles
6. **Passer commande** : Confirmer avec adresse de livraison
7. **Voir l'historique** : Consulter les commandes passées

### Administrateur
1. **Accès admin** : Naviguer vers `index.php?url=admin`
2. **Tableau de bord** : Voir les statistiques
3. **Gérer les commandes** : Modifier les statuts
4. **Gérer les produits** : Ajouter/supprimer des pizzas

## 🎯 Fonctionnalités spéciales

### Composer votre pizza (BONUS)
Cette fonctionnalité permet aux utilisateurs de :
- Choisir la taille (Petite, Moyenne, Grande) avec modificateurs de prix
- Sélectionner le type de pâte (Fine, Épaisse, Sans gluten)
- Ajouter des ingrédients individuels avec prix
- Voir le prix total calculé en temps réel
- Ajouter la pizza personnalisée au panier

### Gestion des sessions
- Authentification sécurisée avec hashage des mots de passe
- Panier persistant pendant la session utilisateur
- Protection des pages nécessitant une connexion

## 🔧 Personnalisation

### Ajouter de nouvelles pizzas
1. Via l'interface d'administration
2. Directement dans la base de données
3. Ajouter les images correspondantes dans `public/images/`

### Modifier les ingrédients
- Modifier la table `ingredients` dans la base de données
- Les prix sont automatiquement calculés dans le composeur

### Personnaliser le design
- Modifier `public/css/style.css` pour le style général
- Modifier `public/css/admin.css` pour l'administration

## 🐛 Dépannage

### Problèmes courants
1. **Erreur de connexion à la base de données**
   - Vérifier les identifiants dans `config/database.php`
   - S'assurer que MySQL est démarré

2. **Pages blanches**
   - Activer l'affichage des erreurs PHP
   - Vérifier les permissions des fichiers

3. **URL non fonctionnelles**
   - Vérifier que le module rewrite Apache est activé
   - Utiliser les URLs complètes si nécessaire

## 📝 Notes de développement

### Architecture MVC
- **Modèles** : Gestion des données et interaction avec la base de données
- **Vues** : Affichage des données et interface utilisateur
- **Contrôleurs** : Logique métier et coordination entre modèles et vues

### Sécurité
- Utilisation de PDO pour prévenir les injections SQL
- Hashage des mots de passe avec `password_hash()`
- Protection contre les attaques XSS avec `htmlspecialchars()`

### Bonnes pratiques
- Code organisé et commenté
- Séparation des responsabilités
- Utilisation des sessions PHP pour la persistance

## 📄 Licence

Ce projet est créé à des fins éducatives et peut être librement modifié et distribué.

---

**Développé avec ❤️ pour Smart Pizzeria**
