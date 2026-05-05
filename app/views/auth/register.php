<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/modern-pizza.css">
</head>
<body>
    <section class="welcome-section">
        <div class="welcome-container">
            <div class="welcome-info">
                <h2>Rejoignez Smart Pizzeria</h2>
                <p>Créez votre compte et profitez de nos offres exclusives</p>
                <ul class="welcome-features">
                    <li>Commandez en quelques clics</li>
                    <li>Suivez vos commandes en temps réel</li>
                    <li>Accédez aux promotions membres</li>
                    <li>Pizzas personnalisées illimitées</li>
                </ul>
                <div style="margin-top: 2rem;">
                    <div style="font-size: 4rem; text-align: center; animation: float 3s ease-in-out infinite;">🍕</div>
                </div>
            </div>
            
            <div class="welcome-login">
                <div class="auth-form fade-in">
                    <h2>Inscription</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="/ProjetPizza2/index.php?url=auth/register" method="POST">
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" placeholder="Jean" required>
                        </div>

                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" placeholder="Dupont" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="jean.dupont@email.com" required>
                        </div>

                        <div class="form-group">
                            <label for="mot_de_passe">Mot de passe</label>
                            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="•••••••" required>
                        </div>

                        <div class="form-group">
                            <label for="telephone">Téléphone (optionnel)</label>
                            <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78">
                        </div>

                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </form>

                    <div class="auth-links">
                        <p>Déjà un compte ? <a href="/ProjetPizza2/index.php?url=auth/login">Connectez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
