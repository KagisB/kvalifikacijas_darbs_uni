<?php

namespace App\Controllers;

require_once "../../vendor/autoload.php";

use App\Models\User;
use Datetime;

class UserController{
    public function validateInput() : bool
    {
        /*
         * validēt lietotāja ievadītos datus login laikā
         * */
        if(isset($_POST['password'])){
            $password = $_POST['password'];
        }
        else return false;
        if(isset($_POST['username'])){
            $username = $_POST['username'];
        }
        else return false;
        if(!$this->validateUsername($username)) {//username nav pareizs
            //echo "Username is incorrect. It should be between 6 and 25 characters";
            return false;
        }
        if(!$this->validatePassword($password)) {//parole nav pareiza
            //echo "Password is incorrect. It should contain at least one upper and lower case letter, one number and be at least 8 characters long";
            return false;
        }

        return (new User())->checkLoginInfo($username,$password);
    }

    public function getUserId() : ?int
    {
        /*
         * Iegūt user id no $_SESSION['userId'], ja nav, tad null
         * */
        session_start();
        if(isset($_SESSION['userId'])) {
            return $_SESSION['userId'];
        }
        return null;
    }

    public function getUserInfo() : ?array
    {
        /*
         * pārbauda kāds status ir user, 0 = parasts, 1= moderators, 2 = admin
         * */
        $user_id = $this->getUserId();
        $userInfo = null;
        if($user_id){
            $userInfo = (new User())->getUserInfo($user_id);
        }
        return $userInfo;
    }

    public function getUserIdFromUsername(string $username) : ?int
    {
        if($this->validateUsername($username)) {
            return (new User)->getUserIdByUsername($username);
        }
        return null;
    }
    public function validateSignUp(string $username, string $password, string $email)
    {
        /*
         * Validēt ievadītos datus sign up laikā
         * */
        if(!$this->validateUsername($username)) {//username nav pareizs
            //echo "Username is incorrect. It should be between 6 and 25 characters";
            return false;
        }
        if(!$this->validatePassword($password)) {//parole nav pareiza
            //echo "Password is incorrect. It should contain at least one upper and lower case letter, one number and be at least 8 characters long";
            return false;
        }
        if(!$this->validateEmail($email)){
            return false;
        }
        $emailFiltered = filter_var($email, FILTER_SANITIZE_EMAIL);
        return (new User())->addUser($username,$password,$emailFiltered);
    }

    public function isUserLoggedIn() : bool
    {
        /*
         * pārbaudīt, vai lietotājs ir ielogojies, vai nē.
         * */
        //Vajadzētu kaut kādu cookie/paņemt metodi no mana prakses darba.
        if(!empty($_SESSION['userId'])){
            return true;
        }
        return false;
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
        if((new User)->usernameExists($username)){
            return false;
        }
        return true;
    }

    public function validateEmail(string $email) : bool
    {
        if((new User)->emailExists($email)){
            return false;
        }
        return true;
    }

    public function logOut()
    {
        session_start();
        session_unset();
        session_destroy();
        if(!isset($_SESSION)){
            return true;
        }
        return false;
    }

    public function logIn()
    {
        if($this->validateInput()) {
            session_start();
            $_SESSION['userId'] = $this->getUserIdFromUsername($_POST['username']);
            $_SESSION['logInStatus'] = true;
            return true;
        }
        return false;
    }

    public function signUp(string $username, string $password, string $email)
    {
        if($this->validateSignUp($username,$password,$email)) {
            return $this->logIn();
        }
        return false;
    }

    public function checkEditUserInfo(?string $username="", ?string $password="", ?string $email="")
    {
        $validation = true;
        if(!$this->validateUsername($username)){
            $validation = false;
        }
        if(!$this->validateEmail($email)){
            $validation = false;
        }
        if(!$this->validatePassword($password)){
            $validation = false;
        }
        $userId = $this->getUserId();
        if($validation and $userId){
            return $this->editUser($username,$password,$email,$userId);
        }
        return false;
    }

    public function editUser(int $userId,?string $username="", ?string $password="", ?string $email="") : bool
    {
        $editStatus = false;
        if(!empty($username)){
            $editStatus = (new User)->editUsername($username, $userId);
        }
        if(!empty($email)){
            $editStatus = (new User)->editUserEmail($email, $userId);
        }
        if(!empty($password)){
            $editStatus = (new User)->editUserPassword($password, $userId);
        }
        return $editStatus;
    }

    public function deleteUser(int $userId)
    {
        if($userId === $this->getUserId()){
            return (new User)->removeUser($userId);
        }
        return false;
    }
}
