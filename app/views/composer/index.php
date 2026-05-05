<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composer votre pizza - Smart Pizzeria Tunisie</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/professional.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>🍕 Smart Pizzeria Tunisie</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/ProjetPizza2/index.php?url=home">Accueil</a></li>
                <li><a href="/ProjetPizza2/index.php?url=menu">Menu</a></li>
                <li><a href="/ProjetPizza2/index.php?url=composer" class="active">Composer votre pizza</a></li>
                <li><a href="/ProjetPizza2/index.php?url=cart">Panier</a></li>
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Composer votre pizza</h1>
            <p class="hero-subtitle">Créez la pizza parfaite selon vos préférences</p>
        </div>
    </section>

    <!-- Contenu Principal -->
    <main class="container">
        <div class="grid grid-2">
            <!-- Formulaire -->
            <div>
                <form id="pizza-composer-form">
                    <!-- Tailles -->
                    <div class="composer-section">
                        <h2 class="section-title">
                            <span class="section-number">1</span>
                            Choisissez la taille
                        </h2>
                        <div class="size-options">
                            <div class="size-option">
                                <input type="radio" name="size_id" id="size_small" value="small" data-multiplier="1" style="display: none;">
                                <label for="size_small" style="cursor: pointer; display: block;">
                                    <div class="size-name">Petite</div>
                                    <div class="size-price">12.00 TND</div>
                                </label>
                            </div>
                            <div class="size-option">
                                <input type="radio" name="size_id" id="size_medium" value="medium" data-multiplier="1.3" style="display: none;">
                                <label for="size_medium" style="cursor: pointer; display: block;">
                                    <div class="size-name">Moyenne</div>
                                    <div class="size-price">15.50 TND</div>
                                </label>
                            </div>
                            <div class="size-option">
                                <input type="radio" name="size_id" id="size_large" value="large" data-multiplier="1.6" style="display: none;">
                                <label for="size_large" style="cursor: pointer; display: block;">
                                    <div class="size-name">Grande</div>
                                    <div class="size-price">19.00 TND</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Types de pâte -->
                    <div class="composer-section">
                        <h2 class="section-title">
                            <span class="section-number">2</span>
                            Choisissez le type de pâte
                        </h2>
                        <div class="grid grid-2">
                            <div class="card">
                                <input type="radio" name="dough_id" id="dough_classic" value="1" data-price="0" style="display: none;">
                                <label for="dough_classic" style="cursor: pointer; display: block;">
                                    <div style="font-weight: 600; color: var(--text-dark);">Classique</div>
                                    <div style="color: var(--accent-color); font-weight: 600;">0.00 TND</div>
                                </label>
                            </div>
                            <div class="card">
                                <input type="radio" name="dough_id" id="dough_fine" value="2" data-price="0" style="display: none;">
                                <label for="dough_fine" style="cursor: pointer; display: block;">
                                    <div style="font-weight: 600; color: var(--text-dark);">Fine</div>
                                    <div style="color: var(--accent-color); font-weight: 600;">0.00 TND</div>
                                </label>
                            </div>
                            <div class="card">
                                <input type="radio" name="dough_id" id="dough_thick" value="3" data-price="3.30" style="display: none;">
                                <label for="dough_thick" style="cursor: pointer; display: block;">
                                    <div style="font-weight: 600; color: var(--text-dark);">Épaisse</div>
                                    <div style="color: var(--accent-color); font-weight: 600;">+3.30 TND</div>
                                </label>
                            </div>
                            <div class="card">
                                <input type="radio" name="dough_id" id="dough_cheese" value="4" data-price="8.20" style="display: none;">
                                <label for="dough_cheese" style="cursor: pointer; display: block;">
                                    <div style="font-weight: 600; color: var(--text-dark);">Fromage farci</div>
                                    <div style="color: var(--accent-color); font-weight: 600;">+8.20 TND</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Ingrédients -->
                    <div class="composer-section">
                        <h2 class="section-title">
                            <span class="section-number">3</span>
                            Choisissez vos ingrédients
                        </h2>
                        <div class="ingredients-grid">
                            <?php foreach ($ingredients as $ingredient): ?>
                                <div class="ingredient-card">
                                    <input type="checkbox" name="ingredients[]" id="ing_<?php echo $ingredient['id']; ?>" 
                                           value="<?php echo $ingredient['id']; ?>"
                                           data-price="<?php echo $ingredient['prix_supplementaire']; ?>"
                                           style="display: none;">
                                    <label for="ing_<?php echo $ingredient['id']; ?>" style="cursor: pointer; display: block;">
                                        <div class="ingredient-name"><?php echo htmlspecialchars($ingredient['nom']); ?></div>
                                        <div class="ingredient-price">+<?php echo number_format($ingredient['prix_supplementaire'], 2); ?> TND</div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Quantité -->
                    <div class="composer-section">
                        <h2 class="section-title">
                            <span class="section-number">4</span>
                            Quantité
                        </h2>
                        <div class="form-group">
                            <label class="form-label">Quantité:</label>
                            <select name="quantity" class="form-control">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Aperçu -->
            <div>
                <div class="preview-section">
                    <h2 class="section-title">Aperçu de votre pizza</h2>
                    <div class="preview-pizza">🍕</div>
                    <div class="preview-details">
                        <div class="preview-item">
                            <span>Pizza:</span>
                            <span>Pizza Personnalisée</span>
                        </div>
                        <div class="preview-item">
                            <span>Taille:</span>
                            <span id="preview-size">Non sélectionnée</span>
                        </div>
                        <div class="preview-item">
                            <span>Pâte:</span>
                            <span id="preview-dough">Non sélectionnée</span>
                        </div>
                        <div class="preview-item">
                            <span>Ingrédients:</span>
                            <span id="preview-ingredients">Aucun</span>
                        </div>
                        <div class="preview-item">
                            <span>Prix total:</span>
                            <span id="total-price">12.00 TND</span>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;" onclick="alert('Pizza ajoutée au panier!')">
                        🛒 Ajouter au panier
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>🍕 Smart Pizzeria Tunisie</h3>
                    <p>Votre pizzeria de confiance avec des pizzas personnalisées</p>
                    <p><strong>Téléphone:</strong> 74 000 000</p>
                    <p><strong>Email:</strong> contact@smartpizzeria.tn</p>
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
                    <h4>Horaires</h4>
                    <p>Lundi - Samedi: 11h00 - 23h00</p>
                    <p>Dimanche: 12h00 - 22h00</p>
                    <p>Livraison: 11h00 - 23h00</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Smart Pizzeria Tunisie - Tous droits réservés</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript professionnel -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des tailles
            document.querySelectorAll('.size-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.size-option').forEach(o => o.classList.remove('selected'));
                    this.classList.add('selected');
                    updatePreview();
                });
            });

            // Gestion des pâtes
            document.querySelectorAll('input[name="dough_id"]').forEach(input => {
                input.addEventListener('change', function() {
                    updatePreview();
                });
            });

            // Gestion des ingrédients
            document.querySelectorAll('.ingredient-card').forEach(card => {
                card.addEventListener('click', function() {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    this.classList.toggle('selected');
                    updatePreview();
                });
            });

            // Gestion de la quantité
            document.querySelector('select[name="quantity"]').addEventListener('change', updatePreview);

            function updatePreview() {
                let basePrice = 12.00; // Prix de base en TND
                let totalPrice = basePrice;
                let selectedSize = 'Non sélectionnée';
                let selectedDough = 'Non sélectionnée';
                let ingredientNames = [];

                // Taille
                const sizeInput = document.querySelector('input[name="size_id"]:checked');
                if (sizeInput) {
                    const sizeMultiplier = parseFloat(sizeInput.dataset.multiplier);
                    totalPrice *= sizeMultiplier;
                    selectedSize = sizeInput.nextElementSibling.querySelector('.size-name').textContent;
                }

                // Pâte
                const doughInput = document.querySelector('input[name="dough_id"]:checked');
                if (doughInput) {
                    const doughPrice = parseFloat(doughInput.dataset.price);
                    totalPrice += doughPrice;
                    selectedDough = doughInput.nextElementSibling.querySelector('div').textContent;
                }

                // Ingrédients
                document.querySelectorAll('input[name="ingredients[]"]:checked').forEach(checkbox => {
                    const price = parseFloat(checkbox.dataset.price);
                    totalPrice += price;
                    const name = checkbox.nextElementSibling.querySelector('.ingredient-name').textContent;
                    ingredientNames.push(name);
                });

                // Quantité
                const quantity = parseInt(document.querySelector('select[name="quantity"]').value);
                totalPrice *= quantity;

                // Mise à jour de l'aperçu
                document.getElementById('preview-size').textContent = selectedSize;
                document.getElementById('preview-dough').textContent = selectedDough;
                document.getElementById('preview-ingredients').textContent = ingredientNames.length > 0 ? ingredientNames.join(', ') : 'Aucun';
                document.getElementById('total-price').textContent = totalPrice.toFixed(2) + ' TND';
            }
        });
    </script>
</body>
</html>
