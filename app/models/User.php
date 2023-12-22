<?php

require_once("app/config/config.php");

class User {
    protected $conn;

    public function __construct(){
        global $conn;
        $this->conn = $conn;
    }

    public function create($username, $password, $email) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $run = $this->conn->prepare($query);
        $run->bind_param("sss", $username, $hashed_password, $email);

        $result = $run->execute();

        if($result) {
            return true;
        }

        return false;
    }

    public function login($username, $password) {
        $query = "SELECT user_id, password from users where username=?";

        $run = $this->conn->prepare($query);
        $run->bind_param("s",$username);
        $run->execute();
        $result = $run->get_result();

        if($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                return true;
            }
        }

        return false;

    }

    public function logout() {
        unset($_SESSION['user_id']);
    }

    public function is_logged() {
        if(isset($_SESSION['user_id']))
            return true;

        return false;
    }

    public function is_unique($username) {
        $query = "SELECT username FROM users WHERE username = ?";
        $run = $this->conn->prepare($query);
        $run->bind_param("s", $username);
        $run->execute();

        $result = $run->get_result();
        if($result->num_rows == 1) {
            return false;
        }
        
        return true;
    }

    public function get_by_id($user_id) {
        $query = "SELECT * FROM users WHERE user_id = ?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $user_id);
        $run->execute();

        $result = $run->get_result();
        return $result->fetch_assoc();
    }
}