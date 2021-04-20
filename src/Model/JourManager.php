<?php

namespace App\Model;

class JourManager extends AbstractManager
{
    public const TABLE = 'plat_du_jour';

    /**
     * Insert new plat du jour in database
     */
    public function insert(array $jour): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`category`,`name`,`description`,`price`,`image`) 
        VALUES (:category,:name,:description,:price,:image)");
        $statement->bindValue('category', $jour['category'], \PDO::PARAM_STR);
        $statement->bindValue('name', $jour['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $jour['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $jour['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $jour['image'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update plat du jour in database
     */
    public function update(array $jour): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET category =:category , 
        name=:name, description=:description, price=:price, image = :image WHERE id=:id");
        $statement->bindValue('category', $jour['category'], \PDO::PARAM_STR);
        $statement->bindValue('name', $jour['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $jour['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $jour['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $jour['image'], \PDO::PARAM_STR);
        $statement->bindValue('id', $jour['id'], \PDO::PARAM_INT);
        return $statement->execute();
    }
}
