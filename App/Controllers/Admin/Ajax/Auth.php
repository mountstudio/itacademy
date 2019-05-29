<?php

namespace App\Controllers\Admin\Ajax;


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
             $this->helper->login($loginOrEmail, $password, $isRemember);
             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Вход выполнен успешно");
             $this->response->setRedirect(Config::REDIRECT_ADMIN_PANEL);
         } catch (CustomException $e){
             if ($e->getCode() == 0) $this->response->setRedirect(Config::REDIRECT_ADMIN_PANEL);
             $this->response->setException($e);
         }
         $this->response->show();
     }

     public function registrationAction()
     {
         $email = (isset($_POST['email']) ? $_POST['email'] : null);
         try {
             $verificationToken = $this->helper->registration($email);
             $mail = new Mail();
             $mail->verifyAccount($verificationToken);
             $mail->send();
             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Мы отправили запрос на Вашу электронную почту (на  " . $verificationToken->getEmail() . "), пожалуйста, перейдите и активируйте Ваш аккаунт");
         } catch (CustomException $e){
             $this->response->setException($e);
         }
         $this->response->show();
     }

     public function confirmRegistrationAction()
     {

         $token = (isset($_POST['token']) ? $_POST['token'] : null);
         $userName = (isset($_POST['userName']) ? $_POST['userName'] : null);
         $name = (isset($_POST['name']) ? $_POST['name'] : null);
         $groupId = (isset($_POST['groupId']) ? $_POST['groupId'] : null);
         $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);
         $address = (isset($_POST['address']) ? $_POST['address'] : null);
         $password = (isset($_POST['password']) ? $_POST['password'] : null);
         $passwordConfirmation = (isset($_POST['passwordConfirmation']) ? $_POST['passwordConfirmation'] : null);

         try {
             if (is_null($token)){
                 throw new CustomException("Токен не был указан", 1);
             }

             $verificationToken = VerificationTokenQuery::create()->findOneByTokenAndType($token, 1);
             if (is_null($verificationToken)){
                 throw new CustomException("Неверный токен", 1);
             }

             $config = \Models\ConfigQuery::create()->findOneByKey('allow_users_choose_group');

             if ($config->getValue() == 1){
                 if (is_null($groupId)){
                     throw new CustomException("Группа не была указана", 1);
                 }

                 if (intval($groupId) == 0){
                     throw new CustomException("Неверная группа", 1);
                 }

                 $group = GroupQuery::create()->findPk(intval($groupId));

                 if (is_null($group)){
                     throw new CustomException("Группа не найдена", 1);
                 }

                 // if ($group->getAllowAdmin()){
                 //     throw new CustomException("Вы не сможете стать админом", 1);
                 // }
             } else {
                 $groupId = \Models\ConfigQuery::create()->findOneByKey('default_user_group');
                 $group = GroupQuery::create()->findPk(intval($groupId->getValue()));
                 if (is_null($group)){
                     throw new CustomException("Группа выбранная по умолчанию для пользователей не существует", 1);
                 }
             }

             $this->helper->confirmRegistration($verificationToken->getEmail(), $userName, $name, $password, $passwordConfirmation, $phone, $address, $group);
             //$verificationToken->delete();
             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Регистрация прошла успешно");
             $this->response->setRedirect(Config::REDIRECT_AFTER_SUCCESSFUL_REGISTRATION);
         } catch (CustomException $e){
             $this->response->setException($e);
         }
         $this->response->show();
     }


     public function confirmChangePasswordAction()
     {
         $token = (isset($_POST['token']) ? $_POST['token'] : null);
         $password = (isset($_POST['newPassword']) ? $_POST['newPassword'] : null);
         $passwordConfirmation = (isset($_POST['passwordConfirmation']) ? $_POST['passwordConfirmation'] : null);

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
                 throw new CustomException("Длина пароля должна быть больше 6 символов", 1);
             }

             $user = $verificationToken->getCurrentUser();
             if (trim($password) != trim($passwordConfirmation)){
                 throw new CustomException("Пароли не совпадают", 1);
             } else {
                 $cryptedPassword = crypt(trim($password), Config::PASSWORD_SALT);
                 $user->setPassword($cryptedPassword);
             }


             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Пароль был изменен успешно");
             $this->response->setRedirect(($user->getCurrentGroup()->getAltName() == 'client') ? Config::REDIRECT_SITE_PANEL : Config::REDIRECT_ADMIN_PANEL);
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
                     throw new CustomException("Заппрос на Ваше почту был отправлен, попробуйте через " . abs(time() - Config::FORGOT_PASSWORD_QUERY_RESENDING_INTERVAL - $verificationTokenArchive->getCreatedAt()->getTimestamp()) . " сек.", 0);
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
             $mail->restorePassword($user, $verificationToken);
             $mail->send();

             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Запрос на изменения пароля был отправлен на Ваш email (" . $user->getEmail() . ")");
             $this->response->setRedirect(($user->getCurrentGroup()->getAltName() == 'client') ? Config::REDIRECT_SITE_PANEL : Config::REDIRECT_ADMIN_PANEL);
         } catch (CustomException $e){
             $this->response->setException($e);
         }
         $this->response->show();
     }
}
