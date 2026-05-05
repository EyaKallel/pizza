<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/modern-pizza.css">
</head>
<body>
    <section class="welcome-section">
        <div class="welcome-container">
            <div class="welcome-info">
                <h2>Bienvenue chez Smart Pizzeria</h2>
                <p>Découvrez nos pizzas artisanales et commandez en ligne</p>
                <ul class="welcome-features">
                    <li>Pizzas fraîches et authentiques</li>
                    <li>Livraison rapide et gratuite</li>
                    <li>Composer votre pizza personnalisée</li>
                    <li>Service client disponible 24/7</li>
                </ul>
                <div style="margin-top: 2rem;">
                    <div style="font-size: 4rem; text-align: center; animation: float 3s ease-in-out infinite;">🍕</div>
                </div>
            </div>
            
            <div class="welcome-login">
                <div class="auth-form fade-in">
                    <h2>Connexion</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="/ProjetPizza2/index.php?url=auth/login" method="POST">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="votre@email.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </form>

                    <div class="auth-links">
                        <p>Pas encore de compte ? <a href="/ProjetPizza2/index.php?url=auth/register">Inscrivez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
