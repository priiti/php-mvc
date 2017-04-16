<?php

namespace App\Models;

use App\Token;
use PDO;

class User extends \Core\Model {

    public $errors = [];

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get all users from db as an array
     * @return array
     */
    public static function getAll() {
        $db = static::getDatabaseConnection();
        $stmt = $db->query(
            'SELECT user_id, firstname, lastname FROM users'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save() {
        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->pwd, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (user_email, password_hash, firstname, lastname)
                VALUES (:email, :password_hash, :firstname, :lastname)';

            $db = static::getDatabaseConnection();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function validate() {
        // Firstname
        if ($this->firstname == '') {
            $this->errors[] = 'Eesnimi on kohustuslik';
        }
        // Lastname
        if ($this->lastname == '') {
            $this->errors[] = 'Perenimi on kohustuslik';
        }
        // Email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Ebakorrektne e-maili aadress';
        }
        if (static::emailExists($this->email)) {
            $this->errors[] = 'E-mail on juba kasutusel';
        }
        // Password
        if (strlen($this->pwd) < 6) {
            $this->errors[] = 'Palun sisesta vähemalt 6 tähemärki';
        }
        if (preg_match('/.*[a-z]+.*/i', $this->pwd) == 0) {
            $this->errors[] = 'Parool peab sisaldama vähemalt ühte tähte';
        }
        if (preg_match('/.*\d+.*/i', $this->pwd) == 0) {
            $this->errors[] = 'Parool peab sisaldama vähemalt ühte numbrit';
        }
    }

    public static function emailExists($email) {
        return static::findUserByEmail($email) !== false;
    }

    public static function findUserByEmail($email) {
        $sql = 'SELECT * FROM users WHERE user_email = :email';
        $db = static::getDatabaseConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function authenticate($email, $password) {
        $user = static::findUserByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }
        return false;
    }

    public static function findUserById($id) {
        $sql = 'SELECT * FROM users WHERE user_id = :id';
        $db = static::getDatabaseConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    public function rememberLogin() {
        $token = new Token();
        $hashed_token = $token->getHash();

        $expiry_timestamp = time() + 60 * 60 * 24 * 30; // 30 days

        $sql = 'INSERT INTO AC_SAVED_LOGINS (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';
        $database = static::getDatabaseConnection();
        $stmt = $database->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }
}