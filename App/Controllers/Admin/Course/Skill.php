<?php

namespace App\Controllers\Admin\Course;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\CourseQuery;
use \Models\CourseSkill;
use Models\CourseSkillQuery;
use \Models\GroupQuery;


use \Models\StaticPageQuery;

use \Models\UserQuery;

use \Models\VacancySalaryQuery;
use \Models\MassTypeQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Skill extends \App\Controllers\Admin\Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('COURSE_ADMIN');

            $courseId = (isset($this->params['courseid']) ? $this->params['courseid'] : null);
            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("ID курса не была указана", 403);
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

            View::renderTemplate('Admin/Course/Skill/all.html', $this->data);
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

            $courseId = (isset($this->params['courseid']) ? $this->params['courseid'] : null);
            if (is_null($courseId) || intval($courseId) == 0){
                throw new CustomException("ID курса не был указан", 403);
            }

            $course = CourseQuery::create()->findPk(intval($courseId));
            if (is_null($course)){
                throw new CustomException("Курс не найден", 404);
            }

            $id = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID навыка не был указан", 403);
            }

            $skill = CourseSkillQuery::create()->findPk(intval($id));
            if (is_null($skill)){
                throw new CustomException("Навык не найден", 404);
            }

            $this->data = array_merge( $this->data,
                array(
                    'course' => $course,
                    'courseSkill' => $skill
                )
            );

            View::renderTemplate('Admin/Course/Skill/edit.html', $this->data);
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
            View::renderTemplate('Admin/Course/Skill/add.html', $this->data);
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
