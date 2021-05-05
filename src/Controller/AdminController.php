<?php

namespace App\Controller;

use App\Model\AdminManager;
use App\Service\CheckUser;

class AdminController extends AbstractController
{
    public function index(): string
    {
        /**
         * On instancie un objet $checkAdmin pour pouvoir utiliser la méthode chackAdmin()
         */
        $checkAdmin = new CheckUser();
        /**
         * Ici si checkAdmin() est vérifié il fait rien sinon il redirige le client
         */
        $checkAdmin->checkAdmin();

        $adminManager = new AdminManager();
        $entrees = $adminManager->selectEntrees('id');
        $boissons = $adminManager->selectBoissons('id');
        $desserts = $adminManager->selectDesserts('id');
        $platDuJour = $adminManager->selectPlatDuJour('id');
        $plats = $adminManager->selectPlats('id');

        return $this->twig->render('Admin/index.html.twig', [
            'entrees' => $entrees,
            'boissons' => $boissons,
            'desserts' => $desserts,
            'platDuJour' => $platDuJour,
            'plats' => $plats
        ]);
    }
}
