<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use Models\CurrencyQuery;
use \Models\Group;
use \Models\GroupQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class User extends Base
{
     public function indexAction()
     {
         try {
             $this->helper->shouldHavePrivilege('SUPER');
             View::renderTemplate('Admin/User/all.html', $this->data);
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

     public function editAction()
     {
         try {
             $this->helper->shouldHavePrivilege('SUPER');
             $eUserId = (isset($this->params['id']) ? $this->params['id'] : null);
             if (is_null($eUserId) || intval($eUserId) == 0){
                 throw new CustomException("User Id is not valid", 403);
             }
             $eUser = UserQuery::create()->findPk(intval($eUserId));
             if (is_null($eUser)){
                 throw new CustomException("Пользователь не найден", 404);
             }

             $groups = GroupQuery::create()->find();
             $currencies = CurrencyQuery::create()->find();

             $this->data = array_merge( $this->data,
                                        array(  'eUser' => $eUser,
                                                'groups' => $groups,
                                                'currencies' => $currencies
                                                )
                                        );
             View::renderTemplate('Admin/User/edit.html', $this->data);
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

     public function addAction()
     {
         try {
             $this->helper->shouldHavePrivilege('SUPER');
             $groups = GroupQuery::create()->find();
             $groupList = array();
             foreach($groups as $group) {
                $groupList[] = $group;
             }
             $this->data = array_merge( $this->data,
                                        array(  'groups' => $groupList
                                                )
                                        );
             View::renderTemplate('Admin/User/add.html', $this->data);
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
