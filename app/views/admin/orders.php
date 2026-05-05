<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - Smart Pizzeria</title>
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
                <table>
                    <thead>
                        <tr>
                            <th>Commande</th>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Livraison</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo $order['prenom'] . ' ' . $order['nom']; ?></td>
                                <td><?php echo $order['email']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['date_commande'])); ?></td>
                                <td><?php echo number_format($order['total'] ?? 0, 2); ?> €</td>
                                <td><?php echo $order['adresse_livraison'] ?? 'Sur place'; ?></td>
                                <td><?php echo $order['telephone']; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['statut']; ?>">
                                        <?php 
                                        $status_labels = [
                                            'en_attente' => 'En attente',
                                            'confirmée' => 'Confirmée',
                                            'en_livraison' => 'En livraison',
                                            'livrée' => 'Livrée',
                                            'annulée' => 'Annulée'
                                        ];
                                        echo $status_labels[$order['statut']] ?? 'Inconnu';
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <select class="status-select" data-order-id="<?php echo $order['id']; ?>">
                                        <option value="en_attente" <?php echo $order['statut'] == 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                        <option value="confirmée" <?php echo $order['statut'] == 'confirmée' ? 'selected' : ''; ?>>Confirmée</option>
                                        <option value="en_livraison" <?php echo $order['statut'] == 'en_livraison' ? 'selected' : ''; ?>>En livraison</option>
                                        <option value="livrée" <?php echo $order['statut'] == 'livrée' ? 'selected' : ''; ?>>Livrée</option>
                                        <option value="annulée" <?php echo $order['statut'] == 'annulée' ? 'selected' : ''; ?>>Annulée</option>
                                    </select>
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
            const statusSelects = document.querySelectorAll('.status-select');
            
            statusSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const orderId = this.dataset.orderId;
                    const newStatus = this.value;
                    
                    if (confirm(`Changer le statut de la commande #${orderId} vers "${this.options[this.selectedIndex].text}"?`)) {
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
