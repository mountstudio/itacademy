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

use \Models\Attachment;
use \Models\AttachmentQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Dashboard extends Base
{
     public function indexAction()
     {
         View::renderTemplate('Admin/Dashboard/index.html', $this->data);
     }

     public function calendarAction()
     {
         View::renderTemplate('Admin/Calendar/index.html', $this->data);
     }

     public function testAction()
     {

         $attachments = AttachmentQuery::create()->filterByCurrentAttachmentAuthor($this->helper->getCurrentUser())->find();
         echo '<pre>';
         foreach ($attachments as $attachment) {
             echo $attachment->getId();
             echo ',';
         }
         echo '</pre>';
     }
}
