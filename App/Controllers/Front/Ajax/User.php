<?php

namespace App\Controllers\Front\Ajax;

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

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class User extends Base
{

     public function editAction()
     {
         $email = (isset($_POST['email']) ? $_POST['email'] : null);

         $name = (isset($_POST['name']) ? $_POST['name'] : null);
         $userName = (isset($_POST['userName']) ? $_POST['userName'] : null);
         $password = ((isset($_POST['newPassword']) && strlen($_POST['newPassword']) > 0) ? $_POST['newPassword'] : null);
         $confirmPassword = ((isset($_POST['confirmPassword']) && strlen($_POST['confirmPassword']) > 0) ? $_POST['confirmPassword'] : null);
         $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);
         $address = (isset($_POST['address']) ? $_POST['address'] : null);
         $activated = (isset($_POST['activated']) ? (($_POST['activated'] == 'true') ? true : false) : null);

         try {
             $this->helper->shouldBeLoggedIn();

             $user = $this->helper->getCurrentUser();

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
                     throw new CustomException("Длина имя пользователя (логин) должна быть больше чем 3 символов", 1);
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
                 throw new CustomException("Имя не была введена", 1);
             } elseif (strlen(trim($name)) < 3){
                 throw new CustomException("Длина имени должна быть больше 3 символов", 1);
             }

             if (!is_null($password) ){
                 if (strlen(trim($password)) < 6){
                     throw new CustomException("Длина пароля должно быть больше 6 символов", 1);
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


             if (!empty(trim($phone)) && !preg_match('/^[0-9]+$/', trim($phone))){
                 throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
             } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12 && substr(trim($phone),0, 3) != "996") {
                  throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
             } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12) {
                  throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
             } elseif (!empty(trim($phone))) {
                 $user->setPhone(trim($phone));
             }


             $user->setName($name);

             if(isset($_FILES["avatar"])) {
                 $isImage = false;
                $check = getimagesize($_FILES["avatar"]["tmp_name"]);
                if($check !== false) {
                    switch ($check["mime"]) {
                        case 'image/png':
                            $isImage = true;
                            break;
                        case 'image/jpeg':
                            $isImage = true;
                            break;
                    }
                    if ($isImage){
                        $image = new Image($_FILES['avatar']['tmp_name']);
                        $imageName = $image->createAllAvatarImageTypes();

                        if (!is_null($user->getAvatarName())) {
                            $tmpImage = new Image(null, $user->getAvatarName());
                            $tmpImage->deleteAllAvatarImageTypes();
                        }

                        $user->setAvatarName($imageName);
                        $this->response->setData(array('image' => $user->getImage()));
                    } else {
                        throw new CustomException("Формат фотографии неподдерживается", 1);
                    }
                } else {
                    throw new CustomException("Формат файла неподдерживается", 1);
                }
            }

             if (!is_null($address)) $user->setAddress($address);

             if (!is_null($activated)) $user->setActivated($activated);

             if ($user->getEmail() != trim($email)){
                 $userByEmail = UserQuery::create()->findOneByEmail(trim($email));
                 if (!is_null($userByEmail)){
                     throw new CustomException("Такой email существует", 1);
                 }
                 $user->setEmail(trim($email));
             }

             $this->response->setMessage("Пользователь сохранен успешо");

             $user->save();
             if (is_null($this->response->getData())) $this->response->setData(array('name' => $user->getName(), 'phone' => $user->getPhone()));
             else $this->response->setData(array_merge($this->response->getData(), array('name' => $user->getName(), 'phone' => $user->getPhone())));

             $this->response->setStatus(JsonResponse::SUCCESS);

         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function deleteAvatarAction()
     {
         $userId = (isset($_POST['id']) ? $_POST['id'] : null);
         try {
             $this->helper->shouldBeLoggedIn();

             $user = $this->helper->getCurrentUser();

             if (!is_null($user->getAvatarName())){
                 $image = new Image(null, $user->getAvatarName());
                 $image->deleteAllAvatarImageTypes();
                 $this->response->setStatus(JsonResponse::SUCCESS);
                 $user->setAvatarName(null);
                 $user->save();
                 $this->response->setData(array('image' => $user->getImage()));
                 $this->response->setMessage("Фото был удален успешно", 0);
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
                 throw new CustomException("Длина пароля должно быть больше 6 символов", 1);
             } elseif (is_null($password)) {
                 throw new CustomException("Новый пароль не был введен", 1);
             } elseif (is_null($passwordConfirmation)) {
                 throw new CustomException("Подтверждение нового пароля не была введена", 1);
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

             $this->response->setMessage("Пароль был поменен успешно");

         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

}
