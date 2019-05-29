<?php

namespace App\Controllers\Admin\Vacancy;

use App\Controllers\Admin\Base;
use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\Group;
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
class Salary extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            $vacancySalaries = VacancySalaryQuery::create()->find();
            $vacancySalaries->prepend((new \Models\VacancySalary())->setId(-1)->setName('Выберите зарплату вакансии'));

            $this->data = array_merge( $this->data,
                array(  'vacancySalaries' => $vacancySalaries
                )
            );

            View::renderTemplate('Admin/Settings/VacancySalary/all.html', $this->data);
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
                throw new CustomException("ID зарплаты вакансии не был указан", 403);
            }

            $vacancySalary = VacancySalaryQuery::create()->findPk(intval($id));
            if (is_null($vacancySalary)){
                throw new CustomException("Зарплата вакансии не найдена", 404);
            }
            $this->data = array_merge( $this->data,
                array(  'vacancySalary' => $vacancySalary
                )
            );


            $vacancySalaries = VacancySalaryQuery::create()->find();
            $vacancySalaries->prepend((new \Models\VacancySalary())->setId(-1)->setName('Выберите зарплату вакансии'));

            $this->data = array_merge( $this->data,
                array(  'vacancySalaries' => $vacancySalaries
                )
            );

            View::renderTemplate('Admin/Settings/VacancySalary/edit.html', $this->data);
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
            View::renderTemplate('Admin/Settings/VacancySalary/add.html', $this->data);
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
