<?php

namespace App\Service;

class CheckUser
{
    public function checkLogin()
    {
        if (!$_SESSION) {
            header('location:/User/Connexion/');
        }
    }

    public function checkAdmin()
    {
        if ($_SESSION['admin'] != true) {
            header('location:/User/Connexion/');
            var_dump($_SESSION['admin']);
        } else {
            var_dump($_SESSION['admin']);
        }
    }
}
