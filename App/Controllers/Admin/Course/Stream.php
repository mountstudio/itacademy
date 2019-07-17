<?php

namespace App\Controllers\Admin\Course;

use App\Controllers\Admin\Base;
use \Core\View;

use \Core\CustomException;

use Models\BranchQuery;
use \Models\ConfigQuery;
use Models\CourseQuery;
use \Models\CourseStatusQuery;
use Models\CourseStreamQuery;
use Models\CourseStreamStatusQuery;
use Models\CurrencyQuery;
use \Models\InstructorQuery;


use \Models\UserQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Stream extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            $courseId = (isset($this->params['courseid']) ? $this->params['courseid'] : null);
            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("ID курса не был указан", 403);
            }

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 404);
            }

            $this->data = array_merge( $this->data,
                array(
                    'course' => $course
                )
            );

            View::renderTemplate('Admin/Course/Stream/all.html', $this->data);
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

    public function showAction()
    {
        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            $courseId = (isset($this->params['courseid']) ? $this->params['courseid'] : null);
            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("ID курса не был указан", 403);
            }

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 404);
            }


            $streamId = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($streamId) || intval($streamId) == 0){
                throw new CustomException("ID потока курса не был указан", 403);
            }

            $stream = CourseStreamQuery::create()->findPk(intval($streamId));
            if (is_null($stream)){
                throw new CustomException("Поток курса не найден", 404);
            }

            $streamStatuses = CourseStreamStatusQuery::create()->find();
            $currencies = CurrencyQuery::create()->find();
            $branches = BranchQuery::create()->find();
            $instructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $instructors = UserQuery::create()->filterByCurrentGroupId(intval($instructorGroup->getValue()))->find();

            $this->data = array_merge( $this->data,
                array(
                    'stream' => $stream,
                    'statuses' => $streamStatuses,
                    'instructors' => $instructors,
                    'currencies' => $currencies
                )
            );
            View::renderTemplate('Admin/Course/Stream/show.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(
                    'error_code' => $e->getCode(),
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
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            $courseId = (isset($this->params['courseid']) ? $this->params['courseid'] : null);
            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("ID курса не был указан", 403);
            }

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 404);
            }


            $streamId = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($streamId) || intval($streamId) == 0){
                throw new CustomException("ID потока курса не был указан", 403);
            }

            $stream = CourseStreamQuery::create()->findPk(intval($streamId));
            if (is_null($stream)){
                throw new CustomException("Поток курса не найден", 404);
            }

            $streamStatuses = CourseStreamStatusQuery::create()->find();
            $currencies = CurrencyQuery::create()->find();
            $branches = BranchQuery::create()->find();
            $instructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $instructors = UserQuery::create()->filterByCurrentGroupId(intval($instructorGroup->getValue()))->find();

            $this->data = array_merge( $this->data,
                array(
                    'stream' => $stream,
                    'statuses' => $streamStatuses,
                    'instructors' => $instructors,
                    'currencies' => $currencies
                )
            );
            View::renderTemplate('Admin/Course/Stream/edit.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(
                    'error_code' => $e->getCode(),
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
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            $courseId = (isset($this->params['courseid']) ? $this->params['courseid'] : null);
            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("ID курса не был указан", 403);
            }

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 404);
            }
            $branches = BranchQuery::create()->find();
            $statuses = CourseStreamStatusQuery::create()->find();
            $currencies = CurrencyQuery::create()->find();

            $instructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $instructors = UserQuery::create()->filterByCurrentGroupId(intval($instructorGroup->getValue()))->find();

            $this->data = array_merge( $this->data,
                array(
                    'course' => $course,
                    'branches' => $branches,
                    'statuses' => $statuses,
                    'instructors' => $instructors,
                    'currencies' => $currencies
                )
            );
            View::renderTemplate('Admin/Course/Stream/add.html', $this->data);
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
