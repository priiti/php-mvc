<?php

namespace App\Models;

use PDO;

class Post extends \Core\Model {

    // Post properties from array
    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function getAll() {
        try {
            $database = static::getDatabaseConnection();
            $statement = $database->prepare("
                SELECT 
                p.id, 
                p.title, 
                concat(SUBSTRING(p.content, 1, 200), '...') content, 
                p.create_date
                FROM posts p
                ORDER BY p.create_date DESC;
            ");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;

        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    public static function getSinglePost($post_id) {
        try {
            $database = static::getDatabaseConnection();
            $sql = "SELECT p.title, p.content, p.create_date, CONCAT(u.firstname, ' ', u.lastname) owner_name, p.id
                    FROM posts p
                    INNER JOIN users u on u.user_id = p.owner_id
                    WHERE p.id = :post_id";

            $stmt = $database->prepare($sql);

            $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;

        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    // Check if array values aren't empty
    public static function validatePostData($data) {
        if(count(array_filter($data)) != count($data)){
            return false;
        }
        return true;
    }

    public function updatePost() {
        try {
            $database = static::getDatabaseConnection();
            $sql = "UPDATE posts SET posts.title = :title, posts.content = :content, posts.create_date=NOW()
                    WHERE posts.id = :post_id";

            $stmt = $database->prepare($sql);

            $stmt->bindValue(':title', $this->post_title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->post_content);
            $stmt->bindValue(':post_id', $this->post_id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    public function addPost() {
        try {
            $database = static::getDatabaseConnection();
            $sql = "INSERT INTO posts (posts.title, posts.content, posts.owner_id, posts.create_date)
                    VALUES (:title, :content, :owner_id, NOW())";
            $stmt = $database->prepare($sql);

            $stmt->bindValue(':title', $this->post_title);
            $stmt->bindValue(':content', $this->post_content);
            $stmt->bindValue(':owner_id', $this->user_id);

            return $stmt->execute();

        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
        return null;
    }
}