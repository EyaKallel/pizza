<?php ob_start(); ?>
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title">Gestion des Commandes</h1>
        <p class="page-description">Consultez et gérez toutes les commandes des clients</p>
    </div>
</div>

<?php if (isset($stats)): ?>
<div class="admin-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid var(--primary-color);">
        <div class="stat-icon" style="font-size: 2rem; margin-bottom: 0.5rem;">📦</div>
        <div class="stat-info">
            <span style="color: var(--text-light); font-size: 0.9rem; font-weight: 500;">Total Commandes</span>
            <h3 style="font-size: 1.8rem; margin: 0; color: var(--text-dark);"><?php echo $stats['total_orders'] ?? 0; ?></h3>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #f1c40f;">
        <div class="stat-icon" style="font-size: 2rem; margin-bottom: 0.5rem;">⏳</div>
        <div class="stat-info">
            <span style="color: var(--text-light); font-size: 0.9rem; font-weight: 500;">En attente</span>
            <h3 style="font-size: 1.8rem; margin: 0; color: var(--text-dark);"><?php echo $stats['pending_orders'] ?? 0; ?></h3>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #2ecc71;">
        <div class="stat-icon" style="font-size: 2rem; margin-bottom: 0.5rem;">🍕</div>
        <div class="stat-info">
            <span style="color: var(--text-light); font-size: 0.9rem; font-weight: 500;">Produits</span>
            <h3 style="font-size: 1.8rem; margin: 0; color: var(--text-dark);"><?php echo $stats['total_products'] ?? 0; ?></h3>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #3498db;">
        <div class="stat-icon" style="font-size: 2rem; margin-bottom: 0.5rem;">👥</div>
        <div class="stat-info">
            <span style="color: var(--text-light); font-size: 0.9rem; font-weight: 500;">Utilisateurs</span>
            <h3 style="font-size: 1.8rem; margin: 0; color: var(--text-dark);"><?php echo $stats['total_users'] ?? 0; ?></h3>
        </div>
    </div>
</div>
<?php endif; ?>

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
                <th>Contact</th>
                <th>Date</th>
                <th>Type</th>
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
                            <div style="font-weight: 600;">
                                <?php 
                                if ($order['nom'] && $order['prenom']) {
                                    echo $order['prenom'] . ' ' . $order['nom'];
                                } elseif ($order['email']) {
                                    echo $order['email'];
                                } else {
                                    echo 'Client inconnu';
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; color: var(--text-light);">
                                <?php if (!empty($order['telephone'])): ?>
                                    📱 <?php echo htmlspecialchars($order['telephone']); ?><br>
                                <?php endif; ?>
                                <?php if (!empty($order['adresse'])): ?>
                                    📍 <?php echo htmlspecialchars(substr($order['adresse'], 0, 30)); ?><?php echo strlen($order['adresse']) > 30 ? '...' : ''; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['date_commande'])); ?></td>
                        <td>
                            <span class="badge badge-<?php echo ($order['type_livraison'] ?? '') === 'livraison' ? 'primary' : 'secondary'; ?>">
                                <?php echo $order['type_livraison_texte'] ?? ($order['type_livraison'] ?? 'N/A'); ?>
                            </span>
                        </td>
                        <td><strong><?php echo number_format($order['total'], 2); ?> TND</strong></td>
                        <td>
                            <span class="badge badge-<?php 
                                $s = $order['statut'] ?? '';
                                echo $s === 'livrée' ? 'success' : ($s === 'en_livraison' ? 'warning' : ($s === 'annulée' ? 'danger' : 'info')); 
                            ?>">
                                <?php echo $order['statut_texte'] ?? ($order['statut'] ?? 'N/A'); ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-sm btn-primary" onclick="showOrderDetails(<?php echo $order['id']; ?>)">
                                    👁️ Détails
                                </button>
                                <select class="form-select" onchange="updateStatus(<?php echo $order['id']; ?>, this.value, this)">
                                    <option value="en_attente" <?php echo ($order['statut'] ?? '') === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                    <option value="confirmée" <?php echo ($order['statut'] ?? '') === 'confirmée' ? 'selected' : ''; ?>>Confirmée</option>
                                    <option value="en_livraison" <?php echo ($order['statut'] ?? '') === 'en_livraison' ? 'selected' : ''; ?>>En livraison</option>
                                    <option value="livrée" <?php echo ($order['statut'] ?? '') === 'livrée' ? 'selected' : ''; ?>>Livrée</option>
                                    <option value="annulée" <?php echo ($order['statut'] ?? '') === 'annulée' ? 'selected' : ''; ?>>Annulée</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr id="details-<?php echo $order['id']; ?>" style="display: none;">
                        <td colspan="8" style="background: var(--bg-light); padding: 1.5rem;">
                            <h4 style="margin-bottom: 1rem; color: var(--primary-color);">
                                📦 Détails de la commande #<?php echo $order['id']; ?>
                            </h4>
                            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                <div>
                                    <h5 style="margin-bottom: 0.5rem; color: var(--text-dark);">📋 Produits commandés</h5>
                                    <?php if (!empty($order['details'])): ?>
                                        <div style="background: white; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                                            <?php foreach ($order['details'] as $detail): ?>
                                                <div style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                                                    <strong><?php echo htmlspecialchars($detail['affichage_nom'] ?? 'Produit'); ?></strong>
                                                    <div style="font-size: 0.9rem; color: var(--text-light);">
                                                        Taille: <?php echo $detail['taille'] ?? 'N/A'; ?> | 
                                                        Quantité: <?php echo $detail['quantite'] ?? 0; ?> | 
                                                        Prix: <?php echo number_format($detail['prix_unitaire'] ?? 0, 2); ?> TND
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p style="color: var(--text-light);">Aucun détail trouvé</p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h5 style="margin-bottom: 0.5rem; color: var(--text-dark);">📝 Informations complémentaires</h5>
                                    <div style="background: white; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                                        <p><strong>📧 Email:</strong> <?php echo htmlspecialchars($order['email'] ?? 'Non spécifié'); ?></p>
                                        <?php if (!empty($order['telephone'])): ?>
                                            <p><strong>📱 Téléphone:</strong> <?php echo htmlspecialchars($order['telephone']); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($order['adresse'])): ?>
                                            <p><strong>📍 Adresse:</strong> <?php echo htmlspecialchars($order['adresse']); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($order['note_client'])): ?>
                                            <p><strong>📝 Note client:</strong> <?php echo htmlspecialchars($order['note_client']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem;">
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

    <script>
    function showOrderDetails(orderId) {
        const detailsRow = document.getElementById('details-' + orderId);
        if (detailsRow.style.display === 'none') {
            document.querySelectorAll('[id^="details-"]').forEach(row => {
                row.style.display = 'none';
            });
            detailsRow.style.display = 'table-row';
        } else {
            detailsRow.style.display = 'none';
        }
    }
    
    function updateStatus(orderId, newStatus, selectElement) {
        if (confirm('Changer le statut de la commande #' + orderId + ' à "' + newStatus + '" ?')) {
            const formData = new URLSearchParams();
            formData.append('order_id', orderId);
            formData.append('status', newStatus);

            fetch('/ProjetPizza2/index.php?url=admin/updateOrderStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = selectElement.closest('tr');
                    const badge = row.querySelector('td:nth-child(7) .badge');
                    if (badge) {
                        const statusClasses = {
                            'livrée': 'success',
                            'en_livraison': 'warning',
                            'annulée': 'danger',
                            'en_attente': 'info',
                            'confirmée': 'info'
                        };
                        badge.className = 'badge badge-' + (statusClasses[newStatus] || 'secondary');
                        badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1).replace('_', ' ');
                    }
                    
                    if (window.showSuccess) {
                        window.showSuccess('Statut mis à jour avec succès');
                    } else {
                        alert('Statut mis à jour');
                    }
                    
                    selectElement.setAttribute('data-original', newStatus);
                } else {
                    alert('Erreur: ' + (data.error || 'Erreur inconnue'));
                    selectElement.value = selectElement.getAttribute('data-original');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur de connexion');
                selectElement.value = selectElement.getAttribute('data-original');
            });
        } else {
            selectElement.value = selectElement.getAttribute('data-original');
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select.form-select').forEach(select => {
            select.setAttribute('data-original', select.value);
        });
    });
    </script>
</div>

<?php
$content = ob_get_clean();
$title = 'Gestion des Commandes';
$page_title = 'Commandes';
$active_page = 'orders';
require __DIR__ . '/layout.php';
?>

