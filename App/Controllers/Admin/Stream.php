<?php


namespace App\Controllers\Admin;


use Core\CustomException;
use Core\View;
use Models\BranchQuery;
use Models\ConfigQuery;
use Models\CourseQuery;
use Models\CourseStreamQuery;
use Models\CourseStreamStatusQuery;
use Models\CurrencyQuery;
use Models\GroupQuery;
use Models\UserQuery;

class Stream extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            View::renderTemplate('Admin/Stream/all.html', $this->data);
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
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            $streamId = (isset($this->params['streamid']) ? $this->params['streamid'] : null);
            if (is_null($streamId) || intval($streamId) == 0){
                throw new CustomException("ID потока не был указан", 403);
            }

            $stream = CourseStreamQuery::create()->findPk(intval($streamId));
            if (is_null($stream)){
                throw new CustomException("Поток не найден", 404);
            }
            $branches = BranchQuery::create()->find();
            $statuses = CourseStreamStatusQuery::create()->find();
            $currencies = CurrencyQuery::create()->find();
            $courses = CourseQuery::create()->find();
            $groups = GroupQuery::create()->find();

            $instructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $instructors = UserQuery::create()->filterByCurrentGroupId(intval($instructorGroup->getValue()))->find();

            $this->data = array_merge( $this->data,
                array(
                    'branches' => $branches,
                    'statuses' => $statuses,
                    'instructors' => $instructors,
                    'currencies' => $currencies,
                    'courses' => $courses,
                    'groups' => $groups,
                )
            );
            View::renderTemplate('Admin/Stream/User/add.html', $this->data);
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