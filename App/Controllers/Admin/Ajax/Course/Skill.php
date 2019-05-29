<?php

namespace App\Controllers\Admin\Ajax\Course;

use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;

use Models\CourseQuery;
use Models\CourseSkill;
use Models\CourseSkillQuery;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\Product;
use \Models\ProductQuery;

use \Models\StaticPageQuery;

use \Models\VacancySalaryQuery;
use \Models\VacancyQuery;
use \Models\ConfigQuery;

use \Models\MassTypeQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Skill extends \App\Controllers\Admin\Ajax\Base
{
    public function listAction()
    {
        $courseId = (isset($_POST['courseId']) ? $_POST['courseId'] : null);
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');
            $paginator = $this->helper->paginator();

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 1);
            }

            $courseSkills = CourseSkillQuery::create()->filterByCurrentCourseSkillCourse($course)->orderBySortableRank()->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
            $this->response->setPaginationDetails($courseSkills);
            $courseSkillData = array();
            foreach ($courseSkills as $courseSkill) {
                $courseSkillData[] = array(
                    'id' => $courseSkill->getId(),
                    'name' => $courseSkill->getName(),
                    'description' => $courseSkill->getDescription(),
                    'logo' => $courseSkill->getLogo(),
                    'actions' => array(
                        'isFirst' => $courseSkill->isFirst(),
                        'isLast' => $courseSkill->isLast()
                    ),
                );
            }
            $this->response->setData($courseSkillData);
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
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $courseSkill = CourseSkillQuery::create()->findPK(intval($id));
            if (is_null($courseSkill)){
                throw new CustomException("Навык курса не найден", 1);
            }

            $courseId = $courseSkill->getCurrentCourseSkillCourse()->getId();
            $courseSkill->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Навык курса успешно удален");
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/courses/' . $courseId . '/skills');
            }
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

        try {
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $courseSkill = CourseSkillQuery::create()->findPK(intval($id));
            if (is_null($courseSkill)){
                throw new CustomException("Навык курса не найден", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание не было введено", 1);
            }

            if(isset($_FILES["logo"])) {
                $courseSkill->setLogo($_FILES["logo"]);
            }


            $courseSkill->setName(trim($name));
            $courseSkill->setDescription(trim($description));

            $courseSkill->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Навык курса успешно сохранено");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function updateAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $action = (isset($_POST['action']) ? $_POST['action'] : null);
        try {
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $courseSkill = CourseSkillQuery::create()->findPK(intval($id));
            if (is_null($courseSkill)){
                throw new CustomException("Навык курса не найден", 1);
            }


            switch ($action){
                case 'toTop':
                    $courseSkill->moveUp();
                    $this->response->setMessage("Навык курса успешно перемещен вверх", 0);
                    break;
                case 'toBottom':
                    $courseSkill->moveDown();
                    $this->response->setMessage("Навык курса успешно перемещен вниз", 0);
                    break;
                default:
                    throw new CustomException("Неправильное действие", 1);
            }
            $courseSkill->save();
            $this->response->setStatus(JsonResponse::SUCCESS);
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
                throw new CustomException("Id не был указан", 1);
            }

            $courseSkill = CourseSkillQuery::create()->findPK(intval($id));
            if (is_null($courseSkill)){
                throw new CustomException("Навык курса не найден", 1);
            }

            if (!is_null($courseSkill->getLogoName())){
                Image::deleteCourseSkillLogo($courseSkill->getLogoName());
                $this->response->setStatus(JsonResponse::SUCCESS);
                $courseSkill->setLogoName(null);

                $this->response->setData(array('logo' => $courseSkill->getLogo()));
                $this->response->setMessage("Логотип успешно удален", 0);
            } else {
                $this->response->setStatus(JsonResponse::FAIL);
                $this->response->setMessage("Навык курса не имеет логотип", 0);
            }

            $courseSkill->save();

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function addAction()
    {
        $courseId = (isset($_POST['courseId']) ? $_POST['courseId'] : null);
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        try {
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');

            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("Id курса не был указан", 1);
            }

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание не было введено", 1);
            }

            $courseSkillByName = CourseSkillQuery::create()->filterByCurrentCourseSkillCourse($course)->findOneByName(trim($name));
            if (!is_null($courseSkillByName)) {
                throw new CustomException("Название такого курса существует", 1);
            }


            $courseSkill = new \Models\CourseSkill();
            $courseSkill->setName(trim($name));
            $courseSkill->setDescription(trim($description));
            $courseSkill->setCurrentCourseSkillCourse($course);
            if(isset($_FILES["logo"])) {
                $courseSkill->setLogo($_FILES["logo"]);
            }

            $courseSkill->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Навык курса успешно создан");
            $this->response->setRedirect('/admin/courses/' . $course->getId() . '/skills');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
