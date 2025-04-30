<?php require_once __DIR__.'/../enum/all_enums.php'; 
use Enum\errors_message;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <style>
        :root {
            --couleur-blanc: #ffffff;
            --couleur-orange: #ff7700;
            --couleur-texte: #030303;
            --couleur-texte-2: #3d3939;
            --couleur-texte-3: #cccccc;
            --couleur-vert-ment: #00938e;
            --couleur-bordure: #dadada;
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--couleur-vert-ment);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: var(--couleur-blanc);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: var(--couleur-texte);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            color: var(--couleur-texte-2);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        input[type="text"] {
            padding: 0.75rem;
            border: 1px solid var(--couleur-bordure);
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 1rem;
            transition: border 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: var(--couleur-orange);
            outline: none;
        }

        button {
            background-color: var(--couleur-orange);
            color: var(--couleur-blanc);
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e46600;
        }

        .error-message {
            color: red;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--couleur-vert-ment);
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Mot de passe oublié</h2>

        <?php if (!empty($errors[errors_message::LOGIN_INTROUVABLE->value])): ?>
            <div class="error-message"><?= $errors[errors_message::LOGIN_INTROUVABLE->value] ?></div>
        <?php endif; ?>

        <form action="index.php?page=auth&action=verify_email" method="POST">
            <label for="login">Entrez votre login</label>
            <input type="text" name="login" id="login">
            <button type="submit">Vérifier</button>
        </form>

        <a class="back-link" href="index.php?page=auth&action=voir_formulaire">← Retour</a>
    </div>

</body>
</html>
