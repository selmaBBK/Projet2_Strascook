<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

class MonCompteController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function connexion()
    {
        return $this->twig->render('MonCompte/identification.twig');
    }

    public function inscription()
    {
        return $this->twig->render('MonCompte/inscription.html.twig');
    }
}
