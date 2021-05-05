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

            // TODO validations (length, format...)
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
            $jourManager = new JourManager();
            $jourManager->insert($jour);
            header('Location: /Admin/index');
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
