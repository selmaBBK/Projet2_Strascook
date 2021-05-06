<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = "users";
    public const TABLE_RESERVATION = 'reservation';
    public const TABLE_ENTREES = 'entrees';
    public const TABLE_BOISSONS = 'boissons';
    public const TABLE_DESSERTS = 'desserts';
    public const TABLE_PLAT_DU_JOUR = 'plat_du_jour';
    public const TABLE_PLATS = 'plats';
    public const TABLE_PANIER = 'panier';



    /**
     * Insert new User in database
     */

    public function insert(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "(name, adress, pass, mail, pseudo)
        VALUES (:name, :adress, :pass, :mail, :pseudo)");
        $statement->bindValue('name', $user['name'], \PDO::PARAM_STR);
        $statement->bindValue('adress', $user['adress'], \PDO::PARAM_STR);
        $statement->bindValue('pass', md5($user['pass']), \PDO::PARAM_STR);
        $statement->bindValue('mail', $user['mail'], \PDO::PARAM_STR);
        $statement->bindValue('pseudo', $user['pseudo'], \PDO::PARAM_STR);


        $statement->execute();
        return (int)$this->pdo->LastinsertId();
    }

    /**
     * Update User in database
     */

    public function update(array $user): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET name = :name, 
        adress = :adress, pass = :pass WHERE id = :id");
        $statement->bindValue('name', $user['name'], \PDO::PARAM_STR);
        $statement->bindValue('adress', $user['adress'], \PDO::PARAM_STR);
        $statement->bindValue('pass', $user['pass'], \PDO::PARAM_STR);
        $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * On regarde si l'utilisateur existe bien dans la bdd
     * et qu'il a le bon mot de pass
     *
     */
    public function checkLogin(array $user)
    {
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE . ' WHERE pseudo=:pseudo AND pass=:pass
        OR mail=:mail');
        //$statement->bindValue('name', $user['name'], \PDO::PARAM_STR);
        $statement->bindValue('pass', $user['pass'], \PDO::PARAM_STR);
        $statement->bindValue('pseudo', $user['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue('mail', $user['mail'], \PDO::PARAM_STR);
        $statement->execute();

        return $user = $statement->fetch();
    }

    public function checkPseudo(array $user)
    {
        $statement = $this->pdo->prepare('SELECT id FROM ' . self::TABLE . ' WHERE pseudo=:pseudo');
        $statement->bindValue('pseudo', $user['pseudo'], \PDO::PARAM_STR);

        $statement->execute();

        return $user = $statement->fetch();
    }

    public function checkMail(array $user)
    {
        $statement = $this->pdo->prepare('SELECT id FROM ' . self::TABLE . ' WHERE mail=:mail');
        $statement->bindValue('mail', $user['mail'], \PDO::PARAM_STR);

        $statement->execute();

        return $user = $statement->fetch();
    }

    public function checkPass(array $user)
    {
        $statement = $this->pdo->prepare('SELECT id FROM ' . self::TABLE . ' WHERE pass=:pass');
        $statement->bindValue('pass', md5($user['pass']), \PDO::PARAM_STR);

        $statement->execute();

        return $user = $statement->fetch();
    }
/** Select informations Reservation
*/
    public function selectAllReservation(int $id)
    {
        $statement = $this->pdo->prepare("SELECT r.adress,
        r.date, pa.plat_du_jour_id, u.name userName, r.adress userAdress,
       e.name entreeName, e.price entreePrice,
       pl.name platName, pl.price platPrice, d.name dessertName, d.price dessertPrice, b.name boissonName,
       d.price boissonPrice, pdj.name pdjName,
       pdj.price pdjPrice, SUM(e.price + pl.price + d.price + b.price) addition FROM "
            . self::TABLE_RESERVATION . " r 
JOIN " . self::TABLE . " u ON u.id=user_id 
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
    public function selectPlatDuJour(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE_PLAT_DU_JOUR;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
}
