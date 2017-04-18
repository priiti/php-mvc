<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use Core\View;

class Profile extends Authenticated {
    public function showAction() {
        View::renderTemplate('Profile/show.html.twig', [
            'user' => Auth::getUser()
        ]);
    }

    public function editAction() {
        View::renderTemplate('Profile/edit.html.twig', [
           'user' => Auth::getUser()
        ]);
    }

    public function updateAction() {
        $user = Auth::getUser();
        if ($user->updateProfile($_POST)) {

            Flash::addMessage('Muudatused salvestatud!', Flash::SUCCESS);

            $this->redirect('/profile/show');
        } else {
            View::renderTemplate('Profile/edit.html.twig', [
                'user' => Auth::getUser()
            ]);
        }
    }
}