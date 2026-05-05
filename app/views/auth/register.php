<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Smart Pizzeria</h1>
            <p>Créez votre compte</p>
        </header>

        <main>
            <div class="auth-form">
                <h2>Inscription</h2>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="/ProjetPizza2/index.php?url=auth/register" method="POST">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" required>
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="mot_de_passe">Mot de passe</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Téléphone (optionnel)</label>
                        <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78">
                    </div>

                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </form>

                <p>Déjà un compte ? <a href="/ProjetPizza2/index.php?url=auth/login">Connectez-vous</a></p>
            </div>
        </main>
    </div>
</body>
</html>
