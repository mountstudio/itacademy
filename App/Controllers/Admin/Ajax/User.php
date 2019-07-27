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

use Models\CourseStreamQuery;
use Models\CurrencyQuery;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;


use \Models\VerificationToken;
use \Models\VerificationTokenQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class User extends Base
{

     public function listAction()
     {
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('SUPER');
             $paginator = $this->helper->paginator();
             $users = UserQuery::create()->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

             $this->response->setPaginationDetails($users);
             $users_data = array();
             foreach ($users as $user) {
                 $group = $user->getCurrentGroup();
                 $users_data[] = array( 'id' => $user->getId(),
                                        'userName' => $user->getUserName(),
                                        'email' => $user->getEmail(),
                                        'name' => $user->getName(),
                                        'avatar' => $user->getLogo(),
                                        'group' => array(   'altName'   => $group->getAltName(),
                                                            'name'      => $group->getName()
                                                        )
                                        );
             }
             $this->response->setData($users_data);
             $this->response->setStatus(JsonResponse::SUCCESS);
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }
     public function studentsAction()
     {
         $streamId = (isset($_POST['streamId']) ? $_POST['streamId'] : null);
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('SUPER');
             $paginator = $this->helper->paginator();

             if ($streamId) {
                 $stream = CourseStreamQuery::create()->findPk($streamId);
                 if (is_null($stream)){
                     throw new CustomException("Поток не найден", 1);
                 }
                 $users = UserQuery::create()->filterByCurrentGroupId([1,2], Criteria::NOT_IN)->filterByCourseStream($stream)->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
             }
             else {
                 $users = UserQuery::create()->filterByCurrentGroupId([1,2], Criteria::NOT_IN)->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
             }

             $this->response->setPaginationDetails($users);
             $users_data = array();
             foreach ($users as $user) {
                 $group = $user->getCurrentGroup();
                 $users_data[] = array( 'id' => $user->getId(),
                                        'userName' => $user->getUserName(),
                                        'email' => $user->getEmail(),
                                        'name' => $user->getName(),
                                        'avatar' => $user->getLogo(),
                                        'group' => array(   'altName'   => $group->getAltName(),
                                                            'name'      => $group->getName()
                                                        )
                                        );
             }
             $this->response->setData($users_data);
             $this->response->setStatus(JsonResponse::SUCCESS);
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function deleteAction($isSelfDeleting = false)
     {
         $userId = (isset($_POST['id']) ? $_POST['id'] : null);
         if ($isSelfDeleting) $password = (isset($_POST['password']) ? $_POST['password'] : null);
         try {
             if ($isSelfDeleting) $this->helper->shouldBeLoggedIn();
             else $this->helper->shouldHavePrivilege('SUPER');

             if ($isSelfDeleting){
                 $user = $this->helper->getCurrentUser();
             } else {
                 if (is_null($userId) || intval($userId) == 0){
                     throw new CustomException("ID пользователя не был указан", 1);
                 }

                 $user = UserQuery::create()->findPK(intval($userId));
                 if (is_null($user)){
                     throw new CustomException("Пользователь не найден", 1);
                 }
             }

            if ($isSelfDeleting) {
                if (is_null($password)){
                    throw new CustomException("Пароль не был указан", 1);
                } elseif (empty(trim($password))) {
                    throw new CustomException("Пароль не был введен", 1);
                }

                $newPassword = trim($password);
                $cryptedPassword = $user->getPassword();

                if ($cryptedPassword != crypt($newPassword, $cryptedPassword)) {
                    throw new CustomException("Пароль введен неправильно", 1);
                }
            }

             $user->delete();
             $this->response->setStatus(JsonResponse::SUCCESS);
             if ($isSelfDeleting) {
                 $this->helper->logout();
                 $this->response->setRedirect(Config::REDIRECT_ADMIN_AFTER_LOGOUT);
             } else $this->response->setRedirect('/admin/users');
             $this->response->setMessage('Пользователь был успешно удален');
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function selfDeleteAction()
     {
         $this->deleteAction(true);
     }

     public function editAction($isSelfEditing = false)
     {
         if (!$isSelfEditing) $userId = (isset($_POST['id']) ? $_POST['id'] : null);
         $email = (isset($_POST['email']) ? $_POST['email'] : null);

         $name = (isset($_POST['name']) ? $_POST['name'] : null);
         $birthDate = (isset($_POST['birthDate']) ? $_POST['birthDate'] : null);
         $about = (isset($_POST['about']) ? $_POST['about'] : null);$about = (isset($_POST['about']) ? $_POST['about'] : null);
         $currencyId = (isset($_POST['currencyId']) ? $_POST['currencyId'] : null);
         $userName = (isset($_POST['userName']) ? $_POST['userName'] : null);
         $password = ((isset($_POST['newPassword']) && strlen($_POST['newPassword']) > 0) ? $_POST['newPassword'] : null);
         $confirmPassword = ((isset($_POST['confirmPassword']) && strlen($_POST['confirmPassword']) > 0) ? $_POST['confirmPassword'] : null);
         $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);
         $address = (isset($_POST['address']) ? $_POST['address'] : null);
         $activated = (isset($_POST['activated']) ? (($_POST['activated'] == 'true') ? true : false) : null);

         try {
             if ($isSelfEditing) $this->helper->shouldBeLoggedIn();
             else $this->helper->shouldHavePrivilege('SUPER');

             $user;
             $group = null;
             if ($isSelfEditing){
                 $user = $this->helper->getCurrentUser();
             } else {
                 if (is_null($userId) || intval($userId) == 0){
                     throw new CustomException("ID пользователя не был указан", 1);
                 }

                 $user = UserQuery::create()->findPK($userId);
                 if (is_null($user)){
                     throw new CustomException("Пользователь не найден", 1);
                 }
             }

             if (!$isSelfEditing){
                $groupId = (isset($_POST['groupId']) ? $_POST['groupId'] : null);

                 if (is_null($groupId) || intval($groupId) == 0){
                     throw new CustomException("ID группы не был указан", 1);
                 }

                 $group = GroupQuery::create()->findPK($groupId);
                 if (is_null($group)){
                     throw new CustomException("Группа не найдена", 1);
                 }
             }

             if (intval($currencyId) != 0){
                 $currency = CurrencyQuery::create()->findPK($currencyId);
                 if (is_null($currency)){
                     throw new CustomException("Валюта не найдена", 1);
                 }
                 $user->setCurrentUserCurrency($currency);
             } else {
                 $user->setCurrentUserCurrency(null);
             }

             if (is_null($email) || empty(trim($email)) ){
                 throw new CustomException("Email не был введен", 1);
             }

             if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) == false){
                 throw new CustomException("Неверный email", 1);
             }

             if (!is_null($userName)){
                 if (empty(trim($userName))){
                     $user->setUserName(null);
                 } elseif (strlen(trim($userName)) < 3){
                     throw new CustomException("Длина логина должна быть больше 3 символов", 1);
                 } else {
                     if ($user->getUserName() != trim($userName)){
                         $userByUserName = UserQuery::create()->findOneByUserName(trim($userName));
                         if (!is_null($userByUserName)){
                             throw new CustomException("Такой логин занят", 1);
                         }
                         $user->setUserName(trim($userName));
                     }
                 }

             }

             if (is_null($name) || empty(trim($name)) ){
                 throw new CustomException("Имя не было введено", 1);
             } elseif (strlen(trim($name)) < 3){
                 throw new CustomException("Длина имени должна быть больше 3 символов", 1);
             }

             if (!is_null($password) ){
                 if (strlen(trim($password)) < 6){
                     throw new CustomException("Длина пароля должна быть больше 6 символов", 1);
                 } elseif (trim($password) != trim($confirmPassword)){
                     throw new CustomException("Пароли не совпадают", 1);
                 } else {
                     $cryptedPassword = crypt(trim($password), Config::PASSWORD_SALT);
                     $user->setPassword($cryptedPassword);
                 }
             }

             if (!empty(trim($phone))) {
                 $phone = str_replace("(","",str_replace(")","",str_replace("-","",str_replace(" ","",$phone))));
             }

             if (is_null($birthDate)){
                 throw new CustomException("Дата рождения не была введена", 1);
             } elseif (!empty(trim($birthDate))) {
                 $user->setBirthDate(\DateTime::createFromFormat('Y-m-d', $birthDate));
             } else {
                 $user->setBirthDate(null);
             }

             if (is_null($about)){
                 throw new CustomException("О себе не был введен", 1);
             } elseif (!empty(trim($about))) {
                 $user->setAbout(trim($about));
             } else {
                 $user->setAbout(null);
             }


             if (!empty(trim($phone)) && !preg_match('/^[0-9]+$/', trim($phone))){
                 throw new CustomException("Номер телефона должен быть введен в формате 996XXXYYYYYY", 1);
             } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12 && substr(trim($phone),0, 3) != "996") {
                  throw new CustomException("Номер телефона должен быть веден в формате 996XXXYYYYYY", 1);
             } elseif (!empty(trim($phone))) {
                 $user->setPhone(trim($phone));
             }


             $user->setName($name);
              if (!$isSelfEditing) $user->setCurrentGroup($group);



             if(isset($_FILES["avatar"])) {
                $user->setLogo($_FILES["avatar"]);
                if (is_null($this->response->getData())) {
                    $this->response->setData( array(   'image' => $user->getLogo()
                                                   )
                                           );
                } else {
                    $this->response->setData(  array_merge($this->response->getData(),
                                                           array(  'image' => $user->getLogo()
                                                               )
                                                           )
                                                       );
                }
            }

             if (!is_null($address)) $user->setAddress($address);

             if (!is_null($activated)) $user->setActivated($activated);

             if ($isSelfEditing && trim($email) != $user->getEmail() && !in_array('USERS_ADMIN', $helper->getUserPrivileges($user))){
                 $verificationTokenArchive = VerificationTokenQuery::create()->filterByCurrentUser($user)->filterByType(2);
                 $verificationTokenArchive->delete();
                  $verificationToken = new VerificationToken();
                  $verificationToken->setEmail(trim($email));
                  $verificationToken->setToken(Functions::generateToken(Config::TOKEN_LENGTH_FOR_LOGIN_AUTH));
                  $verificationToken->setExpiryDateTime(time() + Config::EMAIL_VERIFICATION_EXPIRATION * 86400);
                  $verificationToken->setCurrentUser($user);
                  $verificationToken->setType(2);
                  $verificationToken->save();
                  $mail = new Mail();
                  $mail->changeEmail($user, $verificationToken);
                  $mail->send();
                  $this->response->setMessage("Данные сохранены, однако Вам нужно перейти на почту и подтвердить Ваш email");
             } else {
                 if ($user->getEmail() != trim($email)){
                     $userByEmail = UserQuery::create()->findOneByEmail(trim($email));
                     if (!is_null($userByEmail)){
                         throw new CustomException("Такой email существует", 1);
                     }
                     $user->setEmail(trim($email));
                 }
                 $this->response->setMessage("Пользователь успешно сохранен");
             }

             $user->save();
             if (is_null($this->response->getData())) {
                 $this->response->setData( array(   'name' => $user->getName(),
                                                    'phone' => $user->getPhone()
                                                )
                                        );
             } else {
                 $this->response->setData(  array_merge($this->response->getData(),
                                                        array(  'name' => $user->getName(),
                                                                'phone' => $user->getPhone()
                                                            )
                                                        )
                                                    );
             }

             $this->response->setStatus(JsonResponse::SUCCESS);

         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function selfEditAction()
     {
         $this->editAction(true);
     }
     public function deleteAvatarAction($isSelfEditing = false)
     {
         $userId = (isset($_POST['id']) ? $_POST['id'] : null);
         try {
             if ($isSelfEditing) $this->helper->shouldBeLoggedIn();
             else $this->helper->shouldHavePrivilege('SUPER');

             $user;
             if ($isSelfEditing){
                 $user = $this->helper->getCurrentUser();
             } else {
                 if (is_null($userId) || intval($userId) == 0){
                     throw new CustomException("ID пользователя не был указан", 1);
                 }

                 $user = UserQuery::create()->findPK($userId);
                 if (is_null($user)){
                     throw new CustomException("Пользователь не найден", 1);
                 }
             }
             if (!is_null($user->getLogoName())){
                 Image::deleteUserLogo($user->getLogoName());
                 $this->response->setStatus(JsonResponse::SUCCESS);
                 $user->setLogoName(null);
                 $user->save();

                 $this->response->setData(array('image' => $user->getLogo()));
                 $this->response->setMessage("Фото успешно удален", 0);
             } else {
                 $this->response->setStatus(JsonResponse::FAIL);
                 $this->response->setMessage("Пользователь не имеет фото", 0);
             }
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }



     public function changePasswordAction()
     {
         $currentPassword = ((isset($_POST['currentPassword']) && strlen($_POST['currentPassword']) > 0) ? $_POST['currentPassword'] : null);
         $password = ((isset($_POST['newPassword']) && strlen($_POST['newPassword']) > 0) ? $_POST['newPassword'] : null);
         $passwordConfirmation = ((isset($_POST['confirmNewPassword']) && strlen($_POST['confirmNewPassword']) > 0) ? $_POST['confirmNewPassword'] : null);
         try {
             $this->helper->shouldBeLoggedIn();

             if (is_null($currentPassword)){
                 throw new CustomException("Текущий пароль не был указан", 1);
             }
             if (!is_null($password) && strlen(trim($password)) < 6){
                 throw new CustomException("Длина пароля должна быть больше 6 символов", 1);
             } elseif (is_null($password)) {
                 throw new CustomException("Новый пароль не был введен", 1);
             } elseif (is_null($passwordConfirmation)) {
                 throw new CustomException("Подтверждение нового пароля не было введено", 1);
             } elseif (trim($password) != trim($passwordConfirmation)) {
                 throw new CustomException("Подтверждение пароля не совпадает с новым паролем", 1);
             }

             $user = $this->helper->getCurrentUser();

             $newPassword = trim($password);
             $cryptedPassword = $user->getPassword();

             if ($cryptedPassword != crypt($newPassword, $cryptedPassword)) {
                 throw new CustomException("Текущий пароль введен неправильно", 1);
             }

             $user->setPassword(crypt(trim($newPassword), Config::PASSWORD_SALT));
             $user->save();

             $this->response->setStatus(JsonResponse::SUCCESS);

             $this->response->setMessage("Пароль был успешно изменен");

         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function selfDeleteAvatarAction()
     {
         $this->deleteAvatarAction(true);
     }

     public function addAction($isSelfEditing = false)
     {
         $email = (isset($_POST['email']) ? $_POST['email'] : null);
         $groupId = (isset($_POST['groupId']) ? $_POST['groupId'] : null);
         $name = (isset($_POST['name']) ? $_POST['name'] : null);
         $userName = (isset($_POST['userName']) ? $_POST['userName'] : null);
         $birthDate = (isset($_POST['birthDate']) ? $_POST['birthDate'] : null);
         $password = ((isset($_POST['password']) && strlen($_POST['password']) > 0) ? $_POST['password'] : null);
         $passwordConfirmation = ((isset($_POST['confirmPassword']) && strlen($_POST['confirmPassword']) > 0) ? $_POST['confirmPassword'] : null);
         $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);
         $address = (isset($_POST['address']) ? $_POST['address'] : null);
         $activated = (isset($_POST['activated']) ? (($_POST['activated'] == 'true') ? true : false) : null);
         $about = (isset($_POST['about']) ? $_POST['about'] : null);
         try {
             $this->helper->shouldHavePrivilege('SUPER');
             $user = new \Models\User();


             if (is_null($email) || empty(trim($email)) ){
                 throw new CustomException("Email не был введен", 1);
             }

             if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) == false){
                 throw new CustomException("Неверный email", 1);
             }

             $userByEmail = UserQuery::create()->findOneByEmail(trim($email));

             if (!is_null($userByEmail)){
                 throw new CustomException("Такой email существует", 1);
             }

             if (!is_null($userName)){
                 if (empty(trim($userName))){
                     $user->setUserName(null);
                 } elseif (strlen(trim($userName)) < 3){
                     throw new CustomException("Длина логина должна быть больше 3 символов", 1);
                 } else {
                     $userByUserName = UserQuery::create()->findOneByUserName(trim($userName));
                     if (!is_null($userByUserName)){
                         throw new CustomException("Такой логин занят", 1);
                     }
                     $user->setUserName(trim($userName));
                 }
             }

             $userByEmail = UserQuery::create()->findOneByEmail(trim($email));

             if (!is_null($userByEmail)){
                 throw new CustomException("Такой email существует", 1);
             }

             if (is_null($groupId) || intval($groupId) == 0){
                 throw new CustomException("ID группы не был указан", 1);
             }

             $group = GroupQuery::create()->findPK($groupId);
             if (is_null($group)){
                 throw new CustomException("Группа не найдена", 1);
             }



             if (is_null($name) || empty(trim($name)) ){
                 throw new CustomException("Имя не была введена", 1);
             }

             if (is_null($birthDate)){
                 throw new CustomException("Дата рождения не была введена", 1);
             } elseif (!empty(trim($birthDate))) {
                 $user->setBirthDate(\DateTime::createFromFormat('Y-m-d', $birthDate));
             } else {
                 $user->setBirthDate(null);
             }

             if (!is_null($password) && strlen(trim($password)) < 6){
                 throw new CustomException("Длина пароля должно быть больше 6 символов", 1);
             } elseif (is_null($password)){
                 throw new CustomException("Пароль не был введен", 1);
             } elseif ($password != $passwordConfirmation){
                 throw new CustomException("Пароли не совпадают", 1);
             }


              if (!empty(trim($phone))) {
                  $phone = str_replace("(","",str_replace(")","",str_replace("-","",str_replace(" ","",$phone))));
              }

             if (!empty(trim($phone)) && !preg_match('/^[0-9]+$/', trim($phone))){
                 throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
             } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12 && substr(trim($phone),0, 3) != "996") {
                  throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
             } elseif (!empty(trim($phone))) {
                 $user->setPhone(trim($phone));
             }

             $user->setEmail($email);
             $user->setName($name);
             $user->setCurrentGroup($group);
             $user->setAbout(trim($about));
             $cryptedPassword = crypt(trim($password), Config::PASSWORD_SALT);
             $user->setPassword($cryptedPassword);
             $user->setActivated($activated);

             if (!is_null(trim($address)) && !empty(trim($address))){
                 $user->setAddress($address);
             }

             if (!is_null($activated)) $user->setActivated($activated);

             $user->save();

             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage("Пользователь успешно добавлен");
             $this->response->setRedirect('/admin/users/all');
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

}
