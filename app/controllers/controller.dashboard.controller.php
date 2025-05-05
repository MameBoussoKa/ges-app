<?php
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
            'dashboard' => afficher_dashboard(),
            'promotions' => gestion_promotions(),
            'desactiver_activer' => desactiver_activer(),
            'rechercer_promotion' => rechercher_promotion(),
            'ajout_promotion' => ajouter_promotion(),
            'referentiels' => gestion_referentiels(),
            'voir_form_ajout' => voir_form_ajout(),
            'apprenant' => gestion_apprenants(),
             default => '',
        };
        
    }


    function afficher_dashboard(){
        $sessions = getAllSession();
        $name = getAllPromotions()[promotion::promotion_active->value]();
        
        $user = $sessions[ session::GET_SESSION->value]('user');
        render_view('dashboard',['user' => $user , 'name' => $name],'base');

    }


    function gestion_promotions() {
        $sessions = getAllSession();
        
        $page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        $model = getAllPromotions();
        $referentielsModel = getAllReferenciels();
        $referentiels = $referentielsModel[model_referentiel::get_all_referentiels->value]();
        $name = getAllPromotions()[promotion::promotion_active->value]();
    
        $mot_cle = $sessions[session::GET->value]('recherche') ?? '';
        $reference = $sessions[session::GET->value]('reference') ?? '';
        $statut = $sessions[session::GET->value]('statut') ?? 'tous';
        
        if (!empty($mot_cle) || !empty($reference) || $statut !== 'tous') {
            $allPromos = $model[promotion::FILTRER_PROMOTIONS->value]($mot_cle, $reference, $statut);
        } else {
            $allPromos = $model[promotion::GET_ALL_PROMOTIONS->value]();
        }
        
        $promoActive = null;
        $autresPromos = [];
        
        foreach ($allPromos as $promo) {
            if (strtolower($promo['statu']) === 'active') {
                $promoActive = $promo;
            } else {
                $autresPromos[] = $promo;
            }
        }
        
        $totalPromos = count($autresPromos);
        $promos = array_slice($autresPromos, $offset, $limit);
        
        if ($promoActive !== null) {
            array_unshift($promos, $promoActive);
            $totalPromos++;
        }
        
        $dashboard = $model[promotion::DASHBOARD->value]();
        
        $action_grille_liste = $sessions[session::GET->value]('action_grille_liste') ?? 'promotions';
        $user = $sessions[session::GET_SESSION->value]('user');
        
        $params = [
            'user' => $user,
            'promotions' => $promos,
            'dashboard' => $dashboard,
            'totalPromos' => $totalPromos,
            'limit' => $limit,
            'currentPage' => $page,
            'referentiels' => $referentiels,
            'recherche' => $mot_cle,
            'current_reference' => $reference,
            'current_statut' => $statut,
            'name' => $name
        ];
        
        match ($action_grille_liste) {
            'promotions', 'grille' => render_view('promotions', $params, 'base'),
            'liste' => render_view('promotions.liste', $params, 'base'),
            'ajouter_promotion' => render_view('formulaire_ajout', $params, 'base'),
        };
    }


    function desactiver_activer() {
        $sessions = getAllSession();
        $promo =  $sessions[session::REQUEST->value]('promo') ?? '';
    
        if ($promo) {
            $model = getAllPromotions();
            $model[promotion::ACTIVER_OU_DESACTIVER_PROMO->value]($promo);
        }
    
        gestion_promotions();
    }
    
   function rechercher_promotion() {
    $sessions = getAllSession();
    $mot_cle = $sessions[session::GET->value]('recherche') ?? '';
    $reference = $sessions[session::GET->value]('reference') ?? '';
    $statut = $sessions[session::GET->value]('statut') ?? 'tous';
    $name = getAllPromotions()[promotion::promotion_active->value]();

    $model = getAllPromotions();
    $referentielsModel = getAllReferenciels();
    $referentiels = $referentielsModel['get_all_referentiels']();
    
    $resultats = $model[promotion::FILTRER_PROMOTIONS->value]($mot_cle, $reference, $statut);
    
    $promoActive = null;
    $autresPromos = [];
    
    foreach ($resultats as $promo) {
        if (strtolower($promo['statu']) === 'active') {
            $promoActive = $promo;
        } else {
            $autresPromos[] = $promo;
        }
    }
    
    if ($promoActive !== null) {
        array_unshift($autresPromos, $promoActive);
    }
    
    $dashboard = $model[promotion::DASHBOARD->value]();
    $user = $sessions[session::GET_SESSION->value]('user');
    
    render_view('promotions', [
        'user' => $user,
        'promotions' => $autresPromos,
        'dashboard' => $dashboard,
        'recherche' => $mot_cle,
        'referentiels' => $referentiels,
        'current_reference' => $reference,
        'current_statut' => $statut,
        'name' => $name
    ], 'base');
}



    function ajouter_promotion() {
        $sessions = getAllSession();
        $promotions = getAllPromotions();
        $validateurs = getAllValidateurs();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($sessions[session::POST->value]('nom') ?? '');
            $date_debut = $sessions[session::POST->value]('date_debut') ?? '';
            $date_fin = $sessions[session::POST->value]('date_fin') ?? '';
            $ref = trim($sessions[session::POST->value]('referentiel') ?? '');
            $photoFile = $_FILES['photo'] ?? null;
    
            $promotion = [
                'nom' => $nom,
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'image' => $photoFile,
                'referentiels' => !empty($ref) ? [['nom' => $ref, 'nombre_apprenants' => 0]] : []
            ];
    var_dump( $photoFile ); var_dump(file_exists($photoFile['tmp_name'])); 
            $erreurs = $promotions[promotion::VALIDER_PROMOTION->value]($promotion);
            
            if (empty($erreurs)) {
                $photoPath = save_photo($photoFile);
                
                if ($photoPath === false) {
                    $erreurs['image'] = "Erreur lors de l'enregistrement de l'image";
                } else {
                    $nouvellePromotion = $promotions[promotion::CREER_PROMOTION->value](
                        $nom,
                        $date_debut,
                        $date_fin,
                        $photoPath,
                        [['nom' => $ref, 'nombre_apprenants' => 0]]
                    );
                    
                    $promotions[promotion::AJOUTER_PROMOTION->value]($nouvellePromotion);
                    
                    $sessions[session::SET_SESSION->value]('success', 'Promotion créée avec succès');
                    header('Location: index.php?page=dashboard&action=promotions&action_grille_liste=grille');
                    exit;
                }
            }
    
            $sessions[session::SET_SESSION->value]('errors', $erreurs);
            $sessions[session::SET_SESSION->value]('old', [
                'nom' => $nom,
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'referentiel' => $ref
            ]);
            
            header('Location: index.php?page=dashboard&action=promotions&action_grille_liste=ajouter_promotion');
            exit;
        }
    
        $user = $sessions[session::GET_SESSION->value]('user');
        $errors = $sessions[session::GET_SESSION->value]('errors') ?? [];
        $old_values = $sessions[session::GET_SESSION->value]('old') ?? [];
        
        render_view('formulaire_ajout', [
            'user' => $user,
            'errors' => $errors,
            'old_values' => $old_values
        ], 'base');
    }


    function voir_form_ajout(){
        
        $sessions = getAllSession();
        $user = $sessions[ session::GET_SESSION->value]('user');
        render_view('formulaire_ajout', ['user' => $user], 'base');
    }
   
    function gestion_apprenants(): void {
        $filePath = __DIR__ . '/../data/data.json';

        if (!file_exists($filePath)) {
            $apprenants = [];
        } else {
            $data = json_decode(file_get_contents($filePath), true);
            $apprenants = $data['apprenants'] ?? [];

            // Ajoutez une valeur par défaut pour les clés manquantes
            foreach ($apprenants as &$apprenant) {
                $apprenant['date_heure'] = $apprenant['date_heure'] ?? 'Non spécifiée';
            }
        }

        render_view('apprenant', [
            'apprenants' => $apprenants,
        ], 'base');
    }

    handle_request();

