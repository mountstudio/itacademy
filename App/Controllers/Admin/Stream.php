<?php


namespace App\Controllers\Admin;


use Core\CustomException;
use Core\View;
use Models\BranchQuery;
use Models\ConfigQuery;
use Models\CourseQuery;
use Models\CourseStreamStatusQuery;
use Models\CurrencyQuery;
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

            $userId = (isset($this->params['userid']) ? $this->params['userid'] : null);
            if (is_null($userId) || intval($userId) == 0){
                throw new CustomException("ID пользователя не был указан", 403);
            }

            $user = UserQuery::create()->findPk(intval($userId));
            if (is_null($user)){
                throw new CustomException("Курс не найден", 404);
            }
            $branches = BranchQuery::create()->find();
            $statuses = CourseStreamStatusQuery::create()->find();
            $currencies = CurrencyQuery::create()->find();
            $courses = CourseQuery::create()->find();

            $instructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $instructors = UserQuery::create()->filterByCurrentGroupId(intval($instructorGroup->getValue()))->find();

            $this->data = array_merge( $this->data,
                array(
                    'student' => $user,
                    'branches' => $branches,
                    'statuses' => $statuses,
                    'instructors' => $instructors,
                    'currencies' => $currencies,
                    'courses' => $courses,
                )
            );
            View::renderTemplate('Admin/User/Stream/add.html', $this->data);
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