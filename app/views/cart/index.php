<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier — Smart Pizzeria</title>
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
                <li><a href="/ProjetPizza2/index.php?url=cart" class="active">Panier</a></li>
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
            <h1>Votre Panier</h1>
            <p>Gérez vos commandes</p>
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

            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <h2>Votre panier est vide</h2>
                    <p>Ajoutez des pizzas à votre panier pour commencer votre commande.</p>
                    <a href="/ProjetPizza2/index.php?url=menu" class="btn btn-primary">Voir le menu</a>
                    <a href="/ProjetPizza2/index.php?url=composer" class="btn btn-secondary">Composer une pizza</a>
                </div>
            <?php else: ?>
                <div class="cart-container">
                    <div class="cart-items">
                        <h2>Articles dans votre panier</h2>
                        
                        <?php foreach ($cart_items as $item_id => $item): ?>
                            <div class="cart-item" data-item-id="<?php echo $item_id; ?>">
                                <div class="item-image">
                                    <img src="/ProjetPizza2/public/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                </div>
                                
                                <div class="item-details">
                                    <h3><?php echo $item['name']; ?></h3>
                                    
                                    <?php if ($item['is_custom']): ?>
                                        <div class="custom-details">
                                            <?php if (isset($item['size'])): ?>
                                                <p>Taille: <?php echo $item['size']['name']; ?></p>
                                            <?php endif; ?>
                                            <?php if (isset($item['dough'])): ?>
                                                <p>Pâte: <?php echo $item['dough']['name']; ?></p>
                                            <?php endif; ?>
                                            <?php if (isset($item['ingredients']) && !empty($item['ingredients'])): ?>
                                                <p>Ingrédients: 
                                                    <?php 
                                                    $ingredient_names = [];
                                                    foreach ($item['ingredients'] as $ing) {
                                                        $ingredient_names[] = $ing['name'];
                                                    }
                                                    echo implode(', ', $ingredient_names);
                                                    ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="item-price">Prix unitaire: <?php echo number_format($item['price'], 2); ?> €</div>
                                </div>
                                
                                <div class="item-quantity">
                                    <div class="quantity-controls">
                                        <button class="quantity-btn decrease" data-item-id="<?php echo $item_id; ?>">-</button>
                                        <input type="number" class="quantity-input" value="<?php echo $item['quantity']; ?>" 
                                               min="1" max="10" data-item-id="<?php echo $item_id; ?>">
                                        <button class="quantity-btn increase" data-item-id="<?php echo $item_id; ?>">+</button>
                                    </div>
                                </div>
                                
                                <div class="item-total">
                                    <strong><?php echo number_format($item['price'] * $item['quantity'], 2); ?> €</strong>
                                </div>
                                
                                <div class="item-actions">
                                    <button class="btn btn-danger remove-item" data-item-id="<?php echo $item_id; ?>">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-summary">
                        <h2>Résumé de la commande</h2>
                        
                        <div class="summary-line">
                            <span>Sous-total:</span>
                            <span id="subtotal"><?php echo number_format($total, 2); ?> €</span>
                        </div>
                        
                        <div class="summary-line">
                            <span>Livraison:</span>
                            <span>Gratuite</span>
                        </div>
                        
                        <div class="summary-line total">
                            <span>Total:</span>
                            <span id="cart-total"><?php echo number_format($total, 2); ?> €</span>
                        </div>
                        
                        <div class="cart-actions">
                            <a href="/ProjetPizza2/index.php?url=checkout" class="btn btn-primary btn-large">Passer la commande</a>
                            <button class="btn btn-secondary" id="clear-cart">Vider le panier</button>
                        </div>
                        
                        <div class="continue-shopping">
                            <a href="/ProjetPizza2/index.php?url=menu">← Continuer vos achats</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
                        <li><a href="/ProjetPizza2/index.php?url=contact">Contact</a></li>
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
            // Mettre à jour la quantité
            function updateQuantity(itemId, newQuantity) {
                const formData = new FormData();
                formData.append('item_id', itemId);
                formData.append('quantity', newQuantity);

                fetch('/ProjetPizza2/index.php?url=cart/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour le total
                        document.getElementById('cart-total').textContent = data.total + ' €';
                        document.getElementById('subtotal').textContent = data.total + ' €';
                        
                        // Mettre à jour le total de l'article
                        const item = document.querySelector(`[data-item-id="${itemId}"]`);
                        const price = parseFloat(item.querySelector('.item-price').textContent.replace('€', '').replace(',', '.'));
                        const itemTotal = item.querySelector('.item-total strong');
                        itemTotal.textContent = (price * newQuantity).toFixed(2) + ' €';
                        
                        // Si quantité = 0, supprimer l'article
                        if (newQuantity <= 0) {
                            item.remove();
                            if (document.querySelectorAll('.cart-item').length === 0) {
                                location.reload();
                            }
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            // Gestion des boutons de quantité
            document.querySelectorAll('.quantity-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const itemId = this.dataset.itemId;
                    const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                    let currentValue = parseInt(input.value);
                    
                    if (this.classList.contains('decrease')) {
                        currentValue = Math.max(1, currentValue - 1);
                    } else {
                        currentValue = Math.min(10, currentValue + 1);
                    }
                    
                    input.value = currentValue;
                    updateQuantity(itemId, currentValue);
                });
            });

            // Gestion des inputs de quantité
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    const itemId = this.dataset.itemId;
                    let value = parseInt(this.value);
                    
                    if (isNaN(value) || value < 1) value = 1;
                    if (value > 10) value = 10;
                    
                    this.value = value;
                    updateQuantity(itemId, value);
                });
            });

            // Supprimer un article
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                        const itemId = this.dataset.itemId;
                        const formData = new FormData();
                        formData.append('item_id', itemId);

                        fetch('/ProjetPizza2/index.php?url=cart/remove', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.querySelector(`[data-item-id="${itemId}"]`).remove();
                                document.getElementById('cart-total').textContent = data.total + ' €';
                                document.getElementById('subtotal').textContent = data.total + ' €';
                                
                                if (data.item_count === 0) {
                                    location.reload();
                                }
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            });

            // Vider le panier
            document.getElementById('clear-cart').addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
                    window.location.href = '/ProjetPizza2/index.php?url=cart/clear';
                }
            });
        });
    </script>
</body>
</html>
