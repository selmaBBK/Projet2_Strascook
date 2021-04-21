<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function index()
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll('adress');
        return $this->twig->render('User/user.html.twig', ['users' => $users]);
    }

    /**
     * Show informations for a specific User
     */

    public function show(int $id): string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        return $this->twig->render('User/show.html.twig', ['user' => $user]);
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

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $userManager->update($user);
            header('Location: /User/show/' . $id);
        }
        return $this->twig->render('User/edit.html.twig', ['user' => $user,]);
    }

    /**
     * Add a new dessert
     */

    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $user = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $userManager = new UserManager();
            $id = $userManager->insert($user);
            header('Location:/User/show/' . $id);
        }
        return $this->twig->render('User/add.html.twig');
    }

    /**
     * Delete a specific User
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userManager = new UserManager();
            $userManager->delete($id);
            header('Location:/User/index');
        }
    }
}
