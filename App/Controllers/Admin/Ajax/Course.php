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

use \Models\CourseQuery;


/**
 * Home controller
 *
 * PHP version 7.0
 */
class Course extends Base
{

     public function listAction()
     {
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('COURSE_ADMIN');
             $paginator = $this->helper->paginator();
             $courses = CourseQuery::create()->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
             $this->response->setPaginationDetails($courses);
             $courses_data = array();
             foreach ($courses as $course) {
                 $courses_data[] = array(
                     'id' => $course->getId(),
                     'name' => $course->getName(),
                     'title' => $course->getTitle(),
                     'context' => $course->getContext(),
                     'notes' => $course->getNotes(),
                     'uses' => $course->getUses(),
                     'logo' => $course->getLogo(),
                     'createdAt' => $course->getCreatedAt(),
                     'updatedAt' => $course->getUpdatedAt()
                 );
             }
             $this->response->setData($courses_data);
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
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $course = CourseQuery::create()->findPK(intval($id));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 1);
            }

            $course->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Курс успешно удален');
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/courses/all');
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
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $course = CourseQuery::create()->findPK(intval($id));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 1);
            }

            if (!is_null($course->getLogoName())){
                Image::deleteCourseLogo($course->getLogoName());
                $this->response->setStatus(JsonResponse::SUCCESS);
                $course->setLogoName(null);

                $this->response->setData(array('logo' => $course->getLogo()));
                $this->response->setMessage("Логотип успешно удален", 0);
            } else {
                $this->response->setStatus(JsonResponse::FAIL);
                $this->response->setMessage("Курс не имеет логотип", 0);
            }

            $course->save();

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function deleteCoverAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $course = CourseQuery::create()->findPK(intval($id));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 1);
            }

            if (!is_null($course->getCoverName())){
                Image::deleteCourseCover($course->getCoverName());
                $this->response->setStatus(JsonResponse::SUCCESS);
                $course->setCoverName(null);

                $this->response->setData(array('cover' => $course->getCover()));
                $this->response->setMessage("Обложка успешно удалена", 0);
            } else {
                $this->response->setStatus(JsonResponse::FAIL);
                $this->response->setMessage("Курс не имеет логотип", 0);
            }

            $course->save();

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
        $title = (isset($_POST['title']) ? $_POST['title'] : null);
        $context = (isset($_POST['context']) ? $_POST['context'] : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);
        $metaDescription = (isset($_POST['metaDescription']) ? $_POST['metaDescription'] : null);
        $metaKeywords = (isset($_POST['metaKeywords']) ? $_POST['metaKeywords'] : null);
        $useNotes = (isset($_POST['useNotes']) ? $_POST['useNotes'] : null);
        $uses = (isset($_POST['uses']) ? $_POST['uses'] : null);

        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID курса не был указан", 1);
            }

            $course = CourseQuery::create()->findPK(intval($id));

            if (is_null($course)){
                throw new CustomException("Курс не найден", 1);
            }

            if (is_null($title) || empty(trim($title)) ){
                throw new CustomException("Заголовок не был введен", 1);
            }

            if (trim($name) != $course->getName()){
                $courseByName = CourseQuery::create()->findOneByName(trim($name));
                if (!is_null($courseByName)) {
                    throw new CustomException("Такое название курса существует", 1);
                }
            }

            if (is_null($context)){
                throw new CustomException("Контекст не был указан", 1);
            }

            if (is_null($description) ){
                throw new CustomException("Описание не было указано", 1);
            }

            if (is_null($notes)){
                throw new CustomException("Примечание курса не было введено", 1);
            }

            if (is_null($useNotes)){
                throw new CustomException("Примечание применений не было введено", 1);
            }



            if (!is_array($uses)){
                throw new CustomException("Применении не были введены или тип применений не массив", 1);
            }

            if (is_null($metaDescription) || empty(trim($metaDescription)) ){
                $course->setMetaDescription(null);
            } else {
                $course->setMetaDescription(trim($metaDescription));
            }

            if (is_null($metaKeywords) || empty(trim($metaKeywords)) ){
                $course->setMetaKeywords(null);
            } else {
                $course->setMetaKeywords(trim($metaKeywords));
            }

            $course->setName(trim($name));
            $course->setTitle(trim($title));
            $course->setDescription(trim($description));
            $course->setContext(trim($context));
            $course->setNotes(trim($notes));
            $course->setUses($uses);

            $course->setUseNotes(trim($useNotes));


            if(isset($_FILES["logo"])) {
                $course->setLogo($_FILES["logo"]);
            }

            if(isset($_FILES["cover"])) {
                $course->setCover($_FILES["cover"]);
            }

            $course->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Курс успешно сохранен");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }




    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $title = (isset($_POST['title']) ? $_POST['title'] : null);
        $context = (isset($_POST['context']) ? $_POST['context'] : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);
        $uses = (isset($_POST['uses']) ? $_POST['uses'] : null);
        $useNotes = (isset($_POST['useNotes']) ? $_POST['useNotes'] : null);
        $metaDescription = (isset($_POST['metaDescription']) ? $_POST['metaDescription'] : null);
        $metaKeywords = (isset($_POST['metaKeywords']) ? $_POST['metaKeywords'] : null);
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($title) || empty(trim($title)) ){
                throw new CustomException("Заголовок не был введен", 1);
            }

            $courseByName = CourseQuery::create()->findOneByName(trim($name));
            if (!is_null($courseByName)) {
                throw new CustomException("Такое название курса существует", 1);
            }

            if (is_null($context)){
                throw new CustomException("Контекст не был указан", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание не было указано", 1);
            }

            if (is_null($notes)){
                throw new CustomException("Примечание курса не было введено", 1);
            }

            if (is_null($useNotes)){
                throw new CustomException("Примечание применений не было введено", 1);
            }

            if (!is_array($uses)){
                throw new CustomException("Применении не были введены или тип применений не массив", 1);
            }



            $course = new \Models\Course();

            if (is_null($metaDescription) || empty(trim($metaDescription)) ){
                $course->setMetaDescription(null);
            } else {
                $course->setMetaDescription(trim($metaDescription));
            }

            if (is_null($metaKeywords) || empty(trim($metaKeywords)) ){
                $course->setMetaKeywords(null);
            } else {
                $course->setMetaKeywords(trim($metaKeywords));
            }

            $course->setName(trim($name));
            $course->setTitle(trim($title));
            $course->setDescription(trim($description));
            $course->setContext(trim($context));
            $course->setNotes(trim($notes));
            $course->setUses($uses);
            $course->setUseNotes(trim($useNotes));

            if(isset($_FILES["logo"])) {
                $course->setLogo($_FILES["logo"]);
            }

            if(isset($_FILES["cover"])) {
                $course->setCover($_FILES["cover"]);
            }

            $course->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Курс успешно создан");
            $this->response->setRedirect('/admin/courses/' . $course->getId() . '/edit');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
