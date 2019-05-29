<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\Group;
use \Models\GroupQuery;

use \Models\UserQuery;
use \Models\PrivilegeQuery;


use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class UserGroup extends Base
{
     public function indexAction()
     {
         try {
             $this->helper->shouldHavePrivilege('SUPER');

             $groups = GroupQuery::create()->find();
             $groupList = array();
             $groupList[] = (new Group())->setId(-1)->setName('Выберите группу')->setAltName('-');

             foreach($groups as $group) {
                $groupList[] = $group;
             }

             $this->data = array_merge( $this->data,
                                        array(  'groups' => $groupList
                                                )
                                        );
             View::renderTemplate('Admin/Settings/UserGroup/all.html', $this->data);
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
             $groupId = (isset($this->params['id']) ? $this->params['id'] : null);

             if (is_null($groupId) || intval($groupId) == 0){
                 throw new CustomException("Id группы не был указан", 403);
             }

             $group = GroupQuery::create()->findPk(intval($groupId));
             if (is_null($group)){
                 throw new CustomException("Группа не найдена", 404);
             }

             $groups = GroupQuery::create()->find();
             $groupList = array();
             $groupList[] = (new Group())->setId(-1)->setName('Выберите группу')->setAltName('-');

             foreach($groups as $group_) {
                $groupList[] = $group_;
             }

             $selectedPrivileges = array();
             foreach ($group->getCurrentPrivilegeGroupPriveleges() as $selectedPrivilege) {
                $selectedPrivileges[] = $selectedPrivilege->getId();
             }
             $this->data = array_merge( $this->data,
                                        array(  'groups' => $groupList,
                                                'group' => $group,
                                                'selectedPrivileges' => $selectedPrivileges,
                                                'privileges' => PrivilegeQuery::create()->find()
                                                )
                                        );

             View::renderTemplate('Admin/Settings/UserGroup/edit.html', $this->data);
         } catch (CustomException $e) {
             $this->data = array_merge( $this->data,
                                        array(  'error_code' => $e->getCode(),
                                                'error_title' => "Ошибка",
                                                'error_message' => $e->getMessage()
                                                )
                                        );
             View::renderTemplate('Admin/errorPage.html', $this->data);
         }

     }

     public function addAction()
     {
         try {
             $this->helper->shouldHavePrivilege('SUPER');

             $this->data = array_merge( $this->data,
                                        array(  'privileges' => PrivilegeQuery::create()->find()
                                                )
                                        );
             View::renderTemplate('Admin/Settings/UserGroup/add.html', $this->data);
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
