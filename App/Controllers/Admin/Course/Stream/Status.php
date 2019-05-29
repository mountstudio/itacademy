<?php

namespace App\Controllers\Admin\Course\Stream;

use App\Controllers\Admin\Base;
use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use Models\CourseStreamStatus;
use Models\CourseStreamStatusQuery;
use \Models\Group;
use \Models\GroupQuery;


use \Models\StaticPageQuery;

use \Models\UserQuery;

use \Models\CourseStatusQuery;
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
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            $statuses = CourseStreamStatusQuery::create()->find();
            $statuses->prepend((new \Models\CourseStreamStatus())->setId(-1)->setName('Выберите состояние потока курса'));

            $this->data = array_merge( $this->data,
                array(  'statuses' => $statuses
                )
            );


            View::renderTemplate('Admin/Settings/Course/Stream/Status/all.html', $this->data);
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
                throw new CustomException("ID состояния курса не был указан", 403);
            }

            $courseStreamStatus = CourseStreamStatusQuery::create()->findPk(intval($id));
            if (is_null($courseStreamStatus)){
                throw new CustomException("Состояние курса не найдено", 404);
            }
            $this->data = array_merge( $this->data,
                array(  'courseStreamStatus' => $courseStreamStatus
                )
            );

            $statuses = CourseStreamStatusQuery::create()->find();
            $statuses->prepend((new CourseStreamStatus())->setId(-1)->setName('Выберите состояние потока курса'));

            $this->data = array_merge( $this->data,
                array(  'statuses' => $statuses
                )
            );

            View::renderTemplate('Admin/Settings/Course/Stream/Status/edit.html', $this->data);
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
            View::renderTemplate('Admin/Settings/Course/Stream/Status/add.html', $this->data);
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
