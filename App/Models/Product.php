<?php

namespace App\Models;

use PDO;

class Product extends \Core\Model {

    // public $errors = []; -> implement to show add, edit etc. product errors (validation form)

    // Product properties from array
    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function getAllProducts() {

        try {
            $database = static::getDatabaseConnection();
            $sql = "SELECT p.id, p.product_name, p.product_price, concat(u.firstname, ' ', u.lastname) product_owner
                    FROM products p
                    INNER JOIN users u ON u.user_id = p.product_owner_id";
            $stmt = $database->prepare($sql);
            $stmt->execute();
            $productsResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $productsResults;
        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    public static function getSingleProduct($id) {
        try {
            $sql = "SELECT p.id, p.product_name, p.product_price, p.product_owner_id, 
                    concat(u.firstname, ' ', u.lastname) product_owner_name
                    FROM products p
                    INNER JOIN users u ON u.user_id = p.product_owner_id
                    WHERE id = :id";
            $database = static::getDatabaseConnection();
            $stmt = $database->prepare($sql);
            $stmt->execute([
                ':id' => $id
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    public function insertNewProduct() {

        try {
            $database = static::getDatabaseConnection();
            $sql = 'INSERT into products (product_name, product_price, product_owner_id)
                    VALUES (:product_name, :product_price, :product_owner_id)';

            $stmt = $database->prepare($sql);

            $stmt->bindValue(':product_name', $this->product_name, PDO::PARAM_STR);
            $stmt->bindValue(':product_price', $this->product_price, PDO::PARAM_STR);
            $stmt->bindValue(':product_owner_id', $this->user_id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return false;
    }

    // Check if array values aren't empty
    public static function validateProductData($data) {
        if(count(array_filter($data)) != count($data)){
            return false;
        }
        return true;
    }

    public function updateProduct() {
        try {
            $database = static::getDatabaseConnection();
            $sql = 'UPDATE products 
                    SET product_name = :product_name,
                    product_price = :product_price,
                    product_owner_id = :product_owner_id
                    WHERE id = :product_id';

            $stmt = $database->prepare($sql);

            $stmt->bindValue(':product_name', $this->product_name, PDO::PARAM_STR);
            $stmt->bindValue(':product_price', $this->product_price, PDO::PARAM_STR);
            $stmt->bindValue(':product_id', $this->product_id, PDO::PARAM_INT);
            $stmt->bindValue(':product_owner_id', $this->selected_user_id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }
}