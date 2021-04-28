<?php

namespace App\Controller;

use App\Model\EntreeManager;
use App\Service\CheckUser;

class EntreeController extends AbstractController
{


    /**
     * List entrées
     */
    public function index(): string
    {
        $entreeManager = new EntreeManager();
        $entrees = $entreeManager->selectAll('name');

        return $this->twig->render('Entree/carte_entree.html.twig', ['entrees' => $entrees]);
    }

    /**
     * Tris des entrée suiavnt leur catégorie
     */

    public function indexCat(string $cat): string
    {
        $entreeManager = new EntreeManager();
        $entrees = $entreeManager->selectCat($cat);

        return $this->twig->render('Entree/carte_entree.html.twig', ['entrees' => $entrees]);
    }

    /**
     * Edit a specific entree
     */
    public function edit(int $id): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();
        $entreeManager = new EntreeManager();
        $entree = $entreeManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $entree = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $entreeManager->update($entree);
            header('Location: /Admin/index');
        }

        return $this->twig->render('Entree/edit.html.twig', ['entree' => $entree]);
    }


    /**
     * Add a new entree
     */
    public function add(): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $entree = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $entreeManager = new EntreeManager();
            $entreeManager->insert($entree);
            header('Location: /Admin/index');
        }

        return $this->twig->render('Entree/add.html.twig');
    }


    /**
     * Delete a specific entrée
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemManager = new EntreeManager();
            $itemManager->delete($id);
            header('Location:/Admin/index');
        }
    }
}
