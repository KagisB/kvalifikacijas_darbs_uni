<?php

namespace app\Models;

class User{

    public function addUser(string $username, string $password, string $email) : bool
    {
        /*
         * Izveidot lietotāju ar iedotajiem datiem, paroli šifrēt šeit vai vēl atsevišķā funkcijā?
         * */
    }

    public function userExists(int $user_id) : bool
    {
        /*
         * Pārbaudīt, vai lietotājs eksistē
         * */
    }

    public function checkLoginInfo(int $user_id, string $username, string $password) : bool
    {
        /*
         * Salīdzina padoto paroli ar datubāzē esošo paroli
         * */
    }
}
