<?php

namespace App\Model;

class EntreeManager extends AbstractManager
{
    public const TABLE = 'entrees';

    /**
     * Insert new entree in database
     */
    public function insert(array $entree): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`category`,`name`,`description`,`price`,`image`) 
        VALUES (:category,:name,:description,:price,:image)");
        $statement->bindValue('category', $entree['category'], \PDO::PARAM_STR);
        $statement->bindValue('name', $entree['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $entree['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $entree['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $entree['image'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update entree in database
     */
    public function update(array $entree): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET category =:category , 
        name=:name, description=:description, price=:price, image = :image WHERE id=:id");
        $statement->bindValue('category', $entree['category'], \PDO::PARAM_STR);
        $statement->bindValue('name', $entree['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $entree['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $entree['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $entree['image'], \PDO::PARAM_STR);
        $statement->bindValue('id', $entree['id'], \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function selectCat(string $cat): array
    {

        $query = 'SELECT * FROM ' . static::TABLE . " WHERE category= $cat";



        return $this->pdo->query($query)->fetchAll();
    }

    public function selectVegan(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE . " WHERE category= 'vegan'";
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectVegetarian(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE . " WHERE category= 'vegetarian'";
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectOmnivore(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE . " WHERE category= 'omnivore'";
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
}
