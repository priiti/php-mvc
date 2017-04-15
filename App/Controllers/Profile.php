<?php

namespace App\Controllers;

use App\Auth;
use App\Models\User;
use Core\View;

// If we use Authenticated then all action methods require login
class Profile extends Authenticated {
    public function profileAction() {
        View::renderTemplate('User/profile.html.twig', []);

    }
}