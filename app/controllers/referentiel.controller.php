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
    global $referentiels;


    function gestion_referentiels(){
        $sessions = getAllSession();
        $actionRef = $sessions[session::REQUEST->value]('actionRef') ?? 'ref_active';
    
        match ($actionRef) {
            'ref_active' => afficher_referentiel(),
            'tout_ref' => all_referentiel(),
            'search' => search_referentiel(),
            'ajouter' => ajouter_referentiel(),
            'voir_formulaire' => ajout_ref_form(),
            'affecter_form' => afficher_form_affectation(),
            'affecter' => affecter_referentiel(),
            default => '',
        };
    }

    function ajout_ref_form(){
        $sessions = getAllSession();
        $user = $sessions[ session::GET_SESSION->value]('user');
        render_view('ajouter_ref',['user' => $user],'base');
    }

    function afficher_referentiel(){
        $sessions = getAllSession();
        $referentiels = getAllReferenciels()[model_referentiel::get_referentiel_active->value]();
        $name = getAllPromotions()[promotion::promotion_active->value]();

        $user = $sessions[ session::GET_SESSION->value]('user');

        render_view('referentiels',['user' => $user , 'referentiels' => $referentiels , 'name' => $name],'base');
    }




    function all_referentiel(){
        $sessions = getAllSession();
        $all_referentiels = getAllReferenciels()[model_referentiel::get_all_referentiels->value]();
        $name = getAllPromotions()[promotion::promotion_active->value]();


        $user = $sessions[ session::GET_SESSION->value]('user');


        render_view('referentiels',['user' => $user , 'referentiels' => $all_referentiels , 'name' => $name],'base');
    }



    function search_referentiel(){
        $sessions = getAllSession();
        $user = $sessions[ session::GET_SESSION->value]('user');
        $value = $sessions[session::POST->value]('searchQuery');
        $all_referentiels = getAllReferenciels()[model_referentiel::search_referentiel->value]($value);


        render_view('referentiels',['user' => $user , 'referentiels' => $all_referentiels],'base');
    }








    function ajouter_referentiel(): void {
        $sessions = getAllSession();
        $models = getAllReferenciels();
        
        $errors = [];
        $nom = trim($sessions[session::POST->value]('nom') ?? '');
        $description = trim($sessions[session::POST->value]('description') ?? '');
        $capacite = (int)($sessions[session::POST->value]('capacite') ?? 0);
        $file = $_FILES['photo'] ?? null;
        
        // Validation des champs
        if (empty($nom)) {
            $errors['nom'] = 'Le nom est obligatoire';
        }
        
        if ($capacite <= 0) {
            $errors['capacite'] = 'La capacité doit être un nombre positif';
        }
        
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = 'Une image est obligatoire';
        } elseif (!in_array($file['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
            $errors['photo'] = 'Format d\'image non supporté (JPEG, PNG ou GIF seulement)';
        }
        
        if (!empty($errors)) {
            render_view('ajouter_ref', [
                'errors' => $errors,
                'nom' => $nom,
                'description' => $description,
                'capacite' => $capacite
            ], 'base');
            return;
        }
        
        $imagePath = save_photo($file);
        if (!$imagePath) {
            render_view('ajouter_ref', ['errors' => ['Erreur lors de l\'upload de l\'image.']], 'base');
            return;
        }
        
        $nouveauReferentiel = $models[model_referentiel::creer_referentiel->value](
            $imagePath,
            $nom,
            $capacite,
            $description
        );
        
        $models[model_referentiel::ajouter_referenciel_active->value]([
            'nom' => $nouveauReferentiel['nom'],
            'nombre_apprenants' => $nouveauReferentiel['apprenants']
        ]);
        
        afficher_referentiel();
    }



    function afficher_form_affectation() {
        $sessions = getAllSession();
        $referentielId = (int)$sessions[session::GET->value]('id');
        
        $models = getAllModels();
        $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
        
        // Trouver le référentiel
        $referentiel = current(array_filter(
            $tableau['referentiels'],
            fn($r) => $r['id'] === $referentielId
        ));
        
        if (!$referentiel) {
            header("Location: index.php?page=dashboard&action=referentiels");
            exit;
        }
        
        // Trouver les promotions actives
        $promotionsActives = array_filter(
            $tableau['promotions'],
            fn($p) => ($p['etat'] ?? '') === 'en cours'
        );
        
        render_view('affecter.referentiel', [
            'referentiel' => $referentiel,
            'promotionsActives' => $promotionsActives
        ], 'base');
    }


    function affecter_referentiel() {
        $sessions = getAllSession();
        $models = getAllReferenciels();
        
        $referentielId = (int)$sessions[session::POST->value]('referentiel_id');
        $promotionNom = $sessions[session::POST->value]('promotion_nom');
        $nombreApprenants = (int)$sessions[session::POST->value]('nombre_apprenants');
        
        $success = $models[model_referentiel::affecter_a_promotion->value](
            $referentielId, 
            $promotionNom,
            $nombreApprenants
        );
        
        if ($success) {
            header("Location: index.php?page=dashboard&action=referentiels&success=Le référentiel a été affecté avec succès");
        } else {
            header("Location: index.php?page=dashboard&action=referentiels&error=Erreur lors de l'affectation du référentiel");
        }
        exit;
    }






