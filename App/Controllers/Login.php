<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use Core\View;
use App\Models\User;

class Login extends \Core\Controller {
    public function newAction() {
        View::renderTemplate('Login/login.html.twig', []);
    }

    public function loginAction() {
        $user = User::authenticate($_POST['email'], $_POST['pwd']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {
            Auth::login($user, $remember_me);

            // echo $_SESSION['user_id'];

            $this->redirect(Auth::getSessionUserRequestedPage());
        } else {
            Flash::addMessage('Sisselogimine ebaõnnestus. Palun proovi uuesti.', Flash::WARNING);
            View::renderTemplate('Login/login.html.twig', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
            ]);
        }
    }

    public function logoutAction() {
        Auth::logout();

        $this->redirect('/login/show-logout-message');
    }

    public function showLogoutMessageAction() {
        Flash::addMessage('Väljalogimine õnnestus.', Flash::SUCCESS);

        $this->redirect('/');
    }
}