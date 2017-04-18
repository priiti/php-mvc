<?php

namespace App\Controllers\Admin;

use App\Controllers\Authenticated;

class Users extends Authenticated {



    public function indexAction() {
        echo 'User admin index';
    }
}