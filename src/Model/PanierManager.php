<?php

namespace App\Model;

class PanierManager extends AbstractManager
{
    public const TABLE_ENTREES = 'entrees';
    public const TABLE_BOISSONS = 'boissons';
    public const TABLE_DESSERTS = 'desserts';
    public const TABLE_PLAT_DU_JOUR = 'plat_du_jour';
    public const TABLE_PLATS = 'plats';
    public const TABLE_PANIER = 'panier';


    public function selectEntrees(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE_ENTREES;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectBoissons(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE_BOISSONS;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectDesserts(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE_DESSERTS;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectPlatDuJour(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE_PLAT_DU_JOUR;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectPlats(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE_PLATS;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function insert(array $panier): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE_PANIER .
        " (`plat_du_jour_id`, `entree_id`, `plats_id`, `desserts_id`, `boissons_id`) 
    VALUES (:pdj, :entree, :plat, :dessert, :boisson) ");
        $statement->bindValue('pdj', $panier['pdj']) ;
        $statement->bindValue('entree', $panier['entree']) ;
        $statement->bindValue('plat', $panier['plat']) ;
        $statement->bindValue('dessert', $panier['dessert']) ;
        $statement->bindValue('boisson', $panier['boisson']) ;
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
