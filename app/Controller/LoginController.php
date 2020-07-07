<?php

namespace Controller;


use Hydro\Base\Controller\BaseController;
use Hydro\Base\Database\Driver\SQLite;
use Model\DAO\DAOUser;
use Model\UserModel;

class LoginController extends BaseController
{

    public function index()
    {
        if (isset($_SESSION['currentUser'])) {
            header('location: ' . URL . 'error');
            exit();
        }
        // load views
        require APP . 'View/shared/header.php';
        require APP . 'View/login/header.php';
        require APP . 'View/shared/nav.php';
        require APP . 'View/login/index.php';
        require APP . 'View/shared/footer.php';
        session_destroy();
    }

    public function login()
    {
        if (!(isset($_POST['user-email']) && isset($_POST['user-passwd']))) {
            $_SESSION['user-login-error'] = true;
            header('location: ' . URL . 'login');
            exit();
        }

        $userSentMail = strtolower($_POST['user-email']);
        $userSentPasswd = $_POST['user-passwd'];


        $user = UserModel::getFromDatabaseByMail(new DAOUser(SQLite::connectToSQLite()), $userSentMail);
        if ($user):
            if (password_verify($userSentPasswd, $user->getPassword())) {
                $_SESSION['currentUser'] = $user;
                $_SESSION['csrf_token'] = uniqid('', true);
                header('location: ' . URL);
                exit();
            }
        endif;
        $_SESSION['user-login-error'] = true;
        header('location: ' . URL . 'login');
    }

    private function checkSession()
    {
    }

    public function logout()
    {
        session_destroy();
        header('location: ' . URL);
    }


}
