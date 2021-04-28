<?php

namespace App\Controller;

use App\Model\PanierManager;

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
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

            $panier = $_POST;

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $panierManager = new PanierManager();
            $id = $panierManager->insert($panier);
            header('Location:/Panier/show' . $id);
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
        $panier = $panierManager->selectOneById($id);

        return $this->twig->render('Panier/show.html.twig', ['panier' => $panier]);
    }
}
