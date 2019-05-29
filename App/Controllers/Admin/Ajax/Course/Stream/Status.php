<?php

namespace App\Controllers\Admin\Ajax\Course\Stream;

use App\Controllers\Admin\Ajax\Base;
use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;

use Models\Base\CourseStreamQuery;
use Models\CourseStreamStatusQuery;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\Product;
use \Models\ProductQuery;

use \Models\StaticPageQuery;

use \Models\CourseStatusQuery;
use \Models\CourseQuery;
use \Models\ConfigQuery;

use \Models\MassTypeQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Status extends Base
{

    public function listAction()
    {
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldHavePrivilege('SUPER');
            $paginator = $this->helper->paginator();
            $statuses = CourseStreamStatusQuery::create()->orderBySortableRank()->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

            $this->response->setPaginationDetails($statuses);
            $statusesData = array();
            foreach ($statuses as $status) {
                $statusesData[] = array('id' => $status->getId(),
                    'name' => $status->getName(),
                    'description' => $status->getDescription(),
                    'actions' => array(
                        'isFirst' => $status->isFirst(),
                        'isLast' => $status->isLast()
                    ),
                );
            }
            $this->response->setData($statusesData);
            $this->response->setStatus(JsonResponse::SUCCESS);
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
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID курса неверный", 1);
            }

            $status = CourseStreamStatusQuery::create()->findPK($id);
            if (is_null($status)){
                throw new CustomException("Состояние курса не найдено", 1);
            }


            switch ($action){
                case 'toTop':
                    $status->moveUp();
                    $this->response->setMessage("Состояние курса успешно перемещено вверх", 0);
                    break;
                case 'toBottom':
                    $status->moveDown();
                    $this->response->setMessage("Состояние курса успешно перемещено вниз", 0);
                    break;
                default:
                    throw new CustomException("Invaild action", 1);
            }

            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function deleteAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $alternateId = (isset($_POST['newId']) ? $_POST['newId'] : null);
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID состояние курса не было указано", 1);
            }

            $status = CourseStreamStatusQuery::create()->findPK(intval($id));
            if (is_null($status)){
                throw new CustomException("Состояние потока курса не найдено", 1);
            }

            $courseStreamAltStatus = CourseStreamQuery::create()->filterByCurrentCourseCourseStreamStatus($status)->find();


            if (is_null($alternateId) || intval($alternateId) == 0){
                throw new CustomException("ID альтернативного состояния потока курса не было указано", 1);
            }

            $alternateStatus = CourseStreamStatusQuery::create()->findPK(intval($alternateId));
            if (is_null($alternateStatus)){
                throw new CustomException("Альтернативное состояние потока курса не найдено", 1);
            }

            if ($alternateStatus->getId() == $status->getId()){
                throw new CustomException("Альтернативное состояние курса не может быть равно удаляемому состоянию курса", 1);
            }

            if (sizeof($courseStreamAltStatus) > 0){
                foreach ($courseStreamAltStatus as $courseStreamToChangeStatus) {
                    $courseStreamToChangeStatus->setCurrentCourseCourseStreamStatus($alternateStatus);
                    $courseStreamToChangeStatus->save();
                }
                $this->response->setMessage('Список курсов, которые имели состояние ' . $status->getName() . ' теперь являются как ' . $alternateStatus->getName());
            } else {
                $this->response->setMessage('Состояние потока курса успешно удалено');
            }

            $defaultCourseStatus = ConfigQuery::create()->findOneByKey('default_course_status');
            if (is_null($defaultCourseStatus)){
                $defaultCourseStatus = new \Models\Config();
                $defaultCourseStatus->setKey('default_course_status');
            }
            if ($defaultCourseStatus->getValue() == $status->getId()){
                $defaultCourseStatus->setValue($alternateStatus->getId());
                $defaultCourseStatus->save();
                $this->response->setMessage($this->response->getMessage() . ', также состояние курса по умолчанию в конфигурации было изменено на ' . $alternateStatus->getName());
            }

            $status->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);

            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/settings/courseStreamStatuses/all');
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
        $backgroundColor = (isset($_POST['backgroundColor']) ? $_POST['backgroundColor'] : null);
        $fontColor = (isset($_POST['fontColor']) ? $_POST['fontColor'] : null);

        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID состояния потока курса не был указан", 1);
            }

            $status = CourseStreamStatusQuery::create()->findPK(intval($id));
            if (is_null($status)){
                throw new CustomException("Состояние потока курса не найдено", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание не было введено", 1);
            }

            if (is_null($backgroundColor)){
                throw new CustomException("Цвет фона не указан", 1);
            }

            if (is_null($fontColor)){
                throw new CustomException("Цвет шрифта не указан", 1);
            }

            if (!empty(trim($backgroundColor))){
                $status->setBackgroundColor(trim($backgroundColor));
            } else {
                $status->setBackgroundColor(null);
            }

            if (!empty(trim($fontColor))){
                $status->setFontColor(trim($fontColor));
            } else {
                $status->setFontColor(null);
            }

            if (trim($name) != $status->getName()){
                $statusByName = CourseStreamStatusQuery::create()->findOneByName(trim($name));
                if (!is_null($statusByName)) {
                    throw new CustomException("Такое название состояния потока курса существует", 1);
                }
            }

            $status->setName(trim($name));
            $status->setDescription(trim($description));

            $status->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Состояние потока курса успешно сохранено");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $backgroundColor = (isset($_POST['backgroundColor']) ? $_POST['backgroundColor'] : null);
        $fontColor = (isset($_POST['fontColor']) ? $_POST['fontColor'] : null);

        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название состояния курса не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание состояния курса не было введено", 1);
            }

            $statusByName = CourseStreamStatusQuery::create()->findOneByName(trim($name));
            if (!is_null($statusByName)) {
                throw new CustomException("Такое название состояния потока курса существует", 1);
            }

            if (is_null($backgroundColor)){
                throw new CustomException("Цвет фона не указан", 1);
            }

            if (is_null($fontColor)){
                throw new CustomException("Цвет шрифта не указан", 1);
            }

            $status = new \Models\CourseStreamStatus();

            if (!empty(trim($backgroundColor))){
                $status->setBackgroundColor(trim($backgroundColor));
            }

            if (!empty(trim($fontColor))){
                $status->setFontColor(trim($fontColor));
            }

            $status->setName(trim($name));
            $status->setDescription(trim($description));

            $status->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Состояние потока курса успешно сохранено");
            $this->response->setRedirect('/admin/settings/courseStreamStatuses/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
