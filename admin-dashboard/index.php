<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    header('Location: ../login.html');
    exit();
}

$admin_username = $_SESSION['admin_username'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - AVEC AMOUR</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../admin_panel.css">
    <style>
        .welcome-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            text-align: center;
        }
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .action-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }
        .action-card:hover {
            transform: translateY(-2px);
        }
        .action-card i {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-nav">
            <div class="logo-section">
                <img src="../logo.png" alt="Logo AVEC AMOUR" class="admin-logo">
                <h1 class="gradient-text">Dashboard Admin</h1>
            </div>
            <div class="admin-user">
                <i class="ri-user-line"></i>
                <span>Bienvenue, <?php echo htmlspecialchars($admin_username); ?></span>
                <a href="logout.php" class="logout-btn">
                    <i class="ri-logout-box-line"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    </header>

    <div class="admin-container">
        <main class="admin-main">
            <div class="welcome-message">
                <h2>🎉 Bienvenue dans le panneau d'administration !</h2>
                <p>Connecté en tant que : <strong><?php echo htmlspecialchars($admin_username); ?></strong></p>
                <p>Gérez votre boutique en ligne facilement depuis ce dashboard.</p>
            </div>

            <div class="quick-actions">
                <div class="action-card">
                    <i class="ri-shopping-bag-line"></i>
                    <h3>Gestion des Produits</h3>
                    <p>Ajouter, modifier ou supprimer des produits</p>
                    <a href="../admin_panel.html" class="btn-primary">Accéder</a>
                </div>

                <div class="action-card">
                    <i class="ri-file-list-line"></i>
                    <h3>Commandes</h3>
                    <p>Voir et gérer les commandes clients</p>
                    <a href="../admin_panel.html#orders" class="btn-primary">Voir les commandes</a>
                </div>

                <div class="action-card">
                    <i class="ri-group-line"></i>
                    <h3>Clients</h3>
                    <p>Gérer la base de données clients</p>
                    <a href="../admin_panel.html#customers" class="btn-primary">Gérer</a>
                </div>

                <div class="action-card">
                    <i class="ri-bar-chart-line"></i>
                    <h3>Analytics</h3>
                    <p>Voir les statistiques de vente</p>
                    <a href="../admin_panel.html#analytics" class="btn-primary">Voir stats</a>
                </div>
            </div>

            <div style="margin-top: 2rem; text-align: center;">
                <a href="../admin_panel.html" style="display: inline-block; background: #667eea; color: white; padding: 1rem 2rem; text-decoration: none; border-radius: 6px; font-weight: bold;">
                    🚀 Accéder au Panneau Complet
                </a>
            </div>
        </main>
    </div>

    <style>
        .btn-primary {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 1rem;
        }
        .btn-primary:hover {
            background: #5a67d8;
        }
        .admin-main {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</body>
</html>