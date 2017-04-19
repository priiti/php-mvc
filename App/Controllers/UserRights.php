<?php

namespace App\Controllers;

use App\Auth;

class UserRights extends Authenticated {
    public static function hasRights($role) {
        $user = Auth::getUser();
        if ($user->$role) {
            return true;
        } else {
            return false;
        }
    }
}