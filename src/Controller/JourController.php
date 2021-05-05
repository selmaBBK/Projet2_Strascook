<?php

namespace App\Controller;

use App\Model\JourManager;
use App\Service\CheckUser;

class JourController extends AbstractController
{


    /**
     * List du plat du jour
     */
    public function index(): string
    {
        $jourManager = new JourManager();
        $jours = $jourManager->selectAll('id');

        return $this->twig->render('PlatDuJour/carte_platDuJour.html.twig', ['jours' => $jours]);
    }


    /**
     * Edit a specific plat du jour
     */
    public function edit(int $id): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();
        $jourManager = new JourManager();
        $jour = $jourManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $jour = array_map('trim', $_POST);

            $errors = [];

            if (empty($jour["name"] && isset($jour["name"]))) {
                $errors[1] = " Erreur : Entrez un nom de plat";
            }

            if (($jour["category"] == 'Veuillez choisir une catégorie...' && isset($jour["category"]))) {
                $errors[2] = "Erreur : Entrez une catégorie";
            }

            if (empty($jour["description"] && isset($jour["description"]))) {
                $errors[3] = "Erreur : Entrez une description";
            }

            if (empty($jour["price"] && isset($jour["price"]))) {
                $errors[4] = "Erreur : Entrez un prix";
            }

            if (empty($jour["image"] && isset($jour["image"]))) {
                $errors[5] = "Erreur : Entrez une image";
            }

            if (!empty($errors)) {
                return $this->twig->render('PlatDuJour/edit.html.twig', ['errors' => $errors]);

            }

            if (empty($errors)) {
                // TODO validations (length, format...)

                // if validation is ok, update and redirection
                $jourManager->update($jour);
                header('Location: /Admin/index');
            }
        }

        return $this->twig->render('PlatDuJour/edit.html.twig', ['jour' => $jour]);
    }


    /**
     * Add a new plat du jour
     */
    public function add(): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $jour = array_map('trim', $_POST);

            $errors = [];

            if (empty($jour["name"] && isset($jour["name"]))) {
                $errors[1] = " Erreur : Entrez un nom de plat";
            }

            if (($jour["category"] == 'Veuillez choisir une catégorie...' && isset($jour["category"]))) {
                $errors[2] = "Erreur : Entrez une catégorie";
            }

            if (empty($jour["description"] && isset($jour["description"]))) {
                $errors[3] = "Erreur : Entrez une description";
            }

            if (empty($jour["price"] && isset($jour["price"]))) {
                $errors[4] = "Erreur : Entrez un prix";
            }

            if (empty($jour["image"] && isset($jour["image"]))) {
                $errors[5] = "Erreur : Entrez une image";
            }

            if (!empty($errors)) {
                return $this->twig->render('PlatDuJour/add.html.twig', ['errors' => $errors]);

            }

            if (empty($errors)) {
                // TODO validations (length, format...)

                // if validation is ok, insert and redirection
                $jourManager = new JourManager();
                $jourManager->insert($jour);
                header('Location: /Admin/index');
            }
        }

        return $this->twig->render('PlatDuJour/add.html.twig');
    }


    /**
     * Delete a specific plat du jour
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jourManager = new JourManager();
            $jourManager->delete($id);
            header('Location:/Admin/index');
        }
    }
}
