<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Product;

class Products extends Authenticated {
    public function indexAction() {


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