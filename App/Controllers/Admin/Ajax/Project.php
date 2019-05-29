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


use \Models\ConfigQuery;
use Models\CourseStatusQuery;
use Models\Instructor;
use Models\ProjectQuery;
use \Models\UserQuery;

use \Models\CourseQuery;
use \Models\GroupQuery;


use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Project extends Base
{

     public function listAction()
     {
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('PROJECT_ADMIN');
             $paginator = $this->helper->paginator();
             $projects = ProjectQuery::create()->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);


             $this->response->setPaginationDetails($projects);
             $projects_data = array();
             foreach ($projects as $project) {

                 $projects_data[] = array(
                     'id' => $project->getId(),
                     'name' => $project->getName(),
                     'description' => $project->getDescription(),
                     'context' => $project->getContext(),
                     'logo' => $project->getLogo(),
                     'cover' => $project->getCover(),
                     'createdAt' => $project->getCreatedAt(),
                     'updatedAt' => $project->getUpdatedAt()
                 );
             }
             $this->response->setData($projects_data);
             $this->response->setStatus(JsonResponse::SUCCESS);
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }

    public function deleteAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('PROJECT_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $project = ProjectQuery::create()->findPK(intval($id));
            if (is_null($project)){
                throw new CustomException("Проект не найден", 1);
            }

            $project->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Проект успешно удален');
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/projects/all');
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
            $this->helper->shouldHavePrivilege('PROJECT_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $project = ProjectQuery::create()->findPK(intval($id));
            if (is_null($project)){
                throw new CustomException("Проект не найден", 1);
            }

            if (!is_null($project->getLogoName())){
                Image::deleteProjectLogo($project->getLogoName());
                $this->response->setStatus(JsonResponse::SUCCESS);
                $project->setLogoName(null);

                $this->response->setData(array('logo' => $project->getLogo()));
                $this->response->setMessage("Логотип был успешно удален", 0);
            } else {
                $this->response->setStatus(JsonResponse::FAIL);
                $this->response->setMessage("Проект не имеет логотип", 0);
            }

            $project->save();

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function deleteCoverAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('PROJECT_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $project = ProjectQuery::create()->findPK(intval($id));
            if (is_null($project)){
                throw new CustomException("Проект не найден", 1);
            }

            if (!is_null($project->getCoverName())){
                Image::deleteProjectCover($project->getCoverName());
                $this->response->setStatus(JsonResponse::SUCCESS);
                $project->setCoverName(null);

                $this->response->setData(array('cover' => $project->getCover()));
                $this->response->setMessage("Обложка была успешно удалена", 0);
            } else {
                $this->response->setStatus(JsonResponse::FAIL);
                $this->response->setMessage("Проект не имеет логотип", 0);
            }

            $project->save();

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function editAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $context = (isset($_POST['context']) ? $_POST['context'] : null);
        $metaDescription = (isset($_POST['metaDescription']) ? $_POST['metaDescription'] : null);
        $metaKeywords = (isset($_POST['metaKeywords']) ? $_POST['metaKeywords'] : null);
        try {
            $this->helper->shouldHavePrivilege('PROJECT_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $project = ProjectQuery::create()->findPK(intval($id));
            if (is_null($project)){
                throw new CustomException("Проект не найден", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            if (trim($name) != $project->getName()){
                $projectByName = ProjectQuery::create()->findOneByName(trim($name));
                if (!is_null($projectByName)) {
                    throw new CustomException("Такое название проекта существует", 1);
                }
            }

            if (is_null($context)){
                throw new CustomException("Контекст не был указан", 1);
            }

            if (is_null($description) ){
                throw new CustomException("Описание не было указано", 1);
            }


            if (is_null($metaDescription) || empty(trim($metaDescription)) ){
                $project->setMetaDescription(null);
            } else {
                $project->setMetaDescription(trim($metaDescription));
            }

            if (is_null($metaKeywords) || empty(trim($metaKeywords)) ){
                $project->setMetaKeywords(null);
            } else {
                $project->setMetaKeywords(trim($metaKeywords));
            }

            $project->setName(trim($name));
            $project->setDescription(trim($description));
            $project->setContext(trim($context));

            if(isset($_FILES["logo"])) {
                $project->setLogo($_FILES["logo"]);
            }

            if(isset($_FILES["cover"])) {
                $project->setCover($_FILES["cover"]);
            }

            $project->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Проект успешно сохранен");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }




    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $context = (isset($_POST['context']) ? $_POST['context'] : null);
        $metaDescription = (isset($_POST['metaDescription']) ? $_POST['metaDescription'] : null);
        $metaKeywords = (isset($_POST['metaKeywords']) ? $_POST['metaKeywords'] : null);
        try {
            $this->helper->shouldHavePrivilege('PROJECT_ADMIN');

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            $projectByName = ProjectQuery::create()->findOneByName(trim($name));
            if (!is_null($projectByName)) {
                throw new CustomException("Такое название проекта существует", 1);
            }

            if (is_null($context)){
                throw new CustomException("Контекст не был указан", 1);
            }

            if (is_null($description) ){
                throw new CustomException("Описание не было указано", 1);
            }


            $project = new \Models\Project();

            if (is_null($metaDescription) || empty(trim($metaDescription)) ){
                $project->setMetaDescription(null);
            } else {
                $project->setMetaDescription(trim($metaDescription));
            }

            if (is_null($metaKeywords) || empty(trim($metaKeywords)) ){
                $project->setMetaKeywords(null);
            } else {
                $project->setMetaKeywords(trim($metaKeywords));
            }

            $project->setName(trim($name));
            $project->setDescription(trim($description));
            $project->setContext(trim($context));

            if(isset($_FILES["logo"])) {
                $project->setLogo($_FILES["logo"]);
            }

            if(isset($_FILES["cover"])) {
                $project->setCover($_FILES["cover"]);
            }

            $project->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Проект успешно создан");
            $this->response->setRedirect('/admin/projects/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
