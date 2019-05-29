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



use \Models\OrderQuery;
use \Models\RecallQuery;

use \Models\ProductQuery;
use \Models\OrderStatusQuery;

use \Models\ConfigQuery;
use \Models\EmailSubscribeQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */

class Subscribe extends Base
{
    public function addAction()
    {
        $email = (isset($_POST['email']) ? $_POST['email'] : null);

        try {
            if (is_null($email) || empty(trim($email))){
                throw new CustomException("Email не указан", 403);
            }

            if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                throw new CustomException("Введите валидный email", 403);
            }

            $findEmailSubscribe = EmailSubscribeQuery::create()->findOneByEmail(trim($email));
            if (!is_null($findEmailSubscribe)){
                throw new CustomException("Вы уже подписаны на наши рассылки", 1);
            }

            $subscription = new \Models\EmailSubscribe();
            $subscription->setEmail(trim($email));
            $subscription->setIp(Functions::getIp());

            $subscription->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Вы успешно подписались на нашу рассылку");
        } catch (CustomException $e){
            $this->response->setException($e);
        }
        $this->response->show();
    }
}
