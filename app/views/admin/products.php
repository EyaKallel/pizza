<?php ob_start(); ?>
 <div class="page-header">
     <h1 class="page-title">Gestion des Produits</h1>
     <p class="page-description">Ajoutez, modifiez ou supprimez des produits</p>
 </div>

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
                                    <button class="btn btn-sm btn-info edit-product"
                                            data-product-id="<?php echo $product['id']; ?>"
                                            data-nom="<?php echo htmlspecialchars($product['nom'], ENT_QUOTES); ?>"
                                            data-description="<?php echo htmlspecialchars($product['description'] ?? '', ENT_QUOTES); ?>"
                                            data-prix-s="<?php echo $product['prix_s']; ?>"
                                            data-prix-m="<?php echo $product['prix_m']; ?>"
                                            data-prix-l="<?php echo $product['prix_l']; ?>"
                                            data-categorie-id="<?php echo $product['categorie_id']; ?>">
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

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const addForm = document.getElementById('add-product-form');
            const imageInput = document.getElementById('image');
            const submitBtn = addForm.querySelector('button[type="submit"]');

            const nomInput = document.getElementById('nom');
            const prixSInput = document.getElementById('prix_s');
            const prixMInput = document.getElementById('prix_m');
            const prixLInput = document.getElementById('prix_l');
            const descriptionInput = document.getElementById('description');
            const categorieSelect = document.getElementById('categorie_id');

            let mode = 'add'; // add | edit
            let editingProductId = null;

            function setModeAdd() {
                mode = 'add';
                editingProductId = null;
                submitBtn.textContent = 'Ajouter le produit';
                imageInput.required = true;
            }

            function setModeEdit(productId) {
                mode = 'edit';
                editingProductId = productId;
                submitBtn.textContent = '✏️ Mettre à jour le produit';
                // Pour l'update, l'image est optionnelle
                imageInput.required = false;
            }

            function showError(fieldId, message) {
                const field = document.getElementById(fieldId);
                field.style.borderColor = '#dc3545';

                let errorDiv = field.parentNode.querySelector('.price-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'price-error';
                    field.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = message;
                errorDiv.classList.add('show');
            }

            function validatePrices() {
                const prixS = parseFloat(prixSInput.value);
                const prixM = parseFloat(prixMInput.value);
                const prixL = parseFloat(prixLInput.value);

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

            // Validation en temps réel
            ['prix_s', 'prix_m', 'prix_l'].forEach(id => {
                document.getElementById(id).addEventListener('input', validatePrices);
                document.getElementById(id).classList.add('price-input');
            });

            addForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!validatePrices()) {
                    alert('Veuillez corriger les erreurs de prix avant de soumettre le formulaire.');
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.textContent = mode === 'edit' ? 'Mise à jour en cours...' : 'Ajout en cours...';

                const formData = new FormData(addForm);
                const url = mode === 'edit' ? 'admin/updateProduct' : 'admin/addProduct';
                if (mode === 'edit') {
                    formData.append('product_id', editingProductId);
                }

                fetch('/ProjetPizza2/index.php?url=' + url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(mode === 'edit' ? 'Produit mis à jour avec succès' : 'Produit ajouté avec succès');
                        location.reload();
                    } else {
                        alert(data.error || (mode === 'edit' ? 'Erreur lors de la mise à jour du produit' : 'Erreur lors de l\'ajout du produit'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(mode === 'edit' ? 'Erreur lors de la mise à jour du produit' : 'Erreur lors de l\'ajout du produit');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    if (mode === 'add') {
                        setModeAdd();
                    } else {
                        // Garder le mode edit en cas d'échec
                        imageInput.required = false;
                        submitBtn.textContent = '✏️ Mettre à jour le produit';
                    }
                });
            });

            // Edition produit
            document.querySelectorAll('.edit-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.productId;

                    nomInput.value = this.dataset.nom || '';
                    prixSInput.value = this.dataset.prixS || '';
                    prixMInput.value = this.dataset.prixM || '';
                    prixLInput.value = this.dataset.prixL || '';
                    descriptionInput.value = this.dataset.description || '';
                    categorieSelect.value = this.dataset.categorieId || '';

                    setModeEdit(productId);
                    document.querySelector('.product-form').scrollIntoView({ behavior: 'smooth' });
                });
            });

            // Toggle disponibilité
            document.querySelectorAll('.toggle-switch').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const productId = this.dataset.productId;
                    const newStatus = this.checked ? '1' : '0';

                    const label = this.closest('label').querySelector('.toggle-label');
                    label.textContent = this.checked ? 'Disponible' : 'Indisponible';

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
                            this.checked = !this.checked;
                            label.textContent = this.checked ? 'Disponible' : 'Indisponible';
                            alert(data.error || 'Erreur lors de la modification de la disponibilité');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.checked = !this.checked;
                        label.textContent = this.checked ? 'Disponible' : 'Indisponible';
                        alert('Erreur lors de la modification de la disponibilité');
                    });
                });
            });

            // Suppression de produit
            document.querySelectorAll('.delete-product').forEach(btn => {
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

            // Mode initial
            setModeAdd();
        });
</script>
 
<?php
$content = ob_get_clean();
$title = 'Gestion des Produits';
$page_title = 'Produits';
$active_page = 'products';
require __DIR__ . '/layout.php';
?>
