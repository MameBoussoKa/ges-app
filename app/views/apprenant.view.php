<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/assets/css/apprenant.css">
  <title>Apprenants</title>
 
</head>
<body>
  <div class="navbar">
    <div class="logo">
      Apprenants <span>Dashboard</span>
    </div>
    <div class="search-container">
      <input type="text" class="search-input" placeholder="Recherche...">
      <input type="text" class="filter-input" placeholder="Filtrer par statut...">
    </div>
    <div class="actions">
      <button class="btn btn-primary">+ Nouvel Apprenant</button>
    </div>
  </div>
  
  <div class="content">
    <div class="info-bar">
      <h2>Liste des apprenants</h2>
    </div>
    
    <div class="table-container">
      <form action="index.php?page=dashboard&action=apprenant&action=apprenant">
      <table>
        <thead>
          <tr>
            <th>Photo</th>
            <th>Matricule</th>
            <th>Nom complet</th>
            <th>Adresse</th>
            <th>Téléphone</th>
            <th>Référentiel</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($apprenants)): ?>
            <?php foreach ($apprenants as $apprenant): ?>
              <tr>
                <td>
                  <img src="<?= htmlspecialchars($apprenant['photo']) ?>" alt="Photo de <?= htmlspecialchars($apprenant['nom_complet']) ?>" width="50" height="50" style="border-radius: 50%;">
                </td>
                <td><?= htmlspecialchars($apprenant['matricule']) ?></td>
                <td><?= htmlspecialchars($apprenant['nom_complet']) ?></td>
                <td><?= htmlspecialchars($apprenant['adresse']) ?></td>
                <td><?= htmlspecialchars($apprenant['telephone']) ?></td>
                <td><?= htmlspecialchars($apprenant['referentiel']) ?></td>
                <td>
                  <span class="status <?= $apprenant['statut'] === 'actif' ? 'status-active' : 'status-inactive' ?>">
                    <?= strtoupper($apprenant['statut']) ?>
                  </span>
                </td>
                <td class="actions-cell">
                  <span class="action-menu">...</span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8">Aucun apprenant trouvé.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      </form>
      <div class="pagination">
        <div class="page-info">Affichant 1-8 sur 24</div>
        <div class="page-item">1</div>
        <div class="page-item active">2</div>
        <div class="page-item">3</div>
        <div class="page-item">></div>
      </div>
    </div>
  </div>
</body>
</html>