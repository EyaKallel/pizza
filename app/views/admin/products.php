<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/admin-light.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>Smart Pizzeria - Admin</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/ProjetPizza2/index.php?url=admin/orders">Commandes</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/products" class="active">Produits</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/ingredients">Ingrédients</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/users">Utilisateurs</a></li>
                <li><a href="/ProjetPizza2/index.php?url=home">Voir le site</a></li>
                <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="admin-header">
        <div class="container">
            <h1>Gestion des Produits</h1>
            <p>Ajoutez, modifiez ou supprimez des produits</p>
        </div>
    </header>

    <main>
        <div class="container">
            <!-- Formulaire d'ajout de produit -->
            <div class="product-form">
                <h2>Ajouter un nouveau produit</h2>
                <form id="add-product-form" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">Nom du produit</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prix_s">Prix Small (€)</label>
                            <input type="number" id="prix_s" name="prix_s" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prix_m">Prix Medium (€)</label>
                            <input type="number" id="prix_m" name="prix_m" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prix_l">Prix Large (€)</label>
                            <input type="number" id="prix_l" name="prix_l" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="categorie_id">Catégorie</label>
                            <select id="categorie_id" name="categorie_id" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['nom']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Image du produit</label>
                            <input type="file" id="image" name="image" accept="image/*" required>
                            <small>Formats acceptés: JPG, PNG, GIF (max 5MB)</small>
                        </div>
                        
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="description">Description (optionnel)</label>
                            <textarea id="description" name="description" rows="3" placeholder="Description du produit..."></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Ajouter le produit</button>
                </form>
            </div>

            <!-- Tableau des produits -->
            <div class="products-table">
                <h2>Liste des produits</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Catégorie</th>
                            <th>Disponible</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="/ProjetPizza2/public/images/<?php echo $product['image']; ?>" 
                                         alt="<?php echo $product['nom']; ?>" 
                                         class="product-image-thumb">
                                </td>
                                <td><?php echo $product['nom']; ?></td>
                                <td><?php echo $product['description']; ?></td>
                                <td>
                                    <div class="price-display">
                                        <span class="price-size">S:</span> <span class="price-value"><?php echo number_format($product['prix_s'], 2); ?></span><br>
                                        <span class="price-size">M:</span> <span class="price-value"><?php echo number_format($product['prix_m'], 2); ?></span><br>
                                        <span class="price-size">L:</span> <span class="price-value"><?php echo number_format($product['prix_l'], 2); ?></span>
                                    </div>
                                </td>
                                <td><?php echo $product['category_name']; ?></td>
                                <td>
                                    <label class="availability-toggle">
                                        <input type="checkbox" 
                                               class="toggle-switch" 
                                               data-product-id="<?php echo $product['id']; ?>"
                                               <?php echo ($product['disponible'] ?? 1) ? 'checked' : ''; ?>>
                                        <span class="toggle-slider"></span>
                                        <span class="toggle-label">
                                            <?php echo ($product['disponible'] ?? 1) ? 'Disponible' : 'Indisponible'; ?>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-product" data-product-id="<?php echo $product['id']; ?>">
                                        ✏️ Modifier
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-product" data-product-id="<?php echo $product['id']; ?>">
                                        ❌ Supprimer
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Smart Pizzeria - Panneau d'administration</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Formulaire d'ajout de produit
            const addForm = document.getElementById('add-product-form');
            
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                // Désactiver le bouton pendant le traitement
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Ajout en cours...';
                
                fetch('/ProjetPizza2/index.php?url=admin/addProduct', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produit ajouté avec succès');
                        location.reload();
                    } else {
                        alert(data.error || 'Erreur lors de l\'ajout du produit');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'ajout du produit');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Ajouter le produit';
                });
            });
            
            // Modification de produit
            const editButtons = document.querySelectorAll('.edit-product');
            
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const row = this.closest('tr');
                    
                    // Récupérer les données du produit
                    const productData = {
                        id: productId,
                        nom: row.querySelector('td:nth-child(2)').textContent,
                        prix_s: row.querySelector('td:nth-child(4)').textContent.match(/S: ([\d.]+)/)[1],
                        prix_m: row.querySelector('td:nth-child(4)').textContent.match(/M: ([\d.]+)/)[1],
                        prix_l: row.querySelector('td:nth-child(4)').textContent.match(/L: ([\d.]+)/)[1],
                        description: row.querySelector('td:nth-child(3)').textContent,
                        categorie: row.querySelector('td:nth-child(5)').textContent
                    };
                    
                    // Remplir le formulaire avec les données actuelles
                    document.getElementById('nom').value = productData.nom;
                    document.getElementById('prix_s').value = productData.prix_s;
                    document.getElementById('prix_m').value = productData.prix_m;
                    document.getElementById('prix_l').value = productData.prix_l;
                    document.getElementById('description').value = productData.description;
                    
                    // Changer le bouton pour "Mettre à jour"
                    const submitBtn = document.querySelector('#add-product-form button[type="submit"]');
                    submitBtn.textContent = '✏️ Mettre à jour le produit';
                    submitBtn.onclick = function(e) {
                        e.preventDefault();
                        updateProduct(productId);
                    };
                    
                    // Faire défiler vers le formulaire
                    document.querySelector('.product-form').scrollIntoView({ behavior: 'smooth' });
                });
            });
            
            // Changement de disponibilité avec toggle switch
            const toggleSwitches = document.querySelectorAll('.toggle-switch');
            
            toggleSwitches.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const productId = this.dataset.productId;
                    const newStatus = this.checked ? '1' : '0';
                    const productName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
                    const label = this.nextElementSibling.nextElementSibling;
                    
                    // Mettre à jour le label immédiatement
                    label.textContent = this.checked ? 'Disponible' : 'Indisponible';
                    
                    // Envoyer la mise à jour au serveur
                    fetch('/ProjetPizza2/index.php?url=admin/toggleProductAvailability', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `product_id=${productId}&disponible=${newStatus}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            // Revenir à l'état précédent en cas d'erreur
                            this.checked = !this.checked;
                            label.textContent = this.checked ? 'Disponible' : 'Indisponible';
                            alert(data.error || 'Erreur lors de la modification de la disponibilité');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revenir à l'état précédent en cas d'erreur
                        this.checked = !this.checked;
                        label.textContent = this.checked ? 'Disponible' : 'Indisponible';
                        alert('Erreur lors de la modification de la disponibilité');
                    });
                });
            });
            
            // Validation des prix
            function validatePrices() {
                const prixS = parseFloat(document.getElementById('prix_s').value);
                const prixM = parseFloat(document.getElementById('prix_m').value);
                const prixL = parseFloat(document.getElementById('prix_l').value);
                
                // Réinitialiser les erreurs
                document.querySelectorAll('.price-error').forEach(err => err.classList.remove('show'));
                document.querySelectorAll('.price-input').forEach(input => input.style.borderColor = '');
                
                let isValid = true;
                
                if (prixS >= prixM) {
                    showError('prix_s', 'Le prix Small doit être inférieur au prix Medium');
                    isValid = false;
                }
                
                if (prixM >= prixL) {
                    showError('prix_m', 'Le prix Medium doit être inférieur au prix Large');
                    isValid = false;
                }
                
                if (prixS >= prixL) {
                    showError('prix_s', 'Le prix Small doit être inférieur au prix Large');
                    isValid = false;
                }
                
                if (prixS < 0 || prixM < 0 || prixL < 0) {
                    showError('prix_s', 'Les prix ne peuvent pas être négatifs');
                    isValid = false;
                }
                
                return isValid;
            }
            
            function showError(fieldId, message) {
                const field = document.getElementById(fieldId);
                field.style.borderColor = '#dc3545';
                
                // Créer ou afficher le message d'erreur
                let errorDiv = field.parentNode.querySelector('.price-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'price-error';
                    field.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = message;
                errorDiv.classList.add('show');
            }
            
            // Ajouter la validation lors de la soumission
            document.getElementById('add-product-form').addEventListener('submit', function(e) {
                if (!validatePrices()) {
                    e.preventDefault();
                    alert('Veuillez corriger les erreurs de prix avant de soumettre le formulaire.');
                }
            });
            
            // Validation en temps réel
            ['prix_s', 'prix_m', 'prix_l'].forEach(id => {
                document.getElementById(id).addEventListener('input', validatePrices);
                document.getElementById(id).classList.add('price-input');
            });
            
            // Suppression de produit
            const deleteButtons = document.querySelectorAll('.delete-product');
            
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const productName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
                    
                    if (confirm(`Êtes-vous sûr de vouloir supprimer "${productName}"?`)) {
                        fetch('/ProjetPizza2/index.php?url=admin/deleteProduct', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `product_id=${productId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Produit supprimé avec succès');
                                location.reload();
                            } else {
                                alert(data.error || 'Erreur lors de la suppression du produit');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la suppression du produit');
                        });
                    }
                });
            });
            
            // Fonction pour mettre à jour un produit
            function updateProduct(productId) {
                const form = document.getElementById('add-product-form');
                const formData = new FormData(form);
                formData.append('product_id', productId);
                
                fetch('/ProjetPizza2/index.php?url=admin/updateProduct', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produit mis à jour avec succès');
                        location.reload();
                    } else {
                        alert(data.error || 'Erreur lors de la mise à jour du produit');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la mise à jour du produit');
                });
            }
        });
    </script>
    
    <style>
        /* Toggle Switch Professional */
        .availability-toggle {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }
        
        .toggle-switch {
            position: relative;
            width: 50px;
            height: 26px;
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
            background-color: #ccc;
            border-radius: 34px;
            transition: 0.4s;
            cursor: pointer;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.4s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .toggle-switch:checked + .toggle-slider {
            background-color: #4CAF50;
        }
        
        .toggle-switch:focus + .toggle-slider {
            box-shadow: 0 0 0 2px #4CAF50;
        }
        
        .toggle-switch:checked + .toggle-slider:before {
            transform: translateX(24px);
        }
        
        .toggle-label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            transition: color 0.3s ease;
        }
        
        .toggle-switch:checked + .toggle-slider + .toggle-label {
            color: #4CAF50;
            font-weight: 600;
        }
        
        /* Product Image Styling */
        .product-image-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .product-image-thumb:hover {
            transform: scale(1.05);
        }
        
        /* Price Display Styling */
        .price-display {
            font-family: 'Courier New', monospace;
            line-height: 1.4;
        }
        
        .price-size {
            font-weight: 600;
            color: #666;
            display: inline-block;
            width: 20px;
        }
        
        .price-value {
            font-weight: 700;
            color: #333;
            font-size: 14px;
        }
        
        /* Price Validation Styling */
        .price-input {
            text-align: right;
        }
        
        .price-input:invalid {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        
        .price-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        
        .price-error.show {
            display: block;
        }
    </style>
</body>
</html>
