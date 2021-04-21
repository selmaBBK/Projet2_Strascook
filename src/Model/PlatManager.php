<?php

namespace App\Model;

class PlatManager extends AbstractManager
{
    public const TABLE = 'plats';

    /**
     * Insert new plat in database
     */
    public function insert(array $plat): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
        "(category,name,description,price,image) VALUES (:category,:name,:description,:price,:image)");
        $statement->bindValue('category', $plat['category'], \PDO::PARAM_STR);
        $statement->bindValue('name', $plat['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $plat['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $plat['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $plat['image'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update plat in database
     */
    public function update(array $plat): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET category = :category, name= :name, description = :description,
        price= :price, image= :image WHERE id= :id");
        $statement->bindValue('category', $plat['category'], \PDO::PARAM_STR);
        $statement->bindValue('name', $plat['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $plat['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $plat['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $plat['image'], \PDO::PARAM_STR);
        $statement->bindValue('id', $plat['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function sort(string $cat)
    {
        $query = 'SELECT * FROM ' . static::TABLE . " WHERE category = '$cat'";
        return $this->pdo->query($query)->fetchAll();
    }
}
