<?php

namespace App\Controller;

use App\Model\ReservationManager;
use App\Model\PanierManager;
use App\Service\CheckUser;

class ReservationController extends AbstractController
{
    /**
     * Edit a specific Reservation
     */
    public function edit($id)
    {
        if (isset($_SESSION['id']) && isset($_SESSION['user'])) {
            $reservationManager = new ReservationManager();
            $res = $reservationManager->selectOneById($id);
            if (!empty($_POST['id']) && !empty($_POST['adress']) || !empty($_POST['date'])) {
                $res['id'] = $_POST['id'];
                $res['adress'] = $_POST['adress'];
                $res['date'] = $_POST['date'];
                $reservationManager->update($res);
                header("Admin/index/" . $id);
                $res = $reservationManager->selectOneById($id);
            }
            return $this->twig->render('Panier/edit.html.twig', ['edit' => $res]);
        }
    }

    /**
     * Delete a specific Reservation
     */

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deleteReservation = new ReservationManager();
            $deleteReservation->delete($id);
            header('Location:/Panier/delete');
        }
    }

    /**
     * PAST RESERVATION A FINIR en fonction de la date de la reservation
     */
    public function pastReservation()
    {
        if (isset($_SESSION['id']) && isset($_SESSION['user'])) {
            $id = $_SESSION['id'];
            $pastReservations = new ReservationManager();
            $pastReservations->selectAllReservation($id);
        }
    }

    /**
     * Current Reservation
     */
    public function currentReservation()
    {
        if (isset($_SESSION['id']) && isset($_SESSION['user'])) {
            $id = $_SESSION['id'];
            $currentReservation = new ReservationManager();
            $currentReservation->selectAllReservation($id);
            header('location:/User/identification/');
        }
    }

    /**
     * Add a new Reservation
     */

    public function add(): string
    {
        $checkUser = new checkUser();
        $checkUser->checkLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservation = $_POST;

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $reservationManager = new ReservationManager();
            $id = $reservationManager->insert($reservation);
            header('Location:/Panier/show/' . $id);
        }
        return $this->twig->render('Panier/add.html.twig', [

        ]);
    }

    /**
     * Show information for a specific Reservation
     */
    public function show(int $id): string
    {
        $reservationManager = new ReservationManager();
        $reservation = $reservationManager->selectAllReservation($id);
        var_dump($reservation);
        if ($reservation['plat_du_jour_id'] == 1) {
            $pdj = $reservationManager->selectPlatDuJour();
        } else {
            $pdj = '';
        }
        return $this->twig->render('Reservation/show.html.twig', ['reservation' => $reservation, 'pdj' => $pdj]);
    }
}
