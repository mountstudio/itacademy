<?php
/**
 * Created by PhpStorm.
 * User: Tilek
 * Date: 21.07.2019
 * Time: 22:48
 */

namespace App\Controllers\Admin\Ajax\User;


use App\Controllers\Admin\Ajax\Base;
use Core\CustomException;
use Core\JsonResponse;
use Models\ConfigQuery;
use Models\CourseStreamQuery;
use Models\UserQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class Stream extends Base
{
    public function listAction()
    {
        $userId = (isset($_POST['userId']) ? $_POST['userId'] : null);
        $this->response = new JsonResponse(true);
        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');
            $paginator = $this->helper->paginator();

            $user = UserQuery::create()->findPk(intval($userId));
            if (is_null($user)){
                throw new CustomException("Пользователь не найден", 1);
            }

            $getDefaultOnRecruitment = ConfigQuery::create()->findOneByKey('course_stream_recruitment_status');

//            $user = $this->helper->getCurrentUser();
            $courseStreams = CourseStreamQuery::create()->filterByUser($user)->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
            $this->response->setPaginationDetails($courseStreams);
            $courseStreamData = array();
            foreach ($courseStreams as $courseStream) {
                $cost = $user->convertCurrency($courseStream->getCost(), $courseStream->getCurrentCourseStreamCurrency());
                $course = $courseStream->getCurrentCourseCourseStream();
                $status = $courseStream->getCurrentCourseCourseStreamStatus();
                $instructor = $courseStream->getCurrentCourseStreamInstructor();
                $courseStreamData[] = array(
                    'id' => $courseStream->getId(),
                    'name' => $courseStream->getName(),
                    'notes' => $courseStream->getNotes(),
                    'cost' => array(
                        'value' => $cost['value'],
                        'currency' => array(
                            'id' => $cost['currency']->getId(),
                            'name' => $cost['currency']->getName(),
                            'isoCode' => $cost['currency']->getISOCode()
                        )
                    ),
                    'course' => array(
                        'id' => $course->getId(),
                        'name' => $course->getName(),
                        'logo' => $course->getLogo()
                    ),
                    'status' => array(
                        'id' => $status->getId(),
                        'name' => $status->getName(),
                        'onRecruitment' => intval($getDefaultOnRecruitment->getValue()) == $status->getId()
                    ),
                    'instructor' => (is_null($instructor) ? null : array(
                        'id' => $instructor->getId(),
                        'name' => $instructor->getName(),
                        'logo' => $instructor->getLogo()
                    )),
                    'place' => array(
                        'free' => $courseStream->getNumberOfPlaces() - $courseStream->getNumberOfBusyPlaces(),
                        'busy' => $courseStream->getNumberOfBusyPlaces(),
                        'all' => $courseStream->getNumberOfPlaces()
                    ),
                    'startsAt' => $courseStream->getStartsAt(),
                    'endsAt' => $courseStream->getEndsAt(),
                    'duration' => $courseStream->getDuration(),
                    'createdAt' => $courseStream->getCreatedAt()
                );
            }
//            $this->response->setData((array) $courseStreams);
            $this->response->setData($courseStreamData);
            $this->response->setStatus(JsonResponse::SUCCESS);
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }


    public function addAction()
    {
        $userId = (isset($_POST['userId']) ? $_POST['userId'] : null);
        $streamId = (isset($_POST['streamId']) ? $_POST['streamId'] : null);

        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            if (is_null($userId) || intval($userId) == 0){
                throw new CustomException("Id пользователя не был указан", 1);
            }

            $user = UserQuery::create()->findPk($userId);
            if (is_null($user)) {
                throw new CustomException("Пользователя с таким id не существует", 1);
            }

            if (is_null($streamId) || intval($streamId) == 0){
                throw new CustomException("Id потока не был указан", 1);
            }

            $stream = CourseStreamQuery::create()->findPk($streamId);
            if (is_null($stream)) {
                throw new CustomException("Потока с таким id не существует", 1);
            }

            if ($stream->getNumberOfBusyPlaces() >= $stream->getNumberOfPlaces()) {
                $this->response->setStatus(JsonResponse::FAIL);
                $this->response->setMessage("Нет свободных мест в потоке");
                $this->response->setRedirect('/admin/users/' . $user->getId() . '/streams');
            } else {
                if (!$stream->getUsers()->contains($user)) {
                    $stream->setNumberOfBusyPlaces($stream->getNumberOfBusyPlaces() + 1);
                    $stream->addUser($user);
                    $stream->save();

                    $this->response->setStatus(JsonResponse::SUCCESS);
                    $this->response->setMessage("Пользователь успешно записан к потоку");
                    $this->response->setRedirect('/admin/users/' . $user->getId() . '/streams');
                } else {
                    $this->response->setStatus(JsonResponse::ERROR);
                    $this->response->setMessage("Пользователь уже зарегистрирован на поток");
                    $this->response->setRedirect('/admin/users/' . $user->getId() . '/streams');
                }
            }
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }
}