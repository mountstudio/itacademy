<?php

namespace App\Controllers\Admin\Application;

use App\Controllers\Admin\Base;
use \Core\View;
use \Core\CustomException;

use \Models\ApplicationStatusQuery;
use \Models\MassTypeQuery;



/**
 * Home controller
 *
 * PHP version 7.0
 */
class Status extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            $applicationStatuses = ApplicationStatusQuery::create()->find();
            $applicationStatusList = array();
            $applicationStatusList[] = (new \Models\ApplicationStatus())->setId(-1)->setName('Выберите состояние заявки');

            foreach($applicationStatuses as $applicationStatus) {
                $applicationStatusList[] = $applicationStatus;
            }

            $this->data = array_merge( $this->data,
                array(  'applicationStatuses' => $applicationStatusList
                )
            );


            View::renderTemplate('Admin/Settings/ApplicationStatus/all.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }
    }

    public function editAction()
    {
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            $id = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID состояния заявки не был указан", 403);
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPk(intval($id));
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 404);
            }
            $this->data = array_merge( $this->data,
                array(  'applicationStatus' => $applicationStatus
                )
            );


            $applicationStatuses = ApplicationStatusQuery::create()->find();
            $applicationStatusList = array();
            $applicationStatusList[] = (new \Models\ApplicationStatus())->setId(-1)->setName('Выберите состояние заявки');

            foreach($applicationStatuses as $applicationStatus_) {
                $applicationStatusList[] = $applicationStatus_;
            }

            $this->data = array_merge( $this->data,
                array(  'applicationStatuses' => $applicationStatusList
                )
            );

            View::renderTemplate('Admin/Settings/ApplicationStatus/edit.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }

    }

    public function addAction()
    {
        try {
            $this->helper->shouldHavePrivilege('SUPER');
            View::renderTemplate('Admin/Settings/ApplicationStatus/add.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }

    }
}
