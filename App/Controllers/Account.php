<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use \App\Models\User;
use Core\View;

class Account extends Authenticated {
    public function profileAction() {
        View::renderTemplate('Account/profile.html.twig', []);
    }


}