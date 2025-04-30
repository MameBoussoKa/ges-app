<?php

    namespace Models\Promotions;
    use Enum\session;
    use Enum\champ;
    use Enum\path;
    use Enum\model_auth;
    use Enum\EnumerationData;
    use Enum\Validateur_Connexion;
    use Enum\errors_message;
    use Enum\promotion;
    USE Enum\Validateur;

    global $model_promotions;

    use function Models\getAllModels;
    $models = getAllModels();


    $model_promotions = [
        promotion::GET_ALL_PROMOTIONS->value => function() use (&$models , &$model_promotions): array {
            $data = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            $promotions = $data['promotions'];

            $model_promotions[promotion::TRI_ACTIVE_PROMO->value]($promotions);
            return $promotions;
        },

        promotion::TRI_ACTIVE_PROMO->value => function(array &$promotions): void {
            usort($promotions, function ($a, $b) {
                if ($a['statu'] === 'active' && $b['statu'] !== 'active') {
                    return -1; 
                } elseif ($a['statu'] !== 'active' && $b['statu'] === 'active') {
                    return 1;
                }
                return 0;
            });
        },
        promotion::TOTAL_APPRENANTS_ACTIFS->value => function() use (&$models): int {
            $promotions = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value)['promotions'] ?? [];
            $promotionsActives = array_filter($promotions, fn($promo) => strtolower($promo['statu'] ?? '') === 'active');
            return array_reduce($promotionsActives, fn($carry, $promo) => $carry + ($promo['nombre_apprenants'] ?? 0), 0);
        },
        promotion::promotion_active->value => function() use (&$models): ?string {
            $data = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            $promotions = $data['promotions'] ?? [];
        
            $actives = array_values(array_filter($promotions, function ($promotion) {
                return isset($promotion['statu']) && strtolower($promotion['statu']) === 'active';
            }));
            
            return $actives[0]['nom'] ?? null;
        },
        

        promotion::NB_REFERENTIELS_ACTIFS->value => function() use (&$models): int {
            $promotions = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value)['promotions'] ?? [];
            
            $promotionsActives = array_filter($promotions, fn($promo) => strtolower($promo['statu'] ?? '') === 'active');
            
            $referentiels = array_reduce($promotionsActives, function($carry, $promo) {
                $refNames = array_column($promo['referentiels'] ?? [], 'nom');
                return array_merge($carry, $refNames);
            }, []);
            return count(array_unique($referentiels));
        },
        

        promotion::NB_PROMOTIONS_ACTIVES->value => function() use (&$models): int {
            $promotions = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value)['promotions'] ?? [];
            return count(array_filter($promotions, fn($promo) => strtolower($promo['statu'] ?? '') === 'active'));
        },
        

        promotion::NB_TOTAL_PROMOTIONS->value => function() use (&$models): int {
            $promotions = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value)['promotions'] ?? [];
            return count($promotions);
        },


        promotion::DASHBOARD->value => function()use(&$model_promotions):array{
            $total_apprenants = $model_promotions[promotion::TOTAL_APPRENANTS_ACTIFS->value]();
            $nb_referentiels = $model_promotions[promotion::NB_REFERENTIELS_ACTIFS->value]();
            $nb_promo_actives = $model_promotions[promotion::NB_PROMOTIONS_ACTIVES->value]();
            $total_promotions = $model_promotions[promotion::NB_TOTAL_PROMOTIONS->value]();
            $total_stagiaires = $model_promotions[promotion::NB_TOTAL_STAGIAIRES->value]();
            $total_permanents = $model_promotions[promotion::NB_PERMANENTS->value]();

            $dashboard = [$total_apprenants,$nb_referentiels,$nb_promo_actives,$total_promotions,$total_stagiaires,$total_permanents];
            return $dashboard;
        },



        promotion::NB_TOTAL_STAGIAIRES->value => function() use (&$models): int {
            $data = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            $personnel = $data['personnel'] ?? [];
            
            return count(array_filter($personnel, fn($p) => strtolower($p['role'] ?? '') === 'stagiaire'));
        },
        


        promotion::NB_PERMANENTS->value => function() use (&$models): int {
            $data = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            $personnel = $data['personnel'] ?? [];
            
            return count(array_filter($personnel, fn($p) => strtolower($p['role'] ?? '') === 'permanent'));
        },



        promotion::ACTIVER_OU_DESACTIVER_PROMO->value => function(string $nomPromo) use (&$models) {
            $data = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            $promotions = &$data['promotions'];
            $noms = array_map('strtolower', array_column($promotions, 'nom'));
            $index = array_search(strtolower($nomPromo), $noms);
        
            if ($index !== false) {
                $isActive = strtolower($promotions[$index]['statu']) === 'active';
                    $promotions = array_map(function($promo) {
                    $promo['statu'] = 'inactive';
                    return $promo;
                }, $promotions);
        
                if (!$isActive) {
                    $promotions[$index]['statu'] = 'active';
                }
                $data['promotions'] = $promotions;
        
                $models[EnumerationData::ARRAY_TO_JSON->value]($data, path::DATA->value);
            }
        },


        promotion::RECHERCHER_PAR_NOM->value => function(string $mot_cle) use (&$models): array {
            $promotions = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value)['promotions'] ?? [];
            
            return array_filter($promotions, function ($promo) use ($mot_cle) {
                return str_contains(strtolower($promo['nom']), strtolower($mot_cle));
            });
        },
        
        promotion::CREER_PROMOTION->value => function(
            string $nomPromo,
            string $date_debut,
            string $date_fin,
            string $image,
            array $referentiels
        ) use (&$model_promotions): array {
            return [
                'nom' => $nomPromo,
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'image' => $image,
                'referentiels' => $referentiels,
                'statu' => 'inactif',
                'nombre_apprenants' => 0,
                "status" => "en cours",

            ];
        },

        promotion::AJOUTER_PROMOTION->value => function(array $promo) use (&$models): void {
            $data = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            $data['promotions'][] = $promo;
            $models[EnumerationData::ARRAY_TO_JSON->value]($data, path::DATA->value);
        },

        promotion::VALIDER_PROMOTION->value => function(array $promo) use (&$models, &$validators): array|bool {
            $errors = [];
        
            $validateurs = getAllValidateurs();
            if (($error = $validateurs[Validateur_Connexion::CHAMP_VIDE->value]($promo['nom'] ?? '')) !== false) {
                $errors['nom'] = $error;
            } else {
                $promotions = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value)['promotions'] ?? [];
                $noms = array_map('strtolower', array_column($promotions, 'nom'));
                if (in_array(strtolower(trim($promo['nom'])), $noms)) {
                    $errors['nom'] = "Ce nom de promotion existe déjà.";
                }
            }
        
            if (!isset($promo['image']) || !$validators[Validateur::VALIDE_PHOTO->value]($promo['image'])) {
                $errors['image'] = "Image invalide. Format autorisé : jpg, jpeg, png. Taille max : 2 Mo.";
            }
            if (($error = $validators[Validateur::VALIDE_DATE->value]($promo['date_debut'] ?? '', $promo['date_fin'] ?? '')) !== false) {
                $errors['dates'] = $error;
            }        
            if (empty($promo['referentiels']) || !is_array($promo['referentiels'])) {
                $errors['referentiels'] = "Veuillez sélectionner au moins un référentiel.";
            } 
            return !empty($errors) ? $errors : false;
        },


        promotion::FILTRER_PROMOTIONS->value => function(string $mot_cle = '', string $reference = '', string $statut = 'tous') use (&$models): array {
            $promotions = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value)['promotions'] ?? [];
            
            return array_filter($promotions, function ($promo) use ($mot_cle, $reference, $statut) {
                $matchNom = empty($mot_cle) || 
                           str_contains(strtolower($promo['nom']), strtolower($mot_cle));
                
                $matchRef = empty($reference);
                if (!$matchRef && isset($promo['referentiels'])) {
                    foreach ($promo['referentiels'] as $ref) {
                        if (strtolower($ref['nom']) === strtolower($reference)) {
                            $matchRef = true;
                            break;
                        }
                    }
                }
                
                $matchStatut = ($statut === 'tous') || 
                              ($statut === 'actif' && strtolower($promo['statu']) === 'active') || 
                              ($statut === 'inactif' && strtolower($promo['statu']) !== 'active');
                
                return $matchNom && $matchRef && $matchStatut;
            });
        },
        
    ];

    function getAllPromotions() {
        global $model_promotions;
        return $model_promotions;
    }