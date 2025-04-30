<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/promotions.liste.css">

</head>
<body>
<div class="container">
      <h1>Promotion <span class="apprenants-count">180 apprenants</span></h1>
      <div class="filters">
        <input type="text" placeholder="Rechercher..." />
        <select>
        <option>Filtre par classe</option>
        </select>
        <select>
          <option>Filtre par status</option>
        </select>
        <button class="btn-add"><i class="fa-solid fa-user-plus"></i> Ajouter promotion</button>
      </div>

    <section class="summary-cards">
      <div class="card orange">
        <i class="fas fa-user-graduate"></i>
        <div>
          <h2><?= $dashboard[0]?></h2>
          <p>Apprenants</p>
        </div>
      </div>
      <div class="card orange">
        <i class="fas fa-folder-open"></i>
        <div>
          <h2><?= $dashboard[1]?></h2>
          <p>Référentiels</p>
        </div>
      </div>
      <div class="card orange">
        <i class="fas fa-briefcase"></i>
        <div>
          <h2><?= $dashboard[4]?></h2>
          <p>Stagiaires</p>
        </div>
      </div>
      <div class="card orange">
        <i class="fas fa-user-tie"></i>
        <div>
          <h2><?= $dashboard[5]?></h2>
          <p>Permanant</p>
        </div>
      </div>
    </section>

    <section class="table-section">
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Promotion</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Référentiels</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promotions as $promo): ?>
                <?php
                    $isActive = strtolower($promo['statu']) === 'active';
                    $statusClass = $isActive ? 'active' : 'inactive';
                    $statusColor = $isActive ? 'green' : 'gray';
                    $statusText = ucfirst($promo['statu']);
                ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($promo['image']) ?>" alt="photo" class="avatar" /></td>
                    <td><?= htmlspecialchars($promo['nom']) ?></td>
                    <td><?= date("d/m/Y", strtotime($promo['date_debut'])) ?></td>
                    <td><?= date("d/m/Y", strtotime($promo['date_fin'])) ?></td>
                    <td>
                        <?php foreach ($promo['referentiels'] as $ref): ?>
                            <?php
                                $nomRef = strtoupper(trim($ref['nom']));
                                $bgColor = '#eee'; 
                                $color = '#000';

                                [$bgColor, $color] = match ($nomRef) {
                                  'DEV WEB/MOBILE' => ['#d1f7c4', '#2e7d32'],
                                  'AWS'            => ['#cce5ff', '#004085'],
                                  'HACKEUSE'       => ['#f3d1ff', '#6f42c1'],
                                  'DEV DATA'       => ['#ffe5b4', '#e67e22'],
                                  'REF DIG'        => ['#ffd6e7', '#c2185b'],
                                  default          => ['#eee', '#000'],
                              };
                            ?>
                            <span class="tag" style="background-color: <?= $bgColor ?>; color: <?= $color ?>;">
                                <?= htmlspecialchars($ref['nom']) ?>
                            </span>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <span class="status <?= $statusClass ?>">
                            <i class="fa-solid fa-circle" style="color: <?= $statusColor ?>"></i> <?= $statusText ?>
                        </span>
                    </td>
                    <td><i class="fa-solid fa-ellipsis"></i></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>


<div class="pagination">
    <label>page</label>
    <select onchange="window.location.href='?page=dashboard&action=promotions&action_grille_liste=liste&page_num=' + this.value;">
        <?php
            $totalPages = ceil($totalPromos / $limit);
            for ($i = 1; $i <= $totalPages; $i++): ?>
                <option value="<?= $i ?>" <?= $i == $currentPage ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>

    <p><?= ($limit * ($currentPage - 1)) + 1 ?> à <?= min($limit * $currentPage, $totalPromos) ?> pour <?= $totalPromos ?></p>

    <div class="pagination-controls">
        <?php if ($currentPage > 1): ?>
            <a href="?page=dashboard&action=promotions&action_grille_liste=liste&page_num=<?= $currentPage - 1 ?>">&lt;</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="<?= $i == $currentPage ? 'active' : '' ?>" href="?page=dashboard&action=promotions&action_grille_liste=liste&page_num=<?= $i ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=dashboard&action=promotions&action_grille_liste=liste&page_num=<?= $currentPage + 1 ?>">&gt;</a>
        <?php endif; ?>
    </div>
</div>



</body>
</html>
