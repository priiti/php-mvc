<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Product;
use App\Auth;

class Products extends \Core\Controller {
    public function indexAction() {

        if (!Auth::isLoggedIn()) {
            $this->redirect('/login');
        }

        $products = Product::getAllProducts();

        View::renderTemplate('Products/index.html.twig', [
            'products' => $products
        ]);
    }

    public function addNewProduct() {
        echo 'Ability to add new product';
    }



    public function editProduct() {
        echo 'Ability to edit product';
    }
}