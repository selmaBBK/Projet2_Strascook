<?php

namespace App\Model;

class AdminManager extends AbstractManager
{
    /* La page admin est le rassemblement de toutes les tables,
    on a donc besoin de les appeler via les contantes ci-dessous */
    public const TABLE_ENTREES = 'entrees';
    public const TABLE_BOISSONS = 'boissons';
    public const TABLE_DESSERTS = 'desserts';
    public const TABLE_PLAT_DU_JOUR = 'plat_du_jour';
    public const TABLE_PLATS = 'plats';

    /**
     * On selectionne toutes les entrées
     *
     */
    public function selectEntrees(string $orderBy = '', string $direction = 'ASC'): array
    {
        // avec $query, on écrit la requête SQL
        $query = 'SELECT * FROM ' . self::TABLE_ENTREES;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * On selectionne toutes les boissons
     *
     */
    public function selectBoissons(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE_BOISSONS;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * On selectionne toutes les desserts
     *
     */
    public function selectDesserts(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE_DESSERTS;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * On selectionne le plat du jour
     *
     */
    public function selectPlatDuJour(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE_PLAT_DU_JOUR;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * On selectionne toutes les plats
     *
     */
    public function selectPlats(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE_PLATS;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
}
