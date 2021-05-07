<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\CheckUser;

class UserController extends AbstractController
{
    public function index()
    {
        /**
         * On instancie un objet $checkAdmin pour pouvoir utiliser la méthode chackAdmin()
         */
        $checkAdmin = new CheckUser();
        /**
         * Ici si checkAdmin() est vérifié il fait rien sinon il redirige le client
         */
        $checkAdmin->checkAdmin();
        $userManager = new UserManager();
        $users = $userManager->selectAll('adress');
        return $this->twig->render('User/user.html.twig', ['users' => $users]);
    }

    /**
     * Show informations for a specific User
     */

    public function show(int $id): string
    {
        $checkUser = new checkUser();
        $checkUser->checkLogin();
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        return $this->twig->render('User/show.html.twig', ['userData' => $user]);
    }

    /**
     * Edit a specific User
     */

    public function edit(int $id): string
    {
        $userManager = new userManager();
        $user = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $user = array_map('trim', $_POST);
            $errors = [];

            if (empty($user["pass"] && isset($user["pass"]))) {
                $errors[77] = "⚠️ Erreur : Entrez un mot de passe";
            } else {
                $userManager = new UserManager();
                $checkPass = $userManager->checkPass($user);
                if (!$checkPass) {
                    $errors[645] = '⚠️ Ancien mot de passe incorrect 🤥';
                }
            }

            if (strlen($user["confirmPass"]) < 8) {
                $errors[78] = '⚠️ Erreur : Mot de passe trop court (minimum 8 caractères)';
            }
            if (!empty($errors)) {
                    return $this->twig->render('User/edit.html.twig', ['errors' => $errors]);
            }
            // TODO validations (length, format...)
            if (empty($errors)) {
                $user['pass'] = md5($_POST['confirmPass']);

            $user['pass'] = md5($_POST['pass']);

                // if validation is ok, update and redirection
                $userManager->update($user);
                header('Location: /User/show/' . $id);
            }
        }
        return $this->twig->render('User/edit.html.twig', ['user' => $user,]);
    }

    /**
     * Add a new User
     */

    public function inscription(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $user = array_map('trim', $_POST);

            $pass = $_POST["pass"];

            $errors = [];

            if (empty($user["name"] && isset($user["name"]))) {
                $errors[1] = "⚠️ Erreur : Entrez un prenom";
            }

            if (empty($user["adress"] && isset($user["adress"]))) {
                $errors[2] = "⚠️ Erreur : Entrez une adresse";
            }

            if (empty($user["pass"] && isset($user["pass"]))) {
                $errors[3] = "⚠️ Erreur : Entrez un mot de passe";
            }

            if (strlen($user["pass"]) < 8) {
                $errors[20] = '⚠️ Erreur : Mot de passe trop court (minimum 8 caractères)';
            }

            if (empty($user["repass"] && isset($user["repass"]))) {
                $errors[4] = "⚠️ Erreur : Confirmez votre mot de passe";
            }

            if ($pass != $user['repass']) {
                $errors[5] = '⚠️ Erreur : Les 2 mots de passe ne correspondent pas';
            }

            if (empty($user["pseudo"] && isset($user["pseudo"]))) {
                $errors[6] = "⚠️ Erreur : Entrez un pseudo ";
            } else {
                $userManager = new UserManager();
                $checkPseudo = $userManager->checkPseudo($user);
                if ($checkPseudo) {
                    $errors[14] = '⚠️ Ce pseudo existe déjà trouve-en un autre 😁';
                }
            }

            if (!empty($user["pseudo"]) && !preg_match("/^[a-zA-Z0-9_]+$/", $user['pseudo'])) {
                $errors[7] = "⚠️ Votre pseudo n'est pas valide (ne pas insérer de caractères spéciaux) ";
            }

            if (empty($user["mail"] && isset($user["mail"]))) {
                $errors[8] = "⚠️ Erreur : Entrez une adresse-mail";
            } else {
                $userManager = new UserManager();
                $checkMail = $userManager->checkMail($user);
                if ($checkMail) {
                    $errors[16] = '⚠️ Adresse mail est déjà relié à un compte';
                }
            }

            if (!empty($user["mail"] && !filter_var($user["mail"], FILTER_VALIDATE_EMAIL))) {
                $errors[9] = "⚠️ Erreur : Entrez une adresse-mail valide";
            }

            if (!empty($errors)) {
                return $this->twig->render('User/inscription.html.twig', ['errors' => $errors]);
            }
            // TODO validations (length, format...)

            if (empty($errors)) {
                // if validation is ok, insert and redirection
                $userManager = new UserManager();
                $id = $userManager->insert($user);
                header('Location:/User/show/' . $id);
            }
        }
        return $this->twig->render('User/inscription.html.twig');
    }

    /**
     * Delete a specific User
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            $userManager->delete($id);
            $this->logout();
        }
    }

    public function connexion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            $errors = [];

            if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                $errors[0] = "⚠️ Erreur : Entrez une adresse e-mail";
            } else {
                $userManager = new UserManager();
                $checkMail = $userManager->checkMail($user);
                if (!$checkMail) {
                    $errors[98] = '⚠️ Email ou mot de passe incorrect 🤥';
                }
            }

            if (empty($user["pass"] && isset($user["pass"]))) {
                $errors[2] = "⚠️ Erreur : Entrez un mot de passe";
            } else {
                $userManager = new UserManager();
                $checkPass = $userManager->checkPass($user);
                if (!$checkPass) {
                    $errors[98] = '⚠️ Email ou mot de passe incorrect 🤥';
                }
            }


            if (!empty($errors)) {
                return $this->twig->render('User/identification.html.twig', ['errors' => $errors]);
            }

            if (empty($errors)) {
                if ($_POST['mail'] != '' && $_POST['pass'] != '') {
                    $user = [];
                    $user['pseudo'] = $_POST['mail'];
                    $user['pass'] = md5($_POST['pass']);
                    $user['mail'] = $_POST['mail'];
                    $userManager = new UserManager();
                    $login = $userManager->checkLogin($user);
                    if ($userManager->checkLogin($user)) {
                        $_SESSION['name'] = $login['name'];
                        $_SESSION['userId'] = $login['id'];
                        $_SESSION['adress'] = $login['adress'];
                        $_SESSION['pass'] = $login['pass'];
                        $_SESSION['pseudo'] = $login['pseudo'];
                        $_SESSION['mail'] = $login['mail'];
                        if ($login['is_admin'] == '1') {
                            $_SESSION['admin'] = true;
                        } else {
                            $_SESSION['admin'] = false;
                        }


                        header('Refresh: 1;URL=/User/show/' . $login['id']);
                    } else {
                        header('Location:/User/Connexion/');
                    }
                }
            }
        }
        return $this->twig->render('User/identification.html.twig');
    }

    public function logout()
    {
        session_destroy();
        header('location:/User/Connexion/');
    }
    /**
     * Show information for a specific Reservation
     */
    public function showReservation(int $id): string
    {
        $userManager = new UserManager();
        $reservation = $userManager->selectAllReservation($id);
        $pdj = $userManager->selectPlatDuJour();
        return $this->twig->render('User/showReservation.html.twig', ['reservation' => $reservation, 'pdj' => $pdj]);
    }
}
