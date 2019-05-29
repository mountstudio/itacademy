<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;

use \Core\CustomException;

use \Models\ConfigQuery;
use \Models\CourseStatusQuery;
use Models\CourseStreamStatusQuery;
use \Models\InstructorQuery;

use \Models\CourseQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Course extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');

            View::renderTemplate('Admin/Course/all.html', $this->data);
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
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');
            $courseId = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("ID курса не был указан", 403);
            }

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 404);
            }

            $statuses = CourseStreamStatusQuery::create()->find();

            $instructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $instructors = UserQuery::create()->filterByCurrentGroupId(intval($instructorGroup->getValue()))->find();

            $this->data = array_merge( $this->data,
                array(
                    'course' => $course,
                    'statuses' => $statuses,
                    'instructors' => $instructors
                )
            );
            View::renderTemplate('Admin/Course/edit.html', $this->data);
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
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');

            $courseStatuses = CourseStreamStatusQuery::create()->find();

            $instructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $instructors = UserQuery::create()->filterByCurrentGroupId(intval($instructorGroup->getValue()))->find();

            $this->data = array_merge( $this->data,
                array(
                    'courseStatuses' => $courseStatuses,
                    'instructors' => $instructors
                )
            );
            View::renderTemplate('Admin/Course/add.html', $this->data);
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
