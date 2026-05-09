<?php ob_start(); ?>
 <div class="page-header">
     <h1 class="page-title">Gestion des Utilisateurs</h1>
     <p class="page-description">Voyez tous les clients, gérez les comptes et consultez les historiques</p>
 </div>

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

<?php
$content = ob_get_clean();
$title = 'Gestion des Utilisateurs';
$page_title = 'Utilisateurs';
$active_page = 'users';
require __DIR__ . '/layout.php';
?>
