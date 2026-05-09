<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Smart Pizzeria</title>
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
                <li><a href="/ProjetPizza2/index.php?url=home">Accueil</a></li>
                <li><a href="/ProjetPizza2/index.php?url=menu">Menu</a></li>
                <li><a href="/ProjetPizza2/index.php?url=composer">Composer votre pizza</a></li>
                <li><a href="/ProjetPizza2/index.php?url=cart">Panier</a></li>
                <li><a href="/ProjetPizza2/index.php?url=profile" class="active">Profil</a></li>
                <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="page-header">
        <div class="container">
            <h1>Mon Profil</h1>
            <p>Gérez vos informations et vos commandes</p>
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

            <div class="profile-container">
                <!-- Informations personnelles -->
                <div class="profile-section">
                    <h2>Mes Informations</h2>
                    
                    <form id="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <input type="text" id="prenom" name="prenom" value="<?php echo isset($user_info['prenom']) ? htmlspecialchars($user_info['prenom']) : ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" id="nom" name="nom" value="<?php echo isset($user_info['nom']) ? htmlspecialchars($user_info['nom']) : ''; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" value="<?php echo isset($user_info['email']) ? htmlspecialchars($user_info['email']) : ''; ?>" disabled>
                            <small>L'email ne peut pas être modifié</small>
                        </div>

                        <div class="form-group">
                            <label for="adresse">Adresse</label>
                            <textarea id="adresse" name="adresse" rows="3"><?php echo isset($user_info['adresse']) ? htmlspecialchars($user_info['adresse']) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" value="<?php echo isset($user_info['telephone']) ? htmlspecialchars($user_info['telephone']) : ''; ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>

                <!-- Historique des commandes -->
                <div class="profile-section">
                    <h2>Historique des Commandes</h2>
                    
                    <?php if (empty($orders)): ?>
                        <div class="empty-orders">
                            <p>Vous n'avez pas encore passé de commande.</p>
                            <a href="/ProjetPizza2/index.php?url=menu" class="btn btn-primary">Passer une commande</a>
                        </div>
                    <?php else: ?>
                        <div class="orders-list">
                            <?php foreach ($orders as $order): ?>
                                <div class="order-card">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <h3>Commande #<?php echo $order['id']; ?></h3>
                                            <p class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['date_commande'])); ?></p>
                                        </div>
                                        <div class="order-status">
                                            <span class="status-badge status-<?php echo $order['statut']; ?>">
                                                <?php 
                                                $status_labels = [
                                                    'en_attente' => 'En attente',
                                                    'en_cours' => 'En cours',
                                                    'prete' => 'Prête',
                                                    'livrée' => 'Livrée',
                                                    'annulée' => 'Annulée'
                                                ];
                                                echo $status_labels[$order['statut']] ?? 'Inconnu';
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="order-details">
                                        <div class="order-summary">
                                            <p><strong><?php echo $order['item_count']; ?></strong> article(s)</p>
                                            <p><strong>Total:</strong> <?php echo number_format($order['total'], 2); ?> €</p>
                                        </div>
                                        
                                        <div class="order-actions">
                                            <a href="/ProjetPizza2/index.php?url=profile/orderDetails/<?php echo $order['id']; ?>" class="btn btn-secondary">Voir les détails</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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
                    <p>Téléphone: 01 23 45 67 89</p>
                    <p>Email: contact@smartpizzeria.fr</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Smart Pizzeria</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profile-form');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const data = {};
                
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                
                // Désactiver le bouton pendant le traitement
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Mise à jour...';
                
                fetch('/ProjetPizza2/index.php?url=profile/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Afficher un message de succès
                        const successDiv = document.createElement('div');
                        successDiv.className = 'success-message';
                        successDiv.textContent = data.message;
                        form.parentNode.insertBefore(successDiv, form);
                        
                        // Supprimer le message après 3 secondes
                        setTimeout(() => {
                            successDiv.remove();
                        }, 3000);
                    } else {
                        // Afficher un message d'erreur
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.textContent = data.error;
                        form.parentNode.insertBefore(errorDiv, form);
                        
                        setTimeout(() => {
                            errorDiv.remove();
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la mise à jour du profil');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Mettre à jour';
                });
            });
        });
    </script>
</body>
</html>
