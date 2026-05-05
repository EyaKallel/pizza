<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande - Smart Pizzeria</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>Smart Pizzeria</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php?url=home">Accueil</a></li>
                <li><a href="index.php?url=menu">Menu</a></li>
                <li><a href="index.php?url=composer">Composer votre pizza</a></li>
                <li><a href="index.php?url=cart">Panier</a></li>
                <li><a href="index.php?url=profile">Profil</a></li>
                <li><a href="index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="page-header">
        <div class="container">
            <h1>Finaliser votre commande</h1>
            <p>Vérifiez vos informations et confirmez votre commande</p>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="checkout-container">
                <!-- Récapitulatif de la commande -->
                <div class="order-summary">
                    <h2>Récapitulatif de la commande</h2>
                    
                    <?php foreach ($cart_items as $item): ?>
                        <div class="summary-item">
                            <div class="item-info">
                                <h4><?php echo $item['name']; ?></h4>
                                <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                            </div>
                            <div class="item-price">
                                <?php echo number_format($item['price'] * $item['quantity'], 2); ?> €
                            </div>
                        </div>
                        
                        <?php if ($item['is_custom']): ?>
                            <div class="custom-summary">
                                <?php if (isset($item['size'])): ?>
                                    <small>Taille: <?php echo $item['size']['name']; ?></small><br>
                                <?php endif; ?>
                                <?php if (isset($item['dough'])): ?>
                                    <small>Pâte: <?php echo $item['dough']['name']; ?></small><br>
                                <?php endif; ?>
                                <?php if (isset($item['ingredients']) && !empty($item['ingredients'])): ?>
                                    <small>
                                        Ingrédients: 
                                        <?php 
                                        $ingredient_names = [];
                                        foreach ($item['ingredients'] as $ing) {
                                            $ingredient_names[] = $ing['name'];
                                        }
                                        echo implode(', ', $ingredient_names);
                                        ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    
                    <div class="summary-total">
                        <strong>Total: <?php echo number_format($total, 2); ?> €</strong>
                    </div>
                </div>

                <!-- Formulaire de livraison -->
                <div class="delivery-form">
                    <h2>Informations de livraison</h2>
                    
                    <form id="checkout-form">
                        <div class="form-group">
                            <label for="delivery_address">Adresse de livraison *</label>
                            <textarea id="delivery_address" name="delivery_address" required><?php echo $user_info['address'] ?? ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="phone">Téléphone *</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo $user_info['phone'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="confirm" required>
                                Je confirme ma commande et les informations de livraison
                            </label>
                        </div>

                        <div class="checkout-actions">
                            <a href="index.php?url=cart" class="btn btn-secondary">← Retour au panier</a>
                            <button type="submit" class="btn btn-primary btn-large">Confirmer la commande</button>
                        </div>
                    </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkout-form');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const data = {};
                
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                
                // Validation simple
                if (!data.delivery_address || !data.phone) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    return;
                }
                
                if (!data.confirm) {
                    alert('Veuillez confirmer votre commande');
                    return;
                }
                
                // Désactiver le bouton pendant le traitement
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Traitement en cours...';
                
                fetch('index.php?url=checkout/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'index.php?url=checkout/success/' + data.order_id;
                    } else {
                        alert(data.error || 'Erreur lors du traitement de la commande');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Confirmer la commande';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors du traitement de la commande');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Confirmer la commande';
                });
            });
        });
    </script>
</body>
</html>
