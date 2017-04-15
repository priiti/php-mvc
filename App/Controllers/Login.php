<?php

namespace App\Controllers;

use App\Auth;
use Core\View;
use App\Models\User;

class Login extends \Core\Controller {
    public function newAction() {
        View::renderTemplate('Login/login.html.twig', []);
    }

    public function loginAction() {
        $user = User::authenticate($_POST['email'], $_POST['pwd']);

        if ($user) {

            Auth::login($user);

            // echo $_SESSION['user_id'];

            $this->redirect(Auth::getSessionUserRequestedPage());
        } else {
            View::renderTemplate('Login/login.html.twig', [
                'email' => $_POST['email']
            ]);
        }
    }

    public function logoutAction() {
        Auth::logout();

        $this->redirect('/');
    }
}