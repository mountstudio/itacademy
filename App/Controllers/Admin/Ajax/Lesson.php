<?php
/**
 * Created by PhpStorm.
 * User: Tilek
 * Date: 19.08.2019
 * Time: 1:38
 */

namespace App\Controllers\Admin\Ajax;


use Core\JsonResponse;
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

    }
}