<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Product;
use App\Auth;

class Products extends \Core\Controller {
    public function indexAction() {

        $this->requireLogin();

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