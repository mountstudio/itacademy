<?php

namespace App\Controllers\Front;

use \Core\View;
use Core\CustomException;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Contact extends Base
{

    /**
     * Show the index page
     *
     * @return void
     */


     public function indexAction()
     {
         try {
             $this->breadcrumbs->appendItem('Контакты', '/contacts');
             $this->breadcrumbs->setOnRightSide(true);

             View::renderTemplate('Front/contact.html', $this->data);

         } catch (CustomException $e) {
             if ($e->getCode() == 404){
                 http_response_code(404);
                 View::renderTemplate('Front/404.html');
             } else {
                 $this->data = array_merge(  $this->data,
                                             $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
                   );
                 View::renderTemplate('Front/messagePage.html', $this->data);
             }
         }
     }

}
