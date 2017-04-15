<?php

namespace App\Models;

use PDO;

class Post extends \Core\Model {

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
}