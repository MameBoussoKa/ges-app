<?php
require_once __DIR__ . '/../route/route.web.php';
require_once __DIR__ . '/../enum/all_enums.php';
require_once __DIR__ . '/../services/session.service.php';
require_once __DIR__ . '/../services/validator.service.php';
require_once __DIR__ . '/../models/model.php';


use Enum\session;
use Enum\champ;
use Enum\path;
use Enum\model_auth;
use Enum\EnumerationData;
use Enum\promotion;
use Enum\model_referentiel;
use function Service\Session\getAllSession;
use function Models\Promotions\getAllPromotions;
use function Models\Referentiel\getAllReferenciels;
use function Models\getAllModels;



function handle_request():void{

    $sessions = getAllSession();
    $action =  $sessions[session::REQUEST->value]('action') ?? 'dashboard';

    match ($action) {
        
        'apprenant' => gestion_apprenants(),
         default => '',
    };
    
}

function gestion_apprenants(): void {
    // Données fictives pour tester la page

    $apprenants = [
        [
            'prenom' => 'Jean',
            'projet' => 'Site Web',
            'derniere_tache' => 'Design',
            'progres' => 80,
            'date_heure' => '2025-05-03 14:00',
            'situation' => 'Présent',
            'statut' => 'actif'
        ],
        [
            'prenom' => 'Marie',
            'projet' => 'Application Mobile',
            'derniere_tache' => 'Développement',
            'progres' => 60,
            'date_heure' => '2025-05-03 15:00',
            'situation' => 'Absent',
            'statut' => 'inactif'
        ]
    ];

    // Appeler la vue avec les données
    render_view('apprenant', [
        'apprenant' => $apprenants,
    ], 'base');
}

handle_request();