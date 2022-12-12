<?php

namespace app\Controllers;

use app\Models\User;
use Datetime;

class UserController{
    public function validateInput() : bool
    {
        /*
         * validēt lietotāja ievadītos datus login laikā
         * */
        $password = $_POST['password'];
        $username = $_POST['username'];
        if(!$this->validateUsername($username)) {//username nav pareizs
            echo "Username is incorrect. It should be between 6 and 25 characters";
            return false;
        }
        if(!$this->validatePassword($password)) {//parole nav pareiza
            echo "Password is incorrect. It should contain at least one upper and lower case letter, one number and be at least 8 characters long";
            return false;
        }
        return (new User())->checkLoginInfo($username,$password);
    }

    public function authHTML()
    {
        if(empty($_SESSION['userlogin'])){
            header('Location: ../views/login.php');
            exit();
        }
    }

    public function getUserId() : ?int
    {
        /*
         * Iegūt user id no $_SESSION['userId'], ja nav, tad null
         * */
        if(isset($_SESSION['userId'])) {
            return $_SESSION['userId'];
        }
        return null;
    }

    public function getUserStatus() : ?int
    {
        /*
         * pārbauda kāds status ir user, 0 = parasts, 1= moderators, 2 = admin
         * */
        $user_id = $this->getUserId();
        if($user_id){
            $user = (new User())->getUserInfo($user_id);
            return $user['status'];
        }
        return null;
    }
    public function validateSignUp()
    {
        /*
         * Validēt ievadītos datus sign up laikā
         * */
        $password = $_POST['password'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        if(!$this->validateUsername($username)) {//username nav pareizs
            echo "Username is incorrect. It should be between 6 and 25 characters";
            return false;
        }
        if(!$this->validatePassword($password)) {//parole nav pareiza
            echo "Password is incorrect. It should contain at least one upper and lower case letter, one number and be at least 8 characters long";
            return false;
        }
        $emailFiltered = filter_var($email, FILTER_SANITIZE_EMAIL);
        return (new User())->addUser($username,$password,$emailFiltered);
    }

    public function isUserLoggedIn()
    {
        /*
         * pārbaudīt, vai lietotājs ir ielogojies, vai nē.
         * */
        //Vajadzētu kaut kādu cookie/paņemt metodi no mana prakses darba.
    }

    // https://www.codexworld.com/how-to/validate-password-strength-in-php/
    public function validatePassword(string $password) : bool
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);

        if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
           return false;
        }
        return true;
    }

    public function validateUsername(string $username) : bool
    {
        if(strlen($username) > 25 || strlen($username) < 6){
            return false;
        }
        return true;
    }
}
