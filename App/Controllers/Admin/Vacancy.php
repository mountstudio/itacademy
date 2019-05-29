<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;

use \Core\CustomException;

use \Models\ConfigQuery;
use Models\VacancySalaryQuery;
use \Models\VacancyStatusQuery;
use \Models\InstructorQuery;

use \Models\VacancyQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Vacancy extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('VACANCY_ADMIN');

            View::renderTemplate('Admin/Vacancy/all.html', $this->data);
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
            $this->helper->shouldHavePrivilege('VACANCY_ADMIN');
            $vacancyId = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($vacancyId) || intval($vacancyId) == 0){
                throw new CustomException("ID не был указан", 403);
            }

            $vacancy = VacancyQuery::create()->findPk(intval($vacancyId));
            if (is_null($vacancy)){
                throw new CustomException("Вакансия не найдена", 404);
            }

            $vacancySalaries = VacancySalaryQuery::create()->find();

            $this->data = array_merge( $this->data,
                array(
                    'vacancy' => $vacancy,
                    'vacancySalaries' => $vacancySalaries
                )
            );
            View::renderTemplate('Admin/Vacancy/edit.html', $this->data);
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
            $this->helper->shouldHavePrivilege('VACANCY_ADMIN');

            $vacancySalaries = VacancySalaryQuery::create()->find();

            $this->data = array_merge( $this->data,
                array(
                    'vacancySalaries' => $vacancySalaries
                )
            );
            View::renderTemplate('Admin/Vacancy/add.html', $this->data);
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
