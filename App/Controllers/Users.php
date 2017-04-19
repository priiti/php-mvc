<?php

namespace App\Controllers;

use App\Flash;
use App\Models\User;
use Core\View;

class Users extends Authenticated {
    public function indexAction() {
        $user_list = User::getAll(1);

        View::renderTemplate('Users/index.html.twig', [
            'users' => $user_list
        ]);

    }

    public function editAction() {
        if (UserRights::hasRights("is_admin")) {
            echo "Kasutaja rollide muutmine";
        } else {
            Flash::addMessage("Õigused puuduvad!", Flash::WARNING);
            $this->redirect('/users/index');
        }

    }
}