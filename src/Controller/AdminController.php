<?php

namespace App\Controller;

use App\Model\AdminManager;

class AdminController extends AbstractController
{
    public function index(): string
    {
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
