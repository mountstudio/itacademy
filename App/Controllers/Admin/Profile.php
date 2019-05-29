<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;


use Models\CurrencyQuery;
use \Models\User;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Profile extends Base
{
     public function indexAction()
     {
         try {
             if ($this->helper->getCurrentUser()->getCurrentGroup()->getName() != 'admin'){
                 $verificationToken = VerificationTokenQuery::create()->findOneByCurrentUserAndType($this->helper->getCurrentUser(), 2);
                 if (!is_null($verificationToken)){
                     $this->data = array_merge( $this->data, array( 'verificationEmail' => $verificationToken->getEmail()
                                                      )
                                             );
                 }
             }
             $currencies = CurrencyQuery::create()->find();
             $this->data = array_merge(
                 $this->data,
                 array( 'currencies' => $currencies
                 )
             );
             View::renderTemplate('Admin/Profile/index.html', $this->data);
         } catch (CustomException $e) {
             $this->data = array_merge( $this->data,
                                        array(  'error_code' => $e->getCode(),
                                                'error_title' => "Ошибка",
                                                'error_message' => $e->getMessage(),
                                                )
                                        );
             View::renderTemplate('Admin/errorPage.html', $this->data);
         }

     }
}
