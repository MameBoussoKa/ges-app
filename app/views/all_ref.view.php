<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Référentiels</title>
    <link rel="stylesheet" href="assets/css/referentiel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
</head>
<body>
    <div class="main-content">
        <h2>Référentiels</h2>
        <p class="soustitre">Gérer les référentiels de la promotion active</p>

        <input type="checkbox" id="modal-toggle" hidden>
        
    <div class="search-section">
        <form method="POST" action="index.php?page=dashboard&action=referentiels" class = "barre">
            <input type="hidden" name="actionRef" value="search">
            <input type="text" name="searchQuery" placeholder="Rechercher un référentiel..." class="search-input-1">
        </form>

        <button class="all-btn">
            <a href="index.php?page=dashboard&action=referentiels&actionRef=tout_ref"> <i class="fas fa-list"></i> Tous les référentiels</a>
        </button>
        <label for="modal-toggle" class="add-btn">
            <a href="index.php?page=dashboard&action=referentiels&actionRef=voir_formulaire">
                <i class="fas fa-plus"></i> Affecter à une promotion
            </a>
        </label>
        </div>

<div class="modal-container">
    <div class="modal">
        <h2>Créer un nouveau référentiel</h2>
        <form class="form-container" action="index.php?page=dashboard&action=referentiels&actionRef=ajouter" method="POST" enctype="multipart/form-data">
            <div class="photo-box">
                <label for="photo-upload" class="photo-label">
                    <div class="upload-area">
                        <i class="fa-regular fa-image"></i>
                        <p>Cliquez pour ajouter une photo</p>
                    </div>
                </label>
                <input type="file" id="photo-upload" name="photo" accept="image/*">
            </div>
            <div class="form-group">
                <label for="nom">Nom*</label>
                <input type="text" id="nom" name="nom">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="capacite">Capacité*</label>
                    <input type="number" id="capacite" name="capacite" value="30">
                </div>
                <div class="form-group">
                    <label for="sessions">Nombre de sessions*</label>
                    <select id="sessions" name="sessions">
                        <option value="1">1 session</option>
                        <option value="2">2 sessions</option>
                        <option value="3">3 sessions</option>
                    </select>
                </div>
            </div>
            <div class="modal-buttons">
                <label for="modal-toggle" class="cancel-btn">Annuler</label>
                <button type="submit" class="create-btn">Créer</button>
            </div>
        </form>
    </div>
</div>
        <!-- Liste des référentiels -->
        <div class="referentiel-container">
            <?php if (!empty($referentiels)): ?>
                <?php foreach ($referentiels as $referentiel): ?>
                    <div class="referentiel-card">
                        <div class="image-container">
                            <img src="<?= htmlspecialchars($referentiel['image'] ?? 'assets/images/m.jpg') ?>" 
                                 alt="<?= htmlspecialchars($referentiel['nom']) ?>">
                        </div>
                        <div class="card-content">
                            <h3><?= htmlspecialchars($referentiel['nom']) ?></h3>
                            <h4><?= $referentiel['modules'] ?? 0 ?> module<?= ($referentiel['modules'] ?? 0) > 1 ? 's' : '' ?></h4>
                            <h5><?= nl2br(htmlspecialchars($referentiel['description'] ?? '')) ?></h5>
                            <div class="trait-vert"></div>
                            <div class="apprenants-info">
                                <div class="cercle1"></div>
                                <div class="cercle2"></div>
                                <div class="cercle3"></div>
                                <p><strong><?= $referentiel['apprenants'] ?? 0 ?> apprenant<?= ($referentiel['apprenants'] ?? 0) > 1 ? 's' : '' ?></strong></p>
                            </div>
                            <!-- <a href="index.php?page=dashboard&action=referentiels&actionRef=affecter_form&id=<?= $referentiel['id'] ?>" 
                            class="btn-affecter">
                            Affecter à une promotion
                            </a> -->
                          
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-data">Aucun référentiel disponible pour la promotion active</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>