<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\CustomException;
use App\Config;

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

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

use \Models\Group;
use \Models\GroupQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Auth extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
     private $params;
     function __construct($params) {
         $this->params = $params;
     }
     public function indexAction()
     {

     }

     public function loginAction()
     {
         View::renderTemplate('Admin/login.html');
     }

     public function forgotPasswordAction()
     {
         View::renderTemplate('Admin/forgotPassword.html');
     }

     public function registrationVerificationAction()
     {
         $verificationToken;
         try {
             if (!is_null($this->params) && isset($this->params['token'])){
                 $verificationToken = VerificationTokenQuery::create()->findOneByTokenAndType($this->params['token'], 1);
                 if (!is_null($verificationToken) && $verificationToken->getExpiryDateTime()->getTimestamp() < time()){
                     throw new CustomException("Время токена истек", 1);
                 } elseif (is_null($verificationToken)) {
                     throw new CustomException("Такой токен не существует", 1);
                 }
             } else {
                 throw new CustomException("Токен не был указан", 1);
             }


             $config = \Models\ConfigQuery::create()->findOneByKey('allow_users_choose_group');

             if ($config->getValue() == 1){
                 $groups = GroupQuery::create()->filterByAllowChooseGroup(true)->find();
             }

             $arguments = array('token' => $verificationToken->getToken(),
                                'email' => $verificationToken->getEmail()
                                );

            if ($config->getValue() == 1){
                $arguments = array_merge($arguments, array('groups' => $groups));
            }

             View::renderTemplate('Admin/confirmRegistration.html', $arguments);
         } catch (CustomException $e) {
             $arguments = array('title' => "Ошибка",
                                'header' => array(  'self' => 'Ошибка',
                                                    'title' => $e->getMessage(),
                                                    'message' => ''
                                                    )
                                );
             View::renderTemplate('Admin/messagePage.html', $arguments);
         }


     }


     public function changeEmailVerificationAction()
     {
         $verificationToken;
         try {
             if (!is_null($this->params) && isset($this->params['token'])){
                 $verificationToken = VerificationTokenQuery::create()->findOneByTokenAndType($this->params['token'], 2);
                 if (!is_null($verificationToken) && $verificationToken->getExpiryDateTime()->getTimestamp() < time()){
                     throw new CustomException("Время токена истек", 1);
                 } elseif (is_null($verificationToken)) {
                     throw new CustomException("Такой токен не существует", 1);
                 }
             } else {
                 throw new CustomException("Токен не был указан", 1);
             }

             $arguments = array('title' => "Изменение email",
                                'header' => array(  'self' => 'Успех',
                                                    'title' => 'Email был успешно поменен',
                                                    'message' => 'Через 2 секунд вы будете перенаправлены в панель управление'
                                                ),
                                'redirect' => array('uri' => ($verificationToken->getCurrentUser()->getCurrentGroup()->getAltName() == 'client') ? Config::REDIRECT_SITE_PANEL : Config::REDIRECT_ADMIN_PANEL,
                                                    'time' => 2000
                                                )
                                );
            $user = $verificationToken->getCurrentUser();
            $user->setEmail($verificationToken->getEmail());
            $user->save();
            $verificationToken->delete();
             View::renderTemplate('Admin/messagePage.html', $arguments);
         } catch (CustomException $e) {
             $arguments = array('title' => "Ошибка",
                                'header' => array(  'self' => 'Ошибка',
                                                    'title' => $e->getMessage(),
                                                    'message' => ''
                                                    )
                                );
             View::renderTemplate('Admin/messagePage.html', $arguments);
         }


     }


     public function confirmForgotPasswordVerificationAction()
     {
         $verificationToken;
         try {
             if (!is_null($this->params) && isset($this->params['token'])){
                 $verificationToken = VerificationTokenQuery::create()->findOneByTokenAndType($this->params['token'], 3);
                 if (!is_null($verificationToken) && $verificationToken->getExpiryDateTime()->getTimestamp() < time()){
                     throw new CustomException("Время токена истек", 1);
                 } elseif (is_null($verificationToken)) {
                     throw new CustomException("Такой токен не существует", 1);
                 }
             } else {
                 throw new CustomException("Токен не был указан", 1);
             }

             $arguments = array('token' => $verificationToken->getToken(),
                                'email' => $verificationToken->getCurrentUser()->getEmail()
                                );

             View::renderTemplate('Admin/confirmForgotPassword.html', $arguments);
         } catch (CustomException $e) {
             $arguments = array('title' => "Ошибка",
                                'header' => array(  'self' => 'Ошибка',
                                                    'title' => $e->getMessage(),
                                                    'message' => ''
                                                    )
                                );
             View::renderTemplate('Admin/messagePage.html', $arguments);
         }


     }

     public function registrationAction()
     {
         View::renderTemplate('Admin/registration.html');
     }

     public function logoutAction()
     {

         $helper = new Helper();
         $helper->logout();
         header('Location: ' . Config::REDIRECT_ADMIN_AFTER_LOGOUT);
     }
}
