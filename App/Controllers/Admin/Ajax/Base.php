<?php

namespace App\Controllers\Admin\Ajax;

use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;

use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\LoginToken;
use \Models\LoginTokenQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Base extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
     protected $helper, $response, $params;

     function __construct($params) {
         //sleep(1);
         $this->params = $params;
         $this->response = new JsonResponse();
         $this->helper = new Helper();
     }

     public function indexAction()
     {

     }
}
