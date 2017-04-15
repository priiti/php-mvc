<?php

namespace App;

use App\Models\User;

class Auth {
    public static function login($user) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->user_id;
    }

    public static function logout() {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }

    public static function setSessionUserRequestedPage() {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    // Get current logged- in user data according to user_id from the $_SESSION
    public static function getUser() {
        if (isset($_SESSION['user_id'])) {
            return User::findUserById($_SESSION['user_id']);
        }
    }

    // Returns the $_SESSION return_to page, user has requested while not logged- in
    public static function getSessionUserRequestedPage() {
        // If the page is not saved into session, we redirect into homepage
        return $_SESSION['return_to'] ?? '/';
    }
}