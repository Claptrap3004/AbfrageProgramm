<?php

namespace quiz;

use Exception;

class UserController extends Controller
{
    public function index(): void
    {
        $this->view(UseCase::LOGIN_REGISTER->getView(), []);
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
                        $controller = new QuizQuestionController();
                        $controller->index();
                    }
                    else{
                        $this->reportError("Zu $email existiert kein User");
                    }
                }
            } elseif (isset($_POST['registerUser'])) {
                $emailValidate = $_POST['emailValidate'] ?? '';
                $passwordValidate = $_POST['passwordValidate'] ?? '';
                $userName = $_POST['userName'] ?? '';


                if (!$this->checkCorrectEmail($email))  $this->reportError( 'es ist keine gültige E-Mail eingegeben worden');
                elseif (!$this->validateEqual($email, $emailValidate))  $this->reportError('die eingegebenen E-Mail-Adressen stimmen nicht überein');
                elseif (!$this->validatePassword($password))  $this->reportError('Das Passwort muss mindestens 8 Zeichen lang sein');
                elseif (!$this->validateEqual($password, $passwordValidate))  $this->reportError('Die eingegebenen Passwörter stimmen nicht überein');
                else {

                    try {
                        $id = $this->dbFactory->createUser($userName, $email, $password);
                    } catch (Exception $e) {
                        $this->reportError('User konnte nicht erstellt werden');
                    }
                    $user = Factory::getFactory()->createUser($id);
                    $_SESSION['UserId'] = $user->getId();
                    KindOf::QUIZCONTENT->getDBHandler()->createTables();
                    $stats = new UserStats($user);
                    $this->view(UseCase::WELCOME->getView(), ['user' => $user, 'stats' => $stats]);
                }


            } else {
                $this->reportError('Irgendwas ist schief gelaufen');
            }
        } else {
            $this->view(UseCase::LOGIN_REGISTER->getView(), []);
        }
    }

    private function reportError(string $errorMessage): void
    {
        $this->view(UseCase::LOGIN_REGISTER->getView(), ['error' => $errorMessage]);
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