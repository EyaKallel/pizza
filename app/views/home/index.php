<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Pizzeria — Accueil</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/modern-pizza.css">
    <link rel="stylesheet" href="/ProjetPizza2/public/css/client-site.css">
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

    <section class="landing-hero">
        <div class="landing-hero-inner">
            <div class="landing-hero-copy">
                <h1>La pizza artisanale, tout juste sortie du four</h1>
                <p class="lead">Ingrédients frais, pâte maison et livraison rapide — composez votre pizza ou choisissez parmi nos créations.</p>
                <div class="landing-hero-actions">
                    <a href="/ProjetPizza2/index.php?url=menu" class="landing-btn landing-btn-primary">Voir le menu</a>
                    <a href="/ProjetPizza2/index.php?url=composer" class="landing-btn landing-btn-ghost">Composer ma pizza</a>
                </div>
            </div>
            <div class="landing-hero-visual" aria-hidden="true">🍕</div>
        </div>
    </section>

    <section aria-labelledby="featured-heading">
        <h2 id="featured-heading" class="landing-section-title">Nos créations du moment</h2>
        <div class="landing-card-grid">
            <?php foreach ($featured_products as $product): ?>
                <article class="landing-product-card">
                    <div class="thumb">
                        <img src="/ProjetPizza2/public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
                    </div>
                    <div class="body">
                        <h3><?php echo htmlspecialchars($product['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="landing-price">À partir de <?php echo number_format($product['prix_m'], 2); ?> €</div>
                        <a href="/ProjetPizza2/index.php?url=menu" class="btn btn-primary btn-block">Commander</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="landing-features" aria-labelledby="why-heading">
        <h2 id="why-heading" class="landing-section-title">Pourquoi Smart Pizzeria ?</h2>
        <div class="landing-features-grid">
            <div class="landing-feature">
                <div class="icon" aria-hidden="true">🌿</div>
                <h3>Ingrédients frais</h3>
                <p>Sélection quotidienne pour des saveurs authentiques.</p>
            </div>
            <div class="landing-feature">
                <div class="icon" aria-hidden="true">🎨</div>
                <h3>100 % personnalisable</h3>
                <p>Taille, pâte et garnitures : votre pizza, vos règles.</p>
            </div>
            <div class="landing-feature">
                <div class="icon" aria-hidden="true">⚡</div>
                <h3>Livraison rapide</h3>
                <p>Chaude et croustillante, dans les temps.</p>
            </div>
            <div class="landing-feature">
                <div class="icon" aria-hidden="true">💬</div>
                <h3>Service attentionné</h3>
                <p>Une équipe à votre écoute pour chaque commande.</p>
            </div>
        </div>
    </section>

    <footer class="landing-footer">
        <h3>Smart Pizzeria</h3>
        <p>Votre pizzeria en ligne — <?php echo date('Y'); ?> · Tous droits réservés</p>
    </footer>

    <script>
        document.querySelectorAll('.landing-product-card, .landing-feature').forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(16px)';
            el.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
            requestAnimationFrame(() => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 60 + i * 45);
            });
        });
    </script>
</body>
</html>
