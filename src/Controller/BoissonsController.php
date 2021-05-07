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
