<?php

    use Enum\session;
    use Enum\champ;
    use Enum\path;
    use Enum\model_auth;
    use Enum\EnumerationData;
    use Enum\Validateur_Connexion;
    use Enum\errors_message;
    USE Enum\Validateur;

    $validators = [
        Validateur_Connexion::CHAMP_VIDE->value => function(string $champs)use(&$errors):string|bool{

            return empty($champs) ? $errors[errors_message::CHAMP_VIDE->value] : false;

        },

        Validateur_Connexion::CHAMP_PASSWORD->value => function(string $pass)use(&$errors):string|bool{

            return empty($pass) ? $errors[errors_message::CHAMP_VIDE->value] : false;

        },

        Validateur::VALIDE_PHOTO->value => function(array $file)use($errors):bool{

            if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
                return false;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            return in_array($ext, ['jpg', 'jpeg', 'png'], true);
        },
        
        Validateur::VALIDE_DATE->value => function(string $date1, string $date2) use (&$errors): string|bool {

            if (empty($date1) || empty($date2)) {
                return $errors[errors_message::CHAMP_VIDE->value];
            }
        
            $format = 'd/m/Y';
            $d1 = DateTime::createFromFormat($format, $date1);
            $d2 = DateTime::createFromFormat($format, $date2);
            $aujourdhui = new DateTime();
            $aujourdhui->setTime(0, 0, 0);
        
            if (!$d1 || !$d2 || $d1->format($format) !== $date1 || $d2->format($format) !== $date2) {
                return "Format de date invalide. Utilisez jj/mm/aaaa.";
            }
        
            if ($d1 < $aujourdhui) {
                return "La date de début doit être aujourd'hui ou plus tard.";
            }
        
            if ($d1 >= $d2) {
                return "La date de début doit être antérieure à la date de fin.";
            }
        
            return false;
        }
    ];


    function getAllValidateurs(){
        global $validators;
        return $validators;
    }