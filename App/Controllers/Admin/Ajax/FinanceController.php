<?php


namespace App\Controllers\Admin\Ajax;


use Core\CustomException;
use Core\Helper;
use Core\JsonResponse;
use DateTime;
use Models\Finance;
use Models\FinanceQuery;
use Models\TaskQuery;

class FinanceController extends Base
{
    public function listAction()
    {
        $this->response = new JsonResponse();
        $financeUp = FinanceQuery::create()->filterByType(Finance::FINANCE_UP);
        $financeDown = FinanceQuery::create()->filterByType(Finance::FINANCE_DOWN);
        $finances = FinanceQuery::create()->find();
        $dashboard = [];
        $summ = 0;
        foreach ($financeUp as $index => $finance) {
            $dashboard['financeUp'][$index]['id'] = $finance->getId();
            $dashboard['financeUp'][$index]['title'] = $finance->getTitle();
            $dashboard['financeUp'][$index]['description'] = $finance->getDescription();
            $dashboard['financeUp'][$index]['created_at'] = $finance->getCreatedAt()->format('d-m-Y');
            $dashboard['financeUp'][$index]['manager_id'] = $finance->getManagerId();
            $dashboard['financeUp'][$index]['user_id'] = $finance->getUserId();
            $dashboard['financeUp'][$index]['type'] = $finance->getType();
            $dashboard['financeUp'][$index]['summ'] = $finance->getSumm();
            $summ += $finance->getSumm();
        }

        foreach ($financeDown as $index => $finance) {
            $dashboard['financeDown'][$index]['id'] = $finance->getId();
            $dashboard['financeDown'][$index]['title'] = $finance->getTitle();
            $dashboard['financeDown'][$index]['description'] = $finance->getDescription();
            $dashboard['financeDown'][$index]['created_at'] = $finance->getCreatedAt()->format('d-m-Y');
            $dashboard['financeDown'][$index]['manager_id'] = $finance->getManagerId();
            $dashboard['financeDown'][$index]['user_id'] = $finance->getUserId();
            $dashboard['financeDown'][$index]['type'] = $finance->getType();
            $dashboard['financeDown'][$index]['summ'] = $finance->getSumm();
            $summ -= $finance->getSumm();
        }
        foreach ($finances as $index => $finance) {
            $dashboard['finances'][$index]['id'] = $finance->getId();
            $dashboard['finances'][$index]['title'] = $finance->getTitle();
            $dashboard['finances'][$index]['description'] = $finance->getDescription();
            $dashboard['finances'][$index]['created_at'] = $finance->getCreatedAt()->format('d-m-Y');
            $dashboard['finances'][$index]['manager_id'] = $finance->getManagerId();
            $dashboard['finances'][$index]['user_id'] = $finance->getUserId();
            $dashboard['finances'][$index]['type'] = $finance->getType();
            $dashboard['finances'][$index]['summ'] = $finance->getSumm();
        }
        $dashboard['summ'] = $summ;

        $this->response->setData($dashboard);
        $this->response->setStatus(JsonResponse::SUCCESS);
        $this->response->show();
    }

    public function addAction()
    {
        $title = (isset($_POST['title']) ? $_POST['title'] : null);
        $type = (isset($_POST['type']) ? $_POST['type'] : null);
        $summ = (isset($_POST['summ']) ? $_POST['summ'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $manager = (isset($_POST['manager']) ? $_POST['manager'] : null);
        $student = (isset($_POST['student']) ? $_POST['student'] : null);

        if (is_null($title)){
            throw new CustomException("Поле название обязательно", 1);
        }
        if (is_null($summ)){
            throw new CustomException("Поле сумма обязательно", 1);
        }
        if (is_null($manager)){
            throw new CustomException("Менеджер с таким id не найден", 1);
        }
        if (is_null($student)){
            throw new CustomException("Студент с таким id не найден", 1);
        }

        $finance = new Finance();
        $finance->setType($type);
        $finance->setTitle($title);
        $finance->setSumm($summ);
        $finance->setDescription($description);
        $finance->setManagerId($manager);
        $finance->setUserId($student);

        $finance->save();

        $financeArray['id'] = $finance->getId();
        $financeArray['type'] = $finance->getType();
        $financeArray['title'] = $finance->getTitle();
        $financeArray['description'] = $finance->getDescription();
        $financeArray['summ'] = $finance->getSumm();
        $financeArray['created_at'] = $finance->getCreatedAt()->format('d-m-Y');
        $financeArray['manager_id'] = $finance->getManagerId();
        $financeArray['user_id'] = $finance->getUserId();

        $this->response->setData($financeArray);
        $this->response->setStatus(JsonResponse::SUCCESS);
        $this->response->show();
    }
    
    public function deleteAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('CREATE_TASK');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $finance = TaskQuery::create()->findOneById(intval($id));
            if (is_null($finance)){
                throw new CustomException("Задание не найдено", 1);
            }

            $finance->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Задание было успешно удалено');

            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }
}