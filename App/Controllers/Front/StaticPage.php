<?php

namespace App\Controllers\Front;

use \Core\View;
use \Core\Functions;
use Core\CustomException;
use Models\StaticPageQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class StaticPage extends Base
{

    /**
     * Show the index page
     *
     * @return void
     */

    public function indexAction()
    {
        try {
            $page = (isset($this->params['page']) ? $this->params['page'] : 1);
            $page = intval($page);

            $listTypeReq = isset($_GET['listType']) ? $_GET['listType'] : null;
            $listType = Functions::getCookie('listType');
            if (!is_null($listTypeReq) && (trim($listTypeReq) == 'grid' || trim($listTypeReq) == 'list')){
                Functions::setCookie('listType', $listTypeReq);
                $listType = $listTypeReq;
            } elseif (is_null($listType)){
                Functions::setCookie('listType', 'grid');
                $listType = 'grid';
            }


            if ($page == 0){
                throw new CustomException("Страница не найдена", 404);
            }

            $maxPerPage = 10;
            $staticPages = StaticPageQuery::create()->orderByCreatedAt('desc')->paginate($page, $maxPerPage);
            if ($page != $staticPages->getPage()){
                throw new CustomException("Страница не найдена", 404);
            }

            $this->breadcrumbs->appendItem('Статьи', '/pages');
            $this->breadcrumbs->setOnRightSide(false);

            $this->data = array_merge( $this->data,
                array(
                    'staticPages' => $staticPages,
                    'listType' => $listType
                )
            );

            View::renderTemplate('Front/staticPages.html', $this->data);

        } catch (CustomException $e) {
            $this->data = array_merge(  $this->data,
                $this->helper->buildMessagePage($e->getCode(), $e->getMessage())
            );
            View::renderTemplate('Front/messagePage.html', $this->data);
        }
    }

     public function singleAction()
     {
         $staticPageAlt = (isset($this->params['alturl']) ? $this->params['alturl'] : null);
         try {

             $staticPage = \Models\StaticPageQuery::create()->filterByAvailable(true)->findOneByAltUrl(trim($staticPageAlt));
             if (is_null($staticPage)){
                 throw new CustomException("Страница не найдена", 404);
             }
              $this->breadcrumbs->appendItem('Статьи', '/pages');
             $this->breadcrumbs->appendItem($staticPage->getTitle(), '/pages/' . $staticPage->getAltUrl());
             $this->breadcrumbs->setOnRightSide(true);

             $this->data = array_merge( $this->data,
                                        array(
                                                'staticPage' => $staticPage
                                        ));

             View::renderTemplate('Front/staticPage.html', $this->data);

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
