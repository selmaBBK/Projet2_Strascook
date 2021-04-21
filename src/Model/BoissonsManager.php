<?php

namespace App\Model;

class BoissonsManager extends AbstractManager
{
    public const TABLE = 'boissons';

    /**
     * Insert new Boisson in database
     * @param array $boissons
     * @return int
     */

    public function insert(array $boissons): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`name`,`description`,`price`,`image`) VALUES (:name, :description, :price, :image)");
        $statement->bindValue('name', $boissons['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $boissons['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $boissons['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $boissons['image'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update Boisson in database
     * @param array $boissons
     * @return bool
     */
    public function update(array $boissons): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
            " SET  name = :name, description = :description, price = :price, image = :image WHERE id=:id");
        $statement->bindValue('name', $boissons['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $boissons['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $boissons['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $boissons['image'], \PDO::PARAM_STR);
        $statement->bindValue('id', $boissons['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}
