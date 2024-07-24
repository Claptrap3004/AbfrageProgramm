<?php

namespace quiz;

use Exception;

class UserController extends Controller
{
    public function index(): void
    {
        $this->login();
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

            // user tries to log in
            if (isset($_POST['loginUser'])) {
                $error = $this->checkErrorsLogin($email, $password);
                if ($error->isNoError()) {
                    $userData = KindOf::USER->getDBHandler()->findAll(['userEmail' => $email]);
                    $user = $this->factory->createUser($userData[0]['id']);
                    $_SESSION['UserId'] = $user->getId();
                    $this->view(UseCase::WELCOME->getView(), []);
                } else {
                    $this->reportError($error);
                }
            } // user tries to register
            elseif (isset($_POST['registerUser'])) {
                $emailValidate = $_POST['emailValidate'] ?? '';
                $passwordValidate = $_POST['passwordValidate'] ?? '';
                $userName = $_POST['userName'] ?? '';

                $error = $this->checkErrorsRegister($email, $emailValidate, $password, $passwordValidate);

                if ($error->isNoError()) {
                    try {
                        $id = $this->dbFactory->createUser($userName, $email, $password);
                        $user = Factory::getFactory()->createUser($id);
                        $_SESSION['UserId'] = $user->getId();
                        KindOf::QUIZCONTENT->getDBHandler()->createTables();
                        $stats = new UserStats($user);
                        $this->view(UseCase::WELCOME->getView(), ['user' => $user, 'stats' => $stats]);
                    } catch (Exception $e) {
                        $error->fatal = ErrorMessage::USER_CREATE_FAILS->getErrorElement('');
                        $this->reportError($error);
                    }
                }
                else $this->reportError($error);
            } // invalid post request
            else {
                $this->view(UseCase::LOGIN_REGISTER->getView(), []);
            }
        // no post request
        } else {
            $this->view(UseCase::LOGIN_REGISTER->getView(), []);
        }
    }


    private function checkErrorsLogin(string $email, string $password): LoginRegisterError
    {
        $error = new LoginRegisterError();
        if (!$this->checkCorrectEmail($email)) $error->email = ErrorMessage::EMAIL_INCORRECT->getErrorElement($email);
        elseif (!$this->userExists($email)) $error->email = ErrorMessage::USER_DOES_NOT_EXIST->getErrorElement($email);
        if (!$this->validatePassword($password)) $error->password = ErrorMessage::PASSWORD_INVALID->getErrorElement($password);
        elseif (!$this->checkCorrectPassword($email, $password)) $error->email = ErrorMessage::CREDENTIALS_INVALID->getErrorElement($email);

        return $error;
    }

    private function checkErrorsRegister(string $email, string $emailValidate, string $password, string $passwordValidate): LoginRegisterError
    {
        $error = new LoginRegisterError();
        $error->isLoginError = false;
        if (!$this->checkCorrectEmail($email)) $error->email = ErrorMessage::EMAIL_INCORRECT->getErrorElement($email);
        elseif (!$this->validateEqual($email, $emailValidate)) {
            $error->email = ErrorMessage::EMAILS_NOT_MATCH->getErrorElement($email);
            $error->emailValidate = ErrorMessage::EMAILS_NOT_MATCH->getErrorElement($emailValidate);
        }
        if (!$this->validatePassword($password)) $error->password = ErrorMessage::PASSWORD_INVALID->getErrorElement($password);
        elseif (!$this->validateEqual($password, $passwordValidate)) {
            $error->password = ErrorMessage::PASSWORDS_NOT_MATCH->getErrorElement($password);
            $error->passwordValidate = ErrorMessage::PASSWORDS_NOT_MATCH->getErrorElement($passwordValidate);
        }
        return $error;
    }

    private function reportError(LoginRegisterError $error): void
    {
        $error = json_encode($error);
        $this->view(UseCase::LOGIN_REGISTER->getView(), ['error' => $error]);
    }

    private function validatePassword(string $password): bool
    {

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        return !(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8);
    }

    private function validateEqual(string $first, string $second): bool
    {
        return $first === $second;
    }

    private function checkCorrectEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function userExists($email): bool
    {
        $possibleUser = DBHandlerProvider::getUserDBHandler()->findAll(['userEmail' => $email]);
        return $possibleUser !== [];
    }

    private function checkCorrectPassword(string $email, string $password): bool
    {
        $possibleUser = DBHandlerProvider::getUserDBHandler()->findAll(['userEmail' => $email]);
        return !($possibleUser === []) && password_verify($password, $possibleUser[0]['password']);
    }
}