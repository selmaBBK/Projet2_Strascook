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
        return $this->twig->render('Dessert/edit.html.twig', ['dessert' => $dessert]);
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

            //on gère l'upload ici
            $errors = [];
            $uploadDir = __DIR__ . '/public/assets/images/';
            /* le nom de fichier sur le serveur est ici généré à partir
du nom de fichier sur le poste du client
(mais d'autre stratégies de nommage sont possibles)*/
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            // Je récupère l'extension du fichier
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // Les extensions autorisées
            $extensionsOk = ['jpg', 'webp', 'png', 'jpeg'];
            // Le poids max géré par PHP par défaut est de 2M
            $maxFileSize = 1000000;

            // Je sécurise et effectue mes tests

            /****** Si l'extension est autorisée *************/
            if ((!in_array($extension, $extensionsOk))) {
                $errors[] = 'Veuillez sélectionner une image de type Jpg ou webp ou Png !';
            }


            /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/

            if (file_exists($_FILES['image']['tmp_name']) && filesize($_FILES['image']['tmp_name']) > $maxFileSize) {
                $errors[] = "Votre fichier doit faire moins de 2M !";
            }

            /****** On vérifie si l'image a bien un nom unique *************/

            if (file_exists($uploadFile)) {
                $errors[] = "Cette image est déjà présente ! La preuve !";
            } else {

                /****** Si je n'ai pas d"erreur alors j'upload *************/
                // Je vérifie que le formulaire est soumis, comme pour tout traitement de formulaire.

                /* chemin vers un dossier sur le serveur qui va recevoir
    les fichiers transférés (attention ce dossier doit être accessible en écriture)*/
                $uploadDir = __DIR__ . '/../../public/assets/images/';

                /* le nom de fichier sur le serveur est celui du nom d'origine
    du fichier sur le poste du client (mais d'autre stratégies de
    nommage sont possibles)*/
                $uploadFile = $uploadDir . basename($_FILES['image']['name']);

                /* on déplace le fichier temporaire vers le nouvel emplacement
    sur le serveur. Ca y est, le fichier est uploadé*/
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);
            }
            // fin de l'upload //
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

    public function catSort(string $cat)
    {
        $dessertManager = new DessertManager();
        $desserts = $dessertManager->sort($cat);

        return $this->twig->render('Dessert/dessert.html.twig', ['desserts' => $desserts]);
    }
}
