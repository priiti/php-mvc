<?php

namespace App\Models;

use App\Token;
use PDO;

class RememberedLogin extends \Core\Model {

    public static function findByToken($value) {
        $token = new Token($value);
        $token_hash = $token->getHash();

        $sql = 'SELECT * FROM AC_SAVED_LOGINS
                WHERE token_hash = :token_hash';
        $database = static::getDatabaseConnection();
        $stmt = $database->prepare($sql);

        $stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        // Return as an object
        return $stmt->fetch();

    }

    public function getUser() {
        return User::findUserById($this->user_id);
    }

    public function hasExpired() {
        return strtotime($this->expires_at) < time();
    }

    public function deleteSavedLogin() {
        $sql = 'DELETE FROM AC_SAVED_LOGINS
                WHERE token_hash = :token_hash';

        $database = static::getDatabaseConnection();
        $stmt = $database->prepare($sql);

        $stmt->bindValue(':token_hash', $this->token_hash, PDO::PARAM_STR);
        $stmt->execute();
    }
}