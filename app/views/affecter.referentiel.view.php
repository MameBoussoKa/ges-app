<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affecter un référentiel</title>
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .modal-title {
            font-size: 1.3rem;
            color: #2c3e50;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .btn-cancel {
            background-color: #f0f2f5;
            color: #7f8c8d;
            border: none;
        }
        
        .btn-submit {
            background-color: #3498db;
            color: white;
            border: none;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .referentiels-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }
        
        .referentiel-item {
            padding: 8px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        
        .referentiel-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Affecter le référentiel</h3>
            </div>
            
            <form action="index.php?page=dashboard&action=referentiels&actionRef=affecter" method="POST">
                <input type="hidden" name="referentiel_id" value="<?= $referentiel['id'] ?>">
                
                <div class="form-group">
                    <label class="form-label">Nom du référentiel à affecter</label>
                    <input type="text" class="form-select" value="<?= htmlspecialchars($referentiel['nom']) ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Promotion en cours</label>
                    <select name="promotion_nom" class="form-select" id="promotion-select" required>
                        <?php foreach($promotionsActives as $promo): ?>
                            <option value="<?= htmlspecialchars($promo['nom']) ?>">
                                <?= htmlspecialchars($promo['nom']) ?> (<?= $promo['date_debut'] ?> - <?= $promo['date_fin'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Référentiels déjà affectés</label>
                    <div class="referentiels-list" id="referentiels-list">
                        <?php foreach($promotionsActives[0]['referentiels'] ?? [] as $ref): ?>
                            <div class="referentiel-item">
                                <span><?= htmlspecialchars($ref['nom']) ?></span>
                                <span><?= $ref['nombre_apprenants'] ?> apprenants</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="window.history.back()">Annuler</button>
                    <button type="submit" class="btn btn-submit">Affecter</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>