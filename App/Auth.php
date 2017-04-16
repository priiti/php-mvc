<?php

namespace App;

use App\Models\RememberedLogin;
use App\Models\SavedLogin;
use App\Models\User;

class Auth {
    public static function login($user, $remember_me) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->user_id;

        if ($remember_me) {
            if ($user->rememberLogin()) {
                setcookie('remember_me', $user->remember_token, $user->expiry_timestamp, '/');
            }
        }
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

        static::forgetLogin();
    }

    public static function setSessionUserRequestedPage() {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    // Get current logged- in user data according to user_id from the $_SESSION
    public static function getUser() {
        if (isset($_SESSION['user_id'])) {
            return User::findUserById($_SESSION['user_id']);
        } else {
            return static::loginFromRememberCookie();
        }
    }

    // Returns the $_SESSION return_to page, user has requested while not logged- in
    public static function getSessionUserRequestedPage() {
        // If the page is not saved into session, we redirect into homepage
        return $_SESSION['return_to'] ?? '/';
    }

    protected static function loginFromRememberCookie() {
        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {
            $remembered_login = RememberedLogin::findByToken($cookie);
            if ($remembered_login && !$remembered_login->hasExpired()) {
                $user = $remembered_login->getUser();
                static::login($user, false);

                return $user;
            }
        }
    }

    protected static function forgetLogin() {
        $cookie = $_COOKIE['remember_me'] ?? false;
        if ($cookie) {
            $remebered_login = RememberedLogin::findByToken($cookie);
            if ($remebered_login) {
                $remebered_login->deleteSavedLogin();
            }
            setcookie('remember_me', '', time() - 3600); // set to expire in the past
        }
    }


}