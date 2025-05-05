<?php

    namespace Enum;

    enum EnumerationData: string {

      case JSON_TO_ARRAY = 'json_to_array';
      case ARRAY_TO_JSON =  'array_to_json';
      case GET_ALL_BY_KEY = 'get_all_key';
    }


    enum session: string {
      case POST = 'post';
      case GET = 'get';
      case REQUEST = 'request';
      case SET_SESSION = 'set_session';
      case GET_SESSION = 'get_session';
      case UNSET_SESSION = 'unset_session';
   }


  enum path:string {
      case AUTH = __DIR__.'/../controllers/auth.controller.php';
      case PROMOTIONS = __DIR__.'/../controllers/promotion.controller.php';
      case CONTROLLER_DASHBOARD = __DIR__.'/../controllers/controller.dashboard.controller.php';
      case APPRENANTS = __DIR__.'/../controllers/apprenants.controller.php';

      case DATA = __DIR__.'/../data/data.json';



      case DASHBOARD = 'dashboard';
      case LOGIN = 'auth&action=voir_formulaire';
  }


  enum model_auth: string{
    case VALIDATE_USER = 'validate_user';
    case CREATE_USER = 'create_user';
    case USER_EXIST = 'utilisateur_exist';
    case PASSWORD_CONFIRME_PASSWORD = 'confirmer_password';
    case GET_USER_BY_LOGIN_PASSWORD = 'utilisateur_connecte';
  }

  enum errors_message:string {
    case CHAMP_VIDE = 'champ_vide';
    case NOT_EXIST = 'utilisateur_not_existe';
    case LOGIN_PASSWORD = 'loginPassword';
    case CHAMP_PASSWORD = 'champ_password';
    case LOGIN_INTROUVABLE = 'login_introuvable';
    case PASSWORD_NOT_EGAL = 'champs_different';
    case UPDATE_SUCCESS = 'update_succee_password';
  }


  enum message:string {
    case CONNEXION_SUCCESS = 'connexion_success';
    case DECONNEXION = 'deconnexion_succee';
  }

  enum Validateur_Connexion: string
    {
      case CHAMP_VIDE = 'champ_vide';
      case CHAMP_PASSWORD = 'champ_password';
      case VALIDE_PASSWORD = 'mot_de_passe';
      case SEARCH_BY_LOGIN = 'search_by_login';

      
    }

    enum Validateur:string {
      case VALIDE_PHOTO = "VALIDE_PHOTO";
      case VALIDE_DATE = "valide_date";
    }



    enum champ:string {
      case LOGIN = 'login';
      case PASSWORD = 'password';
      case CONFIRM_PASSWORD = 'confirm_password';
    }


    enum promotion: string {
      case GET_ALL_PROMOTIONS = 'get_all_promotions';
      case TOTAL_APPRENANTS_ACTIFS = 'total_apprenants';
      case NB_REFERENTIELS_ACTIFS = 'nb_referentiels_distincts';
      case NB_PROMOTIONS_ACTIVES = 'nb_promotions_actives';
      case NB_TOTAL_PROMOTIONS = 'nb_total_promotions';
      case DASHBOARD = 'dashboard_des_informations';
      case NB_TOTAL_STAGIAIRES = 'nombre_total_stagiaires';
      case NB_PERMANENTS = 'nombre_total_permanents';
      case ACTIVER_OU_DESACTIVER_PROMO = 'activer_ou_desactiver_promo';
      case RECHERCHER_PAR_NOM = 'rechercher_promo';
      case AJOUTER_PROMOTION = 'ajouter_promo';
      case CREER_PROMOTION = 'creer_promotion';
      case VALIDER_PROMOTION = 'valider_promotion';
      case promotion_active = 'promotion_active';
      case TRI_ACTIVE_PROMO = 'trier_promotions_active';
      case FILTRER_PROMOTIONS = 'filtrer_promotions';
    
  }


  enum model_referentiel:string {
    case get_referentiel_active = 'get_referentiel_active';
    case tab_referentiel_active = 'tab_referentiel_active';
    case get_all_referentiels = 'tout_referentiels';
    case search_referentiel = 'rechercher_referentiel';
    case ajouter_referenciel_active = 'ajouter_referentiel';
    case creer_referentiel = 'creer_referentiel';
    case affecter_a_promotion = 'affecter_referentiel';
  }


  