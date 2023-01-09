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

        return (new User(-1))->checkLoginInfo($username,$password);
    }

    public function getUserId() : ?int
    {
        /*
         * Iegūt user id no $_SESSION['userId'], ja nav, tad null
         * */
        session_start();
        if(!empty($_SESSION['userId'])){
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
            $userInfo = (new User($user_id))->getUserInfo($user_id);
        }
        return $userInfo;
    }

    public function getUserIdFromUsername(string $username) : ?int
    {
        if($this->validateUsername($username)) {
            return (new User(-1))->getUserIdByUsername($username);
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
        if(!$this->checkForUsage($username,$email)){
            return false;
        }
        $emailFiltered = filter_var($email, FILTER_SANITIZE_EMAIL);
        return (new User(-1))->addUser($username,$password,$emailFiltered);
    }

    public function isUserLoggedIn() : bool
    {
        /*
         * pārbaudīt, vai lietotājs ir ielogojies, vai nē.
         * */
        //Vajadzētu kaut kādu cookie/paņemt metodi no mana prakses darba.
        session_start();
        if(!empty($_SESSION['userId'])){
            return true;
        }
        return false;
    }

    // https://www.codexworld.com/how-to/validate-password-strength-in-php/
    public function validatePassword(?string $password) : bool
    {
        if($password){
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);

            if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function validateUsername(string $username) : bool
    {
        if(strlen($username) > 25 || strlen($username) < 6){
            return false;
        }
        return true;
    }

    public function checkForUsage(string $username, string $email) : bool
    {
        if((new User(-1))->emailExists($email)){
            return true;
        }
        if((new User(-1))->usernameExists($username)){
            return true;
        }
        return false;
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
        $userId = $this->getUserId();
        $user = (new User($userId));
        $status1 = $status2 = $status3 = true;
        if($this->checkForUsage($username,$email)){
            $status1 = $status2 = false;
        }
        if($this->validateUsername($username) && $user->getUsername() !==$username){
            $status1 = $user->editUsername($username,$userId);
        }
        if($this->validatePassword($password) && $user->checkPassword($userId,$password)){
            $status2 =$user->editUserPassword($password,$userId);
        }
        if($user->getEmail() !==$email){
            $status3 =$user->editUserEmail($email,$userId);
        }
        return ($status1 || $status2 || $status3);
    }

    public function deleteUser(int $userId)
    {
        if($userId === $this->getUserId()){
            (new User($userId))->removeUser($userId);
            return $this->logOut();
        }
        return false;
    }
}
