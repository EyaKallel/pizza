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
                                 <select class="form-select" onchange="updateStatus(<?php echo $order['id']; ?>, this.value, this)">
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
