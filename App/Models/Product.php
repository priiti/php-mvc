<?php

namespace App\Models;

use PDO;

class Product extends \Core\Model {

    public static function getAllProducts() {

        try {
            $database = static::getDatabaseConnection();
            $statement = $database->query("
                SELECT p.id, p.product_name, p.product_price, p.product_owner_id  
                FROM products p;
            ");
            $productsResults = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $productsResults;
        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    public static function getSingleProduct() {
        try {
            $database = static::getDatabaseConnection();
            $singleProductQuery = $database->prepare("
                SELECT * FROM products p WHERE p.id = ?;
            ");
            $singleProductQuery->execute(':id');
            $singleProduct = $singleProductQuery->fetchAll(PDO::FETCH_ASSOC);
            return $singleProduct;
        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }
}