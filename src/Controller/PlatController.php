<?php

namespace App\Controller;

use App\Model\PlatManager;
use App\Service\CheckUser;

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
            header('Location: /Admin/index');
        }

        return $this->twig->render('Plat/edit.html.twig', ['plat' => $plat,]);
    }


    /**
     * Add a new plat
     */
    public function add(): string
    {
        $checkAdmin = new CheckUser();
        $checkAdmin->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $plat = array_map('trim', $_POST);
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
            $platManager = new PlatManager();
            $platManager->insert($plat);
            header('Location: /Admin/index');
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
            header('Location: /Admin/index');
        }
    }
    public function catSort(string $cat)
    {
        $platManager = new PlatManager();
        $plats = $platManager->sort($cat);

        return $this->twig->render('Plat/index.html.twig', ['plats' => $plats]);
    }
}
