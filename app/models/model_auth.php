<?php

    namespace Models\Auth;

    global $model_auth;

    use Enum\session;
    use Enum\champ;
    use Enum\path;
    use Enum\model_auth;
    use Enum\EnumerationData;
    use Enum\Validateur_Connexion;
    use Enum\errors_message;


    $model_auth = [

        model_auth::GET_USER_BY_LOGIN_PASSWORD->value => function(string $login, string $password) use (&$models) {
            $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
        
            $result = array_values(array_filter($tableau['users'], function($u) use ($login, $password) {
                return $u['login'] === $login && $u['password'] === $password;
            }));
        
            return $result[0] ?? null;
        },

        model_auth::USER_EXIST->value => function(array $user)use(&$models):int{

            $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);

            $index = -1;

            array_walk($tableau['users'] , function($value , $cle)use(&$user , &$index){

                if ($value['login'] == $user['login'] && $value['password'] == $user['password']) {
                    $index = $cle;
                }
            });

            return $index;
        },


        model_auth::VALIDATE_USER->value => function(array $user)use(&$validators ,&$models ,&$errors,&$model_auth):array{

            $error_mess = [];

            if ($validators[ Validateur_Connexion::CHAMP_VIDE->value]($user['login']) != false) {
                $error_mess['champs'] =  $errors[errors_message::CHAMP_VIDE->value];
            }

            if ( $validators[ Validateur_Connexion::CHAMP_VIDE->value]($user['password']) != false) {
                $error_mess['password'] =  $errors[errors_message::CHAMP_PASSWORD->value];
            }
            if ($model_auth[model_auth::USER_EXIST->value]($user) == -1) {
                $error_mess['loginPassword'] = $errors[ errors_message::LOGIN_PASSWORD->value];
            }

            return $error_mess;
            
        },


        model_auth::CREATE_USER->value => function(string $login, string $password): array {
            return [
                'login' => $login,
                'password' => $password,
            ];
        },

       
        model_auth::PASSWORD_CONFIRME_PASSWORD->value => function($password, $confirm_password) {
            if ($password !== $confirm_password || $password == '' || $confirm_password == '') {
                return false;
            }
            return true;
        },
];


    function getAllmodel(){
        global $model_auth;
        return $model_auth;
    }
    