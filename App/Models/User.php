<?php

namespace App\Models;

use App\Auth;
use App\Token;
use PDO;

class User extends \Core\Model {

    public $errors = [];

    // User properties from array
    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    // Get all users from db as an array (exclude with id nr.)
    public static function getAll($id = null) {
        $database = static::getDatabaseConnection();

        $sql = "SELECT 
                CONCAT(users.firstname, ' ', users.lastname) full_name, 
                users.user_id, users.user_email, 
                users.firstname, users.lastname, users.createdate,
                ur.is_admin, ur.add_post, ur.add_product
                FROM users
                INNER JOIN AC_USER_RIGHTS UR
                ON ur.user_id=users.user_id";

        if ($id) {
            $sql .= " WHERE users.user_id NOT IN(:user_id, 1)";

            $stmt = $database->prepare($sql);

            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
        } else {
            $stmt = $database->prepare($sql);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Register / save new user into database
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

    // Validate if signup form data was correct
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
            $this->errors[] = 'Parool peab sisaldama vähemalt 6 tähemärki';
        }
        if (preg_match('/.*[a-z]+.*/i', $this->pwd) == 0) {
            $this->errors[] = 'Parool peab sisaldama vähemalt ühte tähte';
        }
        if (preg_match('/.*\d+.*/i', $this->pwd) == 0) {
            $this->errors[] = 'Parool peab sisaldama vähemalt ühte numbrit';
        }
    }

    // Checks email existance
    public static function emailExists($email) {
        return static::findUserByEmail($email) !== false;
    }

    // Getting the user by email
    public static function findUserByEmail($email) {
        $sql = 'SELECT * FROM users WHERE user_email = :email';
        $db = static::getDatabaseConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    // Authenticates user by email and password_hash -> password_verify
    public static function authenticate($email, $password) {
        $user = static::findUserByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }
        return false;
    }

    // Getting the user by id
    public static function findUserById($id) {
        $sql = 'SELECT * FROM users 
                INNER JOIN AC_USER_RIGHTS UR
                ON ur.user_id=users.user_id
                WHERE users.user_id = :id';

        $db = static::getDatabaseConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    // Save hashed cookie value into database for 'Remember login' possibility
    public function rememberLogin() {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;

        $sql = 'INSERT INTO AC_SAVED_LOGINS (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';
        $database = static::getDatabaseConnection();
        $stmt = $database->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    // update user profile
    public function updateProfile($data) {
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->email = $data['email'];

        if ($data['pwd'] != '') {
            $this->pwd = $data['pwd'];
        }

        $this->validate();

        if (empty($this->errors)) {
            $sql = 'UPDATE users 
                    SET firstname = :firstname, 
                    lastname = :lastname, 
                    user_email = :email, 
                    password_hash = :password_hash
                    WHERE user_id = :id';

            $database = static::getDatabaseConnection();

            $stmt = $database->prepare($sql);

            $stmt->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->bindValue(':password_hash', $this->password_hash, PDO::PARAM_STR);

            $stmt->execute();
        }
        return false;
    }
}