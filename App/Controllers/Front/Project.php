<?php

namespace App\Controllers\Front;

use Core\Functions;
use \Core\View;
use Core\CustomException;
use Models\CourseQuery;
use Models\ProjectQuery;
use Models\VacancyQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Project extends Base
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
             $projects = ProjectQuery::create()->orderByName('asc')->paginate($page, $maxPerPage);
             if ($page != $projects->getPage()){
                 throw new CustomException("Страница не найдена", 404);
             }

             $this->breadcrumbs->appendItem('Проекты', '/projects');
             $this->breadcrumbs->setOnRightSide(false);

             $this->data = array_merge( $this->data,
                 array(
                     'projects' => $projects,
                     'listType' => $listType
                 )
             );

             View::renderTemplate('Front/projects.html', $this->data);

         } catch (CustomException $e) {
             $this->data = array_merge(  $this->data,
                 $this->helper->buildMessagePage($e->getCode(), $e->getMessage())
             );
             View::renderTemplate('Front/messagePage.html', $this->data);
         }
     }

    public function singleAction()
    {
        $altUrl = (isset($this->params['alturl']) ? $this->params['alturl'] : null);
        try {

            $project = ProjectQuery::create()->findOneByAltUrl(trim($altUrl));
            if (is_null($project)){
                throw new CustomException("Проект не найден", 404);
            }


            $this->breadcrumbs->appendItem('Проекты', '/projects');
            $this->breadcrumbs->appendItem($project->getName(), '/projects/' . $project->getAltUrl());
            $this->breadcrumbs->setOnRightSide(false);

            $this->data = array_merge( $this->data,
                array(
                    'project' => $project
                ));

            View::renderTemplate('Front/project.html', $this->data);

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
