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
</head>
<body>
    <div class="conteneur">
        <input type="checkbox" id="toggle-form" class="toggle-checkbox">
        
        <div class="en-tete">
            <div>
                <h2>Promotion</h2>
                <p>Gérer les promotions de l'école</p>
            </div>
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
                        <input type="text" name="recherche" placeholder="Rechercher une promotion..." value="<?= $_GET['recherche'] ?? '' ?>">
                    </div>
                    <div class="select-container">
                    <select name="reference" class="reference-select">
                    <option value="">Tous les référentiels</option>
                    <?php foreach ($referentiels as $ref): ?>
                        <option value="<?= htmlspecialchars($ref['nom']) ?>" 
                            <?= (isset($_GET['reference']) && $_GET['reference'] === $ref['nom'] ? 'selected' : '' )?>>
                            <?= htmlspecialchars($ref['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                    </div>
                    <div class="select-container">
                        <select name="statut" class="statut-select">
                            <option value="tous">Tous</option>
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-rechercher">Rechercher</button>
            </div>
        </form>

       
        <div id="formulaire-ajout">
            <h2>Créer une nouvelle promotion</h2>
            <h4>Remplir les informations ci-dessous pour créer une nouvelle promotion</h4>

            
            <form method="post" action="index.php?page=dashboard&action=ajout_promotion" enctype="multipart/form-data">
    <!-- Nom -->
    <div class="form-group">
        <label for="nom">Nom de la promotion</label>
        <input type="text" id="nom" name="nom"
               value="<?= htmlspecialchars($old_values['nom'] ?? '') ?>">
        <?php if (isset($errors['nom'])): ?>
            <span class="error"><?= htmlspecialchars($errors['nom']) ?></span>
        <?php endif; ?>
    </div>

    <!-- Dates -->
    <div class="date">
        <div class="form-group">
            <label for="debut">Date de début (JJ/MM/AAAA)</label>
            <input type="text" id="debut" name="date_debut" placeholder="JJ/MM/AAAA" 
                   value="<?= htmlspecialchars($old_values['date_debut'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="fin">Date de fin (JJ/MM/AAAA)</label>
            <input type="text" id="fin" name="date_fin" placeholder="JJ/MM/AAAA" 
                   value="<?= htmlspecialchars($old_values['date_fin'] ?? '') ?>">
        </div>
        <?php if (isset($errors['dates'])): ?>
            <span class="error"><?= htmlspecialchars($errors['dates']) ?></span>
        <?php endif; ?>
    </div>

    <!-- Photo -->
    <div class="form-group">
        <label for="photo">Logo de la promotion</label>
        <input type="file" id="photo" name="photo" accept="image/jpeg, image/png">
        <?php if (isset($errors['image'])): ?>
            <span class="error"><?= htmlspecialchars($errors['image']) ?></span>
        <?php endif; ?>
    </div>

    <!-- Référentiel -->
    <div class="form-group">
        <label for="referentiel">Référentiel</label>
        <select id="referentiel" name="referentiel">
            <option value="">Sélectionnez un référentiel</option>
            <?php foreach ($referentiels as $ref): ?>
                <option value="<?= htmlspecialchars($ref['nom']) ?>"
                    <?= (isset($old_values['referentiel']) && $old_values['referentiel'] === $ref['nom']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ref['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['referentiels'])): ?>
            <span class="error"><?= htmlspecialchars($errors['referentiels']) ?></span>
        <?php endif; ?>
    </div>

    <!-- Boutons -->
    <div class="btn-group">
        <a href="index.php?page=dashboard&action=promotions" class="btn cancel">Annuler</a>
        <button type="submit" class="btn submit">Créer la promotion</button>
    </div>
</form>
        </div>
</body>
</html>