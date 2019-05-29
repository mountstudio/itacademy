<?php

namespace App\Controllers\Front;

require_once '../vendor/autoload.php';

use \Core\Image;
use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\Group;
use \Models\GroupQuery;
use \Models\TagTypeQuery;
use \Models\ProductQuery;

use \Models\StaticPageQuery;


use Hybridauth\Hybridauth;
use Hybridauth\HttpClient;

use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

use \Models\UserQuery;
use \Models\PlaceQuery;
use \Models\TagQuery;
use \Models\ConfigQuery;
use \Models\RecallStatusQuery;
use \Models\MassTypeQuery;
use \Models\DeliveryTimeTypeQuery;
use \Models\PaymentTypeQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Auth extends Base
{
    public function facebookAction()
    {

        try {
            $config = [
                'callback' => 'https://eda.kg/auth/facebook',
                'providers' => Config::SOCIAL_AUTH_CONFIG_PROVIDERS
            ];
            $hybridauth = new Hybridauth( $config );
            $adapter = $hybridauth->authenticate( 'Facebook' );
            $tokens = $adapter->getAccessToken();
            $userProfile = $adapter->getUserProfile();

            $userExists = UserQuery::create()->findOneBySocialId($userProfile->identifier);
            if (is_null($userExists)){
                $newUser = UserQuery::create()->findOneByEmail($userProfile->email);

                if (is_null($newUser)){
                    $newUser = new \Models\User();
                    $isNewUser = true;
                } else {
                    $isNewUser = false;
                }

                if ($isNewUser){
                    if (!(is_null($userProfile->email) || empty($userProfile->email))){
                        $newUser->setEmail($userProfile->email);
                    }

                    $newUser->setName($userProfile->displayName);

                    $newUser->setSocialLogo($userProfile->photoURL);

                    $groupId = \Models\ConfigQuery::create()->findOneByKey('default_user_group');
                    $group = GroupQuery::create()->findPk(intval($groupId->getValue()));
                    if (is_null($group)){
                        throw new CustomException("Группа выбранная по умолчанию для пользователей не существует, пожалуйста обращайтесь администрацию", 1);
                    }

                    $newUser->setCurrentGroup($group);
                }

                $newUser->setSocialId($userProfile->identifier);
                $newUser->setSocialToken($tokens['access_token']);

                $newUser->save();

                if ($isNewUser){
                    $this->socialSignUp($tokens['access_token'], $userProfile->displayName);
                } else {
                    $this->helper->socialLogin($userProfile->identifier, true, false);
                    $this->Redirect('/');
                }
            } else {
                if (!$userExists->getActivated()){
                    $this->socialSignUp($userExists->getSocialToken(), $userExists->getName());
                } else {
                    $this->helper->socialLogin($userProfile->identifier, true, false);
                    $this->Redirect('/');
                }
            }

            $adapter->disconnect();


        } catch (CustomException | \Exception $e) {
            $this->data = array_merge(  $this->data,
                                        $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
              );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/messagePage.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/messagePage.html', $this->data);
            }
        }
    }

    public function googleAction()
    {

        try {
            $config = [
                'callback' => 'https://eda.kg/auth/google',
                'providers' => Config::SOCIAL_AUTH_CONFIG_PROVIDERS,
                'access_type' => 'offline'
            ];
            $hybridauth = new Hybridauth( $config );

            $adapter = $hybridauth->authenticate( 'Google' );
            $tokens = $adapter->getAccessToken();
            $userProfile = $adapter->getUserProfile();

            $userExists = UserQuery::create()->findOneBySocialId($userProfile->identifier);
            if (is_null($userExists)){
                $newUser = UserQuery::create()->findOneByEmail($userProfile->email);

                if (is_null($newUser)){
                    $newUser = new \Models\User();
                    $isNewUser = true;
                } else {
                    $isNewUser = false;
                }

                if ($isNewUser){
                    if (!(is_null($userProfile->email) || empty($userProfile->email))){
                        $newUser->setEmail($userProfile->email);
                    }

                    $newUser->setName($userProfile->displayName);
                    $newUser->setSocialLogo($userProfile->photoURL);

                    $groupId = \Models\ConfigQuery::create()->findOneByKey('default_user_group');
                    $group = GroupQuery::create()->findPk(intval($groupId->getValue()));
                    if (is_null($group)){
                        throw new CustomException("Группа выбранная по умолчанию для пользователей не существует, пожалуйста обращайтесь администрацию", 1);
                    }

                    $newUser->setCurrentGroup($group);
                }

                $newUser->setSocialId($userProfile->identifier);
                $newUser->setSocialToken($tokens['access_token']);

                $newUser->save();

                if ($isNewUser){
                    $this->socialSignUp($tokens['access_token'], $userProfile->displayName, $newUser->getImage()['thumb']);
                } else {
                    $this->helper->socialLogin($userProfile->identifier, true, false);
                    $this->Redirect('/');
                }
            } else {
                if (!$userExists->getActivated()){
                    $this->socialSignUp($userExists->getSocialToken(), $userExists->getName(), $userExists->getImage()['thumb']);
                } else {
                    $this->helper->socialLogin($userProfile->identifier, true, false);
                    $this->Redirect('/');
                }
            }

            $adapter->disconnect();

        } catch (CustomException | \Exception $e) {
            $this->data = array_merge(  $this->data,
                                        $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
              );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/messagePage.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/messagePage.html', $this->data);
            }
        }
    }

    private function socialSignUp($socialToken, $name, $avatar)
    {
        $this->data = array_merge(  $this->data,
                                    array(
                                        'token' => $socialToken,
                                        'name' => $name,
                                        'avatar' => $avatar
                                    )
          );
        if ($this->helper->getDevice() == 'other'){
            View::renderTemplate('Front/Desktop/socialLogin.html', $this->data);
        } else {
            View::renderTemplate('Front/Mobile/socialLogin.html', $this->data);
        }
    }


    private function Redirect($url, $permanent = false)
    {
        if (headers_sent() === false)
        {
            header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
        }

        exit();
    }

    public function logoutAction()
    {
        try {
            $this->helper->logout();
            $this->Redirect($_SERVER['HTTP_REFERER'], false);
        } catch (CustomException $e){
            if ($e->getCode() == 0) $this->Redirect($_SERVER['HTTP_REFERER'], false);
            $this->response->setException($e);
        }
        $this->response->show();
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

            $this->data = array_merge( $this->data,
                       array('token' => $verificationToken->getToken(),
                                          'email' => $verificationToken->getCurrentUser()->getEmail()
                                          )
              );

            if ($this->helper->getDevice() == 'other'){
               View::renderTemplate('Front/Desktop/confirmForgotPassword.html', $this->data);
           } else {
               View::renderTemplate('Front/Mobile/confirmForgotPassword.html', $this->data);
           }
        } catch (CustomException $e) {
            $this->data = array_merge(  $this->data,
                                        $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
              );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/messagePage.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/messagePage.html', $this->data);
            }
        }


    }
}
