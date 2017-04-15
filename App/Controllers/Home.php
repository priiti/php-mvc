<?php

namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller {

    protected function before() {
        //echo '(before)';
        //return false; // check if user has logged in or not
    }

    protected function after() {
        //echo '(after)';

    }

    public function indexAction() {
        //echo 'Hello from the index action in the Home controller';
        /*
        View::render('Home/index.php', [
            'name' => 'Priit',
            'colors' => ['red', 'green', 'blue']
        ]);

        */
        View::renderTemplate('Home/index.html.twig', [
            'name' => 'Priit',
            'colors' => ['red', 'Tere', 'blue']
        ]);
    }
}