<?php

namespace App\Controller;

use App\Model\PlatManager;

class PlatController extends AbstractController
{
    /**
     * List plats
     */
    public function index(): string
    {
        $platManager = new PlatManager();
        $plats = $platManager->selectAll('name');

        return $this->twig->render('Plat/index.html.twig', ['plats' => $plats]);
    }


    /**
     * Show informations for a specific plat
     */
    public function show(int $id): string
    {
        $platManager = new PlatManager();
        $plat = $platManager->selectOneById($id);

        return $this->twig->render('Plat/show.html.twig', ['plat' => $plat]);
    }


    /**
     * Edit a specific plat
     */
    public function edit(int $id): string
    {
        $platManager = new PlatManager();
        $plat = $platManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $plat = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $platManager->update($plat);
            header('Location: /plat/show' .$id);
        }

        return $this->twig->render('Plat/edit.html.twig', ['plat' => $plat,]);
    }


    /**
     * Add a new plat
     */
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $plat = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $platManager = new PlatManager();
            $id = $platManager->insert($plat);
            header('Location: /plat/show/' . $id);
        }

        return $this->twig->render('Plat/add.html.twig');
    }


    /**
     * Delete a specific item
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $platManager = new PlatManager();
            $platManager->delete($id);
            header('Location: /plat/index');
        }
    }
}
