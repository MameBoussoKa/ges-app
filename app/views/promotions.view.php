<?php
require_once __DIR__ . '/../services/session.service.php';
use Enum\session;
global $sessions;
$errors = $sessions[session::GET_SESSION->value]('errors') ?? [];
$old_values = $sessions[session::GET_SESSION->value]('old') ?? [];
$sessions[session::UNSET_SESSION->value]('errors');
$sessions[session::UNSET_SESSION->value]('old');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Promotions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/promotions.css">
    <style>
 
    </style>
</head>
<body>
    <div class="conteneur">
        <input type="checkbox" id="toggle-form" class="toggle-checkbox">
        
        <div class="en-tete">
            <div class="pmt">
                <h1>Promotion</h1>
                <p>Gérer les promotions de l'école</p>
            </div>
            <label for="toggle-form" class="ajouter">
                <a href="index.php?page=dashboard&action=promotions&action_grille_liste=ajouter_promotion">
                    <i class="fa-solid fa-plus"></i>
                    Ajouter une promotion
                </a>
            </label>
        </div>

        <div class="grille">
            <div class="item">
                <div class="apprenant">
                    <span><?= $dashboard[0]?></span>
                    <p>Apprenants</p>
                </div>
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="item">
                <div class="apprenant">
                    <span><?= $dashboard[1]?></span>
                    <p>Référentiels</p>
                </div>
                <i class="fa-solid fa-book"></i>
            </div>
            <div class="item">
                <div class="apprenant">
                    <span><?= $dashboard[2]?></span>
                    <p>Promotions actives</p>
                </div>
                <i class="fa-solid fa-check"></i>
            </div>
            <div class="item">
                <div class="apprenant">
                    <span><?= $dashboard[3]?></span>
                    <p>Total Promotions</p>
                </div>
                <i class="fa-solid fa-folder"></i>
            </div>
        </div>
        <div class="tous">
            <a href="index.php?page=dashboard&action=promotions&action_grille_liste=grille" class="btn-grille">Grille</a>
            <a href="index.php?page=dashboard&action=promotions&action_grille_liste=liste" class="btn-liste">Liste</a>
        </div>
        <form method="GET" action="index.php?page=dashboard&action=promotions&action_grille_liste=grille">
    <div class="barre-recherche">
        <input type="hidden" name="page" value="dashboard">
        <input type="hidden" name="action" value="promotions">
        <input type="hidden" name="action_grille_liste" value="grille">
        <div class="barre">
            <div>
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="recherche" 
                       placeholder="Rechercher une promotion..." 
                       value="<?= htmlspecialchars($_GET['recherche'] ?? '') ?>">
            </div>
            <!-- <div class="select-container">
                <select name="reference" class="reference-select">
                    <option value="">Tous les référentiels</option>
                    <?php foreach ($referentiels as $ref): ?>
                        <option value="<?= htmlspecialchars($ref['nom']) ?>" 
                            <?= (isset($_GET['reference']) && $_GET['reference'] === $ref['nom'] ? 'selected' : '' )?>>
                            <?= htmlspecialchars($ref['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div> -->
            <div class="select-container">
                <select name="statut" class="statut-select">
                    <option value="tous" <?= (isset($_GET['statut']) && $_GET['statut'] === 'tous' ? 'selected' : '' )?>>Tous</option>
                    <option value="actif" <?= (isset($_GET['statut']) && $_GET['statut'] === 'actif' ? 'selected' : '' )?>>Actif</option>
                    <option value="inactif" <?= (isset($_GET['statut']) && $_GET['statut'] === 'inactif' ? 'selected' : '' )?>>Inactif</option>
                </select>
            </div>
        </div>
        <!-- <button type="submit" class="btn-rechercher">Rechercher</button> -->
    </div>
</form>

<!-- Section pour afficher les promotions ou le message d'absence de résultats -->
<div class="grille-promo">
    <?php if (empty($promotions)): ?>
        <div class="aucun-resultat">
            <i class="fa-solid fa-magnifying-glass"></i>
            <p>Aucune promotion trouvée pour votre recherche</p>
            <a href="index.php?page=dashboard&action=promotions&action_grille_liste=grille" class="btn-reinitialiser">
                Réinitialiser la recherche
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($promotions as $promo): ?>
            <?php
                $isActive = ($promo['statu']) === 'active';
                $buttonClass = $isActive ? 'btn-active' : 'btn-inactive';
                $iconClass = $isActive ? 'power-red' : 'power-green';
                $buttonText = $isActive ? 'Active' : 'Inactive';
            ?>
            <div class="item1">
                <div class="active-desactive">
                    <button class="<?= $buttonClass ?>"><?= $buttonText ?></button>
                    <?php if (!$isActive): ?>
                        <a href="index.php?page=dashboard&action=desactiver_activer&promo=<?= $promo['nom'] ?>" class="power-off-btn <?= $iconClass ?>">
                            <i class="fa-solid fa-power-off"></i>
                        </a>
                    <?php else: ?>
                        <span class="disabled-power-btn">
                            <i class="fa-solid fa-power-off"></i>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="promo">
                    <img src="<?= htmlspecialchars($promo['image']) ?>" alt="logo promo">
                    <div class="numero-promo">
                        <span><?= htmlspecialchars($promo['nom']) ?></span>
                        <div>
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>Début : <?= date("d-m-Y", strtotime($promo['date_debut'])) ?></span>
                            <span>- Fin : <?= date("d-m-Y", strtotime($promo['date_fin'])) ?></span>
                        </div>
                    </div>
                </div>
                <div class="apprenants">
                    <i class="fa-regular fa-user"></i>
                    <span><?= htmlspecialchars($promo['nombre_apprenants']) ?> Apprenants</span>
                </div>
                <div class="divider"></div>
                <div class="voir-plus">
                    <a href="#">Voir plus</a>
                    <i class="fa-solid fa-angle-right"></i>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
        <div class="pagination">
            <div class="page">
                <label>Page</label>
                <select onchange="window.location.href='?page=dashboard&action=promotions&action_grille_liste=grille&page_num=' + this.value;">
                    <?php
                        $totalPages = ceil($totalPromos / $limit);
                        for ($i = 1; $i <= $totalPages; $i++): ?>
                            <option value="<?= $i ?>" <?= $i == $currentPage ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <p class="pour"><?= ($limit * ($currentPage - 1)) + 1 ?> à <?= min($limit * $currentPage, $totalPromos) ?> sur <?= $totalPromos ?></p>

            <div class="pagination-controls">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=dashboard&action=promotions&action_grille_liste=grille&page_num=<?= $currentPage - 1 ?>">&lt;</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=dashboard&action=promotions&action_grille_liste=grille&page_num=<?= $i ?>" class="<?= $i == $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=dashboard&action=promotions&action_grille_liste=grille&page_num=<?= $currentPage + 1 ?>">&gt;</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="overlay"></div>
    </div>
</body>
</html>