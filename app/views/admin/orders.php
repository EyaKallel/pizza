<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/admin-light.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h1>Smart Pizzeria - Admin</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="/ProjetPizza2/index.php?url=admin/orders" class="active">Commandes</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/products">Produits</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/ingredients">Ingrédients</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/users">Utilisateurs</a></li>
                <li><a href="/ProjetPizza2/index.php?url=home">Voir le site</a></li>
                <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="admin-header">
        <div class="container">
            <h1>Gestion des Commandes</h1>
            <p>Consultez et modifiez le statut des commandes</p>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="orders-table">
                <div class="page-header">
                    <h1 class="page-title">Gestion des Commandes</h1>
                    <p class="page-description">Consultez et gérez toutes les commandes des clients</p>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Toutes les commandes</h3>
                        <div class="table-actions">
                            <span class="badge badge-info">
                                <?php echo count($orders); ?> commande(s)
                            </span>
                        </div>
                    </div>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                                        <td>
                                            <?php 
                                            if ($order['nom'] && $order['prenom']) {
                                                echo $order['prenom'] . ' ' . $order['nom'];
                                            } elseif ($order['email']) {
                                                echo $order['email'];
                                            } else {
                                                echo 'Client inconnu';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['date_commande'])); ?></td>
                                        <td><strong><?php echo number_format($order['total'], 2); ?> €</strong></td>
                                        <td>
                                            <span class="badge badge-<?php echo $order['statut'] === 'livrée' ? 'success' : ($order['statut'] === 'en_cours' ? 'warning' : ($order['statut'] === 'annulée' ? 'danger' : 'info')); ?>">
                                                <?php 
                                                $status_text = [
                                                    'en_attente' => 'En attente',
                                                    'en_cours' => 'En cours',
                                                    'livrée' => 'Livrée',
                                                    'annulée' => 'Annulée'
                                                ];
                                                echo $status_text[$order['statut']] ?? $order['statut'];
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <select class="form-select" onchange="updateStatus(<?php echo $order['id']; ?>, this.value)">
                                                    <option value="en_attente" <?php echo $order['statut'] === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                                    <option value="en_cours" <?php echo $order['statut'] === 'en_cours' ? 'selected' : ''; ?>>En cours</option>
                                                    <option value="livrée" <?php echo $order['statut'] === 'livrée' ? 'selected' : ''; ?>>Livrée</option>
                                                    <option value="annulée" <?php echo $order['statut'] === 'annulée' ? 'selected' : ''; ?>>Annulée</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 3rem;">
                                        <div style="color: var(--text-muted);">
                                            <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                                            <h3>Aucune commande trouvée</h3>
                                            <p>Les commandes apparaîtront ici dès que les clients commenceront à commander</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
    function updateStatus(orderId, newStatus) {
        fetch('/ProjetPizza2/index.php?url=admin/updateOrderStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher un message de succès
                const alert = document.createElement('div');
                alert.className = 'alert alert-success';
                alert.innerHTML = '<span>✓</span> Statut mis à jour avec succès';
                document.querySelector('.admin-content').insertBefore(alert, document.querySelector('.admin-content').firstChild);
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Mettre à jour le badge de statut
                                const row = this.closest('tr');
                                const badge = row.querySelector('.status-badge');
                                
                                // Mettre à jour les classes et le texte
                                badge.className = `status-badge status-${newStatus}`;
                                
                                const statusLabels = {
                                    'en_attente': 'En attente',
                                    'confirmée': 'Confirmée',
                                    'en_livraison': 'En livraison',
                                    'livrée': 'Livrée',
                                    'annulée': 'Annulée'
                                };
                                badge.textContent = statusLabels[newStatus];
                                
                                alert('Statut mis à jour avec succès');
                            } else {
                                alert('Erreur lors de la mise à jour du statut');
                                // Rétablir l'ancienne valeur
                                this.value = this.dataset.originalStatus || 'pending';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la mise à jour du statut');
                            this.value = this.dataset.originalStatus || 'pending';
                        });
                    } else {
                        // Rétablir l'ancienne valeur si l'utilisateur annule
                        this.value = this.dataset.originalStatus || 'pending';
                    }
                });
                
                // Sauvegarder la valeur initiale
                select.dataset.originalStatus = select.value;
            });
        });
    </script>
</body>
</html>
