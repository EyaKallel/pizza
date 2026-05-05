<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Pizzeria - Accueil</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/modern-pizza.css">
    <style>
        /* Design magnifique pour page d'accueil */
        body {
            background: linear-gradient(135deg, #FFF5F5 0%, #FFF0F5 25%, #F0FFF4 50%, #F0FFFF 75%, #FFF8DC 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Hero Section Magnifique */
        .hero-magnifique {
            background: linear-gradient(135deg, 
                rgba(255, 182, 193, 0.1) 0%, 
                rgba(255, 218, 185, 0.1) 25%, 
                rgba(255, 255, 224, 0.1) 50%, 
                rgba(240, 255, 240, 0.1) 75%, 
                rgba(240, 248, 255, 0.1) 100%);
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-magnifique::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(255, 182, 193, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 218, 185, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255, 255, 224, 0.2) 0%, transparent 60%);
            animation: floatingColors 20s ease-in-out infinite;
        }

        @keyframes floatingColors {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(1deg); }
            50% { transform: translateY(-10px) rotate(-1deg); }
            75% { transform: translateY(-30px) rotate(0.5deg); }
        }

        .hero-content-magnifique {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .hero-title-magnifique {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(45deg, #FF69B4, #FFA07A, #FFD700, #98FB98, #87CEEB);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 4s ease-in-out infinite;
            margin-bottom: 1rem;
            text-shadow: 0 4px 20px rgba(255, 105, 180, 0.3);
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .hero-subtitle-magnifique {
            font-size: 1.5rem;
            color: #FF69B4;
            font-weight: 600;
            margin-bottom: 2rem;
            animation: float 3s ease-in-out infinite;
        }

        /* Pizza Animation */
        .pizza-animation {
            font-size: 6rem;
            animation: rotatePizza 10s linear infinite;
            margin: 2rem 0;
            filter: drop-shadow(0 10px 30px rgba(255, 105, 180, 0.4));
        }

        @keyframes rotatePizza {
            0% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(90deg) scale(1.1); }
            50% { transform: rotate(180deg) scale(1); }
            75% { transform: rotate(270deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(1); }
        }

        /* Boutons Magnifiques */
        .hero-buttons-magnifique {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-magnifique {
            padding: 1.2rem 2.5rem;
            font-size: 1.2rem;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .btn-magnifique::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .btn-magnifique:hover::before {
            left: 100%;
        }

        .btn-primary-magnifique {
            background: linear-gradient(45deg, #FF69B4, #FFA07A);
            color: white;
            box-shadow: 0 8px 30px rgba(255, 105, 180, 0.4);
        }

        .btn-primary-magnifique:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(255, 105, 180, 0.6);
        }

        .btn-secondary-magnifique {
            background: linear-gradient(45deg, #98FB98, #87CEEB);
            color: #2C3E50;
            box-shadow: 0 8px 30px rgba(152, 251, 152, 0.4);
        }

        .btn-secondary-magnifique:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(152, 251, 152, 0.6);
        }

        /* Section Pizzas Populaires */
        .pops-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.9) 0%, 
                rgba(240, 255, 240, 0.9) 100%);
            position: relative;
        }

        .section-title-magnifique {
            text-align: center;
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(45deg, #FF69B4, #98FB98);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 3s ease-in-out infinite;
            margin-bottom: 3rem;
        }

        .pizza-grid-magnifique {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .pizza-card-magnifique {
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF8DC 100%);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(255, 182, 193, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .pizza-card-magnifique::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #FF69B4, #FFA07A, #FFD700, #98FB98, #87CEEB);
            background-size: 200% 200%;
            animation: gradientShift 2s linear infinite;
        }

        .pizza-card-magnifique:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 60px rgba(255, 105, 180, 0.4);
        }

        .pizza-image-magnifique {
            height: 250px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #FFE4E1 0%, #FFF0F5 100%);
        }

        .pizza-image-magnifique img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .pizza-card-magnifique:hover .pizza-image-magnifique img {
            transform: scale(1.1);
        }

        .pizza-info-magnifique {
            padding: 2rem;
        }

        .pizza-name-magnifique {
            font-size: 1.5rem;
            font-weight: 700;
            color: #FF69B4;
            margin-bottom: 1rem;
        }

        .pizza-description-magnifique {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .pizza-price-magnifique {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(45deg, #FF69B4, #FFA07A);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 3s ease-in-out infinite;
            margin-bottom: 1.5rem;
        }

        /* Section Features Magnifique */
        .features-magnifique {
            padding: 4rem 0;
            background: linear-gradient(135deg, 
                rgba(255, 240, 245, 0.9) 0%, 
                rgba(240, 248, 255, 0.9) 100%);
        }

        .features-grid-magnifique {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .feature-card-magnifique {
            background: linear-gradient(135deg, #FFFFFF 0%, #F0FFFF 100%);
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(135, 206, 235, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .feature-card-magnifique::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(135, 206, 235, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .feature-card-magnifique:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 25px 60px rgba(135, 206, 235, 0.4);
        }

        .feature-icon-magnifique {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .feature-title-magnifique {
            font-size: 1.5rem;
            font-weight: 700;
            color: #87CEEB;
            margin-bottom: 1rem;
        }

        .feature-description-magnifique {
            color: #666;
            line-height: 1.6;
        }

        /* Footer Magnifique */
        .footer-magnifique {
            background: linear-gradient(135deg, #FF69B4 0%, #87CEEB 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
        }

        .footer-content-magnifique {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title-magnifique {
                font-size: 2.5rem;
            }
            
            .hero-subtitle-magnifique {
                font-size: 1.2rem;
            }
            
            .pizza-animation {
                font-size: 4rem;
            }
            
            .hero-buttons-magnifique {
                flex-direction: column;
                align-items: center;
            }
            
            .section-title-magnifique {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>🍕 Smart Pizzeria</h1>
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

    <!-- Hero Section Magnifique -->
    <section class="hero-magnifique">
        <div class="hero-content-magnifique">
            <h1 class="hero-title-magnifique">Bienvenue chez Smart Pizzeria</h1>
            <p class="hero-subtitle-magnifique">Des pizzas magnifiques pour des moments inoubliables</p>
            <div class="pizza-animation">🍕</div>
            <div class="hero-buttons-magnifique">
                <a href="/ProjetPizza2/index.php?url=menu" class="btn-magnifique btn-primary-magnifique">
                    🍕 Commander maintenant
                </a>
                <a href="/ProjetPizza2/index.php?url=composer" class="btn-magnifique btn-secondary-magnifique">
                    🎨 Composer ma pizza
                </a>
            </div>
        </div>
    </section>

    <!-- Section Pizzas Populaires -->
    <section class="pops-section">
        <h2 class="section-title-magnifique">Nos Pizzas Magiques</h2>
        <div class="pizza-grid-magnifique">
            <?php foreach ($featured_products as $product): ?>
                <div class="pizza-card-magnifique">
                    <div class="pizza-image-magnifique">
                        <img src="/ProjetPizza2/public/images/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
                    </div>
                    <div class="pizza-info-magnifique">
                        <h3 class="pizza-name-magnifique"><?php echo htmlspecialchars($product['nom']); ?></h3>
                        <p class="pizza-description-magnifique"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="pizza-price-magnifique"><?php echo number_format($product['prix_m'], 2); ?> €</div>
                        <a href="/ProjetPizza2/index.php?url=menu" class="btn-magnifique btn-primary-magnifique">
                            🛒 Ajouter au panier
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section Features -->
    <section class="features-magnifique">
        <h2 class="section-title-magnifique">Pourquoi nous choisir?</h2>
        <div class="features-grid-magnifique">
            <div class="feature-card-magnifique">
                <div class="feature-icon-magnifique">🍕</div>
                <h3 class="feature-title-magnifique">Ingrédients Frais</h3>
                <p class="feature-description-magnifique">Des produits locaux et frais pour des pizzas d'exception</p>
            </div>
            <div class="feature-card-magnifique">
                <div class="feature-icon-magnifique">🎨</div>
                <h3 class="feature-title-magnifique">Création Personnalisée</h3>
                <p class="feature-description-magnifique">Composez votre pizza unique avec nos ingrédients premium</p>
            </div>
            <div class="feature-card-magnifique">
                <div class="feature-icon-magnifique">🚀</div>
                <h3 class="feature-title-magnifique">Livraison Éclair</h3>
                <p class="feature-description-magnifique">Recevez votre commande en 30 minutes maximum</p>
            </div>
            <div class="feature-card-magnifique">
                <div class="feature-icon-magnifique">💝</div>
                <h3 class="feature-title-magnifique">Service Client</h3>
                <p class="feature-description-magnifique">Une équipe disponible pour vous satisfaire</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-magnifique">
        <div class="footer-content-magnifique">
            <h3>🍕 Smart Pizzeria</h3>
            <p>Votre pizzeria de confiance avec des pizzas magnifiques</p>
            <p>&copy; 2024 Smart Pizzeria - Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Animations au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les cartes
        document.querySelectorAll('.pizza-card-magnifique, .feature-card-magnifique').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>
