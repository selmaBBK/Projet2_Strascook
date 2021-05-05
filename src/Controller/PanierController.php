<?php

namespace App\Controller;

use _HumbugBox5ccdb2ccdb35\Nette\Utils\DateTime;
use App\Model\PanierManager;
use App\Service\CheckUser;
use Cassandra\Date;
use mysql_xdevapi\TableUpdate;

class PanierController extends AbstractController
{
    public function index(): string
    {
        $panierManager = new PanierManager();
        $entrees = $panierManager->selectEntrees('id');
        $boissons = $panierManager->selectBoissons('id');
        $desserts = $panierManager->selectDesserts('id');
        $platDuJour = $panierManager->selectPlatDuJour('id');
        $plats = $panierManager->selectPlats('id');

        return $this->twig->render('Panier/index.html.twig', [
            'entrees' => $entrees,
            'boissons' => $boissons,
            'desserts' => $desserts,
            'platDuJour' => $platDuJour,
            'plats' => $plats
        ]);
    }
    public function add(): string
    {
        $panierManager = new PanierManager();
        $entrees = $panierManager->selectEntrees('id');
        $boissons = $panierManager->selectBoissons('id');
        $desserts = $panierManager->selectDesserts('id');
        $platDuJour = $panierManager->selectPlatDuJour('id');
        $plats = $panierManager->selectPlats('id');

        $checkUser = new checkUser();
        $checkUser->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $panier = $_POST;

            $errors = [];

            if (isset($panier['date']) && empty($panier['name'])) {
                $errors[5] = '⚠️ Entrez une date de commande';
            }

            if (isset($panier['date']) && !empty($panier['date'])) {
                $presentTime = date("Y-m-d H:i:s");
                $diff = abs(strtotime($_POST['date']) - strtotime($presentTime));
                $hour = (int)date('H', strtotime($_POST['date']));
                $years = floor($diff / (365 * 60 * 60 * 24));
                $jour = date('%D', strtotime($_POST['date']));
                $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days = ceil(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                if ($days - 1 <= 2) {
                    $errors[1] = '⚠️ Il faut une date de 2 jours minimum avant la commande';
                }

                if ($jour == '%Sat' || $jour == '%Sun') {
                    $errors[3] = '⚠️ Nous sommes férmée le week-end 😢';
                }

                if ($hour <= 17 || $hour > 23) {
                    $errors[2] = '⚠️ : Horraires de livraison seulement après 17h';
                }
            }

            if (!empty($errors)) {
                return $this->twig->render('Panier/add.html.twig', ['errors' => $errors]);
            }

            if (empty($errors)) {
                if ($_POST['pdj'] == '') {
                    $_POST['pdj'] = null;
                }
                if ($_POST['entree'] == '') {
                    $_POST['entree'] = null;
                }
                if ($_POST['plat'] == '') {
                    $_POST['plat'] = null;
                }
                if ($_POST['dessert'] == '') {
                    $_POST['dessert'] = null;
                }
                if ($_POST['boisson'] == '') {
                    $_POST['boisson'] = null;
                }
                $_POST['date'] = date("Y-m-d H:i:s");

                // TODO validations (length, format...)

                // if validation is ok, insert and redirection
                $panierManager = new PanierManager();
                $id = $panierManager->insert($panier);
                header('Location:/Panier/show' . $id);
            }

            // if validation is ok, insert and redirection
            $panierManager = new PanierManager();

            $id = $panierManager->insert($panier);
            $panierManager->insertReservation($panier, $id);
            header('Location:/Reservation/show/' . $id);
        }
        return $this->twig->render('Panier/add.html.twig', [
            'entrees' => $entrees,
            'boissons' => $boissons,
            'desserts' => $desserts,
            'platDuJour' => $platDuJour,
            'plats' => $plats,
        ]);
    }
    public function show(int $id): string
    {
        $panierManager = new PanierManager();
        $panier = $panierManager->selectPanier($id);
        if ($panier['plat_du_jour_id'] == 1) {
            $pdj = $panierManager->selectPlatDuJour();
        } else {
            $pdj = '';
        }
        return $this->twig->render('Panier/show.html.twig', ['panier' => $panier, 'pdj' => $pdj]);
    }
}
