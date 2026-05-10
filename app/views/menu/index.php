<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu — Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/modern-pizza.css">
    <link rel="stylesheet" href="/ProjetPizza2/public/css/client-site.css">
</head>
<body class="menu-page">
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>Smart Pizzeria</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/ProjetPizza2/index.php?url=home">Accueil</a></li>
                <li><a href="/ProjetPizza2/index.php?url=menu" class="active">Menu</a></li>
                <li><a href="/ProjetPizza2/index.php?url=composer">Composer votre pizza</a></li>
                <li><a href="/ProjetPizza2/index.php?url=cart">Panier</a></li>
                <?php if ($user_logged_in): ?>
                    <li><a href="/ProjetPizza2/index.php?url=profile">Profil</a></li>
                    <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/ProjetPizza2/index.php?url=auth/login">Connexion</a></li>
                    <li><a href="/ProjetPizza2/index.php?url=auth/register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <header class="page-header">
        <div class="container">
            <h1>Notre menu</h1>
            <p>Découvrez toutes nos pizzas, préparées avec passion</p>
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

            <?php if (empty($categories)): ?>
                <div class="menu-empty-hint" role="status">
                    <p><strong>Aucune catégorie</strong> dans la base de données. Importez <code>database_final.sql</code> ou vérifiez la connexion MySQL (<code>smart_pizzaria</code>).</p>
                </div>
            <?php endif; ?>

            <?php foreach ($categories as $category): ?>
                <?php $catId = (int) $category['id']; ?>
                <section class="category-section">
                    <h2><?php echo htmlspecialchars($category['nom']); ?></h2>

                    <div class="pizza-grid">
                        <?php if (!empty($products_by_category[$catId])): ?>
                            <?php foreach ($products_by_category[$catId] as $product): ?>
                                <div class="pizza-card">
                                    <div class="pizza-image">
                                        <?php
                                            $rawImage = (string) ($product['image'] ?? '');
                                            $productName = (string) ($product['nom'] ?? '');
                                            $publicImage = 'pizza-placeholder.png';

                                            $imageDir = realpath(__DIR__ . '/../../../public/images');
                                            $imagePath = $imageDir && $rawImage !== '' ? $imageDir . DIRECTORY_SEPARATOR . $rawImage : null;
                                            if ($imagePath && is_file($imagePath)) {
                                                $publicImage = $rawImage;
                                            } else {
                                                $n = strtolower($productName);
                                                if (strpos($n, 'pepperoni') !== false) {
                                                    $publicImage = 'Pepperoni.jpg';
                                                } elseif (strpos($n, 'margherita') !== false || strpos($n, 'margarita') !== false) {
                                                    $publicImage = 'margherita.webp';
                                                } elseif (strpos($n, 'fromage') !== false) {
                                                    $publicImage = '4 fromage.jpg';
                                                } elseif (strpos($n, 'thon') !== false || strpos($n, 'oignon') !== false) {
                                                    $publicImage = 'Thon Oignons.jpg';
                                                } elseif (strpos($n, 'vég') !== false || strpos($n, 'veg') !== false || strpos($n, 'végétar') !== false || strpos($n, 'vegetar') !== false) {
                                                    $publicImage = 'Végétarienne.jpg';
                                                }
                                            }
                                        ?>
                                        <img src="/ProjetPizza2/public/images/<?php echo rawurlencode($publicImage); ?>" alt="<?php echo htmlspecialchars($productName, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.onerror=null;this.src='/ProjetPizza2/public/images/pizza-placeholder.png';">
                                    </div>
                                    <div class="pizza-info">
                                        <h3><?php echo htmlspecialchars($product['nom']); ?></h3>
                                        <p class="pizza-description"><?php echo htmlspecialchars($product['description']); ?></p>
                                        <div class="menu-price-row" aria-label="Tarifs par taille">
                                            <span class="price-chip">S <span><?php echo number_format($product['prix_s'], 2); ?> TND</span></span>
                                            <span class="price-chip">M <span><?php echo number_format($product['prix_m'], 2); ?> TND</span></span>
                                            <span class="price-chip">L <span><?php echo number_format($product['prix_l'], 2); ?> TND</span></span>
                                        </div>

                                        <?php if ($user_logged_in): ?>
                                            <form action="/ProjetPizza2/index.php?url=menu/addToCart" method="POST" class="add-to-cart-form">
                                                <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                                                <div class="menu-form-selects">
                                                    <div class="quantity-selector">
                                                        <label for="size_<?php echo (int) $product['id']; ?>">Taille</label>
                                                        <select name="size" id="size_<?php echo (int) $product['id']; ?>">
                                                            <option value="S">S · <?php echo number_format($product['prix_s'], 2); ?> TND</option>
                                                            <option value="M" selected>M · <?php echo number_format($product['prix_m'], 2); ?> TND</option>
                                                            <option value="L">L · <?php echo number_format($product['prix_l'], 2); ?> TND</option>
                                                        </select>
                                                    </div>
                                                    <div class="quantity-selector">
                                                        <label for="quantity_<?php echo (int) $product['id']; ?>">Qté</label>
                                                        <select name="quantity" id="quantity_<?php echo (int) $product['id']; ?>">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-block menu-add-btn">Ajouter</button>
                                            </form>
                                        <?php else: ?>
                                            <a href="/ProjetPizza2/index.php?url=auth/login" class="btn btn-secondary btn-block">Connectez-vous pour commander</a>
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
                        <li><a href="/ProjetPizza2/index.php?url=menu">Menu</a></li>
                        <li><a href="/ProjetPizza2/index.php?url=composer">Composer votre pizza</a></li>
                        <li><a href="/ProjetPizza2/index.php?url=cart">Panier</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Téléphone : 01 23 45 67 89</p>
                    <p>Email : contact@smartpizzeria.fr</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Smart Pizzeria</p>
            </div>
        </div>
    </footer>
</body>
</html>
