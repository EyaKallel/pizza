<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>Smart Pizzeria</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php?url=home">Accueil</a></li>
                <li><a href="index.php?url=menu" class="active">Menu</a></li>
                <li><a href="index.php?url=composer">Composer votre pizza</a></li>
                <li><a href="index.php?url=cart">Panier</a></li>
                <?php if ($user_logged_in): ?>
                    <li><a href="index.php?url=profile">Profil</a></li>
                    <li><a href="index.php?url=auth/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="index.php?url=auth/login">Connexion</a></li>
                    <li><a href="index.php?url=auth/register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <header class="page-header">
        <div class="container">
            <h1>Notre Menu</h1>
            <p>Découvrez toutes nos délicieuses pizzas</p>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php foreach ($categories as $category): ?>
                <section class="category-section">
                    <h2><?php echo $category['nom']; ?></h2>
                    
                    <div class="pizza-grid">
                        <?php if (isset($products_by_category[$category['id']]) && !empty($products_by_category[$category['id']])): ?>
                            <?php foreach ($products_by_category[$category['id']] as $product): ?>
                                <div class="pizza-card">
                                    <div class="pizza-image">
                                        <img src="../public/images/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>">
                                    </div>
                                    <div class="pizza-info">
                                        <h3><?php echo $product['nom']; ?></h3>
                                        <p class="pizza-description"><?php echo $product['description']; ?></p>
                                        <div class="pizza-price">
                                            S: <?php echo number_format($product['prix_s'], 2); ?> € | 
                                            M: <?php echo number_format($product['prix_m'], 2); ?> € | 
                                            L: <?php echo number_format($product['prix_l'], 2); ?> €
                                        </div>
                                        
                                        <?php if ($user_logged_in): ?>
                                            <form action="index.php?url=menu/addToCart" method="POST" class="add-to-cart-form">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <div class="quantity-selector">
                                                    <label for="size_<?php echo $product['id']; ?>">Taille:</label>
                                                    <select name="size" id="size_<?php echo $product['id']; ?>">
                                                        <option value="S">Petite (<?php echo number_format($product['prix_s'], 2); ?> €)</option>
                                                        <option value="M" selected>Moyenne (<?php echo number_format($product['prix_m'], 2); ?> €)</option>
                                                        <option value="L">Grande (<?php echo number_format($product['prix_l'], 2); ?> €)</option>
                                                    </select>
                                                </div>
                                                <div class="quantity-selector">
                                                    <label for="quantity_<?php echo $product['id']; ?>">Quantité:</label>
                                                    <select name="quantity" id="quantity_<?php echo $product['id']; ?>">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                                            </form>
                                        <?php else: ?>
                                            <a href="index.php?url=auth/login" class="btn btn-secondary">Connectez-vous pour commander</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun produit dans cette catégorie pour le moment.</p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Smart Pizzeria</h3>
                    <p>Votre pizzeria de confiance avec des pizzas personnalisées.</p>
                </div>
                <div class="footer-section">
                    <h4>Liens utiles</h4>
                    <ul>
                        <li><a href="index.php?url=menu">Menu</a></li>
                        <li><a href="index.php?url=composer">Composer votre pizza</a></li>
                        <li><a href="index.php?url=contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Téléphone: 01 23 45 67 89</p>
                    <p>Email: contact@smartpizzeria.fr</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Smart Pizzeria. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
