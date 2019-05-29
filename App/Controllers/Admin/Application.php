<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use Models\CourseQuery;
use \Models\Group;
use \Models\GroupQuery;


use \Models\StaticPageQuery;
use \Models\NotificationQuery;
use \Models\UserQuery;
use \Models\ConfigQuery;

use \Models\ApplicationQuery;

use \Models\ApplicationStatusQuery;
use \Models\MassTypeQuery;
use \Models\PaymentTypeQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Application extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('APPLICATION_ADMIN');

            $applicationStatuses = ApplicationStatusQuery::create()->find();
            $applicationCourses = CourseQuery::create()->find();

            $this->data = array_merge( $this->data,
                array(  'applicationStatuses' => $applicationStatuses,
                    'applicationCourses' => $applicationCourses
                )
            );

            View::renderTemplate('Admin/Application/all.html', $this->data);
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
                throw new CustomException("ID заявки не был указан", 403);
            }

            $application = ApplicationQuery::create()->findPk(intval($id));
            if (is_null($application)){
                throw new CustomException("Заявка не найдена", 404);
            }

            $courses = CourseQuery::create()->find();
            $applicationStatuses = ApplicationStatusQuery::create()->find();


            $this->data = array_merge( $this->data,
                array(  'application' => $application,
                    'courses' => $courses,
                    'applicationStatuses' => $applicationStatuses
                )
            );

            $applicationStatuses = ApplicationStatusQuery::create()->find();

            $applicationStatusList = array();

            foreach($applicationStatuses as $applicationStatus) {
                $applicationStatusList[] = $applicationStatus;
            }

            $this->data = array_merge( $this->data,
                array(  'applicationStatuses' => $applicationStatusList
                )
            );

            View::renderTemplate('Admin/Application/edit.html', $this->data);
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
            $this->helper->shouldHavePrivilege('APPLICATION_ADMIN');

            $applicationStatuses = ApplicationStatusQuery::create()->find();
            $courses = CourseQuery::create()->find();
            $defaultApplicationStatusId = ConfigQuery::create()->findOneByKey('default_application_status');
            $this->data = array_merge( $this->data,
                array(  'applicationStatuses' => $applicationStatuses,
                    'courses' => $courses,
                    'defaultApplicationStatusId' => $defaultApplicationStatusId->getValue()
                )
            );


            View::renderTemplate('Admin/Application/add.html', $this->data);
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
