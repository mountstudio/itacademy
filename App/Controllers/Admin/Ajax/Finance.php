<?php


namespace App\Controllers\Admin\Ajax;


use Core\CustomException;
use Core\Helper;
use Core\JsonResponse;
use DateTime;
use Models\Finance;
use Models\FinanceQuery;
use Models\TaskQuery;

class Task extends Base
{
    public function listAction()
    {
        $this->response = new JsonResponse();
        $financeUp = FinanceQuery::create()->filterByType(Finance::FINANCE_UP);
        $financeDown = FinanceQuery::create()->filterByType(Finance::FINANCE_DOWN);

        $dashboard = [];

        foreach ($financeUp as $index => $finance) {
            $dashboard['financeUp'][$index]['id'] = $finance->getId();
            $dashboard['financeUp'][$index]['title'] = $finance->getTitle();
            $dashboard['financeUp'][$index]['description'] = $finance->getDescription();
            $dashboard['financeUp'][$index]['created_at'] = $finance->getCreatedAt()->format('Y-m-d');
        }

        foreach ($financeDown as $index => $finance) {
            $dashboard['financeDown'][$index]['id'] = $finance->getId();
            $dashboard['financeDown'][$index]['title'] = $finance->getTitle();
            $dashboard['financeDown'][$index]['description'] = $finance->getDescription();
            $dashboard['financeDown'][$index]['created_at'] = $finance->getCreatedAt()->format('Y-m-d');
        }

        $this->response->setData($dashboard);
        $this->response->setStatus(JsonResponse::SUCCESS);
        $this->response->show();
    }

    public function addAction()
    {
        $title = (isset($_POST['title']) ? $_POST['title'] : null);
        $dateEnd = (isset($_POST['dateend']) ? $_POST['dateend'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $done = (isset($_POST['done']) ? $_POST['done'] : null);

        $finance = new \Models\Task();
        $finance->setTitle($title);
        $finance->setDateend($dateEnd);
        $finance->setDescription($description);
        $finance->setDone(false);

        $finance->save();

        $finance->setDateend($finance->getDateend()->format('Y-m-d'));

        $this->response->setData($finance->toArray());
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