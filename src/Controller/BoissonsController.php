<?php

namespace App\Controller;

use App\Model\BoissonsManager;
use App\Service\CheckUser;

class BoissonsController extends AbstractController
{
    /**
     * List Boissons
     */
    public function index(): string
    {
        $boissonsManager = new BoissonsManager();
        $boissons = $boissonsManager->selectAll('name');

        return $this->twig->render('Boissons/boissons.index.html.twig', ['boissons' => $boissons]);
    }

    /**
     * Show information for a specific Boisson
     */
    public function show(int $id): string
    {
        $boissonsManager = new BoissonsManager();
        $boissons = $boissonsManager->selectOneById($id);

        return $this->twig->render('Boissons/show.html.twig', ['boissons' => $boissons]);
    }

    /**
     * Edit a specific Boisson
     */
    public function edit(int $id): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();
        $boissonsManager = new BoissonsManager();
        $boissons = $boissonsManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $boissons = array_map('trim', $_POST);

            $errors = [];

            if (empty($boissons["name"] && isset($boissons["name"]))) {
                $errors[1] = " Erreur : Entrez un nom de plat";
            }

            if (($boissons["category"] == 'Veuillez choisir une catégorie...' && isset($boissons["category"]))) {
                $errors[2] = "Erreur : Entrez une catégorie";
            }

            if (empty($boissons["description"] && isset($boissons["description"]))) {
                $errors[3] = "Erreur : Entrez une description";
            }

            if (empty($boissons["price"] && isset($boissons["price"]))) {
                $errors[4] = "Erreur : Entrez un prix";
            }

            if (empty($boissons["image"] && isset($boissons["image"]))) {
                $errors[5] = "Erreur : Entrez une image";
            }

            if (!empty($errors)) {
                return $this->twig->render('Boissons/add.html.twig', ['errors' => $errors]);

            }

            if (empty($errors)) {

                // if validation is ok, update and redirection
                $boissonsManager->update($boissons);
                header('Location: /Admin/index/' . $id);
            }
        }

        return $this->twig->render('Boissons/edit.html.twig', ['boissons' => $boissons,]);
    }

    /**
     * Add a new Boisson
     */

    public function add(): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $boissons = array_map('trim', $_POST);

            $errors = [];

            if (empty($boissons["name"] && isset($boissons["name"]))) {
                $errors[1] = " Erreur : Entrez un nom de plat";
            }

            if (($boissons["category"] == 'Veuillez choisir une catégorie...' && isset($boissons["category"]))) {
                $errors[2] = "Erreur : Entrez une catégorie";
            }

            if (empty($boissons["description"] && isset($boissons["description"]))) {
                $errors[3] = "Erreur : Entrez une description";
            }

            if (empty($boissons["price"] && isset($boissons["price"]))) {
                $errors[4] = "Erreur : Entrez un prix";
            }

            if (empty($boissons["image"] && isset($boissons["image"]))) {
                $errors[5] = "Erreur : Entrez une image";
            }

            if (!empty($errors)) {
                return $this->twig->render('Boissons/add.html.twig', ['errors' => $errors]);

            }

            if (empty($errors)) {

                // if validation is ok, insert and redirection
                $boissonsManager = new BoissonsManager();
                $id = $boissonsManager->insert($boissons);
                header('Location:/Admin/index/' . $id);
            }
        }

        return $this->twig->render('Boissons/add.html.twig');
    }


    /**
     * Delete a specific Boisson
     */

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $boissonsManager = new BoissonsManager();
            $boissonsManager->delete($id);
            header('Location:/Boissons/index');
        }
    }
}
