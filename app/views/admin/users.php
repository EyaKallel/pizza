<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Smart Pizzeria</title>
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
                <li><a href="/ProjetPizza2/index.php?url=admin/products">Produits</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/ingredients">Ingrédients</a></li>
                <li><a href="/ProjetPizza2/index.php?url=admin/users" class="active">Utilisateurs</a></li>
                <li><a href="/ProjetPizza2/index.php?url=home">Voir le site</a></li>
                <li><a href="/ProjetPizza2/index.php?url=auth/logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="admin-header">
        <div class="container">
            <h1>👤 Gestion des Utilisateurs</h1>
            <p>Voyez tous les clients, gérez les comptes et consultez les historiques</p>
        </div>
    </header>

    <main>
        <div class="container">
            <!-- Filtres et recherche -->
            <div class="filters-section">
                <div class="filter-controls">
                    <input type="text" id="search-users" placeholder="🔍 Rechercher par nom, email..." class="search-input">
                    <select id="role-filter" class="filter-select">
                        <option value="">Tous les rôles</option>
                        <option value="admin">Admin</option>
                        <option value="client">Client</option>
                    </select>
                    <select id="status-filter" class="filter-select">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actifs</option>
                        <option value="blocked">Bloqués</option>
                    </select>
                    <button id="refresh-users" class="btn btn-secondary">🔄 Actualiser</button>
                </div>
            </div>

            <!-- Tableau des utilisateurs -->
            <div class="users-table">
                <h2>📋 Liste des utilisateurs</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Date d'inscription</th>
                            <th>Commandes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        <?php if (isset($users) && !empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr data-user-id="<?php echo $user['id']; ?>">
                                    <td>#<?php echo $user['id']; ?></td>
                                    <td>
                                        <div class="user-info">
                                            <strong><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></strong>
                                            <?php if (!empty($user['adresse'])): ?>
                                                <small><?php echo htmlspecialchars($user['adresse']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="email-link">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['telephone'] ?? '-'); ?></td>
                                    <td>
                                        <span class="role-badge role-<?php echo $user['role']; ?>">
                                            <?php 
                                            $role_labels = [
                                                'admin' => '👨‍💼 Admin',
                                                'client' => '👤 Client'
                                            ];
                                            echo $role_labels[$user['role']] ?? ucfirst($user['role']);
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo ($user['blocked'] ?? 0) ? 'status-cancelled' : 'status-ready'; ?>">
                                            <?php echo ($user['blocked'] ?? 0) ? '🚫 Bloqué' : '✅ Actif'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($user['created_at'] ?? 'now')); ?></td>
                                    <td>
                                        <a href="/ProjetPizza2/index.php?url=admin/userOrders/<?php echo $user['id']; ?>" class="orders-link">
                                            <?php echo $user['order_count'] ?? 0; ?> commandes
                                        </a>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-info view-orders" data-user-id="<?php echo $user['id']; ?>">
                                                📦 Voir commandes
                                            </button>
                                            <?php if ($user['role'] !== 'admin'): ?>
                                                <?php if (($user['blocked'] ?? 0) == 0): ?>
                                                    <button class="btn btn-sm btn-warning block-user" data-user-id="<?php echo $user['id']; ?>">
                                                        🚫 Bloquer
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-success unblock-user" data-user-id="<?php echo $user['id']; ?>">
                                                        ✅ Débloquer
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-danger delete-user" data-user-id="<?php echo $user['id']; ?>">
                                                    ❌ Supprimer
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 40px;">
                                    <p style="color: #666;">📭 Aucun utilisateur trouvé.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Statistiques des utilisateurs -->
            <div class="stats-section">
                <h2>📊 Statistiques des utilisateurs</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_users'] ?? 0; ?></h3>
                            <p>Total des utilisateurs</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👤</div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_clients'] ?? 0; ?></h3>
                            <p>Clients actifs</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🚫</div>
                        <div class="stat-info">
                            <h3><?php echo $stats['blocked_users'] ?? 0; ?></h3>
                            <p>Utilisateurs bloqués</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📈</div>
                        <div class="stat-info">
                            <h3><?php echo $stats['new_users_this_month'] ?? 0; ?></h3>
                            <p>Nouveaux ce mois</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal pour voir les commandes d'un utilisateur -->
    <div id="orders-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📦 Historique des commandes</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body" id="orders-modal-body">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Smart Pizzeria - Panneau d'administration</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche d'utilisateurs
            const searchInput = document.getElementById('search-users');
            const roleFilter = document.getElementById('role-filter');
            const statusFilter = document.getElementById('status-filter');
            const tbody = document.getElementById('users-tbody');

            function filterUsers() {
                const searchTerm = searchInput.value.toLowerCase();
                const roleValue = roleFilter.value;
                const statusValue = statusFilter.value;
                
                const rows = tbody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const role = row.querySelector('.role-badge').textContent.toLowerCase();
                    const status = row.querySelector('.status-badge').textContent.toLowerCase();
                    
                    const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                    const matchesRole = !roleValue || role.includes(roleValue);
                    const matchesStatus = !statusValue || status.includes(statusValue === 'active' ? 'actif' : 'bloqué');
                    
                    row.style.display = matchesSearch && matchesRole && matchesStatus ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterUsers);
            roleFilter.addEventListener('change', filterUsers);
            statusFilter.addEventListener('change', filterUsers);

            // Modal pour les commandes
            const modal = document.getElementById('orders-modal');
            const closeBtn = document.querySelector('.close-modal');

            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });

            // Voir les commandes d'un utilisateur
            document.querySelectorAll('.view-orders').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    const userName = this.closest('tr').querySelector('.user-info strong').textContent;
                    
                    document.querySelector('#orders-modal h3').textContent = `📦 Commandes de ${userName}`;
                    
                    fetch(`/ProjetPizza2/index.php?url=admin/getUserOrders/${userId}`)
                        .then(response => response.json())
                        .then(data => {
                            const modalBody = document.getElementById('orders-modal-body');
                            if (data.success && data.orders.length > 0) {
                                let html = '<div class="orders-list">';
                                data.orders.forEach(order => {
                                    html += `
                                        <div class="order-item">
                                            <div class="order-header">
                                                <strong>Commande #${order.id}</strong>
                                                <span class="order-date">${new Date(order.date_commande).toLocaleDateString('fr-FR')}</span>
                                            </div>
                                            <div class="order-details">
                                                <p>Total: ${order.total ? Number(order.total).toFixed(2) + ' €' : 'N/A'}</p>
                                                <p>Statut: <span class="status-badge status-${order.statut}">${order.statut}</span></p>
                                            </div>
                                        </div>
                                    `;
                                });
                                html += '</div>';
                            } else {
                                html = '<p style="text-align: center; padding: 20px;">📭 Aucune commande trouvée pour cet utilisateur.</p>';
                            }
                            modalBody.innerHTML = html;
                            modal.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('orders-modal-body').innerHTML = '<p style="color: red;">Erreur lors du chargement des commandes.</p>';
                            modal.style.display = 'block';
                        });
                });
            });

            // Bloquer/Débloquer un utilisateur
            document.querySelectorAll('.block-user, .unblock-user').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    const isBlocking = this.classList.contains('block-user');
                    const userName = this.closest('tr').querySelector('.user-info strong').textContent;
                    
                    const action = isBlocking ? 'bloquer' : 'débloquer';
                    if (confirm(`Êtes-vous sûr de vouloir ${action} "${userName}"?`)) {
                        fetch(`/ProjetPizza2/index.php?url=admin/${isBlocking ? 'block' : 'unblock'}User`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `user_id=${userId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(`Utilisateur ${action === 'bloquer' ? 'bloqué' : 'débloqué'} avec succès`);
                                location.reload();
                            } else {
                                alert(data.error || `Erreur lors de l'${action} de l'utilisateur`);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert(`Erreur lors de l'${action} de l'utilisateur`);
                        });
                    }
                });
            });

            // Supprimer un utilisateur
            document.querySelectorAll('.delete-user').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    const userName = this.closest('tr').querySelector('.user-info strong').textContent;
                    
                    if (confirm(`⚠️ Êtes-vous sûr de vouloir supprimer "${userName}"? Cette action est irréversible!`)) {
                        fetch(`/ProjetPizza2/index.php?url=admin/deleteUser`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `user_id=${userId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Utilisateur supprimé avec succès');
                                location.reload();
                            } else {
                                alert(data.error || 'Erreur lors de la suppression de l\'utilisateur');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la suppression de l\'utilisateur');
                        });
                    }
                });
            });

            // Actualiser la liste
            document.getElementById('refresh-users').addEventListener('click', function() {
                location.reload();
            });
        });
    </script>

    <style>
        .filters-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-controls {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-input, .filter-select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
        }

        .filter-select {
            min-width: 150px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .user-info small {
            color: #666;
            font-size: 12px;
        }

        .email-link {
            color: #007bff;
            text-decoration: none;
        }

        .email-link:hover {
            text-decoration: underline;
        }

        .role-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .role-admin {
            background: #e3f2fd;
            color: #1976d2;
        }

        .role-client {
            background: #e8f5e8;
            color: #388e3c;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .orders-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .orders-link:hover {
            text-decoration: underline;
        }

        .stats-section {
            margin-top: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 20px;
        }

        .close-modal {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }

        .close-modal:hover {
            color: #000;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .order-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-date {
            color: #666;
            font-size: 14px;
        }

        .order-details {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .order-details p {
            margin: 0;
        }
    </style>
</body>
</html>
