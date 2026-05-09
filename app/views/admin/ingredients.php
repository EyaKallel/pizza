<?php ob_start(); ?>
 <div class="page-header">
     <h1 class="page-title">Gestion des Ingrédients</h1>
     <p class="page-description">Ajoutez, modifiez ou supprimez des ingrédients pour la composition de pizzas</p>
 </div>

 <!-- Formulaire d'ajout d'ingrédient -->
 <div class="product-form">
     <h2>➕ Ajouter un nouvel ingrédient</h2>
     <form id="add-ingredient-form">
                   <input type="hidden" id="ingredient_id" name="ingredient_id" value="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">Nom de l'ingrédient</label>
                            <input type="text" id="nom" name="nom" placeholder="ex: Fromage, Olives, Poulet..." required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prix_supplementaire">Prix supplémentaire (€)</label>
                            <input type="number" id="prix_supplementaire" name="prix_supplementaire" step="0.01" min="0" value="0.00">
                        </div>
                        
                        <div class="form-group">
                            <label for="disponible">Disponible</label>
                            <select id="disponible" name="disponible">
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">➕ Ajouter l'ingrédient</button>
                </form>
 </div>

 <!-- Tableau des ingrédients -->
 <div class="products-table">
     <h2>👁️ Liste des ingrédients</h2>
     <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prix suppl.</th>
                            <th>Disponible</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($ingredients) && !empty($ingredients)): ?>
                            <?php foreach ($ingredients as $ingredient): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($ingredient['nom']); ?></strong>
                                    </td>
                                    <td>
                                        <?php if ($ingredient['prix_supplementaire'] > 0): ?>
                                            <span class="price">+<?php echo number_format($ingredient['prix_supplementaire'], 2); ?> €</span>
                                        <?php else: ?>
                                            <span class="price">Gratuit</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $ingredient['disponible'] ? 'status-ready' : 'status-cancelled'; ?>">
                                            <?php echo $ingredient['disponible'] ? '✅ Disponible' : '❌ Indisponible'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-ingredient"
                                                data-ingredient-id="<?php echo $ingredient['id']; ?>"
                                                data-nom="<?php echo htmlspecialchars($ingredient['nom'], ENT_QUOTES); ?>"
                                                data-prix-supp="<?php echo $ingredient['prix_supplementaire']; ?>"
                                                data-disponible="<?php echo $ingredient['disponible']; ?>">
                                            ✏️ Modifier
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-ingredient" data-ingredient-id="<?php echo $ingredient['id']; ?>">
                                            ❌ Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px;">
                                    <p style="color: #666;">📭 Aucun ingrédient trouvé. Ajoutez votre premier ingrédient!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
 </div>

 <!-- Section d'utilisation -->
 <div class="info-section">
     <h2>👉 Utilisation dans "Composer votre pizza"</h2>
     <div class="info-grid">
         <div class="info-card">
             <h3>🍕 Composition personnalisée</h3>
             <p>Les ingrédients ajoutés ici seront disponibles pour les clients dans la section "Composer votre pizza".</p>
         </div>
         <div class="info-card">
             <h3>💰 Prix supplémentaires</h3>
             <p>Les ingrédients avec un prix supplémentaire seront ajoutés au coût total de la pizza personnalisée.</p>
         </div>
         <div class="info-card">
             <h3>📦 Gestion des stocks</h3>
             <p>Marquez un ingrédient comme "Indisponible" pour le retirer temporairement du menu sans le supprimer.</p>
         </div>
     </div>
 </div>

<script>
       document.addEventListener('DOMContentLoaded', function() {
           const addForm = document.getElementById('add-ingredient-form');
           const ingredientIdInput = document.getElementById('ingredient_id');
           const submitBtn = addForm.querySelector('button[type="submit"]');
           const nomInput = document.getElementById('nom');
           const prixInput = document.getElementById('prix_supplementaire');
           const disponibleSelect = document.getElementById('disponible');

           let mode = 'add'; // add | edit

           function setModeAdd() {
               mode = 'add';
               ingredientIdInput.value = '';
               submitBtn.textContent = '➕ Ajouter l\'ingrédient';
           }

           function setModeEdit(ingredientId, nom, prix, disponible) {
               mode = 'edit';
               ingredientIdInput.value = ingredientId;
               nomInput.value = nom;
               prixInput.value = prix;
               disponibleSelect.value = String(disponible);
               submitBtn.textContent = '✏️ Mettre à jour l\'ingrédient';
               addForm.scrollIntoView({ behavior: 'smooth' });
           }

           addForm.addEventListener('submit', function(e) {
               e.preventDefault();

               const formData = new FormData(this);
               const data = {};
               for (let [key, value] of formData.entries()) {
                   data[key] = value;
               }

               submitBtn.disabled = true;
               submitBtn.textContent = mode === 'edit' ? 'Mise à jour...' : 'Ajout en cours...';

               const url = mode === 'edit'
                   ? '/ProjetPizza2/index.php?url=admin/updateIngredient'
                   : '/ProjetPizza2/index.php?url=admin/addIngredient';

               fetch(url, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/x-www-form-urlencoded',
                   },
                   body: new URLSearchParams(data)
               })
               .then(response => response.json())
               .then(res => {
                   if (res.success) {
                       alert(res.message || 'OK');
                       location.reload();
                   } else {
                       alert(res.error || 'Erreur');
                   }
               })
               .catch(err => {
                   console.error('Error:', err);
                   alert('Erreur lors de l\'enregistrement de l\'ingrédient');
               })
               .finally(() => {
                   submitBtn.disabled = false;
                   submitBtn.textContent = mode === 'edit' ? '✏️ Mettre à jour l\'ingrédient' : '➕ Ajouter l\'ingrédient';
               });
           });

           // Edition
           document.querySelectorAll('.edit-ingredient').forEach(btn => {
               btn.addEventListener('click', function() {
                   const ingredientId = this.dataset.ingredientId;
                   const nom = this.dataset.nom || '';
                   const prix = this.dataset.prixSupp || 0;
                   const disponible = this.dataset.disponible || 1;

                   setModeEdit(ingredientId, nom, prix, disponible);
               });
           });

           // Suppression
           document.querySelectorAll('.delete-ingredient').forEach(btn => {
               btn.addEventListener('click', function() {
                   const ingredientId = this.dataset.ingredientId;
                   const ingredientName = this.closest('tr').querySelector('td:first-child strong').textContent;

                   if (confirm(`Êtes-vous sûr de vouloir supprimer "${ingredientName}" ?`)) {
                       fetch('/ProjetPizza2/index.php?url=admin/deleteIngredient', {
                           method: 'POST',
                           headers: {
                               'Content-Type': 'application/x-www-form-urlencoded',
                           },
                           body: `ingredient_id=${ingredientId}`
                       })
                       .then(response => response.json())
                       .then(res => {
                           if (res.success) {
                               alert(res.message || 'OK');
                               location.reload();
                           } else {
                               alert(res.error || 'Erreur lors de la suppression');
                           }
                       })
                       .catch(err => {
                           console.error('Error:', err);
                           alert('Erreur lors de la suppression de l\'ingrédient');
                       });
                   }
               });
           });
       });
</script>

<?php
$content = ob_get_clean();
$title = 'Gestion des Ingrédients';
$page_title = 'Ingrédients';
$active_page = 'ingredients';
require __DIR__ . '/layout.php';
?>
