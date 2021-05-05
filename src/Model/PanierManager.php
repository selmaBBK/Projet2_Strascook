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
    public const TABLE_RESERVATION = 'reservation';


    /**
     * On selectionne toutes les entrées
     *
     */
    public function selectEntrees(string $orderBy = '', string $direction = 'ASC'): array
    {
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
        $query = 'SELECT * FROM ' . static::TABLE_BOISSONS;
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
        $query = 'SELECT * FROM ' . static::TABLE_DESSERTS;
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
        $query = 'SELECT * FROM ' . static::TABLE_PLAT_DU_JOUR;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * On selectionne touts les plats
     *
     */
    public function selectPlats(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE_PLATS;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * On insère le contenu de $_POST qui correspond à $panier (cf controller)
     *
     */
    public function insert(array $panier): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE_PANIER .
            " (`plat_du_jour_id`, `entree_id`, `plats_id`, `desserts_id`, `boissons_id`) 
        VALUES (:pdj, :entree, :plat, :dessert, :boisson) ");
        $statement->bindValue('pdj', $panier['pdj']);
        $statement->bindValue('entree', $panier['entree']);
        $statement->bindValue('plat', $panier['plat']);
        $statement->bindValue('dessert', $panier['dessert']);
        $statement->bindValue('boisson', $panier['boisson']);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
    public function selectPanier(int $id)
    {
        $statement = $this->pdo->prepare("SELECT e.name entreeName, e.price entreePrice,
       pl.name platName, pl.price platPrice, d.name dessertName,
       d.price dessertPrice, b.name boissonName, d.price boissonPrice,
       pa.plat_du_jour_id FROM " . self::TABLE_PANIER .
            " pa LEFT JOIN " . self::TABLE_ENTREES . " e ON e.id=pa.entree_id 
        LEFT JOIN " . self::TABLE_PLATS . " pl ON pl.id=pa.plats_id 
        LEFT JOIN " . self::TABLE_DESSERTS . " d ON d.id=pa.desserts_id 
        LEFT JOIN " . self::TABLE_BOISSONS . " b ON b.id=pa.boissons_id 
        LEFT JOIN " . self::TABLE_PLAT_DU_JOUR . " pdj ON pdj.id=pa.plat_du_jour_id
        Where pa.id=:id");

        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetch();
    }

    public function insertReservation(array $panier, int $id): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE_RESERVATION .
            " (`date`, `adress`, `panier_id`, `user_id`) 
    VALUES (:date, :adress, :panier_id, :user_id) ");
        $statement->bindValue('adress', $panier['adress']);
        $statement->bindValue('date', $panier['date']);
        $statement->bindValue('user_id', $_SESSION['userId']);
        $statement->bindValue('panier_id', $id);
        $statement->execute();
        return $this->pdo->lastInsertId();
    }
}
