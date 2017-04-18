<?php

namespace App\Controllers;

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

    }
}