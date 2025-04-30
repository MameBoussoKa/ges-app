<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un nouveau référentiel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .creation-container {
            border-radius: 12px;
            width: 100%;
            padding: 30px;
            margin-top: 20px;
            background-color: #f7fbfc;
        }

        .creation-header {
            margin-bottom: 25px;
        }

        .creation-header h2 {
            color: #2c3e50;
            font-weight: 600;
            font-size: 24px;
        }

        .creation-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Upload photo */
        .photo-section {
            text-align: center;
            margin-bottom: 15px;
        }

        .photo-label {
            display: inline-block;
            cursor: pointer;
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 2px dashed #ccc;
            margin: 0 auto;
        }

        .photo-preview i {
            font-size: 40px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .photo-preview p {
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Champs de formulaire */
        .form-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-field label {
            color: #34495e;
            font-weight: 500;
            font-size: 14px;
        }

        .form-field label.required:after {
            content: " *";
            color: #e74c3c;
        }

        .form-input {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s ease;
        }

        .form-input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Disposition en ligne */
        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-field {
            flex: 1;
        }

        /* Boutons */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
        }

        .form-button {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .button-cancel {
            background-color: #f0f2f5;
            color: #7f8c8d;
            border: none;
        }

        .button-cancel:hover {
            background-color: #e0e3e6;
        }

        .button-submit {
            background-color: #3498db;
            color: white;
            border: none;
        }

        .button-submit:hover {
            background-color: #2980b9;
        }

        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
        }

        .input-error {
            border-color: #e74c3c !important;
        }

        .error-container {
            color: #e74c3c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #fdecea;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="creation-container">
        <div class="creation-header">
            <h2>Créer un nouveau référentiel</h2>
        </div>
        
        <!-- <?php if (!empty($errors)): ?>
            <div class="error-container">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?> -->
        
        <form class="creation-form" action="index.php?page=dashboard&action=referentiels&actionRef=ajouter" method="POST" enctype="multipart/form-data">
            <div class="photo-section">
                <label for="photo-upload" class="photo-label">
                    <div class="photo-preview">
                        <i class="fa-regular fa-image"></i>
                        <p>Cliquez pour ajouter une photo</p>
                        <input type="file" id="photo-upload" name="photo" accept="image/*" class="hidden-upload">
                    </div>
                </label>
                <?php if (!empty($errors['photo'])): ?>
                    <p class="error-message"><?= htmlspecialchars($errors['photo']) ?></p>
                <?php endif; ?>
            </div>
            
            <div class="form-field">
                <label for="nom" class="required">Nom</label>
                <input type="text" id="nom" name="nom" class="form-input <?= !empty($errors['nom']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($nom ?? '') ?>">
                <?php if (!empty($errors['nom'])): ?>
                    <p class="error-message"><?= htmlspecialchars($errors['nom']) ?></p>
                <?php endif; ?>
            </div>
            
            <div class="form-field">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-input form-textarea"><?= htmlspecialchars($description ?? '') ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-field">
                    <label for="capacite" class="required">Capacité</label>
                    <input type="number" id="capacite" name="capacite" value="<?= htmlspecialchars($capacite ?? '30') ?>" class="form-input <?= !empty($errors['capacite']) ? 'input-error' : '' ?>">
                    <?php if (!empty($errors['capacite'])): ?>
                        <p class="error-message"><?= htmlspecialchars($errors['capacite']) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="form-field">
                    <label for="sessions" class="required">Nombre de sessions</label>
                    <select id="sessions" name="sessions" class="form-input">
                        <option value="1" <?= (isset($sessions) && $sessions == '1') ? 'selected' : '' ?>>1 session</option>
                        <option value="2" <?= (isset($sessions) && $sessions == '2') ? 'selected' : '' ?>>2 sessions</option>
                        <option value="3" <?= (isset($sessions) && $sessions == '3') ? 'selected' : '' ?>>3 sessions</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="index.php?page=dashboard&action=referentiels" class="form-button button-cancel">Annuler</a>
                <button type="submit" class="form-button button-submit">Créer</button>
            </div>
        </form>
    </div>

    <style>
        .hidden-upload {
            position: absolute;
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            z-index: -1;
        }

        .photo-label {
            display: block;
            cursor: pointer;
        }

        .photo-preview {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 8px;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 2px dashed #ccc;
            margin: 0 auto;
        }
    </style>
</body>
</html>