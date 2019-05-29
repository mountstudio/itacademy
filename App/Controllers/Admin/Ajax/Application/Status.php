<?php

namespace App\Controllers\Admin\Ajax\Application;

use App\Controllers\Admin\Ajax\Base;
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

use \Models\Product;
use \Models\ProductQuery;

use \Models\StaticPageQuery;

use \Models\ApplicationStatusQuery;
use \Models\ApplicationQuery;
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
            $applicationStatuses = ApplicationStatusQuery::create()->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

            $this->response->setPaginationDetails($applicationStatuses);
            $applicationStatusesData = array();
            foreach ($applicationStatuses as $applicationStatus) {
                $applicationStatusesData[] = array('id' => $applicationStatus->getId(),
                    'name' => $applicationStatus->getName(),
                    'description' => $applicationStatus->getDescription(),
                    'actions' => array(
                        'isFirst' => $applicationStatus->isFirst(),
                        'isLast' => $applicationStatus->isLast()
                    ),
                );
            }
            $this->response->setData($applicationStatusesData);
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
                throw new CustomException("ID состояния заявки неверный", 1);
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPK($id);
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 1);
            }


            switch ($action){
                case 'toTop':
                    $applicationStatus->moveUp();
                    $this->response->setMessage("Состояние заявки успешно перемещен вверх", 0);
                    break;
                case 'toBottom':
                    $applicationStatus->moveDown();
                    $this->response->setMessage("Состояние заявки успешно перемещен вниз", 0);
                    break;
                default:
                    throw new CustomException("Неверное действие", 1);
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
                throw new CustomException("ID состояние заявки не был указан", 1);
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPK(intval($id));
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 1);
            }

            $applicationAltStatus = ApplicationQuery::create()->filterByCurrentApplicationStatus($applicationStatus)->find();


            if (is_null($alternateId) || intval($alternateId) == 0){
                throw new CustomException("ID альтернативного состояния заявки не было указано", 1);
            }

            $alternateApplicationStatus = ApplicationStatusQuery::create()->findPK(intval($alternateId));
            if (is_null($alternateApplicationStatus)){
                throw new CustomException("Альтернативное состояние заявки не найдено", 1);
            }

            if ($alternateApplicationStatus->getId() == $applicationStatus->getId()){
                throw new CustomException("Альтернативное состояние заявки не может быть равно удаляемому состоянию заявки", 1);
            }

            if (sizeof($applicationAltStatus) > 0){
                foreach ($applicationAltStatus as $applicationToChangeStatus) {
                    $applicationToChangeStatus->setCurrentApplicationStatus($alternateApplicationStatus);
                    $applicationToChangeStatus->save();
                }
                $this->response->setMessage('Список заявок, которые имели состояние ' . $applicationStatus->getName() . ' теперь являются как ' . $alternateApplicationStatus->getName());
            } else {
                $this->response->setMessage('Состояние заявки успешно удалено');
            }

            $defaultApplicationStatus = ConfigQuery::create()->findOneByKey('default_application_status');
            if (is_null($defaultApplicationStatus)){
                $defaultApplicationStatus = new \Models\Config();
                $defaultApplicationStatus->setKey('default_application_status');
            }
            if ($defaultApplicationStatus->getValue() == $applicationStatus->getId()){
                $defaultApplicationStatus->setValue($alternateApplicationStatus->getId());
                $defaultApplicationStatus->save();
                $this->response->setMessage($this->response->getMessage() . ', также состояние заявки по умолчанию в конфигурации было изменено на ' . $alternateApplicationStatus->getName());
            }

            $applicationStatus->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);

            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/settings/applicationStatuses/all');
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
                throw new CustomException("ID состояния заявки не был указан", 1);
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPK(intval($id));
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название состояния заявки не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание состояния звонка не былл введено", 1);
            }

            if (is_null($backgroundColor)){
                throw new CustomException("Цвет фона не указан", 1);
            }

            if (is_null($fontColor)){
                throw new CustomException("Цвет шрифта не указан", 1);
            }

            if (!empty(trim($backgroundColor))){
                $applicationStatus->setBackgroundColor(trim($backgroundColor));
            } else {
                $applicationStatus->setBackgroundColor(null);
            }

            if (!empty(trim($fontColor))){
                $applicationStatus->setFontColor(trim($fontColor));
            } else {
                $applicationStatus->setFontColor(null);
            }

            if (trim($name) != $applicationStatus->getName()){
                $applicationStatusByName = ApplicationStatusQuery::create()->findOneByName(trim($name));
                if (!is_null($applicationStatusByName)) {
                    throw new CustomException("Такое название состояния заявки существует", 1);
                }
            }

            $applicationStatus->setName(trim($name));
            $applicationStatus->setDescription(trim($description));

            $applicationStatus->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Состояние заявки успешно сохранено");
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
                throw new CustomException("Название состояния заявки не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание состояния звонка не было введено", 1);
            }

            $applicationStatusByName = ApplicationStatusQuery::create()->findOneByName(trim($name));
            if (!is_null($applicationStatusByName)) {
                throw new CustomException("Такое название состояния заявки существует", 1);
            }

            if (is_null($backgroundColor)){
                throw new CustomException("Цвет фона не указан", 1);
            }

            if (is_null($fontColor)){
                throw new CustomException("Цвет шрифта не указан", 1);
            }

            $applicationStatus = new \Models\ApplicationStatus();

            if (!empty(trim($backgroundColor))){
                $applicationStatus->setBackgroundColor(trim($backgroundColor));
            }

            if (!empty(trim($fontColor))){
                $applicationStatus->setFontColor(trim($fontColor));
            }

            $applicationStatus->setName(trim($name));
            $applicationStatus->setDescription(trim($description));

            $applicationStatus->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Состояние заявки успешно создано");
            $this->response->setRedirect('/admin/settings/applicationStatuses/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
