<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = "users";


    /**
     * Insert new User in database
     */

    public function insert(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "(name, adress, pass, mail, pseudo)
        VALUES (:name, :adress, :pass, :mail, :pseudo)");
        $statement->bindValue('name', $user['name'], \PDO::PARAM_STR);
        $statement->bindValue('adress', $user['adress'], \PDO::PARAM_STR);
        $statement->bindValue('pass', md5($user['pass']), \PDO::PARAM_STR);
        $statement->bindValue('mail', $user['mail'], \PDO::PARAM_STR);
        $statement->bindValue('pseudo', $user['pseudo'], \PDO::PARAM_STR);


        $statement->execute();
        return (int)$this->pdo->LastinsertId();
    }

    /**
     * Update User in database
     */

    public function update(array $user): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET 
        adress = :adress, pass = :pass WHERE id = :id");
        $statement->bindValue('adress', $user['adress'], \PDO::PARAM_STR);
        $statement->bindValue('pass', $user['pass'], \PDO::PARAM_STR);
        $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * On regarde si l'utilisateur existe bien dans la bdd
     * et qu'il a le bon mot de pass
     *
     */
    public function checkLogin(array $user)
    {
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE . ' WHERE pseudo=:pseudo AND pass=:pass
        OR mail=:mail');
        //$statement->bindValue('name', $user['name'], \PDO::PARAM_STR);
        $statement->bindValue('pass', $user['pass'], \PDO::PARAM_STR);
        $statement->bindValue('pseudo', $user['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue('mail', $user['mail'], \PDO::PARAM_STR);
        $statement->execute();

        return $user = $statement->fetch();
    }

    public function checkPseudo(array $user)
    {
        $statement = $this->pdo->prepare('SELECT id FROM ' . self::TABLE . ' WHERE pseudo=:pseudo');
        $statement->bindValue('pseudo', $user['pseudo'], \PDO::PARAM_STR);

        $statement->execute();

        return $user = $statement->fetch();
    }

    public function checkMail(array $user)
    {
        $statement = $this->pdo->prepare('SELECT id FROM ' . self::TABLE . ' WHERE mail=:mail');
        $statement->bindValue('mail', $user['mail'], \PDO::PARAM_STR);

        $statement->execute();

        return $user = $statement->fetch();
    }

    public function checkPass(array $user)
    {
        $statement = $this->pdo->prepare('SELECT id FROM ' . self::TABLE . ' WHERE pass=:pass');
        $statement->bindValue('pass', md5($user['pass']), \PDO::PARAM_STR);

        $statement->execute();

        return $user = $statement->fetch();
    }
}
