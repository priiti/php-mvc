<?php

namespace App\Controllers;

// Controller for adding require login to all action methods we choose to
// It's just important to extend controller class from Authenticated abstract class
abstract class Authenticated extends \Core\Controller {
    protected function before() {
        $this->requireLogin();
    }

}