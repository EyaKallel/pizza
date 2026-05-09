<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande confirmée — Smart Pizzeria</title>
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
                <li><a href="/ProjetPizza2/index.php?url=profile">Profil</a></li>
                <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="page-header">
        <div class="container">
            <h1>Commande Confirmée!</h1>
            <p>Merci pour votre commande</p>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="success-container">
                <div class="success-message">
                    <div class="success-icon">✓</div>
                    <h2>Votre commande a été passée avec succès!</h2>
                    <p>Numéro de commande: <strong>#<?php echo $order_details['id']; ?></strong></p>
                    <p>Nous vous enverrons une notification lorsque votre commande sera prête.</p>
                </div>

                <div class="order-details">
                    <h3>Détails de la commande</h3>
                    
                    <div class="detail-row">
                        <span>Date:</span>
                        <span><?php echo date('d/m/Y H:i', strtotime($order_details['created_at'])); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Statut:</span>
                        <span class="status-badge status-<?php echo $order_details['status']; ?>">
                            <?php 
                            $status_labels = [
                                'pending' => 'En attente',
                                'preparing' => 'En préparation',
                                'ready' => 'Prête',
                                'delivered' => 'Livrée',
                                'cancelled' => 'Annulée'
                            ];
                            echo $status_labels[$order_details['status']] ?? 'Inconnu';
                            ?>
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Adresse de livraison:</span>
                        <span><?php echo $order_details['delivery_address']; ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Téléphone:</span>
                        <span><?php echo $order_details['phone']; ?></span>
                    </div>
                    
                    <?php 
                    $subtotal = $order_details['total_amount'] - $order_details['delivery_fee'];
                    ?>
                    <div class="detail-row">
                        <span>Sous-total:</span>
                        <span><?php echo number_format($subtotal, 2); ?> €</span>
                    </div>
                    <div class="detail-row">
                        <span>Frais de livraison:</span>
                        <span><?php echo $order_details['delivery_fee'] > 0 ? number_format($order_details['delivery_fee'], 2) . ' €' : 'Gratuit'; ?></span>
                    </div>
                    <div class="detail-row">
                        <span>Total:</span>
                        <span class="total-amount"><?php echo number_format($order_details['total_amount'], 2); ?> €</span>
                    </div>
                </div>

                <div class="order-items">
                    <h3>Articles commandés</h3>
                    
                    <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <div class="item-info">
                                <h4>
                                    <?php echo $item['product_name'] ?: 'Pizza Personnalisée'; ?>
                                    <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                                </h4>
                                <?php if ($item['is_custom']): ?>
                                    <small class="custom-indicator">Pizza personnalisée</small>
                                <?php endif; ?>
                            </div>
                            <div class="item-price">
                                <?php echo number_format($item['price'] * $item['quantity'], 2); ?> €
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="next-steps">
                    <h3>Prochaines étapes</h3>
                    <ul>
                        <li>📞 Nous vous appellerons pour confirmer votre commande</li>
                        <li>👨‍🍳 Votre commande sera préparée (environ 20-30 minutes)</li>
                        <li>🚚 Votre commande sera livrée à l'adresse indiquée</li>
                        <li>📊 Vous pouvez suivre le statut de votre commande dans votre profil</li>
                    </ul>
                </div>

                <div class="action-buttons">
                    <a href="/ProjetPizza2/index.php?url=home" class="btn btn-primary">Retour à l'accueil</a>
                    <a href="/ProjetPizza2/index.php?url=profile" class="btn btn-secondary">Voir mes commandes</a>
                    <a href="/ProjetPizza2/index.php?url=menu" class="btn btn-secondary">Passer une autre commande</a>
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
</body>
</html>
