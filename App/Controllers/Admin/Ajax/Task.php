<?php


namespace App\Controllers\Admin\Ajax;


use Core\Helper;
use Core\JsonResponse;
use DateTime;
use Models\LessonQuery;
use Models\TaskQuery;

class Task extends Base
{
    public function listAction()
    {
        $this->response = new JsonResponse();
        $tasks = TaskQuery::create()->find();
        $tasksEnded = TaskQuery::create()->filterByDone(true);

        $this->response->setData([$tasks, $tasksEnded]);
        $this->response->setStatus(JsonResponse::SUCCESS);
        $this->response->show();
    }

    public function addAction()
    {
        $title = (isset($_POST['title']) ? $_POST['title'] : null);
        $dateEnd = (isset($_POST['dateEnd']) ? $_POST['dateEnd'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $done = (isset($_POST['done']) ? $_POST['done'] : null);

        if ($dateEnd) {
            $dateArray = date_parse($dateEnd);
            $dateEnd = new DateTime(vsprintf('%s/%s/%s %s:%s', [
                $dateArray['month'],
                $dateArray['day'],
                $dateArray['year'],
                $dateArray['hour'],
                $dateArray['minute'],
            ]));
        }

        $lesson = new \Models\Task();
        $lesson->setTitle($title);
        $lesson->setDateend($dateEnd);
        $lesson->setDescription($description);
        $lesson->setDone(false);

        if (isset($_FILES['doc'])) {
            $helper = new Helper();
            $fileName = uniqid('doc_').'.'.pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['doc']['tmp_name'], $helper->getUploadDir().'/'.$fileName)) {
                $lesson->setDoc($fileName);
            }
        }
        $lesson->save();

        $this->response->setData($lesson->toArray());
        $this->response->setStatus(JsonResponse::SUCCESS);
        $this->response->show();
    }
}