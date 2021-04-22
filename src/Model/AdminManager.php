<?php


namespace App\Model;

class AdminManager extends AbstractManager
{
    public const TABLE_ENTREES = 'entrees';
    public const TABLE_BOISSONS = 'boissons';
    public const TABLE_DESSERTS = 'desserts';
    public const TABLE_PLAT_DU_JOUR = 'plat_du_jour';
    public const TABLE_PLATS = 'plats';

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
}
