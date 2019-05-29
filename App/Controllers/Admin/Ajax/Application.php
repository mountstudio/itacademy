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

use Models\CourseQuery;
use Models\CourseStreamQuery;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\Product;
use \Models\ProductQuery;

use \Models\StaticPageQuery;


use \Models\ApplicationStatusQuery;
use \Models\ApplicationQuery;

use \Models\PaymentTypeQuery;
use \Models\PlaceQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Application extends Base
{

    public function listAction()
    {
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldHavePrivilege('APPLICATION_ADMIN');
            $paginator = $this->helper->paginator();

            $statusId = (isset($_POST['applicationStatusId']) ? $_POST['applicationStatusId'] : null);
            if(is_null($statusId)){
                throw new CustomException("ID состояния заявки не был указан", 1);
            }

            if (intval($statusId) == 0){
                $applications = ApplicationQuery::create()->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
            } else {
                $applications = ApplicationQuery::create()->filterByCurrentApplicationStatusId(intval($statusId))->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
            }

            $this->response->setPaginationDetails($applications);
            $applicationsData = array();
            foreach ($applications as $application) {
                $status = $application->getCurrentApplicationStatus();

                $course = $application->getCurrentCourseApplication();
                $courseStream = $application->getCurrentCourseStreamApplication();
                $applicationsData[] = array('id' => $application->getId(),
                    'name' => $application->getName(),
                    'phone' => $application->getPhone(),
                    'notes' => $application->getNotes(),
                    'description' => $application->getDescription(),
                    'createdAt' => $application->getCreatedAt(),
                    'updatedAt' => $application->getUpdatedAt(),
                    'course' => (is_null($course) ? null : array(
                        'id' => $course->getId(),
                        'name' => $course->getName()
                    )),
                    'courseStream' => (is_null($courseStream) ? null : array(
                        'id' => $courseStream->getId(),
                        'name' => $courseStream->getName()
                    )),
                    'status' => array(  'id' => $status->getId(),
                        'name' => $status->getName(),
                        'fontColor' => $status->getFontColor(),
                        'backgroundColor' => $status->getBackgroundColor()
                    )
                );
            }
            $this->response->setData($applicationsData);
            $this->response->setStatus(JsonResponse::SUCCESS);
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    /*
         public function liveReloadAction()
         {
             $this->response = new JsonResponse();
             try {
                 $this->helper->shouldHavePrivilege('APPLICATION_ADMIN');

                 $lastUpdatedTime = (isset($_POST['lastUpdatedTime']) ? $_POST['lastUpdatedTime'] : null);

                 if (is_null($lastUpdatedTime) || intval($lastUpdatedTime) == 0){
                     throw new CustomException("Последнее обновленное время не было указано", 1);
                 }

                 $applications = ApplicationQuery::create()->orderById('desc')->filterByCreatedAt(array('min' => intval($lastUpdatedTime)))->find();

                 $applicationsData = array();
                 foreach ($applications as $application) {
                     $status = $application->getCurrentApplicationStatus();
                     $applicationsData[] = array('id' => $application->getId(),
                                            'name' => $application->getName(),
                                            'phone' => $application->getPhone(),
                                            'notes' => $application->getNotes(),
                                            'description' => $application->getDescription(),
                                            'status' => array(  'id' => $status->getId(),
                                                                'name' => $status->getName()
                                                            )
                                            );
                 }
                 $this->response->setData($applicationsData);
                 $this->response->setStatus(JsonResponse::SUCCESS);
             } catch (CustomException $e) {
                 $this->response->setException($e);
             }

             $this->response->show();
         }
    */
    public function deleteAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID заявки не был указан", 1);
            }

            $application = ApplicationQuery::create()->findPK(intval($id));
            if (is_null($application)){
                throw new CustomException("Заявка не найдена", 1);
            }

            $application->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Заявка была удалена успешно');
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/applications/all');
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
        $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $statusId = (isset($_POST['statusId']) ? $_POST['statusId'] : null);
        $courseId = (isset($_POST['courseId']) ? $_POST['courseId'] : null);

        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("Id заявки не был указан", 1);
            }

            $application = ApplicationQuery::create()->findPK(intval($id));
            if (is_null($application)){
                throw new CustomException("Заявка не найдена", 1);
            }

            if (is_null($statusId) || intval($statusId) == 0){
                throw new CustomException("ID состояния заявки не был указан", 1);
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPK(intval($statusId));
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                    throw new CustomException("Имя заявителя не был введен", 1);
            }

            if (!empty(trim($phone)) && !preg_match('/^[0-9]+$/', trim($phone))){
                throw new CustomException("Номер телефона должен иметь только цифры", 1);
            } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12 && substr(trim($phone),0, 3) != "996") {
                throw new CustomException("Номер телефона должен быть введен в формате 996XXXYYYYYY", 1);
            } elseif (empty(trim($phone))) {
                throw new CustomException("Номер телефона не был введен", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание заявки не было введено", 1);
            }

            if (is_null($notes)){
                throw new CustomException("Примечание заявки не было введено", 1);
            }

            if (!is_null($courseId) && intval($courseId) != 0){
                $course = CourseQuery::create()->findPk(intval($courseId));
                if (is_null($course)){
                    throw new CustomException("Курс не найден", 1);
                }
                $application->setCurrentCourseApplication($course);
            }

            $application->setName(trim($name));
            $application->setPhone(trim($phone));
            $application->setDescription(trim($description));
            $application->setNotes(trim($notes));
            $application->setCurrentApplicationStatus($applicationStatus);


            $application->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Заявка сохранена успешно");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }


    public function updateAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);
        $statusId = (isset($_POST['statusId']) ? $_POST['statusId'] : null);

        try {
            $this->helper->shouldHavePrivilege('APPLICATION_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID заявки не был указан", 1);
            }

            $application = ApplicationQuery::create()->findPK(intval($id));
            if (is_null($application)){
                throw new CustomException("Заявка не найдена", 1);
            }

            if (is_null($statusId) || intval($statusId) == 0){
                throw new CustomException("ID состояния заявки не был указан", 1);
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPK(intval($statusId));
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 1);
            }

            if (is_null($notes)){
                throw new CustomException("Примечание заявки не было введено", 1);
            }

            $application->setNotes(trim($notes));
            $application->setCurrentApplicationStatus($applicationStatus);

            $application->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Заявка обновлена успешо");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $statusId = (isset($_POST['statusId']) ? $_POST['statusId'] : null);
        $courseId = (isset($_POST['courseId']) ? $_POST['courseId'] : null);
        $courseStreamId = (isset($_POST['courseStreamId']) ? $_POST['courseStreamId'] : null);
        try {
            $this->helper->shouldHavePrivilege('APPLICATION_ADMIN');

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Имя заявителя не было введено", 1);
            }

            if (is_null($statusId) || intval($statusId) == 0){
                throw new CustomException("ID состояния заявки не был указан", 1);
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPK(intval($statusId));
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 1);
            }


            if (!empty(trim($phone)) && !preg_match('/^[0-9]+$/', trim($phone))){
                throw new CustomException("Номер телефона должен иметь только цифры", 1);
            } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12 && substr(trim($phone),0, 3) != "996") {
                throw new CustomException("Номер телефона должен быть введен в формате 996XXXYYYYYY", 1);
            } elseif (empty(trim($phone))) {
                throw new CustomException("Номер телефона не был введен", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание заявки не было введено", 1);
            }

            if (is_null($notes)){
                throw new CustomException("Примечание заявки не было введено", 1);
            }

            $application = new \Models\Application();
            if (!is_null($courseId) && intval($courseId) != 0){
                $course = CourseQuery::create()->findPk(intval($courseId));
                if (is_null($course)){
                    throw new CustomException("Курс не найден", 1);
                }
                $application->setCurrentCourseApplication($course);

                if (!is_null($courseStreamId) && intval($courseStreamId) != 0){
                    $courseStream = CourseStreamQuery::create()->findPk(intval($courseStreamId));
                    if (is_null($courseStream)){
                        throw new CustomException("Поток курса не найден", 1);
                    }
                    $application->setCurrentCourseStreamApplication($courseStream);
                }
            }



            $application->setName(trim($name));
            $application->setPhone(trim($phone));
            $application->setDescription(trim($description));
            $application->setNotes(trim($notes));
            $application->setCurrentApplicationStatus($applicationStatus);

            $application->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Заявка создана успешо");
            $this->response->setRedirect('/admin/applications/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
