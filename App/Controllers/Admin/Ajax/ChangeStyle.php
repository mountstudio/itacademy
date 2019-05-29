<?php

namespace App\Controllers\Admin\Ajax;

use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use App\Config;

use \Models\AdminStyle;
use \Models\AdminStyleQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class ChangeStyle extends Base
{
     public function indexAction()
     {
         $type = (isset($_POST['type']) ? $_POST['type'] : null);

         if ($type == 'custom_style'){
             $value = (isset($_POST['value']) ? $_POST['value'] : "white");
         } else $value = (isset($_POST['value']) ? (($_POST['value'] == 'true') ? true : false) : null);

         try {
             $this->helper->shouldBeLoggedIn();
             $currentUser = $this->helper->getCurrentUser();
             if (is_null($currentUser)){
                 throw new CustomException("Ошибка обработки запроса", 1);

             }
             $adminStyle = $currentUser->getCurrentAdminStyle();
             if (is_null($type) || is_null($value)){
                 throw new CustomException("Тип или значение не указано", 1);
             }

             if (is_null($adminStyle)) $adminStyle = new AdminStyle();

             switch ($type){
                 case "f_header":
                     $adminStyle->setAllowFHeader($value);
                     break;
                 case "f_sidebar":
                     $adminStyle->setAllowFSidebar($value);
                     break;
                 case "h_bar":
                     $adminStyle->setAllowHBar($value);
                     break;
                 case "t_sidebar":
                     $adminStyle->setAllowTSidebar($value);
                     break;
                 case "c_menu":
                     $adminStyle->setAllowCMenu($value);
                     break;
                 case "h_menu":
                     $adminStyle->setAllowHMenu($value);
                     break;
                 case "b_layout":
                     $adminStyle->setAllowBLayout($value);
                     break;
                 case "custom_style":
                     $adminStyle->setCustomStyle($value);
                     break;
                 case "reset":
                     $adminStyle = new AdminStyle();
                     break;
                 default:

                     break;
             }

             $currentUser->setCurrentAdminStyle($adminStyle);
             $currentUser->save();

             $this->response->setStatus(JsonResponse::SUCCESS);
         } catch (CustomException $e){
             $this->response->setException($e);
         }
         $this->response->show();
     }
}
