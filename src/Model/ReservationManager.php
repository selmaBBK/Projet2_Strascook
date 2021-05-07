<?php

namespace App\Model;

class ReservationManager extends AbstractManager
{
    public const TABLE = 'reservation';
    public const TABLE_ENTREES = 'entrees';
    public const TABLE_BOISSONS = 'boissons';
    public const TABLE_DESSERTS = 'desserts';
    public const TABLE_PLAT_DU_JOUR = 'plat_du_jour';
    public const TABLE_PLATS = 'plats';
    public const TABLE_PANIER = 'panier';
    public const TABLE_USER = 'users';


    /**
     * Select informations Reservation
     */
    public function selectAllReservation(int $id)
    {
        $statement = $this->pdo->prepare("SELECT r.adress,
        r.date, pa.plat_du_jour_id, u.name userName, r.adress userAdress,
        e.name entreeName, e.price entreePrice,
        pl.name platName, pl.price platPrice, d.name dessertName, d.price dessertPrice, b.name boissonName,
        d.price boissonPrice, pdj.name pdjName,
        pdj.price pdjPrice, SUM(e.price + pl.price + d.price + b.price) addition FROM "
            . self::TABLE . " r 
        JOIN " . self::TABLE_USER . " u ON u.id=user_id 
        JOIN " . self::TABLE_PANIER . " pa ON pa.id=panier_id
        LEFT JOIN " . self::TABLE_ENTREES . " e ON e.id=pa.entree_id 
        LEFT JOIN " . self::TABLE_PLATS . " pl ON pl.id=pa.plats_id 
        LEFT JOIN " . self::TABLE_DESSERTS . " d ON d.id=pa.desserts_id 
        LEFT JOIN " . self::TABLE_BOISSONS . " b ON b.id=pa.boissons_id 
        LEFT JOIN " . self::TABLE_PLAT_DU_JOUR . " pdj ON pdj.id=pa.plat_du_jour_id
        WHERE r.id=$id");
        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetch();
    }

    /**
     * Delete  reservation
     */
    public function delete(int $id): void
    {

        $statement = $this->pdo->prepare("DELETE FROM " . static::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     *Update Reservation in database
     */
    public function update(array $res)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `adress` = :adress WHERE id=:id");
        $statement->bindValue('id', $res['id'], \PDO::PARAM_INT);
        $statement->bindValue('adress', $res['adress'], \PDO::PARAM_STR);

        $statement->execute();
    }
    public function selectPlatDuJour(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE_PLAT_DU_JOUR;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
}
