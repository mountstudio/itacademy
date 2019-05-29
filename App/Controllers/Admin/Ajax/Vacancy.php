<?php

namespace App\Controllers\Admin\Ajax;

use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;


use \Models\ConfigQuery;
use Models\CourseStatusQuery;
use Models\Instructor;
use \Models\UserQuery;

use \Models\CourseQuery;
use \Models\GroupQuery;


use Models\VacancyQuery;
use Models\VacancySalaryQuery;
use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Vacancy extends Base
{

     public function listAction()
     {
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('VACANCY_ADMIN');
             $paginator = $this->helper->paginator();
             $vacancies = VacancyQuery::create()->orderBySortableRank()->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

             $this->response->setPaginationDetails($vacancies);
             $vacancies_data = array();
             foreach ($vacancies as $vacancy) {
                 $salary = $vacancy->getCurrentVacancyVacancySalary();

                 $vacancies_data[] = array(
                     'id' => $vacancy->getId(),
                     'name' => $vacancy->getName(),
                     'context' => $vacancy->getContext(),
                     'description' => $vacancy->getDescription(),
                     'salary' => array(
                         'id' => $salary->getId(),
                         'name' => $salary->getName()
                     ),
                     'actions' => array(
                         'isFirst' => $vacancy->isFirst(),
                         'isLast' => $vacancy->isLast()
                     ),
                     'logo' => $vacancy->getLogo(),
                     'createdAt' => $vacancy->getCreatedAt(),
                     'updatedAt' => $vacancy->getUpdatedAt()
                 );
             }
             $this->response->setData($vacancies_data);
             $this->response->setStatus(JsonResponse::SUCCESS);
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
            $this->helper->shouldHavePrivilege('VACANCY_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID вакансии неверный", 1);
            }

            $vacancy = VacancyQuery::create()->findPK(intval($id));
            if (is_null($vacancy)){
                throw new CustomException("Вакансия не найдена", 1);
            }


            switch ($action){
                case 'toTop':
                    $vacancy->moveUp();
                    $this->response->setMessage("Вакансия успешно перемещен вверх", 0);
                    break;
                case 'toBottom':
                    $vacancy->moveDown();
                    $this->response->setMessage("Вакансия успешно перемещен вниз", 0);
                    break;
                default:
                    throw new CustomException("Неверное действие", 1);
            }
            $vacancy->save();
            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function deleteAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('VACANCY_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $vacancy = VacancyQuery::create()->findPK(intval($id));
            if (is_null($vacancy)){
                throw new CustomException("Вакансия не найдена", 1);
            }

            $vacancy->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Вакансия успешно удалена');
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/vacancies/all');
            }
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }


    public function deleteLogoAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('VACANCY_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $vacancy = VacancyQuery::create()->findPK(intval($id));
            if (is_null($vacancy)){
                throw new CustomException("Вакансия не найдена", 1);
            }

            if (!is_null($vacancy->getLogoName())){
                Image::deleteVacancyLogo($vacancy->getLogoName());
                $this->response->setStatus(JsonResponse::SUCCESS);
                $vacancy->setLogoName(null);

                $this->response->setData(array('logo' => $vacancy->getLogo()));
                $this->response->setMessage("Логотип успешно удален", 0);
            } else {
                $this->response->setStatus(JsonResponse::FAIL);
                $this->response->setMessage("Вакансия не имеет логотип", 0);
            }

            $vacancy->save();

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
        $context = (isset($_POST['context']) ? $_POST['context'] : null);
        $metaDescription = (isset($_POST['metaDescription']) ? $_POST['metaDescription'] : null);
        $metaKeywords = (isset($_POST['metaKeywords']) ? $_POST['metaKeywords'] : null);
        $salaryId = (isset($_POST['salaryId']) ? $_POST['salaryId'] : null);

        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID вакансии не был указан", 1);
            }

            $vacancy = VacancyQuery::create()->findPK(intval($id));
            if (is_null($vacancy)){
                throw new CustomException("Вакансия не найден", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            if (trim($name) != $vacancy->getName()){
                $vacancyByName = VacancyQuery::create()->findOneByName(trim($name));
                if (!is_null($vacancyByName)) {
                    throw new CustomException("Такое название вакансии существует", 1);
                }
            }

            if (is_null($context)){
                throw new CustomException("Контекст не был указан", 1);
            }

            if (is_null($description) ){
                throw new CustomException("Описание не было указано", 1);
            }

            $vacancySalary = VacancySalaryQuery::create()->findPk(intval($salaryId));
            if (is_null($vacancySalary)){
                throw new CustomException("Зарплата вакансии не найдена", 1);
            }

            if (is_null($metaDescription) || empty(trim($metaDescription)) ){
                $vacancy->setMetaDescription(null);
            } else {
                $vacancy->setMetaDescription(trim($metaDescription));
            }

            if (is_null($metaKeywords) || empty(trim($metaKeywords)) ){
                $vacancy->setMetaKeywords(null);
            } else {
                $vacancy->setMetaKeywords(trim($metaKeywords));
            }

            $vacancy->setName(trim($name));
            $vacancy->setDescription(trim($description));
            $vacancy->setContext(trim($context));
            $vacancy->setCurrentVacancyVacancySalary($vacancySalary);

            if(isset($_FILES["logo"])) {
                $vacancy->setLogo($_FILES["logo"]);
            }

            $vacancy->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Вакансия успешно сохранена");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }




    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $context = (isset($_POST['context']) ? $_POST['context'] : null);
        $metaDescription = (isset($_POST['metaDescription']) ? $_POST['metaDescription'] : null);
        $metaKeywords = (isset($_POST['metaKeywords']) ? $_POST['metaKeywords'] : null);
        $salaryId = (isset($_POST['salaryId']) ? $_POST['salaryId'] : null);

        try {
            $this->helper->shouldHavePrivilege('SUPER');


            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            $vacancyByName = VacancyQuery::create()->findOneByName(trim($name));
            if (!is_null($vacancyByName)) {
                throw new CustomException("Такое название вакансии существует", 1);
            }

            if (is_null($context)){
                throw new CustomException("Контекст не был указан", 1);
            }

            if (is_null($description) ){
                throw new CustomException("Описание не было указано", 1);
            }

            $vacancySalary = VacancySalaryQuery::create()->findPk(intval($salaryId));
            if (is_null($vacancySalary)){
                throw new CustomException("Зарплата вакансии не найдена", 1);
            }

            $vacancy = new \Models\Vacancy();

            if (is_null($metaDescription) || empty(trim($metaDescription)) ){
                $vacancy->setMetaDescription(null);
            } else {
                $vacancy->setMetaDescription(trim($metaDescription));
            }

            if (is_null($metaKeywords) || empty(trim($metaKeywords)) ){
                $vacancy->setMetaKeywords(null);
            } else {
                $vacancy->setMetaKeywords(trim($metaKeywords));
            }

            $vacancy->setName(trim($name));
            $vacancy->setDescription(trim($description));
            $vacancy->setContext(trim($context));
            $vacancy->setCurrentVacancyVacancySalary($vacancySalary);


            if(isset($_FILES["logo"])) {
                $vacancy->setLogo($_FILES["logo"]);
            }


            $vacancy->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Вакансия успешно создана");
            $this->response->setRedirect('/admin/vacancies/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
