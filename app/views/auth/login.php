<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Smart Pizzeria</h1>
            <p>Connectez-vous à votre compte</p>
        </header>

        <main>
            <div class="auth-form">
                <h2>Connexion</h2>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?url=auth/login" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>

                <p>Pas encore de compte ? <a href="/ProjetPizza2/index.php?url=auth/register">Inscrivez-vous</a></p>
            </div>
        </main>
    </div>
</body>
</html>
