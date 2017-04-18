<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\User;
use \Core\View;
use App\Models\Product;

class Products extends Authenticated {
    public function indexAction() {


        $products = Product::getAllProducts();

        View::renderTemplate('Products/index.html.twig', [
            'products' => $products
        ]);
    }

    public function addAction() {
        View::renderTemplate('Products/add.html.twig', [
            'users' => $users = User::getAll(1)
        ]);
    }

    // Inser new product action
    public function insertAction() {
        if (Product::validateProductData($_POST)) {
            $user = Auth::getUser();
            $data['user_id'] = $user->user_id;
            $data = array_merge($data, $_POST);
            $product = new Product($data);

            if ($product->insertNewProduct()) {
                Flash::addMessage('Toote lisamine õnnestus!', Flash::SUCCESS);
                $this->redirect('/products/add');
            } else {
                Flash::addMessage('Toote lisamine ebaõnnestus!', Flash::WARNING);
                $this->redirect('/products/add');
            }
        } else {
            Flash::addMessage('Kontrolli toote andmeid.', Flash::WARNING);
            $this->redirect('/products/add');
        }
    }

    // Start editing and fill form fields with the one which was selected from the list
    public function editAction() {
        $parameters = $this->route_parameters;
        $product = Product::getSingleProduct($parameters['id']);
        $users = User::getAll($product['product_owner_id']);

        if ($product) {
            View::renderTemplate('Products/edit.html.twig', [
                'product' => $product,
                'users' => $users
            ]);
        } else {
            View::renderTemplate('404.html.twig', []);
        }
    }

    // Save product editing options
    public function saveAction() {
        if (Product::validateProductData($_POST)) {
            $product = new Product($_POST);
            if ($product->updateProduct()) {
                Flash::addMessage('Toote muutmine õnnestus!', Flash::SUCCESS);
                $this->redirect('/products/index');
            } else {
                Flash::addMessage('Toote muutmine ebaõnnestus!', Flash::WARNING);
                $this->redirect('/products/index');
            }
        } else {
            Flash::addMessage('Toote muutmine ebaõnnestus!', Flash::WARNING);
            $this->redirect('/products/index');
        }
    }

    // Delete product
    public function deleteAction() {

    }
}