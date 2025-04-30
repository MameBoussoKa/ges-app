<?php

    namespace Service\Session;
    session_start();
    global $sessions;

    use Enum\session;

    
    $sessions = [

        session::POST->value => function(string $action){
            return $_POST[$action] ?? null;
        },

        session::GET->value => function(string $action){
            return $_GET[$action] ?? null;
        },

        session::REQUEST->value => function(string $action){
            return $_REQUEST[$action] ?? null;
        },

        session::SET_SESSION->value => function(string $cle , string|array $value){
            $_SESSION[$cle] = $value;
        },

        session::GET_SESSION->value => function(string $cle):array|string{
            return $_SESSION[$cle] ?? [];
        },

        session::UNSET_SESSION->value => function(string $cle): void {
            if (isset($_SESSION[$cle])) {
                unset($_SESSION[$cle]);
            }
        }
    ];


    function getAllSession(){
        global $sessions;
        return $sessions;
    }