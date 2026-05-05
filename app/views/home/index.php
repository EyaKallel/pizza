<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Pizzeria - Accueil</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>Smart Pizzeria</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/ProjetPizza2/index.php?url=home" class="active">Accueil</a></li>
                <li><a href="/ProjetPizza2/index.php?url=menu">Menu</a></li>
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

    <header class="hero">
        <div class="hero-content">
            <h1>Bienvenue à Smart Pizzeria</h1>
            <p>Les meilleures pizzas de la ville, avec notre unique fonctionnalité "Composer votre pizza"</p>
            <div class="hero-buttons">
                <a href="/ProjetPizza2/index.php?url=menu" class="btn btn-primary">Commander</a>
                <a href="/ProjetPizza2/index.php?url=composer" class="btn btn-secondary">Composer votre pizza</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="/ProjetPizza2/public/images/hero-pizza.jpg" alt="Pizza délicieuse" onerror="this.style.background='linear-gradient(135deg, #ff6b6b, #d32f2f)'; this.style.height='300px'; this.style.display='block';">
        </div>
    </header>

    <main>
        <section class="featured">
            <div class="container">
                <h2>Nos Pizzas Populaires</h2>
                <div class="pizza-grid">
                    <?php foreach ($featured_products as $product): ?>
                        <div class="pizza-card">
                            <div class="pizza-image">
                                <img src="/ProjetPizza2/public/images/<?php echo $product['image']; ?>" alt="<?php echo $product['nom']; ?>" onerror="this.src='/ProjetPizza2/public/images/pizza-placeholder.png';">
                            </div>
                            <div class="pizza-info">
                                <h3><?php echo $product['nom']; ?></h3>
                                <p><?php echo $product['description']; ?></p>
                                <div class="pizza-price"><?php echo number_format($product['prix_m'], 2); ?> €</div>
                                <a href="/ProjetPizza2/index.php?url=menu" class="btn btn-primary">Ajouter au panier</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2>Pourquoi choisir Smart Pizzeria ?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🍕</div>
                        <h3>Ingrédients Frais</h3>
                        <p>Nous utilisons uniquement des ingrédients frais et locaux pour nos pizzas.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎨</div>
                        <h3>Personnalisation</h3>
                        <p>Créez votre pizza personnalisée avec nos ingrédients de qualité.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🚀</div>
                        <h3>Livraison Rapide</h3>
                        <p>Livraison rapide à domicile ou en point relais.</p>
                    </div>
                </div>
            </div>
        </section>
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
