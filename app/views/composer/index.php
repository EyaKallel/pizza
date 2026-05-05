<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composer votre pizza - Smart Pizzeria</title>
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
                <li><a href="index.php?url=composer" class="active">Composer votre pizza</a></li>
                <li><a href="index.php?url=cart">Panier</a></li>
                <li><a href="index.php?url=profile">Profil</a></li>
                <li><a href="index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="page-header">
        <div class="container">
            <h1>Composer votre pizza</h1>
            <p>Créez la pizza parfaite selon vos goûts</p>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="pizza-composer">
                <div class="composer-form">
                    <form id="pizza-form">
                        <!-- Taille -->
                        <div class="form-section">
                            <h3>1. Choisissez la taille</h3>
                            <div class="options-grid">
                                <?php foreach ($sizes as $size): ?>
                                    <div class="option-card">
                                        <input type="radio" name="size_id" id="size_<?php echo $size['id']; ?>" 
                                               value="<?php echo $size['id']; ?>" required
                                               data-modifier="<?php echo $size['price_modifier']; ?>">
                                        <label for="size_<?php echo $size['id']; ?>">
                                            <span class="option-name"><?php echo $size['name']; ?></span>
                                            <span class="option-modifier">x<?php echo $size['price_modifier']; ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Type de pâte -->
                        <div class="form-section">
                            <h3>2. Choisissez le type de pâte</h3>
                            <div class="options-grid">
                                <?php foreach ($doughs as $dough): ?>
                                    <div class="option-card">
                                        <input type="radio" name="dough_id" id="dough_<?php echo $dough['id']; ?>" 
                                               value="<?php echo $dough['id']; ?>" required
                                               data-price="<?php echo $dough['price_modifier']; ?>">
                                        <label for="dough_<?php echo $dough['id']; ?>">
                                            <span class="option-name"><?php echo $dough['name']; ?></span>
                                            <span class="option-price">+<?php echo $dough['price_modifier']; ?>€</span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Ingrédients -->
                        <div class="form-section">
                            <h3>3. Choisissez vos ingrédients</h3>
                            <div class="ingredients-grid">
                                <?php foreach ($ingredients as $ingredient): ?>
                                    <div class="ingredient-card">
                                        <input type="checkbox" name="ingredients[]" id="ing_<?php echo $ingredient['id']; ?>" 
                                               value="<?php echo $ingredient['id']; ?>"
                                               data-price="<?php echo $ingredient['price']; ?>">
                                        <label for="ing_<?php echo $ingredient['id']; ?>">
                                            <span class="ingredient-name"><?php echo $ingredient['name']; ?></span>
                                            <span class="ingredient-price">+<?php echo $ingredient['price']; ?>€</span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Quantité -->
                        <div class="form-section">
                            <h3>4. Quantité</h3>
                            <div class="quantity-selector">
                                <label for="quantity">Quantité:</label>
                                <select name="quantity" id="quantity">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Prix total -->
                        <div class="price-display">
                            <h3>Prix total: <span id="total-price">5.00</span>€</h3>
                        </div>

                        <button type="submit" class="btn btn-primary btn-large">Ajouter au panier</button>
                    </form>
                </div>

                <div class="pizza-preview">
                    <h3>Aperçu de votre pizza</h3>
                    <div class="preview-image">
                        <img src="../public/images/pizza-preview.jpg" alt="Aperçu pizza">
                    </div>
                    <div class="preview-details">
                        <h4 id="preview-name">Pizza Personnalisée</h4>
                        <div id="preview-size">Taille: Non sélectionnée</div>
                        <div id="preview-dough">Pâte: Non sélectionnée</div>
                        <div id="preview-ingredients">Ingrédients: Aucun</div>
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
            const form = document.getElementById('pizza-form');
            const totalPriceElement = document.getElementById('total-price');
            const previewSize = document.getElementById('preview-size');
            const previewDough = document.getElementById('preview-dough');
            const previewIngredients = document.getElementById('preview-ingredients');

            function calculatePrice() {
                const basePrice = 5.00;
                let totalPrice = basePrice;

                // Taille
                const selectedSize = document.querySelector('input[name="size_id"]:checked');
                if (selectedSize) {
                    const modifier = parseFloat(selectedSize.dataset.modifier);
                    totalPrice *= modifier;
                    
                    const sizeLabel = selectedSize.nextElementSibling.querySelector('.option-name').textContent;
                    previewSize.textContent = 'Taille: ' + sizeLabel;
                } else {
                    previewSize.textContent = 'Taille: Non sélectionnée';
                }

                // Pâte
                const selectedDough = document.querySelector('input[name="dough_id"]:checked');
                if (selectedDough) {
                    const doughPrice = parseFloat(selectedDough.dataset.price);
                    totalPrice += doughPrice;
                    
                    const doughLabel = selectedDough.nextElementSibling.querySelector('.option-name').textContent;
                    previewDough.textContent = 'Pâte: ' + doughLabel;
                } else {
                    previewDough.textContent = 'Pâte: Non sélectionnée';
                }

                // Ingrédients
                const selectedIngredients = document.querySelectorAll('input[name="ingredients[]"]:checked');
                let ingredientNames = [];
                selectedIngredients.forEach(checkbox => {
                    const price = parseFloat(checkbox.dataset.price);
                    totalPrice += price;
                    
                    const name = checkbox.nextElementSibling.querySelector('.ingredient-name').textContent;
                    ingredientNames.push(name);
                });
                
                if (ingredientNames.length > 0) {
                    previewIngredients.textContent = 'Ingrédients: ' + ingredientNames.join(', ');
                } else {
                    previewIngredients.textContent = 'Ingrédients: Aucun';
                }

                totalPriceElement.textContent = totalPrice.toFixed(2);
            }

            // Écouter les changements
            document.querySelectorAll('input[name="size_id"], input[name="dough_id"], input[name="ingredients[]"]').forEach(input => {
                input.addEventListener('change', calculatePrice);
            });

            // Soumission du formulaire
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const data = {};
                
                for (let [key, value] of formData.entries()) {
                    if (key === 'ingredients[]') {
                        if (!data.ingredients) data.ingredients = [];
                        data.ingredients.push(value);
                    } else {
                        data[key] = value;
                    }
                }

                fetch('index.php?url=composer/addToCart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = 'index.php?url=cart';
                    } else {
                        alert(data.error || 'Erreur lors de l\'ajout au panier');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'ajout au panier');
                });
            });

            // Calcul initial
            calculatePrice();
        });
    </script>
</body>
</html>
