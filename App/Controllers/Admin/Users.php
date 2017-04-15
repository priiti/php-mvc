<?php

namespace App\Controllers\Admin;

class Users extends \Core\Controller {


    protected function before() {
        // make sure admin is logged in
    }

    public function indexAction() {
        echo 'User admin index';
    }
}