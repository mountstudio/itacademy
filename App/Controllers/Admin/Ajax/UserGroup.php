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
use \Models\ConfigQuery;
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

     public function listAction()
     {
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('SUPER');
             $paginator = $this->helper->paginator();
             $groups = GroupQuery::create()->orderById('asc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

             $this->response->setPaginationDetails($groups);
             $groups_data = array();
             foreach ($groups as $group) {
                $privileges = array();
                foreach ($group->getCurrentPrivilegeGroupPriveleges() as $privilege) {
                    $privileges[] = $privilege->getAlt();
                }
                 $groups_data[] = array('id' => $group->getId(),
                                        'name' => $group->getName(),
                                        'altName' => $group->getAltName(),
                                        'numberOfUsers' => UserQuery::create()->filterByCurrentGroup($group)->count(),
                                        'privileges' => implode(', ', $privileges)
                                        );
             }
             $this->response->setData($groups_data);
             $this->response->setStatus(JsonResponse::SUCCESS);
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function deleteAction()
     {
         $groupId = (isset($_POST['id']) ? $_POST['id'] : null);
         $alternateGroupId = (isset($_POST['newRoleId']) ? $_POST['newRoleId'] : null);
         try {
             $this->helper->shouldHavePrivilege('SUPER');

             if (is_null($groupId) || intval($groupId) == 0){
                 throw new CustomException("ID группы не был указан", 1);
             }

             $group = GroupQuery::create()->findPK(intval($groupId));
             if (is_null($group)){
                 throw new CustomException("Группа не найдена", 1);
             }


             $usersAltGroup = UserQuery::create()->filterByCurrentGroup($group)->find();


                 if (is_null($alternateGroupId) || intval($alternateGroupId) == 0){
                     throw new CustomException("ID альтернативной группы не был указан", 1);
                 }

                 $alternateGroup = GroupQuery::create()->findPK(intval($alternateGroupId));
                 if (is_null($alternateGroup)){
                     throw new CustomException("Альтернативная группа не найдена", 1);
                 }

                 if ($alternateGroup->getId() == $group->getId()){
                     throw new CustomException("Альтернативная группа не может быть равна самой удаляемой группы", 1);
                 }

            if (sizeof($usersAltGroup) > 0){
                 foreach ($usersAltGroup as $userToChangeGroup) {
                     $userToChangeGroup->setCurrentGroup($alternateGroup);
                     $userToChangeGroup->save();
                 }

                 $defaultUserGroup = ConfigQuery::create()->findOneByKey('default_user_group');
                 if (is_null($defaultUserGroup)){
                     $defaultUserGroup = new ConfigQuery();
                     $defaultUserGroup->setKey('default_user_group');
                 }

                 $defaultUserGroup->setValue($alternateGroup->getId());
                 $defaultUserGroup->save();


                $this->response->setMessage('Пользователи, которые были в группе ' . $group->getName() . ', теперь является как ' . $alternateGroup->getName() . ', также была изменена группа пользователей по умолчанию на ' . $alternateGroup->getName());
            } else {
                $this->response->setMessage('Группа была удалена успешно');
            }



            $defaultUserGroup = ConfigQuery::create()->findOneByKey('default_user_group');
            if (is_null($defaultUserGroup)){
                $defaultUserGroup = new \Models\Config();
                $defaultUserGroup->setKey('default_user_group');
            }
            if ($defaultUserGroup->getValue() == $group->getId()){
                $defaultUserGroup->setValue($alternateGroup->getId());
                $defaultUserGroup->save();
                $this->response->setMessage($this->response->getMessage() . ', также группа пользователей по умолчанию в конфигурации была изменена на ' . $alternateGroup->getName());
            }

             $group->delete();
             $this->response->setStatus(JsonResponse::SUCCESS);
             $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
             if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                 $this->response->setRedirect('/admin/settings/userGroups');
             }
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function editAction()
     {
         $id = (isset($_POST['id']) ? $_POST['id'] : null);
         $name = (isset($_POST['name']) ? $_POST['name'] : null);
         $altName = (isset($_POST['altName']) ? $_POST['altName'] : null);

         $privileges = (isset($_POST['privileges']) ? $_POST['privileges'] : null);

         try {
             $this->helper->shouldHavePrivilege('SUPER');

              if (is_null($id) || intval($id) == 0){
                  throw new CustomException("ID группы не был указан", 1);
              }

              $group = GroupQuery::create()->findPK(intval($id));
              if (is_null($group)){
                  throw new CustomException("Группа не найдена", 1);
              }

              if (is_null($name) || empty(trim($name)) ){
                  throw new CustomException("Имя не было введено", 1);
              }

              if (trim($name) != $group->getName()){
                  $groupByName = GroupQuery::create()->findOneByName(trim($name));
                  if (!is_null($groupByName)) {
                      throw new CustomException("Такое имя группы существует", 1);
                  }
              }

              if (is_null($altName) || empty(trim($altName)) ){
                  throw new CustomException("Альтернативное имя не было введено", 1);
              }

              if (trim($altName) != $group->getAltName()){
                  $groupByAltName = GroupQuery::create()->findOneByAltName(trim($altName));
                  if (!is_null($groupByAltName)) {
                      throw new CustomException("Такое альтернативное имя группы существует", 1);
                  }
              }

              if (is_null($privileges) || !is_array($privileges)){
                  throw new CustomException("Не были получены привелегии", 1);
              }

              $group->setName(trim($name));
              $group->setAltName(trim($altName));

              $getPrivileges = PrivilegeQuery::create()->findPks($privileges);
              $group->setCurrentPrivilegeGroupPriveleges($getPrivileges);

              $group->save();

              $this->response->setStatus(JsonResponse::SUCCESS);
              $this->response->setMessage("Группа успешно сохранена");
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function addAction()
     {
         $name = (isset($_POST['name']) ? $_POST['name'] : null);
         $altName = (isset($_POST['altName']) ? $_POST['altName'] : null);

         $privileges = (isset($_POST['privileges']) ? $_POST['privileges'] : null);


         try {
             $this->helper->shouldHavePrivilege('SUPER');

              if (is_null($name) || empty(trim($name)) ){
                  throw new CustomException("Имя не было введено", 1);
              }

              $groupByName = GroupQuery::create()->findOneByName(trim($name));
              if (!is_null($groupByName)) {
                  throw new CustomException("Такое имя группы существует", 1);
              }

              if (is_null($altName) || empty(trim($altName)) ){
                  throw new CustomException("Альтернативное имя не было введено", 1);
              }

              $groupByAltName = GroupQuery::create()->findOneByAltName(trim($altName));
              if (!is_null($groupByAltName)) {
                  throw new CustomException("Такое альтернативное имя группы существует", 1);
              }

              if (is_null($privileges) || !is_array($privileges)){
                  throw new CustomException("Не были получены привелегии", 1);
              }

              $group = new Group();
              $group->setName(trim($name));
              $group->setAltName(trim($altName));

              $getPrivileges = PrivilegeQuery::create()->findPks($privileges);
              $group->setCurrentPrivilegeGroupPriveleges($getPrivileges);

              $group->save();

              $this->response->setStatus(JsonResponse::SUCCESS);
              $this->response->setMessage("Группа успешно создана");
              $this->response->setRedirect('/admin/settings/userGroups/all');
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

}
