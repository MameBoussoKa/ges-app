<?php
    namespace Errors\Fr;
    global $errors;

    use Enum\errors_message;


    $errors = [
        errors_message::CHAMP_VIDE->value => 'Le champs peut pas etre vide.',
        errors_message::CHAMP_PASSWORD->value => 'Le champs password ne peut pas etre vide.',
        errors_message::NOT_EXIST->value => 'L\'utilisateur n\'existe pas.',
        errors_message::LOGIN_PASSWORD->value => 'Login ou Password incorect.',
        errors_message::LOGIN_INTROUVABLE->value => 'login introuvable...',
        errors_message::PASSWORD_NOT_EGAL->value => 'Les mots de passe ne correspondent pas. ou champ vide',
        errors_message::UPDATE_SUCCESS->value => 'Mot de passe modifié avec succès !',
    ];

    function getAllErrors(){
        global $errors;
        return $errors;
    }