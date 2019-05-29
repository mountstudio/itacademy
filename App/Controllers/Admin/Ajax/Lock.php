<?php

namespace App\Controllers\Admin\Ajax;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use \Core\JsonResponse;
use \Core\CustomException;
use App\Config;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Lock extends Base
{
    public function indexAction()
    {
        $action = (isset($_POST['action']) ? $_POST['action'] : null);
        try {
            $this->helper->shouldBeLoggedIn();

            if ($action == 'lock'){
             $token = $this->helper->getCurrentToken();
             $isLocked = $token->data->locked;
             if (!$isLocked) {
                 Functions::modifyJWT($token, true);
                 $this->response->setStatus(JsonResponse::SUCCESS);
             } else {
                 throw new CustomException("Вы уже заблокировали экран", 1);
             }
            } elseif ($action == 'unlock'){
                 $user = $this->helper->getCurrentUser();
                 $token = $this->helper->getCurrentToken();
                 $password = (isset($_POST['password']) ? $_POST['password'] : null);
                 if (is_null($password)){
                     throw new CustomException("Пароль не был указан", 1);
                 } else {
                     $userPassword = trim($password);
                     $cryptedPassword = $user->getPassword();

                     if ($cryptedPassword == crypt($userPassword, $cryptedPassword)) {

                         Functions::modifyJWT($token, false);
                         $this->response->setStatus(JsonResponse::SUCCESS);
                     } else {
                         throw new CustomException("Неправильный пароль", 1);
                     }
                 }
            }
        } catch (CustomException $e){
             $this->response->setException($e);
        }
        $this->response->show();
    }
}
