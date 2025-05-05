<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>GESTION APPRENANT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/promo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/apprenant.css">
</head>
<body>

    <input type="checkbox" id="menu-toggle" hidden>
    <label for="menu-toggle" class="toggle-sidebar">☰ Menu</label>

    <div class="layout">
        <aside class="sidebar">
            <div>
                <div class="logo-container">
                    <div class="logo">
                        <img src="assets/images/d.png" alt="Logo">
                    </div>
                    <div class="promotion-label"><?= htmlspecialchars($name ?? 'Promotion') ?></div>
                </div>

                <nav>
                    <ul>
                        <li><a href="#"><i class="fas fa-home"></i> Tableau de bord</a></li>
                        <li><a href="index.php?page=dashboard&action=promotions"><i class="fas fa-folder"></i> Promotions</a></li>
                        <li><a href="index.php?page=dashboard&action=referentiels"><i class="fas fa-book"></i> Référentiels</a></li>
                        <li><a href="index.php?page=dashboard&action=apprenant"><i class="fas fa-users"></i> Apprenants</a></li>
                        <li><a href="#"><i class="fas fa-calendar-check"></i> Gestion des présences</a></li>
                        <li><a href="#"><i class="fas fa-laptop"></i> Kits & Laptops</a></li>
                        <li><a href="#"><i class="fas fa-chart-bar"></i> Rapports & Stats</a></li>
                    </ul>
                </nav>
            </div>

            <div class="logout">
                <form method="post" action="index.php">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit"><i class="fas fa-sign-out-alt"></i> Se déconnecter</button>
                </form>
            </div>
        </aside>
        <div class="main-area">
            <div class="topbar">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <div class="user-info">
                    <div class="user-avatar">
                        <img src="<?= htmlspecialchars($user['image'] ?? 'assets/images/a.gpeg') ?>" alt="Avatar">
                    </div>
                </div>
            </div>
            <main>
                <?= $content ?? '' ?>
            </main>   
        </div>
    </div>

</body>
</html>




