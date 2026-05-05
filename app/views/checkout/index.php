<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser la commande - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/modern-pizza.css">
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .delivery-options {
            background: var(--background-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-light);
        }
        
        .option-card {
            border: 2px solid var(--border-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .option-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .option-card.selected {
            border-color: var(--primary-color);
            background: rgba(255, 107, 53, 0.05);
        }
        
        .option-card.selected::after {
            content: "✓";
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--primary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }
        
        .option-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .option-icon {
            font-size: 1.5rem;
        }
        
        .option-description {
            color: var(--text-medium);
            margin-bottom: 1rem;
        }
        
        .option-price {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .delivery-form {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }
        
        .order-summary {
            background: var(--background-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-light);
        }
        
        .summary-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-light);
        }
        
        .summary-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .summary-items {
            margin-bottom: 1.5rem;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-light);
        }
        
        .item-info h4 {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .item-quantity {
            color: var(--text-medium);
            font-size: 0.9rem;
        }
        
        .item-price {
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-top: 2px solid var(--primary-color);
            margin-top: 1rem;
        }
        
        .summary-total strong {
            color: var(--text-dark);
            font-size: 1.3rem;
        }
        
        .checkout-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
        }
        
        .btn-large {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }
        
        .btn-secondary {
            background: var(--background-white);
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .pickup-info {
            background: linear-gradient(135deg, rgba(78, 205, 196, 0.1) 0%, rgba(255, 230, 109, 0.1) 100%);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid rgba(78, 205, 196, 0.3);
        }
        
        .pickup-info h4 {
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        
        .pickup-info p {
            color: var(--text-medium);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            
            .checkout-actions {
                flex-direction: column;
            }
        }
    </style>
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
                <li><a href="/ProjetPizza2/index.php?url=profile">Profil</a></li>
                <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <div class="checkout-container">
        <h1 style="text-align: center; color: var(--text-dark); margin-bottom: 2rem; font-size: 2rem;">Finaliser votre commande</h1>
        
        <div class="checkout-grid">
            <!-- Options de livraison -->
            <div class="delivery-options">
                <h2 style="color: var(--text-dark); margin-bottom: 1.5rem;">Mode de livraison</h2>
                
                <div class="option-card" id="delivery-option" onclick="selectDeliveryOption('delivery')">
                    <div class="option-title">
                        <span class="option-icon">🚚</span>
                        <span>Livraison à domicile</span>
                    </div>
                    <div class="option-description">
                        Recevez votre commande directement chez vous en 30-45 minutes
                    </div>
                    <div class="option-price">Frais de livraison: 3.50 €</div>
                </div>
                
                <div class="option-card" id="pickup-option" onclick="selectDeliveryOption('pickup')">
                    <div class="option-title">
                        <span class="option-icon">🏪</span>
                        <span>Retrait sur place</span>
                    </div>
                    <div class="option-description">
                        Venez chercher votre commande directement à notre pizzeria
                    </div>
                    <div class="option-price">Gratuit</div>
                </div>
                
                <!-- Formulaire de livraison (visible seulement si livraison sélectionnée) -->
                <div class="delivery-form" id="delivery-form" style="display: none;">
                    <h3 style="color: var(--text-dark); margin-bottom: 1rem;">Informations de livraison</h3>
                    
                    <form id="checkout-form">
                        <div class="form-group">
                            <label for="delivery_address">Adresse de livraison *</label>
                            <textarea id="delivery_address" name="delivery_address" rows="3" placeholder="123 Rue de la Pizza, 75001 Paris" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="phone">Téléphone *</label>
                            <input type="tel" id="phone" name="phone" placeholder="06 12 34 56 78" required>
                        </div>

                        <div class="form-group">
                            <label for="instructions">Instructions de livraison (optionnel)</label>
                            <textarea id="instructions" name="instructions" rows="2" placeholder="Code d'accès, étage, sonnette, etc."></textarea>
                        </div>
                    </form>
                </div>
                
                <!-- Informations de retrait (visible seulement si retrait sélectionné) -->
                <div class="pickup-info" id="pickup-info" style="display: none;">
                    <h4>📍 Adresse du restaurant</h4>
                    <p>
                        <strong>Smart Pizzeria</strong><br>
                        123 Avenue des Pizzas<br>
                        75001 Paris<br>
                        Téléphone: 01 23 45 67 89<br>
                        <br>
                        <strong>Horaires d'ouverture:</strong><br>
                        Lundi - Dimanche: 11h00 - 23h00
                    </p>
                </div>
            </div>

            <!-- Récapitulatif de la commande -->
            <div class="order-summary">
                <div class="summary-header">
                    <h2 class="summary-title">Récapitulatif</h2>
                    <span style="color: var(--text-medium);"><?php echo count($cart_items); ?> article(s)</span>
                </div>
                
                <div class="summary-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="summary-item">
                            <div class="item-info">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                                <?php if ($item['is_custom']): ?>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                                        <?php if (isset($item['size'])) echo "Taille: " . htmlspecialchars($item['size']['name']) . " "; ?>
                                        <?php if (isset($item['dough'])) echo "Pâte: " . htmlspecialchars($item['dough']['name']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="item-price">
                                <?php echo number_format($item['price'] * $item['quantity'], 2); ?> €
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="padding: 1rem 0; border-top: 1px solid var(--border-light);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Sous-total:</span>
                        <span><?php echo number_format($total, 2); ?> €</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;" id="delivery-fees">
                        <span>Frais de livraison:</span>
                        <span>0.00 €</span>
                    </div>
                </div>
                
                <div class="summary-total">
                    <span>Total:</span>
                    <strong id="total-price"><?php echo number_format($total, 2); ?> €</strong>
                </div>
                
                <div class="checkout-actions">
                    <a href="/ProjetPizza2/index.php?url=cart" class="btn-large btn-secondary">← Retour au panier</a>
                    <button type="button" class="btn-large btn-primary" onclick="confirmOrder()" id="confirm-btn" disabled>
                        Confirmer la commande
                    </button>
                </div>
            </div>
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
