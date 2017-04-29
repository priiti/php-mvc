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
            $parameters = $this->route_parameters;

            View::renderTemplate('Users/edit.html.twig', [
                'users' => User::findUserById($parameters['id'])
            ]);

        } else {
            Flash::addMessage("Õigused puuduvad!", Flash::WARNING);
            $this->redirect('/users/index');
        }

    }

    public function saveAction() {
        if (UserRights::hasRights("is_admin")) {

            $user = new User($_POST);




            echo "<pre>";
            print_r($user);
            echo "</pre>";



        } else {
            Flash::addMessage("Õigused puuduvad!", Flash::WARNING);
            $this->redirect('/users/index');
        }
    }
}