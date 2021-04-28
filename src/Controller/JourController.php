<?php

namespace App\Controller;

use App\Model\JourManager;

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
        $jourManager = new JourManager();
        $jour = $jourManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $jour = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $jourManager->update($jour);
            header('Location: /Jour/index');
        }

        return $this->twig->render('PlatDuJour/edit.html.twig', ['jour' => $jour]);
    }


    /**
     * Add a new plat du jour
     */
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $jour = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $jourManager = new JourManager();
            $jourManager->insert($jour);
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
            header('Location:/Jour/index');
        }
    }
}