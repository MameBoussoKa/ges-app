<?php
$errors = $errors ?? [];
$old_user = $old_user ?? ['login' => '', 'password' => ''];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ges-apprenants login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body class="body_form">
    <div class="conteneur">
        <img src="assets/images/logoApp.png" alt="logo site">
        <div class="bienvenu">
            <div class="bienvenu-1">Bienvenu sur</div>
            <div class="ecole">Ecole du code Sonatel Academy</div>
        </div>
        <div class="se-connecter">
            Se connecter
        </div>
        <?php if (!empty($message) && $message === 'DECONNEXION'): ?>
                <div class="success-message">Vous avez été déconnecté avec succès.</div>
            <?php endif; ?>
        <div class="formulaire">

            <?php if (isset($errors['loginPassword'])): ?>
                <div class="erreurs error-message"><?= $errors['loginPassword'] ?></div>
            <?php endif; ?>

            <form action="index.php?page=auth&action=login" method="POST">

                <div class="login">
                    <label for="login">Login</label>
                    <input type="text" placeholder="Matricule ou email" name="login"
                        value="<?= htmlspecialchars($old_user['login']) ?>"
                        class="<?= isset($errors['champs']) ? 'error-input' : '' ?>">
                    <?php if (isset($errors['champs'])): ?>
                        <div class="erreur-champ error-message"><?= $errors['champs']?></div>
                    <?php endif; ?>
                </div>

                <div class="password">
                    <label for="password">Mot de Passe</label>
                    <input type="password" placeholder="Mot de Passe" name="password"
                        class="<?= isset($errors['password']) ? 'error-input' : '' ?>">
                    <?php if (isset($errors['password'])): ?>
                        <div class="erreur-champ error-message"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>
                
                <a href="index.php?page=auth&action=forget_password">Mot de passe oublié ?</a>

                <div class="connexion">
                    <button type="submit">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
