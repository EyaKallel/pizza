<?php ob_start(); ?>
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
                            <span class="badge badge-<?php echo $order['type_livraison'] === 'livraison' ? 'primary' : 'secondary'; ?>">
                                <?php echo $order['type_livraison_texte'] ?? $order['type_livraison']; ?>
                            </span>
                        </td>
                        <td><strong><?php echo number_format($order['total'], 2); ?> TND</strong></td>
                        <td>
                            <span class="badge badge-<?php echo $order['statut'] === 'livrée' ? 'success' : ($order['statut'] === 'en_livraison' ? 'warning' : ($order['statut'] === 'annulée' ? 'danger' : 'info')); ?>">
                                <?php echo $order['statut_texte'] ?? $order['statut']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-sm btn-primary" onclick="showOrderDetails(<?php echo $order['id']; ?>)">
                                    👁️ Détails
                                </button>
                                <select class="form-select" onchange="updateStatus(<?php echo $order['id']; ?>, this.value, this)">
                                    <option value="en_attente" <?php echo $order['statut'] === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                    <option value="confirmée" <?php echo $order['statut'] === 'confirmée' ? 'selected' : ''; ?>>Confirmée</option>
                                    <option value="en_livraison" <?php echo $order['statut'] === 'en_livraison' ? 'selected' : ''; ?>>En livraison</option>
                                    <option value="livrée" <?php echo $order['statut'] === 'livrée' ? 'selected' : ''; ?>>Livrée</option>
                                    <option value="annulée" <?php echo $order['statut'] === 'annulée' ? 'selected' : ''; ?>>Annulée</option>
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
                                                    <strong><?php echo htmlspecialchars($detail['affichage_nom']); ?></strong>
                                                    <div style="font-size: 0.9rem; color: var(--text-light);">
                                                        Taille: <?php echo $detail['taille']; ?> | 
                                                        Quantité: <?php echo $detail['quantite']; ?> | 
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
            // Cacher tous les autres détails
            document.querySelectorAll('[id^="details-"]').forEach(row => {
                row.style.display = 'none';
            });
            // Afficher les détails de cette commande
            detailsRow.style.display = 'table-row';
        } else {
            detailsRow.style.display = 'none';
        }
    }
    
    function updateStatus(orderId, newStatus, selectElement) {
        if (confirm('Changer le statut de la commande #' + orderId + ' à "' + newStatus + '" ?')) {
            fetch('/ProjetPizza2/index.php?url=admin/updateOrderStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=' + orderId + '&statut=' + newStatus
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + (data.error || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur de connexion');
            });
        } else {
            selectElement.value = selectElement.getAttribute('data-original');
        }
    }
    
    // Sauvegarder la valeur originale au chargement
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select').forEach(select => {
            select.setAttribute('data-original', select.value);
        });
    });
    </script>
 </div>

 <script>
    function updateStatus(orderId, newStatus, selectEl) {
        fetch('/ProjetPizza2/index.php?url=admin/updateOrderStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${encodeURIComponent(orderId)}&status=${encodeURIComponent(newStatus)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success && selectEl) {
                const row = selectEl.closest('tr');
                if (row) {
                    const badge = row.querySelector('td:nth-child(5) .badge');
                    if (badge) {
                        const statusText = {
                            'en_attente': 'En attente',
                            'en_cours': 'En cours',
                            'livrée': 'Livrée',
                            'annulée': 'Annulée'
                        };
                        const badgeType = (newStatus === 'livrée')
                            ? 'success'
                            : (newStatus === 'en_cours')
                                ? 'warning'
                                : (newStatus === 'annulée')
                                    ? 'danger'
                                    : 'info';

                        badge.className = `badge badge-${badgeType}`;
                        badge.textContent = statusText[newStatus] || newStatus;
                    }
                }
            }

            const container = document.querySelector('.admin-content');
            if (!container) {
                return;
            }

            const alert = document.createElement('div');
            alert.className = `alert ${data && data.success ? 'alert-success' : 'alert-danger'}`;
            alert.innerHTML = data && data.success
                ? '<span>✓</span> Statut mis à jour avec succès'
                : `<span>⚠</span> ${(data && data.error) ? data.error : 'Erreur lors de la mise à jour'}`;

            container.insertBefore(alert, container.firstChild);

            window.setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 3500);
        })
        .catch(() => {
            const container = document.querySelector('.admin-content');
            if (!container) {
                return;
            }

            const alert = document.createElement('div');
            alert.className = 'alert alert-danger';
            alert.innerHTML = '<span>⚠</span> Erreur lors de la mise à jour';
            container.insertBefore(alert, container.firstChild);
        });
    }
 </script>
<?php
$content = ob_get_clean();
$title = 'Gestion des Commandes';
$page_title = 'Commandes';
$active_page = 'orders';
require __DIR__ . '/layout.php';
?>
