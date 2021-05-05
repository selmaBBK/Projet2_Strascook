<?php

namespace App\Controller;

use App\Model\DessertManager;
use App\Service\CheckUser;

class DessertController extends AbstractController
{
    /**
     * List desserts
     */

    public function index()
    {
        $dessertManager = new DessertManager();
        $desserts = $dessertManager->selectall('name');
        return $this->twig->render('Dessert/dessert.html.twig', ['desserts' => $desserts]);
    }

    /**
     * Show informations for a specific dessert
     */

    public function show(int $id): string
    {
        $dessertManager = new DessertManager();
        $dessert = $dessertManager->selectOneById($id);

        return $this->twig->render('Dessert/show.html.twig', ['dessert' => $dessert]);
    }

    /**
     * Edit a specific dessert
     */

    public function edit(int $id): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();
        $dessertManager = new DessertManager();
        $dessert = $dessertManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $dessert = array_map('trim', $_POST);

            $errors = [];

            if (empty($dessert["name"] && isset($dessert["name"]))) {
                $errors[1] = " Erreur : Entrez un nom de plat";
            }

            if (($dessert["category"] == 'Veuillez choisir une catégorie...' && isset($dessert["category"]))) {
                $errors[2] = "Erreur : Entrez une catégorie";
            }

            if (empty($dessert["description"] && isset($dessert["description"]))) {
                $errors[3] = "Erreur : Entrez une description";
            }

            if (empty($dessert["price"] && isset($dessert["price"]))) {
                $errors[4] = "Erreur : Entrez un prix";
            }

            if (empty($dessert["image"] && isset($dessert["image"]))) {
                $errors[5] = "Erreur : Entrez une image";
            }

            if (!empty($errors)) {
                return $this->twig->render('Dessert/add.html.twig', ['errors' => $errors]);

            }
            // TODO validations (length, format...)

            if (empty($errors)) {
                // if validation is ok, update and redirection
                $dessertManager->update($dessert);
                header('Location: /Admin/index/' . $id);
            }
        }
        return $this->twig->render('Dessert/edit.html.twig', ['dessert' => $dessert,]);
    }

    /**
     * Add a new dessert
     */

    public function add(): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $dessert = array_map('trim', $_POST);

            $errors = [];

            if (empty($dessert["name"] && isset($dessert["name"]))) {
                $errors[1] = " Erreur : Entrez un nom de plat";
            }

            if (($dessert["category"] == 'Veuillez choisir une catégorie...' && isset($dessert["category"]))) {
                $errors[2] = "Erreur : Entrez une catégorie";
            }

            if (empty($dessert["description"] && isset($dessert["description"]))) {
                $errors[3] = "Erreur : Entrez une description";
            }

            if (empty($dessert["price"] && isset($dessert["price"]))) {
                $errors[4] = "Erreur : Entrez un prix";
            }

            if (empty($dessert["image"] && isset($dessert["image"]))) {
                $errors[5] = "Erreur : Entrez une image";
            }

            if (!empty($errors)) {
                return $this->twig->render('Dessert/add.html.twig', ['errors' => $errors]);

            }
            // TODO validations (length, format...)

            if (empty($errors)) {
                // if validation is ok, insert and redirection
                $dessertManager = new DessertManager();
                $id = $dessertManager->insert($dessert);
                header('Location:/Admin/index/' . $id);
            }
        }
        return $this->twig->render('Dessert/add.html.twig');
    }

    /**
     * Delete a specific dessert
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dessertManager = new DessertManager();
            $dessertManager->delete($id);
            header('Location:/Admin/index');
        }
    }
}
