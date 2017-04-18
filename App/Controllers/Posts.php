<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use \Core\View;
use App\Models\Post;

class Posts extends Authenticated {

    public function indexAction() {

        $posts = Post::getAll();

        View::renderTemplate('Posts/index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function singleAction() {
        $parameters = $this->route_parameters;
        $post = Post::getSinglePost($parameters['id']);

        if ($post) {
            View::renderTemplate('Posts/single.html.twig', [
                'post' => $post
            ]);
        } else {
            //Flash::addMessage('Postitust ei leitud!', Flash::WARNING);
            View::renderTemplate('404.html.twig', []);
        }
    }

    public function addAction() {
        View::renderTemplate('Posts/add.html.twig', []);
    }

    public function editAction() {
        $post = Post::getSinglePost($this->route_parameters['id']);

        if ($post) {
            View::renderTemplate('Posts/edit.html.twig', [
                'post' => $post
            ]);
        } else {
            View::renderTemplate('404.html.twig', []);
        }
    }

    public function saveAction() {
        if (Post::validatePostData($_POST)) {
            $post = new Post($_POST);
            if ($post->updatePost()) {
                Flash::addMessage('Postituse muutmine õnnestus!', Flash::SUCCESS);
                $this->redirect('/posts/index');
            } else {
                Flash::addMessage('Postituse muutmine ebaõnnestus!', Flash::WARNING);
                $this->redirect('/posts/index');
            }

        } else {
            Flash::addMessage('Postituse muutmine ebaõnnestus!', Flash::WARNING);
            $this->redirect('/posts/index');
        }
    }

    public function newAction() {
        if (Post::validatePostData($_POST)) {
            $user = Auth::getUser();
            $data['user_id'] = $user->user_id;
            $data = array_merge($data, $_POST);
            $post = new Post($data);

            if ($post->addPost()) {
                Flash::addMessage('Postituse lisamine õnnestus!', Flash::SUCCESS);
                $this->redirect('/posts/index');
            } else {
                Flash::addMessage('Postituse lisamine ebaõnnestus!', Flash::WARNING);
                $this->redirect('/posts/index');
            }
        } else {
            Flash::addMessage('Postituse salvestamine ebaõnnestus!', Flash::WARNING);
            $this->redirect('/posts/index');
        }
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
    }
}