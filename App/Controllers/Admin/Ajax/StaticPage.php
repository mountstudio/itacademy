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

use \Models\StaticPageQuery;


use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class StaticPage extends Base
{

     public function listAction()
     {
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');
             $paginator = $this->helper->paginator();
             $staticPages = StaticPageQuery::create()->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

             $this->response->setPaginationDetails($staticPages);
             $staticPagesData = array();
             foreach ($staticPages as $staticPage) {
                 $staticPagesData[] = array('id' => $staticPage->getId(),
                                        'title' => $staticPage->getTitle(),
                                        'altUrl' => $staticPage->getAltUrl(),
                                        'isAvailable' => $staticPage->getAvailable(),
                                        'notes' => $staticPage->getNotes(),
                                        'createdAt' => $staticPage->getCreatedAt(),
                                        'updatedAt' => $staticPage->getUpdatedAt()
                                        );
             }
             $this->response->setData($staticPagesData);
             $this->response->setStatus(JsonResponse::SUCCESS);
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function deleteAction()
     {
         $staticPageId = (isset($_POST['id']) ? $_POST['id'] : null);
         try {
             $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');

             if (is_null($staticPageId) || intval($staticPageId) == 0){
                 throw new CustomException("ID статической страницы не был указан", 1);
             }

             $staticPage = StaticPageQuery::create()->findPK(intval($staticPageId));
             if (is_null($staticPage)){
                 throw new CustomException("Статическая страница не найдена", 1);
             }

             $staticPage->delete();
             $this->response->setStatus(JsonResponse::SUCCESS);
             $this->response->setMessage('Статическая страница была успешно удалена');
             $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
             if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                 $this->response->setRedirect('/admin/staticPages/all');
             }
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }


     public function deleteLogoAction()
     {
         $id = (isset($_POST['id']) ? $_POST['id'] : null);
         try {
             $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');

             if (is_null($id) || intval($id) == 0){
                 throw new CustomException("ID не был указан", 1);
             }

             $staticPage = StaticPageQuery::create()->findPK(intval($id));
             if (is_null($staticPage)){
                 throw new CustomException("Статическая страница не найдена", 1);
             }

             if (!is_null($staticPage->getLogoName())){
                 Image::deleteStaticPageLogo($staticPage->getLogoName());
                 $this->response->setStatus(JsonResponse::SUCCESS);
                 $staticPage->setLogoName(null);

                 $this->response->setData(array('logo' => $staticPage->getLogo()));
                 $this->response->setMessage("Logo успешно удален", 0);
             } else {
                 $this->response->setStatus(JsonResponse::FAIL);
                 $this->response->setMessage("Статическая страница не имеет logo", 0);
             }

             $staticPage->save();

         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }


    public function deleteCoverAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $staticPage = StaticPageQuery::create()->findPK(intval($id));
            if (is_null($staticPage)){
                throw new CustomException("Статическая страница не найдена", 1);
            }

            if (!is_null($staticPage->getCoverName())){
                Image::deleteStaticPageCover($staticPage->getCoverName());
                $this->response->setStatus(JsonResponse::SUCCESS);
                $staticPage->setCoverName(null);

                $this->response->setData(array('cover' => $staticPage->getCover()));
                $this->response->setMessage("Обложка успешно удалена", 0);
            } else {
                $this->response->setStatus(JsonResponse::FAIL);
                $this->response->setMessage("Статическая страница не имеет обложку", 0);
            }

            $staticPage->save();

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }


     public function editAction()
     {
         $id = (isset($_POST['id']) ? $_POST['id'] : null);
         $title = (isset($_POST['title']) ? $_POST['title'] : null);
         $content = (isset($_POST['content']) ? $_POST['content'] : null);
         $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);
         $context = (isset($_POST['context']) ? $_POST['context'] : null);

         $metaDescription = (isset($_POST['metaDescription']) ? $_POST['metaDescription'] : null);
         $metaKeywords = (isset($_POST['metaKeywords']) ? $_POST['metaKeywords'] : null);

         $isAvailable = (isset($_POST['isAvailable']) ? (($_POST['isAvailable'] == 'true') ? true : false) : null);

         try {
             $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');

              if (is_null($id) || intval($id) == 0){
                  throw new CustomException("ID статической страницы не был указан", 1);
              }

              $staticPage = StaticPageQuery::create()->findPK(intval($id));
              if (is_null($staticPage)){
                  throw new CustomException("Статическая страница не найдена", 1);
              }

              if (is_null($title) || empty(trim($title)) ){
                  throw new CustomException("Заголовок не был введен", 1);
              }

              if (trim($title) != $staticPage->getTitle()){
                  $staticPageByTitle = StaticPageQuery::create()->findOneByTitle(trim($title));
                  if (!is_null($staticPageByTitle)) {
                      throw new CustomException("Такой заголовок статической страницы существует", 1);
                  }
              }

              if (is_null($isAvailable)){
                  throw new CustomException("Чекбокс доступ на статическую страницу не был указан", 1);
              }

              if (is_null($notes)){
                  throw new CustomException("Примечании статической страницы не был введен", 1);
              }

              if (is_null($content)){
                  throw new CustomException("Содержание статической страницы не было введено", 1);
              }

             if (is_null($metaDescription) || empty(trim($metaDescription)) ){
                 $staticPage->setMetaDescription(null);
             } else {
                 $staticPage->setMetaDescription(trim($metaDescription));
             }

             if (is_null($metaKeywords) || empty(trim($metaKeywords)) ){
                 $staticPage->setMetaKeywords(null);
             } else {
                 $staticPage->setMetaKeywords(trim($metaKeywords));
             }



              $staticPage->setTitle(trim($title));
              $staticPage->setAvailable($isAvailable);
              $staticPage->setContent($content);
             $staticPage->setContext($context);
              if (!empty(trim($notes))){
                  $staticPage->setNotes(trim($notes));
              }
             if(isset($_FILES["logo"])) {
                 $staticPage->setLogo($_FILES["logo"]);
             }

             if(isset($_FILES["cover"])) {
                 $staticPage->setCover($_FILES["cover"]);
             }
              $staticPage->save();

              $this->response->setStatus(JsonResponse::SUCCESS);
              $this->response->setMessage("Статическая страница сохранена успешо");
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

     public function addAction()
     {
         $title = (isset($_POST['title']) ? $_POST['title'] : null);
         $content = (isset($_POST['content']) ? $_POST['content'] : null);
         $context = (isset($_POST['context']) ? $_POST['context'] : null);
         $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);
         $metaDescription = (isset($_POST['metaDescription']) ? $_POST['metaDescription'] : null);
         $metaKeywords = (isset($_POST['metaKeywords']) ? $_POST['metaKeywords'] : null);

         $isAvailable = (isset($_POST['isAvailable']) ? (($_POST['isAvailable'] == 'true') ? true : false) : null);

         try {
             $this->helper->shouldHavePrivilege('STATIC_PAGE_ADMIN');

             if (is_null($title) || empty(trim($title)) ){
                 throw new CustomException("Заголовок не был введен", 1);
             }

             $staticPageByTitle = StaticPageQuery::create()->findOneByTitle(trim($title));
             if (!is_null($staticPageByTitle)) {
                 throw new CustomException("Такой заголовок статической страницы существует", 1);
             }


             $staticPage = new \Models\StaticPage();



             if (is_null($isAvailable)){
                 throw new CustomException("Чекбокс доступ на статическую страницу на сайте не был введен", 1);
             }


             if (is_null($notes)){
                 throw new CustomException("Примечании статической страницы не был введен", 1);
             }

             if (is_null($content)){
                 throw new CustomException("Содержание статической страницы не был введен", 1);
             }


             if (is_null($metaDescription) || empty(trim($metaDescription)) ){
                 $staticPage->setMetaDescription(null);
             } else {
                 $staticPage->setMetaDescription(trim($metaDescription));
             }

             if (is_null($metaKeywords) || empty(trim($metaKeywords)) ){
                 $staticPage->setMetaKeywords(null);
             } else {
                 $staticPage->setMetaKeywords(trim($metaKeywords));
             }

             $staticPage->setTitle(trim($title));
             $staticPage->setAvailable($isAvailable);
             $staticPage->setContext($context);
             $staticPage->setContent($content);

             if (!empty(trim($notes))){
                 $staticPage->setNotes(trim($notes));
             }

             if(isset($_FILES["logo"])) {
                 $staticPage->setLogo($_FILES["logo"]);
             }


             if(isset($_FILES["cover"])) {
                 $staticPage->setCover($_FILES["cover"]);
             }

             $staticPage->save();

              $this->response->setStatus(JsonResponse::SUCCESS);
              $this->response->setMessage("Статическая страница создана успешо");
              $this->response->setRedirect('/admin/staticPages/all');
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

}
