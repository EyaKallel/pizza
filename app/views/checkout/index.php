<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser la commande - Smart Pizzeria</title>
    <?php
    $checkoutWebBase = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    $checkoutProcessUrl = ($checkoutWebBase === '' ? '' : $checkoutWebBase) . '/index.php?url=checkout/process';
    $checkoutSuccessPrefix = ($checkoutWebBase === '' ? '' : $checkoutWebBase) . '/index.php?url=checkout/success/';
    ?>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/modern-pizza.css">
    <link rel="stylesheet" href="/ProjetPizza2/public/css/client-site.css">
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
                <li><a href="/ProjetPizza2/index.php?url=cart">Panier</a></li>
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
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Smart Pizzeria</h3>
                    <p>Finalisation de commande sécurisée.</p>
                </div>
                <div class="footer-section">
                    <h4>Liens</h4>
                    <ul>
                        <li><a href="/ProjetPizza2/index.php?url=menu">Menu</a></li>
                        <li><a href="/ProjetPizza2/index.php?url=cart">Panier</a></li>
                        <li><a href="/ProjetPizza2/index.php?url=profile">Profil</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Téléphone : 01 23 45 67 89</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Smart Pizzeria</p>
            </div>
        </div>
    </footer>

    <script>
        const CHECKOUT_PROCESS_URL = <?php echo json_encode($checkoutProcessUrl, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        const CHECKOUT_SUCCESS_PREFIX = <?php echo json_encode($checkoutSuccessPrefix, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        const defaultUserPhone = <?php echo json_encode(isset($user_info['telephone']) ? $user_info['telephone'] : '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

        let deliveryMode = null;
        const BASE_SUBTOTAL = <?php echo number_format($total, 2, '.', ''); ?>;
        const DELIVERY_FEE = 3.50;

        function updateSummary() {
            const deliveryFee = (deliveryMode === 'delivery') ? DELIVERY_FEE : 0;
            const total = BASE_SUBTOTAL + deliveryFee;
            
            document.getElementById('delivery-fees').innerHTML = 
                '<span>Frais de livraison:</span><span>' + deliveryFee.toFixed(2) + ' €</span>';
            document.getElementById('total-price').textContent = total.toFixed(2) + ' €';
        }

        function selectDeliveryOption(mode) {
            deliveryMode = mode;
            document.querySelectorAll('#delivery-option, #pickup-option').forEach(function (el) {
                el.classList.remove('selected');
            });
            var deliveryForm = document.getElementById('delivery-form');
            var pickupInfo = document.getElementById('pickup-info');
            var confirmBtn = document.getElementById('confirm-btn');

            if (mode === 'delivery') {
                document.getElementById('delivery-option').classList.add('selected');
                deliveryForm.style.display = 'block';
                pickupInfo.style.display = 'none';
                confirmBtn.disabled = false;
            } else {
                document.getElementById('pickup-option').classList.add('selected');
                deliveryForm.style.display = 'none';
                pickupInfo.style.display = 'block';
                confirmBtn.disabled = false;
            }
            updateSummary();
        }

        function confirmOrder() {
            var btn = document.getElementById('confirm-btn');
            var delivery_address = '';
            var phone = '';
            var instructions = '';

            if (deliveryMode === 'delivery') {
                var form = document.getElementById('checkout-form');
                delivery_address = form.delivery_address.value.trim();
                phone = form.phone.value.trim();
                instructions = form.instructions ? form.instructions.value.trim() : '';
                if (!delivery_address || !phone) {
                    alert('Veuillez remplir l\'adresse et le téléphone.');
                    return;
                }
            } else if (deliveryMode === 'pickup') {
                delivery_address = 'Retrait sur place — Smart Pizzeria, 123 Avenue des Pizzas, 75001 Paris';
                phone = (defaultUserPhone || '').trim();
                if (!phone) {
                    phone = window.prompt('Numéro de téléphone pour votre commande :') || '';
                }
                if (!phone) {
                    return;
                }
            } else {
                alert('Choisissez la livraison ou le retrait sur place.');
                return;
            }

            btn.disabled = true;
            var body = new URLSearchParams();
            body.set('delivery_address', delivery_address);
            body.set('phone', phone);
            body.set('instructions', instructions);
            body.set('type_livraison', deliveryMode === 'pickup' ? 'sur_place' : 'livraison');

            fetch(CHECKOUT_PROCESS_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
                body: body
            })
                .then(function (r) {
                    return r.text().then(function (text) {
                        var data;
                        try {
                            data = JSON.parse(text);
                        } catch (e) {
                            console.error('Réponse serveur:', text);
                            throw new Error('Le serveur n\'a pas renvoyé du JSON (vérifiez la base de données et les erreurs PHP).');
                        }
                        if (!r.ok) {
                            throw new Error(data.error || ('HTTP ' + r.status));
                        }
                        return data;
                    });
                })
                .then(function (data) {
                    if (data.success) {
                        window.location.href = CHECKOUT_SUCCESS_PREFIX + data.order_id;
                    } else {
                        alert(data.error || 'Erreur lors du traitement de la commande');
                        btn.disabled = false;
                    }
                })
                .catch(function (err) {
                    alert(err.message || 'Erreur réseau');
                    btn.disabled = false;
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('checkout-form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    confirmOrder();
                });
            }
        });
    </script>
</body>
</html>
