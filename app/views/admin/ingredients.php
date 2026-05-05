<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Ingrédients - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/style.css">
    <link rel="stylesheet" href="/ProjetPizza2/public/css/admin.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>Smart Pizzeria - Admin</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/ProjetPizza2/index.php?url=admin/orders">Commandes</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/products">Produits</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/ingredients" class="active">Ingrédients</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/users">Utilisateurs</a></li>
                <li><a href="/ProjetPizza2/index.php?url=home">Voir le site</a></li>
                <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="admin-header">
        <div class="container">
            <h1>🧀 Gestion des Ingrédients</h1>
            <p>Ajoutez, modifiez ou supprimez des ingrédients pour la composition de pizzas</p>
        </div>
    </header>

    <main>
        <div class="container">
            <!-- Formulaire d'ajout d'ingrédient -->
            <div class="product-form">
                <h2>➕ Ajouter un nouvel ingrédient</h2>
                <form id="add-ingredient-form">
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
                            <label for="type">Type</label>
                            <select id="type" name="type">
                                <option value="ingredient">Ingrédient</option>
                                <option value="sauce">Sauce</option>
                                <option value="fromage">Fromage</option>
                                <option value="viande">Viande</option>
                                <option value="legume">Légume</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="disponible">Disponible</label>
                            <select id="disponible" name="disponible">
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="description">Description (optionnel)</label>
                            <textarea id="description" name="description" rows="2" placeholder="Description de l'ingrédient..."></textarea>
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
                            <th>Type</th>
                            <th>Prix suppl.</th>
                            <th>Disponible</th>
                            <th>Description</th>
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
                                        <span class="ingredient-type type-<?php echo $ingredient['type']; ?>">
                                            <?php echo ucfirst($ingredient['type']); ?>
                                        </span>
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
                                        <?php echo htmlspecialchars($ingredient['description'] ?? '-'); ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-ingredient" data-ingredient-id="<?php echo $ingredient['id']; ?>">
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
                                <td colspan="6" style="text-align: center; padding: 40px;">
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
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Smart Pizzeria - Panneau d'administration</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Formulaire d'ajout d'ingrédient
            const addForm = document.getElementById('add-ingredient-form');
            
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const data = {};
                
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Ajout en cours...';
                
                fetch('/ProjetPizza2/index.php?url=admin/addIngredient', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Ingrédient ajouté avec succès');
                        location.reload();
                    } else {
                        alert(data.error || 'Erreur lors de l\'ajout de l\'ingrédient');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'ajout de l\'ingrédient');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = '➕ Ajouter l\'ingrédient';
                });
            });
            
            // Suppression d'ingrédient
            const deleteButtons = document.querySelectorAll('.delete-ingredient');
            
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const ingredientId = this.dataset.ingredientId;
                    const ingredientName = this.closest('tr').querySelector('td:first-child strong').textContent;
                    
                    if (confirm(`Êtes-vous sûr de vouloir supprimer "${ingredientName}"?`)) {
                        fetch('/ProjetPizza2/index.php?url=admin/deleteIngredient', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `ingredient_id=${ingredientId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Ingrédient supprimé avec succès');
                                location.reload();
                            } else {
                                alert(data.error || 'Erreur lors de la suppression de l\'ingrédient');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la suppression de l\'ingrédient');
                        });
                    }
                });
            });
        });
    </script>

    <style>
        .ingredient-type {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .type-ingredient { background: #e3f2fd; color: #1976d2; }
        .type-sauce { background: #fce4ec; color: #c2185b; }
        .type-fromage { background: #fff3e0; color: #f57c00; }
        .type-viande { background: #ffebee; color: #d32f2f; }
        .type-legume { background: #e8f5e8; color: #388e3c; }
        .type-autre { background: #f3e5f5; color: #7b1fa2; }
        
        .price {
            font-weight: bold;
            color: #4caf50;
        }
        
        .info-section {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-card {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .info-card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .info-card p {
            margin: 0;
            color: #666;
            line-height: 1.5;
        }
    </style>
</body>
</html>
