<?php

namespace App\Controllers\Front\Ajax;


if (php_sapi_name() == 'cli'){
    // Setup the autoloading
    require_once 'vendor/autoload.php';

    // Setup Propel
    require_once 'generated-conf/config.php';
} else {
    // Setup the autoloading
    require_once '../vendor/autoload.php';

    // Setup Propel
    require_once '../generated-conf/config.php';
}


use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\Mail;
use \Core\Functions;
use \Core\CustomException;
use App\Config;



use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\User;
use \Models\UserQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Auth extends Base
{
    public function loginAction()
    {
        $loginOrEmail = (isset($_POST['LoginOrEmail']) ? $_POST['LoginOrEmail'] : null);
        $password = (isset($_POST['password']) ? $_POST['password'] : null);
        $isRemember = (isset($_POST['isRemember']) ? (($_POST['isRemember'] == 'true') ? true : false) : false);
        try {
            $this->helper->login($loginOrEmail, $password, $isRemember, false);
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("вход выполнен успешно");
            $this->response->setRedirect($_SERVER['HTTP_REFERER']);
        } catch (CustomException $e){
            if ($e->getCode() == 0) $this->response->setRedirect($_SERVER['HTTP_REFERER']);
            $this->response->setException($e);
        }
        $this->response->show();
    }

    public function logoutAction()
    {
        try {
            $this->helper->logout();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Выход выполнен успешно");
            $this->response->setRedirect($_SERVER['HTTP_REFERER']);
        } catch (CustomException $e){
            if ($e->getCode() == 0) $this->response->setRedirect($_SERVER['HTTP_REFERER']);
            $this->response->setException($e);
        }
        $this->response->show();
    }

    public function socialSignUpAction()
    {
        $socialToken = (isset($_POST['token']) ? $_POST['token'] : null);
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);

        try {
            if (is_null($phone) || empty(trim($phone))){
                throw new CustomException("Не был введен телефон", 1);
            }

            if (is_null($socialToken)){
                throw new CustomException("Токен не был введен", 1);
            }

            if (!empty(trim($phone))) {
                $phone = str_replace("(","",str_replace(")","",str_replace("-","",str_replace(" ","",$phone))));
            }

            $user = UserQuery::create()->findOneBySocialToken($socialToken);
            if (is_null($user)){
                throw new CustomException("Токен не найден", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Имя не была введена", 1);
            } elseif (strlen(trim($name)) < 2) {
                throw new CustomException("Длина имени должна быть больше 2 символов", 1);
            } else {
                $user->setName(trim($name));
            }


            if (!empty(trim($phone)) && !preg_match('/^[0-9]+$/', trim($phone))){
                throw new CustomException("Телефон должен содержать только цифры", 1);
            } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12 && substr(trim($phone),0, 3) != "996") {
                 throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
            } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12) {
                 throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
            } elseif (!empty(trim($phone))) {
                $user->setPhone(trim($phone));
            }

            $user->setActivated(true);
            $user->setSocialToken(null);
            $user->save();

            $this->helper->socialLogin($user->getSocialId(), true, false);
            $this->response->setRedirect('/');

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Регистрация прошла успешно");

        } catch (CustomException $e){
            $this->response->setException($e);
        }
        $this->response->show();
    }

     public function registrationAction()
     {
         $email = (isset($_POST['email']) ? $_POST['email'] : null);
         $name = (isset($_POST['name']) ? $_POST['name'] : null);
         $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);
         $password = (isset($_POST['password']) ? $_POST['password'] : null);

         try {

             $groupId = \Models\ConfigQuery::create()->findOneByKey('default_user_group');
             $group = GroupQuery::create()->findPk(intval($groupId->getValue()));
             if (is_null($group)){
                 throw new CustomException("Группа выбранная по умолчанию для пользователей не существует, пожалуйста обращайтесь администрацию", 1);
             }


             $this->helper->confirmRegistration($email, null, $name, $password, $password, $phone, null, $group);

             $this->helper->logout();

             $this->helper->login($email, $password, true, false);
             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Регистрация прошла успешно");
             $this->response->setRedirect($_SERVER['HTTP_REFERER']);
         } catch (CustomException $e){
             $this->response->setException($e);
         }
         $this->response->show();
     }


     public function confirmChangePasswordAction()
     {
         $token = (isset($_POST['token']) ? $_POST['token'] : null);
         $password = (isset($_POST['password']) ? $_POST['password'] : null);
         $passwordConfirmation = (isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : null);

         try {
             if (is_null($token)){
                 throw new CustomException("Токен не был указан", 1);
             }

             $verificationToken = VerificationTokenQuery::create()->findOneByTokenAndType($token, 3);
             if (is_null($verificationToken)){
                 throw new CustomException("Неверный токен", 1);
             }

             if (is_null($password) || empty(trim($password)) ){
                 throw new CustomException("Не был введен пароль", 1);
             }

             if (strlen(trim($password)) < 6){
                 throw new CustomException("Длина паролей должны быть больше 6 символов", 1);
             }

             $user = $verificationToken->getCurrentUser();
             if (trim($password) != trim($passwordConfirmation)){
                 throw new CustomException("Пароли не совпадают", 1);
             } else {
                 $cryptedPassword = crypt(trim($password), Config::PASSWORD_SALT);
                 $user->setPassword($cryptedPassword);
             }


             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Пароль был поменен успешно");
             $this->response->setRedirect('/');
             $user->save();
             $verificationToken->delete();
         } catch (CustomException $e){
             $this->response->setException($e);
         }
         $this->response->show();
     }

     public function forgotPasswordQueryAction()
     {
         $email = (isset($_POST['email']) ? $_POST['email'] : null);

         try {

             if (is_null($email) || empty(trim($email)) ){
                 throw new CustomException("Email не был введён", 1);
             }

             if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) == false){
                 throw new CustomException("Неверный email", 1);
             }

             $user = UserQuery::create()->findOneByEmail(trim($email));
             if (is_null($user)){
                 throw new CustomException("Пользователь с таким email не найден", 1);
             }

             $verificationTokenArchive = VerificationTokenQuery::create()->findOneByCurrentUserAndType($user, 3);
             if (!is_null($verificationTokenArchive)){
                 if ($verificationTokenArchive->getCreatedAt()->getTimestamp() > time() - Config::FORGOT_PASSWORD_QUERY_RESENDING_INTERVAL){
                     throw new CustomException("Недавно вам отправили email, попробуйте после " . abs(time() - Config::FORGOT_PASSWORD_QUERY_RESENDING_INTERVAL - $verificationTokenArchive->getCreatedAt()->getTimestamp()) . " сек.", 0);
                 } else {
                     $verificationTokenArchive->delete();
                 }
             }
             $verificationToken = new VerificationToken();
             $verificationToken->setCurrentUser($user);
             $verificationToken->setType(3);
             $verificationToken->setToken(Functions::generateToken(Config::TOKEN_LENGTH_FOR_PASSWORD_QUERY));
             $verificationToken->setExpiryDateTime(time() + Config::FORGOT_PASSWORD_QUERY_EXPIRATION * 86400);
             $verificationToken->save();

             $mail = new Mail();
             $mail->restorePassword($user, $verificationToken, true);
             $mail->send();

             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Запрос на замену пароля был отправлен на ваш email (" . $user->getEmail() . ")");
         } catch (CustomException $e){
             $this->response->setException($e);
         }
         $this->response->show();
     }
}
