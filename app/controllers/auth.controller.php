<?php

    namespace Controller\Auth_Controller;

    use Enum\session;
    use Enum\champ;
    use Enum\path;
    use Enum\model_auth;
    use Enum\EnumerationData;
    use Enum\promotion;
    use function Models\Auth\getAllModel;
    use function  Service\Session\getAllSession;
    use function Errors\Fr\getAllErrors;
    use function Models\getAllModels;
    use Enum\errors_message;
    use function Models\Promotions\getAllPromotions;



    function handle_request():void{

        $sessions = getAllSession();

        $action =  $sessions[session::REQUEST->value]('action') ??  'voir_formulaire';
        $message = $sessions[session::GET->value]('message') ?? null;

        match ($action) {
            'login' => connexion_utilisateurs(),

            'voir_formulaire' => voir_formulaire(),

            'forget_password' => forget_password(),

            'verify_email' => verifier_email(),

            'update_password' => changer_mot_de_passe(),

            'logout' => deconnexion_utilisateur(),

             default => '',
        };
        
    }

    function voir_formulaire(){
        $sessions = getAllSession();
        $message = $sessions[session::GET->value]('message') ?? null;

        render_view('login' , ['message' => $message]);
    }

    function forget_password(){
        $sessions = getAllSession();
        $message = $sessions[session::GET->value]('message') ?? null;

        render_view('forget_password');
    }


    function connexion_utilisateurs() {
        $model_auth = getAllModel();
        $sessions = getAllSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $sessions[session::POST->value](champ::LOGIN->value) ?? '';
            $password = $sessions[session::POST->value](champ::PASSWORD->value) ?? '';

            $user = $model_auth[model_auth::CREATE_USER->value]($login, $password);
            $errors = $model_auth[model_auth::VALIDATE_USER->value]($user);

            if (empty($errors)) {
                $user = $model_auth[model_auth::GET_USER_BY_LOGIN_PASSWORD->value]($login, $password);
                $sessions[session::SET_SESSION->value]('user', $user);

                // Redirection vers la page des promotions
                redirect_to_root('dashboard&action=promotions&action_grille_liste=grille');
            } else {
                render_view('login', [
                    'errors' => $errors,
                    'old_user' => $user
                ]);
            }
        }
    }



    function verifier_email(): void {
        $sessions = getAllSession();
        $models = getAllModels();
        $errors = getAllErrors();

        $login = $sessions[session::POST->value](champ::LOGIN->value) ?? '';

        $data = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
    
        $user = array_filter($data['users'], fn($u) => $u['login'] === $login);

        if (!empty($user)) {
            $sessions[session::SET_SESSION->value]('user',$user);
            render_view('new_password');
        } else {
            render_view('forget_password', ['errors' => $errors]);
        }
    }
    



    function changer_mot_de_passe(): void {
        $sessions = getAllSession();
        $models = getAllModels();
        $errors = getAllErrors();
        $model_auth = getAllModel();
        $password = $sessions[session::POST->value](champ::PASSWORD->value) ?? '';
        $confirm_password = $sessions[session::POST->value](champ::CONFIRM_PASSWORD->value) ?? '';
        $user_array = $sessions[session::GET_SESSION->value]('user') ?? [];
        $user = $user_array[0] ?? null;
        if (!$user || !isset($user['login'])) {
            render_view('forget_password', ['errors' => [$errors[errors_message::NOT_EXIST->value]]]);
            return;
        }
        if (!$model_auth[model_auth::PASSWORD_CONFIRME_PASSWORD->value]($password, $confirm_password)) {
            render_view('new_password', ['errors' => [$errors[errors_message::PASSWORD_NOT_EGAL->value]]]);
            return;
        }
        $data = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
        
        $data['users'] = array_map(function($u) use ($user, $password) {
            if ($u['login'] === $user['login']) {
                $u['password'] = $password;
            }
            return $u;
        }, $data['users']);
    
        $models[EnumerationData::ARRAY_TO_JSON->value]($data, path::DATA->value);
        $sessions[session::UNSET_SESSION->value]('user'); 
        render_view('new_password', [
            'success' => [$errors[errors_message::UPDATE_SUCCESS->value]]
        ]);
    }
    


    function deconnexion_utilisateur(): void {
        $sessions = getAllSession();
        
        $sessions[session::UNSET_SESSION->value]('user'); 
        session_destroy();
        redirect_to_root(path::LOGIN->value.'&message=DECONNEXION');
    }
    
    
    handle_request();

