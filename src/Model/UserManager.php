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
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "(name, adress, pass)
        VALUES (:name, :adress, :pass)");
        $statement->bindValue('name', $user['name'], \PDO::PARAM_STR);
        $statement->bindValue('adress', $user['adress'], \PDO::PARAM_STR);
        $statement->bindValue('pass', md5($user['pass']), \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->LastinsertId();
    }

    /**
     * Update User in database
     */

    public function update(array $user): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET name = :name, 
        adress = :adress, pass = :pass WHERE id = :id");
        $statement->bindValue('name', $user['name'], \PDO::PARAM_STR);
        $statement->bindValue('adress', $user['adress'], \PDO::PARAM_STR);
        $statement->bindValue('pass', $user['pass'], \PDO::PARAM_STR);
        $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function checkLogin(array $user)
    {
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE . ' WHERE name=:name AND pass=:pass');
        $statement->bindValue('name', $user['name'], \PDO::PARAM_STR);
        $statement->bindValue('pass', $user['pass'], \PDO::PARAM_STR);
        $statement->execute();
        return $user = $statement->fetch();
    }
}
