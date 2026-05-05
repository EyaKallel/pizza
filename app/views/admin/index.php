<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Smart Pizzeria</title>
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
                <li><a href="/ProjetPizza2/index.php?url=admin" class="active">Tableau de bord</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/orders">Commandes</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/products">Produits</a></li>
                <li><a href="/ProjetPizza2/index.php?url=home">Voir le site</a></li>
                <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="admin-header">
        <div class="container">
            <h1>Tableau de bord administrateur</h1>
            <p>Gérez votre pizzeria</p>
        </div>
    </header>

    <main>
        <div class="container">
            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total_orders']; ?></h3>
                        <p>Total des commandes</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['revenue'] ?? 0, 2); ?> €</h3>
                        <p>Chiffre d'affaires</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">⏰</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pending_orders']; ?></h3>
                        <p>Commandes en attente</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🍕</div>
                    <div class="stat-info">
                        <h3>5</h3>
                        <p>Produits disponibles</p>
                    </div>
                </div>
            </div>

            <!-- Commandes récentes -->
            <div class="recent-section">
                <div class="section-header">
                    <h2>Commandes récentes</h2>
                    <a href="index.php?url=admin/orders" class="btn btn-secondary">Voir toutes</a>
                </div>
                
                <div class="orders-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Commande</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo number_format($order['total_amount'], 2); ?> €</td>
                                    <td>
                                        <span class="status-badge status-<?php echo $order['status']; ?>">
                                            <?php 
                                            $status_labels = [
                                                'pending' => 'En attente',
                                                'preparing' => 'En préparation',
                                                'ready' => 'Prête',
                                                'delivered' => 'Livrée',
                                                'cancelled' => 'Annulée'
                                            ];
                                            echo $status_labels[$order['status']] ?? 'Inconnu';
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="updateOrderStatus(<?php echo $order['id']; ?>)">
                                            Modifier
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="quick-actions">
                <h2>Actions rapides</h2>
                <div class="actions-grid">
                    <a href="index.php?url=admin/orders" class="action-card">
                        <div class="action-icon">📋</div>
                        <h3>Gérer les commandes</h3>
                        <p>Voir et modifier le statut des commandes</p>
                    </a>
                    
                    <a href="index.php?url=admin/products" class="action-card">
                        <div class="action-icon">🍕</div>
                        <h3>Gérer les produits</h3>
                        <p>Ajouter, modifier ou supprimer des produits</p>
                    </a>
                    
                    <a href="index.php?url=menu" class="action-card">
                        <div class="action-icon">👁️</div>
                        <h3>Voir le menu</h3>
                        <p>Consulter le menu comme un client</p>
                    </a>
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
        function updateOrderStatus(orderId) {
            const status = prompt('Nouveau statut (pending, preparing, ready, delivered, cancelled):');
            if (status) {
                fetch('index.php?url=admin/updateOrderStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `order_id=${orderId}&status=${status}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.error);
                    }
                });
            }
        }
    </script>
</body>
</html>
