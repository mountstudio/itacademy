<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\Group;
use \Models\GroupQuery;


use \Models\StaticPageQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class StaticPage extends Base
{
     public function indexAction()
     {
         try {
             $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');

             View::renderTemplate('Admin/StaticPage/all.html', $this->data);
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
             $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');
             $staticPageId = (isset($this->params['id']) ? $this->params['id'] : null);
             if (is_null($staticPageId) || intval($staticPageId) == 0){
                 throw new CustomException("ID статической страницы не был указан", 403);
             }

             $staticPage = StaticPageQuery::create()->findPk(intval($staticPageId));
             if (is_null($staticPage)){
                 throw new CustomException("Статическая страница не найдена", 404);
             }
             
             $this->data = array_merge( $this->data,
                                        array(  'staticPage' => $staticPage
                                                )
                                        );
             View::renderTemplate('Admin/StaticPage/edit.html', $this->data);
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
             $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');
             View::renderTemplate('Admin/StaticPage/add.html', $this->data);
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
