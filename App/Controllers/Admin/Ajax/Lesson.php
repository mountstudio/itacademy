<?php
/**
 * Created by PhpStorm.
 * User: Tilek
 * Date: 19.08.2019
 * Time: 1:38
 */

namespace App\Controllers\Admin\Ajax;


use Core\CustomException;
use Core\Helper;
use Core\JsonResponse;
use DateTime;
use Models\LessonQuery;
use Models\StreamLessonQuery;

class Lesson extends Base
{
    public function listAction()
    {
        $this->response = new JsonResponse();
        $events = LessonQuery::create()->find();

        $eventsArray = [];
        foreach ($events as $event) {
            $eventsArray[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'dateStart' => $event->getDateStart(),
                'dateEnd' => $event->getDateEnd(),
                'doc' => $event->getDoc(),
                'video_link' => $event->getVideoLink(),
                'allDay' => $event->getAllDay(),
            ];
        }

        $this->response->setData($eventsArray);
        $this->response->setStatus(JsonResponse::SUCCESS);
        $this->response->show();
    }

    public function addAction()
    {
        $title = (isset($_POST['title']) ? $_POST['title'] : null);
        $startDate = (isset($_POST['startDate']) ? $_POST['startDate'] : null);
        $endDate = (isset($_POST['endDate']) ? $_POST['endDate'] : null);
        $allDay = (isset($_POST['allDay']) ? $_POST['allDay'] : null);

        if ($startDate) {
            $dateArray = date_parse($startDate);
            $startDate = new DateTime(vsprintf('%s/%s/%s %s:%s', [
                $dateArray['month'],
                $dateArray['day'],
                $dateArray['year'],
                $dateArray['hour'],
                $dateArray['minute'],
            ]));
            $date = new DateTime(vsprintf('%s/%s/%s %s:%s', [
                $dateArray['month'],
                $dateArray['day'],
                $dateArray['year'],
                $dateArray['hour'],
                $dateArray['minute'],
            ]));
            if ($endDate) {
                $endDate = $date->add(new \DateInterval("PT".$endDate."H"));
            }
        }

        $lesson = new \Models\Lesson();
        $lesson->setTitle($title);
        $lesson->setDateStart($startDate);
        $lesson->setDateEnd($endDate);
        $lesson->setAllDay($allDay);

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

    public function deleteAction()
    {
        $id = (isset($_POST['lesson_id']) ? $_POST['lesson_id'] : null);
        try {
            $this->helper->shouldHavePrivilege('CREATE_LESSON');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $lesson = LessonQuery::create()->findOneById(intval($id));
            if (is_null($lesson)){
                throw new CustomException("Урок не найден", 1);
            }

            $lesson->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Урок была успешно удален');

            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }
}