<?php

namespace App\Controllers\Admin\Ajax\Vacancy;

use App\Controllers\Admin\Ajax\Base;
use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;

use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\Product;
use \Models\ProductQuery;

use \Models\StaticPageQuery;

use \Models\VacancySalaryQuery;
use \Models\VacancyQuery;
use \Models\ConfigQuery;

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

    public function listAction()
    {
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldHavePrivilege('SUPER');
            $paginator = $this->helper->paginator();
            $vacancySalaries = VacancySalaryQuery::create()->orderBySortableRank()->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

            $this->response->setPaginationDetails($vacancySalaries);
            $vacancySalariesData = array();
            foreach ($vacancySalaries as $vacancySalary) {
                $vacancySalariesData[] = array('id' => $vacancySalary->getId(),
                    'name' => $vacancySalary->getName(),
                    'description' => $vacancySalary->getDescription(),
                    'actions' => array(
                        'isFirst' => $vacancySalary->isFirst(),
                        'isLast' => $vacancySalary->isLast()
                    ),
                );
            }
            $this->response->setData($vacancySalariesData);
            $this->response->setStatus(JsonResponse::SUCCESS);
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function deleteAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $alternateId = (isset($_POST['newId']) ? $_POST['newId'] : null);
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID зарплаты вакансии не был указан", 1);
            }

            $vacancySalary = VacancySalaryQuery::create()->findPK(intval($id));
            if (is_null($vacancySalary)){
                throw new CustomException("Зарплата вакансии не найдена", 1);
            }

            $vacancyAltStatus = VacancyQuery::create()->filterByCurrentVacancyVacancySalary($vacancySalary)->find();


            if (is_null($alternateId) || intval($alternateId) == 0){
                throw new CustomException("ID альтернативной зарплаты вакансии не был указан", 1);
            }

            $alternateVacancySalary = VacancySalaryQuery::create()->findPK(intval($alternateId));
            if (is_null($alternateVacancySalary)){
                throw new CustomException("Альтернативная зарплата вакансии не найдена", 1);
            }

            if ($alternateVacancySalary->getId() == $vacancySalary->getId()){
                throw new CustomException("Альтернативная зарплата вакансии не может быть равна удаляемой зарплаты вакансии", 1);
            }

            if (sizeof($vacancyAltStatus) > 0){
                foreach ($vacancyAltStatus as $vacancyToChangeStatus) {
                    $vacancyToChangeStatus->setCurrentVacancyVacancySalary($alternateVacancySalary);
                    $vacancyToChangeStatus->save();
                }
                $this->response->setMessage('Список зарплат вакансий, которые имели состояние ' . $vacancySalary->getName() . ' теперь являются как ' . $alternateVacancySalary->getName());
            } else {
                $this->response->setMessage('Зарплата вакансии успешно удалено');
            }

            $defaultVacancySalary = ConfigQuery::create()->findOneByKey('default_vacancy_status');

            if (is_null($defaultVacancySalary)){
                $defaultVacancySalary = new \Models\Config();
                $defaultVacancySalary->setKey('default_vacancy_status');
            }

            if ($defaultVacancySalary->getValue() == $vacancySalary->getId()){
                $defaultVacancySalary->setValue($alternateVacancySalary->getId());
                $defaultVacancySalary->save();
                $this->response->setMessage($this->response->getMessage() . ', также зарплата заявки по умолчанию в конфигурации была изменена на ' . $alternateVacancySalary->getName());
            }

            $vacancySalary->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);

            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/settings/vacancySalaries/all');
            }
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function editAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);

        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID зарплаты вакансии не был указан", 1);
            }

            $vacancySalary = VacancySalaryQuery::create()->findPK(intval($id));
            if (is_null($vacancySalary)){
                throw new CustomException("Зарплата вакансии не найдена", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название зарплаты вакансии не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание зарплаты вакансии не было введено", 1);
            }


            if (trim($name) != $vacancySalary->getName()){
                $vacancySalaryByName = VacancySalaryQuery::create()->findOneByName(trim($name));
                if (!is_null($vacancySalaryByName)) {
                    throw new CustomException("Такое название зарплаты существует", 1);
                }
            }

            $vacancySalary->setName(trim($name));
            $vacancySalary->setDescription(trim($description));

            $vacancySalary->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Зарплата вакансии успешно сохранена");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function updateAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $action = (isset($_POST['action']) ? $_POST['action'] : null);
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID зарплаты вакансии неверный", 1);
            }

            $vacancySalary = VacancySalaryQuery::create()->findPK($id);
            if (is_null($vacancySalary)){
                throw new CustomException("Зарплата вакансии не найдена", 1);
            }


            switch ($action){
                case 'toTop':
                    $vacancySalary->moveUp();
                    $this->response->setMessage("Зарплата вакансии успешно перемещен вверх", 0);
                    break;
                case 'toBottom':
                    $vacancySalary->moveDown();
                    $this->response->setMessage("Зарплата вакансии успешно перемещен вниз", 0);
                    break;
                default:
                    throw new CustomException("Invaild action", 1);
            }
            $vacancySalary->save();
            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название зарплаты вакансии не было введено", 1);
            }

            if (is_null($description)){
                throw new CustomException("Описание зарплаты вакансии не было введено", 1);
            }

            $vacancySalaryByName = VacancySalaryQuery::create()->findOneByName(trim($name));
            if (!is_null($vacancySalaryByName)) {
                throw new CustomException("Такое название зарплаты вакансии существует", 1);
            }


            $vacancySalary = new \Models\VacancySalary();
            $vacancySalary->setName(trim($name));
            $vacancySalary->setDescription(trim($description));

            $vacancySalary->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Зарплата вакансии успешно сохранена");
            $this->response->setRedirect('/admin/settings/vacancySalaries/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
