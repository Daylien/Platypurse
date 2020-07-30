<?php
namespace Controller;

use Hydro\Base\Controller\BaseController;
use Hydro\Base\Database\Driver\SQLite;
use Model\DAO\OfferDAO;
use Model\OfferModel;
use PDOException;

class HomeController extends BaseController
{
    /**
     *  index page
     */
    public function index()
    {
        // load views
        echo headers_sent();
        require APP . 'View/shared/header.php';
        echo headers_sent();
        require APP . 'View/home/header.php';
        echo headers_sent();
        require APP . 'View/shared/nav.php';
        echo headers_sent();
        require APP . 'View/home/index.php';
        //echo headers_sent();
        require APP . 'View/shared/footer.php';
    }

    /**
     * Returns the newest offers
     * @return array
     */
    public static function getNewestOffers() {
        $sqlite = new SQLite();
        try {
            $con = $sqlite->getCon();
            $models = OfferModel::getNewestOffers(new OfferDAO($con));
            unset($sqlite);
            return $models;
        } catch (PDOException $ex) {
            unset($sqlite);
            header('location: ' . URL . 'error/databaseError');
            exit();
        }
    }

    /**
     * Returns the hot offer
     * @return OfferModel
     */
    public static function getHotOffer() {
        echo headers_sent();
        $sqlite = new SQLite();
        try {
            $con = $sqlite->getCon();
            $model = OfferModel::getHotOffer(new OfferDAO($con));
            unset($sqlite);
            return $model;
        } catch (PDOException $ex) {
            die(header('location: ' . URL . 'error/databaseError'));
        }
    }
}
