<?php


namespace App\Controllers\Admin\Ajax;


use Core\CustomException;
use Core\Helper;
use Core\JsonResponse;
use DateTime;
use Models\TaskQuery;

class Task extends Base
{
    public function listAction()
    {
        $this->response = new JsonResponse();
        $tasks = TaskQuery::create()->filterByDone(false);
        $tasksEnded = TaskQuery::create()->filterByDone(true);

        $dashboard = [];

        foreach ($tasks as $index => $task) {
            $dashboard['tasks'][$index]['id'] = $task->getId();
            $dashboard['tasks'][$index]['title'] = $task->getTitle();
            $dashboard['tasks'][$index]['description'] = $task->getDescription();
            $dashboard['tasks'][$index]['dateend'] = $task->getDateend()->format('Y-m-d');
        }

        foreach ($tasksEnded as $index => $task) {
            $dashboard['tasksEnded'][$index]['id'] = $task->getId();
            $dashboard['tasksEnded'][$index]['title'] = $task->getTitle();
            $dashboard['tasksEnded'][$index]['description'] = $task->getDescription();
            $dashboard['tasksEnded'][$index]['dateend'] = $task->getDateend()->format('Y-m-d');
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

        $task = new \Models\Task();
        $task->setTitle($title);
        $task->setDateend($dateEnd);
        $task->setDescription($description);
        $task->setDone(false);

        $task->save();

        $task->setDateend($task->getDateend()->format('Y-m-d'));

        $this->response->setData($task->toArray());
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

            $task = TaskQuery::create()->findOneById(intval($id));
            if (is_null($task)){
                throw new CustomException("Задание не найдено", 1);
            }

            $task->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Задание было успешно удалено');

            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }
}