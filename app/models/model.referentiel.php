<?php

    namespace Models\Referentiel;

    global $models;
    global $referentiels;

    
    use Enum\session;
    use Enum\champ;
    use Enum\path;
    use Enum\model_auth;
    use Enum\EnumerationData;
    use Enum\model_referentiel;


    $referentiels = [
        model_referentiel::get_referentiel_active->value => function() use($models): array {
            $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            
            $promotionActive = current(array_filter(
                $tableau['promotions'], 
                fn($p) => ($p['statu'] ?? '') === 'active'
            ));
            
            if (!$promotionActive) return [];
            
            $nomsReferentiels = array_column($promotionActive['referentiels'], 'nom');

            
            return array_filter(
                $tableau['referentiels'],
                fn($ref) => in_array($ref['nom'], $nomsReferentiels)
            );

        },

        model_referentiel::get_all_referentiels->value => function() use($models): array {
            $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            
            return $tableau['referentiels'] ?? [];
        },



        model_referentiel::search_referentiel->value => function(string $searchTerm) use($models): array {
            $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
            $allReferentiels = $tableau['referentiels'] ?? [];
            
            if (empty($searchTerm)) {
                return $allReferentiels;
            }
    
            $searchTerm = strtolower($searchTerm);
            
            return array_filter($allReferentiels, function($ref) use($searchTerm) {
                $nomMatch = strpos(strtolower($ref['nom'] ?? ''), $searchTerm) !== false;
                $descMatch = strpos(strtolower($ref['description'] ?? ''), $searchTerm) !== false;
                
                return $nomMatch || $descMatch;
            });
        },

        model_referentiel::creer_referentiel->value => function(string $path_image, string $nom, int $nbApprenants, string $description) use ($models):array {
            $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
        
            $nouvel_id = count($tableau['referentiels']) + 1;
        
            $nouveau_referentiel = [
                "id" => $nouvel_id,
                "nom" => $nom,
                "description" => $description,
                "modules" => $nbApprenants,
                "apprenants" => $nbApprenants,
                "image" => $path_image,
            ];
        
            $tableau['referentiels'][] = $nouveau_referentiel;
        
            $models[EnumerationData::ARRAY_TO_JSON->value]($tableau, path::DATA->value);
            return $nouveau_referentiel;
        },

        model_referentiel::ajouter_referenciel_active->value => function(array $referentiel) use ($models) {
            $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
        
            $index_promotion_active = array_search('active', array_column($tableau['promotions'], 'statu'));
        
            if ($index_promotion_active !== false) {
                $tableau['promotions'][$index_promotion_active]['referentiels'][] = $referentiel;
        
                $models[EnumerationData::ARRAY_TO_JSON->value]($tableau, path::DATA->value);
            }
        },



    model_referentiel::affecter_a_promotion->value => function(int $referentielId, string $promotionNom) use ($models) {
        $tableau = $models[EnumerationData::JSON_TO_ARRAY->value](path::DATA->value);
        
        $referentiel = current(array_filter(
            $tableau['referentiels'],
            fn($r) => $r['id'] === $referentielId
        ));
        
        if (!$referentiel) return false;
        
        $indexPromotion = array_search('active', array_column($tableau['promotions'], 'statu'));
        
        if ($indexPromotion !== false) {
            $dejaAffecte = in_array($referentiel['nom'], 
                array_column($tableau['promotions'][$indexPromotion]['referentiels'], 'nom'));
            
            if (!$dejaAffecte) {
                $tableau['promotions'][$indexPromotion]['referentiels'][] = [
                    'nom' => $referentiel['nom'],
                    'nombre_apprenants' => $referentiel['apprenants']
                ];
                
                $models[EnumerationData::ARRAY_TO_JSON->value]($tableau, path::DATA->value);
                return true;
            }
        }
        
        return false;
    }
    ];


    function getAllReferenciels(){
        global $referentiels;
        return $referentiels;
    }