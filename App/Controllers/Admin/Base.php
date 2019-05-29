<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;


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
class Base extends \Core\Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
     protected $helper, $data, $params;

     function __construct($params) {
         $this->params = $params;
         $this->helper = new Helper();

         try {
              if (is_null($this->helper->getCurrentUser())) throw new CustomException("Не авторизован", 1);
              elseif ($this->helper->getCurrentToken()->data->locked) throw new CustomException("Экран заблокирован", 9);

             $this->data  =  array( 'styles'=>  $this->helper->getAdminPanelStyles(),
                                    'user'  =>  $this->helper->getCurrentUser(),
                                    'userPriveleges' => $this->helper->getUserPrivileges(),
                                    'encodedToken'  =>  Functions::getCookie('token'),
                                    'notifications' => \Core\Notification::getAllNotifications($this->helper->getCurrentUser()),
                                    'helper'=>  $this->helper,
                                    'currentYear' => date('Y'),
                                    'titlePostfix' => ' - Control Panel' . Config::TITLE_POSTFIX
                                    );

         } catch (CustomException $e) {
             if ($e->getCode() == 9){
                 $this->helper = new Helper();
                 $this->data = array(   'styles'=>  $this->helper->getAdminPanelStyles(),
                                        'user'  =>  $this->helper->getCurrentUser(),
                                        'userPriveleges' => $this->helper->getUserPrivileges(),
                                        'encodedToken'  =>  Functions::getCookie('token'),
                                        'helper'=>  $this->helper,
                                        'currentYear' => date('Y'),
                                        'titlePostfix' => ' - Control Panel' . Config::TITLE_POSTFIX
                                        );
                 View::renderTemplate('Admin/locked.html', $this->data);
                 die();
             }
             header('Location: ' . Config::REDIRECT_ADMIN_AFTER_LOGOUT);
         }
     }

     public function indexAction()
     {

     }
}
