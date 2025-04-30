<?php
    require_once __DIR__ . '/../route/route.web.php';
    require_once __DIR__ . '/../enum/all_enums.php';
    require_once __DIR__ . '/../translate/fr/error.fr.php';
    require_once __DIR__ . '/../services/session.service.php';
    require_once __DIR__ . '/../services/validator.service.php';
    require_once __DIR__ . '/../models/model.php';
    require_once __DIR__ . '/../models/model_auth.php';
    require_once __DIR__ . '/../models/model.promotions.php';
    require_once __DIR__ . '/../controllers/controller.php';
    require_once __DIR__ . '/../models/model.referentiel.php';
    require_once __DIR__ . '/../controllers/referentiel.controller.php';

    use Enum\session;
    use Enum\path;

    function require_function(string $path){
        require_once $path;
    }

    function handle_route():void{
        global $sessions;

        $page = $sessions[session::REQUEST->value]('page') ?? 'auth';

        match ($page) {

            'auth' => require_function(path::AUTH->value),

            'dashboard' => require_function(path::CONTROLLER_DASHBOARD->value),

            

            default => "404 - Page non trouvée",
        };
    }

    handle_route();
