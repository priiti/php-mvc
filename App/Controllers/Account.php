<?php

namespace App\Controllers;

use \App\Models\User;
use Core\View;

class Account extends Authenticated {
    public function profileAction() {
        View::renderTemplate('User/profile.html.twig', []);
    }

    public function changeAction() {
        echo "tere";
    }
}