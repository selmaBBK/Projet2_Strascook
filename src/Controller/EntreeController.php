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

            $errors = [];

            if (empty($entree["name"] && isset($entree["name"]))) {
                $errors[1] = " Erreur : Entrez un nom de plat";
            }

            if (($entree["category"] == 'Veuillez choisir une catégorie...' && isset($entree["category"]))) {
                $errors[2] = "Erreur : Entrez une catégorie";
            }

            if (empty($entree["description"] && isset($entree["description"]))) {
                $errors[3] = "Erreur : Entrez une description";
            }

            if (empty($entree["price"] && isset($entree["price"]))) {
                $errors[4] = "Erreur : Entrez un prix";
            }

            if (empty($entree["image"] && isset($entree["image"]))) {
                $errors[5] = "Erreur : Entrez une image";
            }

            if (!empty($errors)) {
                return $this->twig->render('Entree/add.html.twig', ['errors' => $errors]);
            }

            if (empty($errors)) {
                // TODO validations (length, format...)

                // if validation is ok, update and redirection
                $entreeManager->update($entree);
                header('Location: /Admin/index');
            }
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
          
            $errors = [];

            if (empty($entree["name"] && isset($entree["name"]))) {
                $errors[1] = " Erreur : Entrez un nom de plat";
            }

            if (($entree["category"] == 'Veuillez choisir une catégorie...' && isset($entree["category"]))) {
                $errors[2] = "Erreur : Entrez une catégorie";
            }

            if (empty($entree["description"] && isset($entree["description"]))) {
                $errors[3] = "Erreur : Entrez une description";
            }

            if (empty($entree["price"] && isset($entree["price"]))) {
                $errors[4] = "Erreur : Entrez un prix";
            }

            if (empty($entree["image"] && isset($entree["image"]))) {
                $errors[5] = "Erreur : Entrez une image";
            }

            if (!empty($errors)) {
                return $this->twig->render('Entree/add.html.twig', ['errors' => $errors]);
            }

            if (empty($errors)) {
                // TODO validations (length, format...)

                // if validation is ok, insert and redirection
                $entreeManager = new EntreeManager();
                $entreeManager->insert($entree);
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
