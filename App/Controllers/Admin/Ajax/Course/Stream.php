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

use Models\BranchQuery;
use Models\CourseQuery;
use Models\CourseSkill;
use Models\CourseSkillQuery;
use Models\CourseStatusQuery;
use Models\CourseStream;
use Models\CourseStreamQuery;
use Models\CourseStreamStatusQuery;
use Models\CurrencyQuery;
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
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Stream extends \App\Controllers\Admin\Ajax\Base
{
    public function listAction()
    {
        $courseId = (isset($_POST['courseId']) ? $_POST['courseId'] : null);
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');
            $paginator = $this->helper->paginator();

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 1);
            }

            $getDefaultOnRecruitment = ConfigQuery::create()->findOneByKey('course_stream_recruitment_status');

            $user = $this->helper->getCurrentUser();
            $courseStreams = CourseStreamQuery::create()->filterByCurrentCourseCourseStream($course)->orderByCreatedAt(Criteria::DESC)->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
            $this->response->setPaginationDetails($courseStreams);
            $courseStreamData = array();
            foreach ($courseStreams as $courseStream) {
                $cost = $user->convertCurrency($courseStream->getCost(), $courseStream->getCurrentCourseStreamCurrency());
                $course = $courseStream->getCurrentCourseCourseStream();
                $status = $courseStream->getCurrentCourseCourseStreamStatus();
                $instructor = $courseStream->getCurrentCourseStreamInstructor();
                $courseStreamData[] = array(
                    'id' => $courseStream->getId(),
                    'name' => $courseStream->getName(),
                    'notes' => $courseStream->getNotes(),
                    'cost' => array(
                        'value' => $cost['value'],
                        'currency' => array(
                            'id' => $cost['currency']->getId(),
                            'name' => $cost['currency']->getName(),
                            'isoCode' => $cost['currency']->getISOCode()
                        )
                    ),
                    'course' => array(
                        'id' => $course->getId(),
                        'name' => $course->getName(),
                        'logo' => $course->getLogo()
                    ),
                    'status' => array(
                        'id' => $status->getId(),
                        'name' => $status->getName(),
                        'onRecruitment' => intval($getDefaultOnRecruitment->getValue()) == $status->getId()
                    ),
                    'instructor' => (is_null($instructor) ? null : array(
                        'id' => $instructor->getId(),
                        'name' => $instructor->getName(),
                        'logo' => $instructor->getLogo()
                    )),
                    'place' => array(
                        'free' => 0,
                        'busy' => 0,
                        'all' => $courseStream->getNumberOfPlaces()
                    ),
                    'startsAt' => $courseStream->getStartsAt(),
                    'endsAt' => $courseStream->getEndsAt(),
                    'duration' => $courseStream->getDuration(),
                    'createdAt' => $courseStream->getCreatedAt()
                );
            }
            $this->response->setData($courseStreamData);
            $this->response->setStatus(JsonResponse::SUCCESS);
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function liteStreamListAction()
    {
        $courseId = (isset($_POST['courseId']) ? $_POST['courseId'] : null);
        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');


            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден по id " . $courseId, 1);
            }

            $courseStreams = CourseStreamQuery::create()->filterByCurrentCourseCourseStream($course)->find();


            $courseStreamData = array();
            foreach ($courseStreams as $courseStream) {
                $courseStreamData[] = array(
                    'id' => $courseStream->getId(),
                    'name' => $courseStream->getName()
                );
            }
            $this->response->setData($courseStreamData);
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
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $courseStream = CourseStreamQuery::create()->findPK(intval($id));
            if (is_null($courseStream)){
                throw new CustomException("Поток курса не найден", 1);
            }

            $courseId = $courseStream->getCurrentCourseCourseStream()->getId();
            $courseStream->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Поток курса успешно удален");
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/courses/' . $courseId . '/streams');
            }
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function editAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $showOnWebSite = (isset($_POST['showOnWebSite']) ? (($_POST['showOnWebSite'] == 'true') ? true : false) : null);
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $cost = (isset($_POST['cost']) ? $_POST['cost'] : null);
        $currencyId = (isset($_POST['currencyId']) ? $_POST['currencyId'] : null);
        $startsAt = (isset($_POST['startsAt']) ? $_POST['startsAt'] : null);
        $endsAt = (isset($_POST['endsAt']) ? $_POST['endsAt'] : null);
        $numberOfPlaces = (isset($_POST['numberOfPlaces']) ? $_POST['numberOfPlaces'] : null);
        $statusId = (isset($_POST['statusId']) ? $_POST['statusId'] : null);
        $instructorId = (isset($_POST['instructorId']) ? $_POST['instructorId'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);


        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $stream = CourseStreamQuery::create()->findPK(intval($id));
            if (is_null($stream)){
                throw new CustomException("Поток курса не найден", 1);
            }

            if (is_null($showOnWebSite)){
                throw new CustomException("Чекбокс показать на сайте не был введен", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание не было введено", 1);
            }


            if ($stream->getName() != trim($name)){
                $courseStreamByName = CourseStreamQuery::create()->findOneByName(trim($name));
                if (!is_null($courseStreamByName)) {
                    throw new CustomException("Название такого потока курса существует", 1);
                }
            }


            if (is_null($notes)){
                throw new CustomException("Примечание не было введено", 1);
            }


            if (is_null($currencyId) || intval($currencyId) == 0){
                throw new CustomException("Id валюты не был указан", 1);
            }

            $currency = CurrencyQuery::create()->findPk(intval($currencyId));
            if (is_null($currency)){
                throw new CustomException("Валюта не найдена", 1);
            }

            if (is_null($cost)){
                throw new CustomException("Стоимость потока не был указан", 1);
            }


            if (is_null($numberOfPlaces)){
                throw new CustomException("Количество мест не указан", 1);
            }

            if (intval($numberOfPlaces) <= 0){
                throw new CustomException("Количество мест должно быть больше нуля", 1);
            }

            if (is_null($statusId) || intval($statusId) == 0){
                throw new CustomException("Id состоянии потока не был указан", 1);
            }

            $status = CourseStreamStatusQuery::create()->findPk(intval($statusId));
            if (is_null($status)){
                throw new CustomException("Состояние потока курса не найдено", 1);
            }


            if (is_null($instructorId)){
                throw new CustomException("Id преподавателя не был указан", 1);
            } elseif (intval($instructorId) == 0){
                $stream->setCurrentCourseStreamInstructor(null);
            } else {
                $instructor = UserQuery::create()->findPk(intval($instructorId));
                if (is_null($instructor)){
                    throw new CustomException("Пользователь, выбранный как инструктор не найден", 1);
                }
                if (!$instructor->isInstructor()){
                    throw new CustomException("Пользователь, выбранный как инструктор не находится в роле инструктора", 1);
                }
                $stream->setCurrentCourseStreamInstructor($instructor);
            }





            if (is_null($startsAt)){
                throw new CustomException("Дата начала не найдена", 1);
            }

            if (is_null($endsAt)){
                throw new CustomException("Дата окончания не найдена", 1);
            }

            $startsAtFormat = explode('-', trim($startsAt));
            if (sizeof($startsAtFormat) != 3 || !checkdate($startsAtFormat[1], $startsAtFormat[2], $startsAtFormat[0])){
                throw new CustomException("Дата начала введена неправильно", 1);
            }

            $endsAtFormat = explode('-', trim($endsAt));
            if (sizeof($endsAtFormat) != 3 || !checkdate($endsAtFormat[1], $endsAtFormat[2], $endsAtFormat[0])){
                throw new CustomException("Дата окончания введена неправильно", 1);
            }


            $stream->setName(trim($name));
            $stream->setCost(intval($cost));
            $stream->setCurrentCourseStreamCurrency($currency);
            $stream->setShowOnWebSite($showOnWebSite);
            $stream->setStartsAt(\DateTime::createFromFormat('Y-m-d', trim($startsAt)));
            $stream->setEndsAt(\DateTime::createFromFormat('Y-m-d', trim($endsAt)));

            $stream->setNumberOfPlaces(intval($numberOfPlaces));
            $stream->setCurrentCourseCourseStreamStatus($status);


            $stream->setDescription(trim($description));
            $stream->setNotes(trim($notes));

            $stream->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Поток курса успешно сохранено");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }


    public function addAction()
    {
        $courseId = (isset($_POST['courseId']) ? $_POST['courseId'] : null);
        $showOnWebSite = (isset($_POST['showOnWebSite']) ? (($_POST['showOnWebSite'] == 'true') ? true : false) : null);

        $branchId = (isset($_POST['branchId']) ? $_POST['branchId'] : null);
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $cost = (isset($_POST['cost']) ? $_POST['cost'] : null);
        $currencyId = (isset($_POST['currencyId']) ? $_POST['currencyId'] : null);
        $startsAt = (isset($_POST['startsAt']) ? $_POST['startsAt'] : null);
        $endsAt = (isset($_POST['endsAt']) ? $_POST['endsAt'] : null);
        $numberOfPlaces = (isset($_POST['numberOfPlaces']) ? $_POST['numberOfPlaces'] : null);
        $statusId = (isset($_POST['statusId']) ? $_POST['statusId'] : null);
        $instructorId = (isset($_POST['instructorId']) ? $_POST['instructorId'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);

        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("Id курса не был указан", 1);
            }

            if (is_null($showOnWebSite)){
                throw new CustomException("Чекбокс показать на сайте не был введен", 1);
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



            $courseStreamByName = CourseStreamQuery::create()->findOneByName(trim($name));
            if (!is_null($courseStreamByName)) {
                throw new CustomException("Название такого потока курса существует", 1);
            }

            if (is_null($notes)){
                throw new CustomException("Примечание не было введено", 1);
            }


            if (is_null($branchId) || intval($branchId) == 0){
                throw new CustomException("Id филиала не был указан", 1);
            }

            $branch = BranchQuery::create()->findPk(intval($branchId));
            if (is_null($branch)){
                throw new CustomException("Филиал не найден", 1);
            }



            if (is_null($currencyId) || intval($currencyId) == 0){
                throw new CustomException("Id валюты не был указан", 1);
            }

            $currency = CurrencyQuery::create()->findPk(intval($currencyId));
            if (is_null($currency)){
                throw new CustomException("Валюта не найдена", 1);
            }

            if (is_null($cost)){
                throw new CustomException("Стоимость потока не был указан", 1);
            }


            if (is_null($numberOfPlaces)){
                throw new CustomException("Количество мест не указан", 1);
            }

            if (intval($numberOfPlaces) <= 0){
                throw new CustomException("Количество мест должно быть больше нуля", 1);
            }


            if (is_null($statusId) || intval($statusId) == 0){
                throw new CustomException("Id состоянии потока не был указан", 1);
            }

            $status = CourseStreamStatusQuery::create()->findPk(intval($statusId));
            if (is_null($status)){
                throw new CustomException("Состояние потока курса не найдено", 1);
            }


            $stream = new CourseStream();
            if (is_null($instructorId)){
                throw new CustomException("Id преподавателя не был указан", 1);
            } elseif (intval($instructorId) == 0){
                $stream->setCurrentCourseStreamInstructor(null);
            } else {
                $instructor = UserQuery::create()->findPk(intval($instructorId));
                if (is_null($instructor)){
                    throw new CustomException("Пользователь, выбранный как инструктор не найден", 1);
                }
                if (!$instructor->isInstructor()){
                    throw new CustomException("Пользователь, выбранный как инструктор не находится в роле инструктора", 1);
                }
                $stream->setCurrentCourseStreamInstructor($instructor);
            }



            if (is_null($startsAt)){
                throw new CustomException("Дата начала не найдена", 1);
            }

            if (is_null($endsAt)){
                throw new CustomException("Дата окончания не найдена", 1);
            }

            $startsAtFormat = explode('-', trim($startsAt));
            if (sizeof($startsAtFormat) != 3 || !checkdate(intval($startsAtFormat[1]), intval($startsAtFormat[2]), intval($startsAtFormat[0]))){
                throw new CustomException("Дата начала введена неправильно", 1);
            }

            $endsAtFormat = explode('-', trim($endsAt));
            if (sizeof($endsAtFormat) != 3 || !checkdate(intval($endsAtFormat[1]), intval($endsAtFormat[2]), intval($endsAtFormat[0]))){
                throw new CustomException("Дата окончания введена неправильно", 1);
            }


            $stream->setName(trim($name));
            $stream->setShowOnWebSite($showOnWebSite);
            $stream->setCurrentCourseCourseStream($course);
            $stream->setCurrentCourseStreamBranch($branch);
            $stream->setCost(intval($cost));
            $stream->setCurrentCourseStreamCurrency($currency);

            $stream->setStartsAt(\DateTime::createFromFormat('Y-m-d', trim($startsAt)));
            $stream->setEndsAt(\DateTime::createFromFormat('Y-m-d', trim($endsAt)));

            $stream->setNumberOfPlaces(intval($numberOfPlaces));
            $stream->setCurrentCourseCourseStreamStatus($status);
            $stream->setDescription(trim($description));
            $stream->setNotes(trim($notes));

            $stream->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Поток курса успешно создан");
            $this->response->setRedirect('/admin/courses/' . $course->getId() . '/streams');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
