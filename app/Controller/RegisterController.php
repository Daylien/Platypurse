<?php

namespace Controller;

use Hydro\Base\Controller\BaseController;
use Hydro\Base\Database\Driver\SQLite;
use Model\DAO\UserDAO;
use Model\UserModel;
use Hydro\Helper\Date;
use PDOException;

class RegisterController extends BaseController {

    public function index(){
        require APP . 'View/shared/header.php';
        require APP . 'View/register/header.php';
        require APP . 'View/shared/nav.php';
        require APP . 'View/register/index.php';
        require APP . 'View/shared/footer.php';
        session_destroy();
    }

    public function register(){
        if(!(isset($_POST["user-email"]) || isset($_POST["user-passwd"])
        || isset($_POST['user-display-name']) || isset($_POST['user-passwd2'])
            || isset($_POST['agb-confirm']))){
            $_SESSION['register-error'] = true;
            header('location:' . URL . 'register');
        }

        $userInputDisplayName = $_POST['user-display-name'];
        $userInputMail = strtolower($_POST['user-email']);
        $userInputPassswd = $_POST['user-passwd'];
        $userInputPassswd2 = $_POST['user-passwd2'];

        if($userInputPassswd != $userInputPassswd2){
            $_SESSION['register-error-password'] = true;
            header('location:' . URL . 'register');
            exit();
        }

        if(!isset($_POST['agb-confirm'])){
            $_SESSION['register-error-agb'] = true;
            header('location:' . URL . 'register');
            exit();
        }

        $defaultImagePath = "assets/nav/user-circle-solid.svg";
        $mime = "image/svg+xml";
        $image = base64_encode(file_get_contents($defaultImagePath));

        $userModel = new UserModel(hexdec(uniqid()),
            $userInputDisplayName,
            $userInputMail,
            password_hash($userInputPassswd, PASSWORD_DEFAULT),
            2,
            0,
            Date::now(),
            $mime,
            $image,
            0,
            0);

        $con = SQLITE::connectToSQLite();
        try {
            $con->beginTransaction();
            $userDao = new UserDAO($con);
            $check = $userModel->insertIntoDatabase($userDao);

            if($check){
                $con->commit();
                unset($userModel);
                header('location: '. URL . 'login');
            } else {
                $con->rollback();
                unset($userModel);
                $_SESSION['register-error'] = true;
                header('location: '. URL . 'register');
            }
        } catch (PDOException $e) {
            // TODO: Error handling
            // print "error go brr";
            $con->rollback();
        }
    }

    public static function verify() {
        require APP . 'View/shared/header.php';
        require APP . 'View/register/header.php';
        require APP . 'View/shared/nav.php';
        require APP . 'View/shared/footer.php';
    }
}
