<?php

namespace App\Controllers;

use App\Models\User;
use Core\View;

class Signup extends \Core\Controller {
    /**
     * Show signup page
     *
     * @return void
     */
    public function signupAction() {
        View::renderTemplate('Signup/new.html.twig', []);
    }

    /**
     * Sign up new user
     *
     * @return void
     */
    public function createAction() {
        $user = new User($_POST);

        if ($user->save()) {
            // HTTP status code 303 is used for POST / REDIRECT / GET pattern
            // If user refreshes the page, we redirect onto success page
            // header('Location: http://' . $_SERVER['HTTP_HOST'] . '/signup/success', true, 303);
            // exit;
            $this->redirect('/signup/success');
        } else {
            View::renderTemplate('Signup/new.html.twig', [
               'user' => $user
            ]);
        }
//        echo "<pre>";
//        print_r($_POST);
//        echo "<pre>";
    }
    public function successAction() {
        View::renderTemplate('Signup/success.html.twig', []);
    }
}