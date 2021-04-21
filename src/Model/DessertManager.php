<?php

namespace App\Model;

class DessertManager extends AbstractManager
{
    public const TABLE = 'desserts';

    /**
     * Insert new item in database
     */

    public function insert(array $dessert): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "(category,name,description,price,image) 
        VALUES (:category,:name,:description,:price,:image)");
        $statement->bindValue('category', $dessert['category'], \PDO::PARAM_STR);
        $statement->bindValue('name', $dessert['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $dessert['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $dessert['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $dessert['image'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->LastinsertId();
    }

    /**
     * Update item in database
     */

    public function update(array $dessert): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET category = :category, name= :name, 
        description = :description, price= :price, image= :image WHERE id= :id");
        $statement->bindValue('category', $dessert['category'], \PDO::PARAM_STR);
        $statement->bindValue('name', $dessert['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $dessert['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $dessert['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $dessert['image'], \PDO::PARAM_STR);
        $statement->bindValue('id', $dessert['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}
