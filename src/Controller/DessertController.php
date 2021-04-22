<?php

namespace App\Controller;

use App\Model\DessertManager;

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
        $dessertManager = new DessertManager();
        $dessert = $dessertManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $dessert = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $dessertManager->update($dessert);
            header('Location: /Admin/index/' . $id);
        }
        return $this->twig->render('Dessert/edit.html.twig', ['dessert' => $dessert,]);
    }

    /**
     * Add a new dessert
     */

    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $dessert = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $dessertManager = new DessertManager();
            $id = $dessertManager->insert($dessert);
            header('Location:/Admin/index/' . $id);
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
