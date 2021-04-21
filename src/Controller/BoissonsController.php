<?php

namespace App\Controller;

use App\Model\BoissonsManager;

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
        $boissonsManager = new BoissonsManager();
        $boissons = $boissonsManager->selectOneById($id);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $boissons = array_map('trim', $_POST);


            // if validation is ok, update and redirection
            $boissonsManager->update($boissons);
            header('Location: /Boissons/show/' . $id);
        }

        return $this->twig->render('Boissons/edit.html.twig', ['boissons' => $boissons,]);
    }

    /**
     * Add a new Boisson
     */

    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $boissons = array_map('trim', $_POST);


            // if validation is ok, insert and redirection
            $boissonsManager = new BoissonsManager();
            $id = $boissonsManager->insert($boissons);
            header('Location:/Boissons/show/' . $id);
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
