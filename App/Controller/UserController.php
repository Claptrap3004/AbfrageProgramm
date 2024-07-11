<?php

namespace quiz;

use Exception;

class UserController extends Controller
{
    public function index(): void
    {
        header("refresh:0.2;url='" . HOST . "index'");
    }

    public function logout(): void
    {
        unset($_SESSION['UserId']);
        $this->index();
    }

    /**
     * deals with both login and register, on correct login or new successful registration user is redirected to
     * welcome page, else encountered errors are reported on login page
     * @return void
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (isset($_POST['loginUser'])) {
                if ($this->checkCorrectEmail($email) && $this->checkCorrectPassword($email, $password)) {
                    $userData = KindOf::USER->getDBHandler()->findAll(['userEmail' => $email]);
                    if ($userData !== []){
                        $user = $this->factory->createUser($userData[0]['id']);
                        $_SESSION['UserId'] = $user->getId();
                        header("refresh:0.01;url='" . HOST . "QuizQuestion/'");
                    }
                    else{
                        $this->view('login/login', ['error' => 'User existiert nicht', 'username' => $email]);
                    }
                }
            } elseif (isset($_POST['registerUser'])) {
                $emailValidate = $_POST['emailValidate'] ?? '';
                $passwordValidate = $_POST['passwordValidate'] ?? '';
                $userName = $_POST['userName'] ?? '';
                $errorMessage = '';

                if (!$this->checkCorrectEmail($email)) $errorMessage = 'es ist keine gültige E-Mail eingegeben worden';
                elseif (!$this->validateEqual($email, $emailValidate)) $errorMessage = 'die eingegebenen E-Mail-Adressen stimmen nicht überein';
                elseif (!$this->validatePassword($password)) $errorMessage = 'Das Passwort muss mindestens 8 Zeichen lang sein';
                elseif (!$this->validateEqual($password, $passwordValidate)) $errorMessage = 'Die eingegebenen Passwörter stimmen nicht überein';
                if ($errorMessage !== '') $this->view('login/login', ['error' => $errorMessage, 'username' => $userName]);
                else {

                    try {
                        $id = $this->dbFactory->createUser($userName, $email, $password);
                    } catch (Exception $e) {
                        $this->view('login/login', ['error' => $e, 'username' => $userName]);
                    }
                    $user = Factory::getFactory()->createUser($id);
                    $_SESSION['UserId'] = $user->getId();
                    KindOf::QUIZCONTENT->getDBHandler()->createTables();
                    $stats = new UserStats($user);
                    $this->view('welcome', ['user' => $user, 'stats' => $stats]);
                }


            } else {
                $errorMessage = 'Irgendwas ist schief gelaufen';
                $this->view('login/login', ['error' => $errorMessage]);
            }
        } else {
            $this->view('login/login', []);
        }
    }

    private function validatePassword(string $password): bool
    {

        return strlen($password) >= 8;
    }

    private function validateEqual(string $first, string $second): bool
    {
        return $first === $second;
    }

    private function checkCorrectEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function checkCorrectPassword(string $email, string $password): bool
    {
        $possibleUser = DBHandlerProvider::getUserDBHandler()->findAll(['userEmail' => $email]);
        if ($possibleUser === []) return false;
        return password_verify($password, $possibleUser[0]['password']);
    }
}