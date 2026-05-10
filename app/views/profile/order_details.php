<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la commande — Smart Pizzeria</title>
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
            <h1>Détails de la Commande #<?php echo $order_details['id']; ?></h1>
            <p>Informations complètes de votre commande</p>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="order-details-container">
                <!-- Informations générales -->
                <div class="order-info-section">
                    <h2>Informations de la Commande</h2>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Numéro de commande:</label>
                            <span>#<?php echo $order_details['id']; ?></span>
                        </div>
                        
                        <div class="info-item">
                            <label>Date:</label>
                            <span><?php echo date('d/m/Y H:i', strtotime($order_details['created_at'])); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <label>Statut:</label>
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
                        
                        <div class="info-item">
                            <label>Total:</label>
                            <span class="total-amount"><?php echo number_format($order_details['total_amount'], 2); ?> TND</span>
                        </div>
                    </div>
                    
                    <div class="delivery-info">
                        <h3>Informations de Livraison</h3>
                        <p><strong>Adresse:</strong> <?php echo $order_details['delivery_address']; ?></p>
                        <p><strong>Téléphone:</strong> <?php echo $order_details['phone']; ?></p>
                    </div>
                </div>

                <!-- Articles de la commande -->
                <div class="order-items-section">
                    <h2>Articles Commandés</h2>
                    
                    <div class="items-list">
                        <?php foreach ($order_items as $item): ?>
                            <div class="order-item">
                                <div class="item-header">
                                    <h4>
                                        <?php echo $item['product_name'] ?: 'Pizza Personnalisée'; ?>
                                        <?php if ($item['is_custom']): ?>
                                            <span class="custom-badge">Personnalisée</span>
                                        <?php endif; ?>
                                    </h4>
                                    <div class="item-quantity-price">
                                        <span class="quantity">x<?php echo $item['quantity']; ?></span>
                                        <span class="price"><?php echo number_format($item['price'] * $item['quantity'], 2); ?> TND</span>
                                    </div>
                                </div>
                                
                                <div class="item-details">
                                    <p>Prix unitaire: <?php echo number_format($item['price'], 2); ?> TND</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php 
                    $subtotal = $order_details['total_amount'] - $order_details['delivery_fee'];
                    ?>
                    <div class="order-total">
                        <div class="total-line">
                            <span>Sous-total:</span>
                            <span><?php echo number_format($subtotal, 2); ?> TND</span>
                        </div>
                        <div class="total-line">
                            <span>Livraison:</span>
                            <span><?php echo $order_details['delivery_fee'] > 0 ? number_format($order_details['delivery_fee'], 2) . ' TND' : 'Gratuite'; ?></span>
                        </div>
                        <div class="total-line final-total">
                            <span>Total:</span>
                            <span><?php echo number_format($order_details['total_amount'], 2); ?> TND</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="order-actions-section">
                    <div class="action-buttons">
                        <a href="/ProjetPizza2/index.php?url=profile" class="btn btn-secondary">← Retour au profil</a>
                        <a href="/ProjetPizza2/index.php?url=menu" class="btn btn-primary">Passer une nouvelle commande</a>
                    </div>
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
