<?php
namespace Controller;

use Hydro\Base\Controller\BaseController;
use Hydro\Base\Database\Driver\SQLite;
use Model\DAO\OfferDAO;
use Model\DAO\UserDAO;
use Model\DAO\UserRatingDAO;
use Model\UserModel;
use Model\OfferModel;
use Model\UserRatingModel;

class ProfileController extends BaseController
{
    public function index(){

        // only redirect a user to the login page if is not
        // possible to display a user
        if (!isset($_SESSION['currentUser']) && !isset($_GET['id'])) {
            header('location: ' . URL . 'login');
        }

        require APP . 'View/shared/header.php';
        require APP . 'View/profile/header.php';
        require APP . 'View/shared/nav.php';
        require APP . 'View/profile/index.php';
        require APP . 'View/shared/footer.php';
    }

    public static function edit() {
        if (isset($_GET['id'])) {
            header('location: ' . URL . 'editProfile?id=' . $_GET['id']);
            exit();
        }
        header('location: ' . URL . 'editProfile');
    }

    public static function getUser($id, $dao = null){
        if(!isset($dao)):
            $sqlite = new SQLite();
            $con = $sqlite->getCon();
            $dao = new UserDAO($con);
            $unset = true;
        endif;

        $model = UserModel::getUser($dao, $id);
        if(isset($unset)):
            unset($sqlite);
        endif;

        return $model;
    }

    public static function getDisplayUser() {
      if (isset($_GET['id'])) return ProfileController::getUser($_GET['id']);
      return $_SESSION['currentUser'];
      // if both are not set redirect to login
      // see index()
    }

    public static function getOffersByUserId() {
        $id = ProfileController::getDisplayUser()->getId();
        $sqlite = new SQLite();
        $con = $sqlite->getCon();
        $model = OfferModel::getFromDatabaseByUserId(new OfferDAO($con),$id);
        unset($sqlite);
        return $model;
    }

    public static function getSavedOffersForCurrentUser() {
        $id = ProfileController::getDisplayUser()->getId();
        $sqlite = new SQLite();
        $con = $sqlite->getCon();
        $model = OfferModel::getSavedOffersFromDatabaseByUserId(new OfferDAO($con), $id);
        unset($sqlite);
        return $model;
    }

    public static function getRatingForUserId($userId) {
        $sqlite = new SQLite();
        $con = $sqlite->getCon();
        $userRatingDao = new UserRatingDAO($con);
        $model = UserRatingModel::getRatingFromDatabaseForUserId($userRatingDao, $userId);
        unset($sqlite);
        return $model;
    }

    public static function insertRating($fromUserId, $forUserId, $rating) {
        if(isset($_SESSION["currentUser"])):
            $sqlite = new SQLite();
            $con = $sqlite->getCon();
            $dao = new UserRatingDAO($con);

            $userRating = UserRatingModel::getFromDatabaseByFromUserIdAndForUserId($dao, $fromUserId, $forUserId);
            if(empty($userRating->getId())):
                $userRating = new UserRatingModel(hexdec(uniqid()),
                    $fromUserId, $forUserId, $rating);

                $check = $userRating->insertIntoDatabase($dao);
            else:
                $userRating->setRating($rating);
                $check = $userRating->updateInDatabase($dao);
            endif;
            unset($sqlite);

            if($check):
                header('location: ' . URL . 'profile?id=' . $forUserId);
                exit();
            endif;
        endif;
        header('location: ' . URL . 'login');
        exit();
    }

    public static function disableUser() {
        $sqlite = new SQLite();
        $con = $sqlite->getCon();
        $dao = new UserDAO($con);
        $user = UserModel::getFromDatabaseById($dao, $_POST['user_id']);

        $user->deactivateInDatabase($dao);
        unset($sqlite);
        header('location: ' . URL . 'profile?id=' . $user->getId());
        exit();
    }

    public static function enableUser() {
        $sqlite = new SQLite();
        $con = $sqlite->getCon();
        $dao = new UserDAO($con);
        $user = UserModel::getFromDatabaseById($dao, $_POST['user_id']);

        $user->activateInDatabase($dao);
        unset($sqlite);
        header('location: ' . URL . 'profile?id=' .$user->getId());
        exit();

    }
}