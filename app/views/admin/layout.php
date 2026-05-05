<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Administration'; ?> - Smart Pizzeria</title>
    <link rel="stylesheet" href="/ProjetPizza2/public/css/admin-professional.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-header">
                <h1>Smart Pizzeria</h1>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="/ProjetPizza2/index.php?url=admin/orders" class="<?php echo $active_page === 'orders' ? 'active' : ''; ?>">Commandes</a></li>
                    <li><a href="/ProjetPizza2/index.php?url=admin/products" class="<?php echo $active_page === 'products' ? 'active' : ''; ?>">Produits</a></li>
                    <li><a href="/ProjetPizza2/index.php?url=admin/ingredients" class="<?php echo $active_page === 'ingredients' ? 'active' : ''; ?>">Ingrédients</a></li>
                    <li><a href="/ProjetPizza2/index.php?url=admin/users" class="<?php echo $active_page === 'users' ? 'active' : ''; ?>">Utilisateurs</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <div>
                    <h2 class="page-title"><?php echo $page_title ?? 'Administration'; ?></h2>
                </div>
                <div class="admin-user-info">
                    <span>Bonjour, <?php echo $_SESSION['prenom'] ?? 'Admin'; ?></span>
                    <div class="admin-avatar">
                        <?php echo strtoupper(substr($_SESSION['prenom'] ?? 'A', 0)); ?>
                    </div>
                    <a href="/ProjetPizza2/index.php?url=auth/logout" class="btn btn-secondary btn-sm">Déconnexion</a>
                </div>
            </header>

            <!-- Page Content -->
            <div class="admin-content">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <span>✓</span>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <span>⚠</span>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php echo $content ?? ''; ?>
            </div>
        </main>
    </div>
</body>
</html>
