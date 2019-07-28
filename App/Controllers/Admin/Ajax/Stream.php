<?php
/**
 * Created by PhpStorm.
 * User: Tilek
 * Date: 21.07.2019
 * Time: 22:48
 */

namespace App\Controllers\Admin\Ajax;


use App\Config;
use App\Controllers\Admin\Ajax\Base;
use Core\CustomException;
use Core\JsonResponse;
use Models\ConfigQuery;
use Models\CourseStreamQuery;
use Models\GroupQuery;
use Models\Passport;
use Models\UserQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class Stream extends Base
{
    public function listAction()
    {
        $this->response = new JsonResponse(true);
        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');
            $paginator = $this->helper->paginator();

            $getDefaultOnRecruitment = ConfigQuery::create()->findOneByKey('course_stream_recruitment_status');

            $user = $this->helper->getCurrentUser();
            $courseStreams = CourseStreamQuery::create()->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
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
        $email = (isset($_POST['email']) ? $_POST['email'] : null);
        $groupId = (isset($_POST['groupId']) ? $_POST['groupId'] : null);
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $userName = (isset($_POST['userName']) ? $_POST['userName'] : null);
        $birthDate = (isset($_POST['birthDate']) ? $_POST['birthDate'] : null);
        $password = ((isset($_POST['password']) && strlen($_POST['password']) > 0) ? $_POST['password'] : null);
        $passwordConfirmation = ((isset($_POST['confirmPassword']) && strlen($_POST['confirmPassword']) > 0) ? $_POST['confirmPassword'] : null);
        $phone = (isset($_POST['phone']) ? $_POST['phone'] : null);
        $address = (isset($_POST['address']) ? $_POST['address'] : null);
        $activated = (isset($_POST['activated']) ? (($_POST['activated'] == 'true') ? true : false) : null);
        $about = (isset($_POST['about']) ? $_POST['about'] : null);
        $serial = (isset($_POST['serial']) ? $_POST['serial'] : null);
        $inn = (isset($_POST['inn']) ? $_POST['inn'] : null);
        $operator = (isset($_POST['operator']) ? $_POST['operator'] : null);
        $startDate = (isset($_POST['startDate']) ? $_POST['startDate'] : null);
        $streamId = (isset($_POST['streamId']) ? $_POST['streamId'] : null);

        try {
            $this->helper->shouldHavePrivilege('COURSE_STREAM_ADMIN');

            if (is_null($streamId) || intval($streamId) == 0){
                throw new CustomException("Id потока не был указан", 1);
            }

            $stream = CourseStreamQuery::create()->findPk($streamId);
            if (is_null($stream)) {
                throw new CustomException("Потока с таким id не существует", 1);
            }

            $user = new \Models\User();
            $passport = new Passport();

            if (is_null($email) || empty(trim($email)) ){
                throw new CustomException("Email не был введен", 1);
            }

            if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) == false){
                throw new CustomException("Неверный email", 1);
            }

            $userByEmail = UserQuery::create()->findOneByEmail(trim($email));

            if (!is_null($userByEmail)){
                throw new CustomException("Такой email существует", 1);
            }

            if (!is_null($userName)){
                if (empty(trim($userName))){
                    $user->setUserName(null);
                } elseif (strlen(trim($userName)) < 3){
                    throw new CustomException("Длина логина должна быть больше 3 символов", 1);
                } else {
                    $userByUserName = UserQuery::create()->findOneByUserName(trim($userName));
                    if (!is_null($userByUserName)){
                        throw new CustomException("Такой логин занят", 1);
                    }
                    $user->setUserName(trim($userName));
                }
            }

            $userByEmail = UserQuery::create()->findOneByEmail(trim($email));

            if (!is_null($userByEmail)){
                throw new CustomException("Такой email существует", 1);
            }

            if (is_null($groupId) || intval($groupId) == 0){
                throw new CustomException("ID группы не был указан", 1);
            }

            $group = GroupQuery::create()->findPK($groupId);
            if (is_null($group)){
                throw new CustomException("Группа не найдена", 1);
            }



            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Имя не была введена", 1);
            }

            if (is_null($birthDate)){
                throw new CustomException("Дата рождения не была введена", 1);
            } elseif (!empty(trim($birthDate))) {
                $user->setBirthDate(\DateTime::createFromFormat('Y-m-d', $birthDate));
            } else {
                $user->setBirthDate(null);
            }

            if (!is_null($password) && strlen(trim($password)) < 6){
                throw new CustomException("Длина пароля должно быть больше 6 символов", 1);
            } elseif (is_null($password)){
                throw new CustomException("Пароль не был введен", 1);
            } elseif ($password != $passwordConfirmation){
                throw new CustomException("Пароли не совпадают", 1);
            }


            if (!empty(trim($phone))) {
                $phone = str_replace("(","",str_replace(")","",str_replace("-","",str_replace(" ","",$phone))));
            }

            if (!empty(trim($phone)) && !preg_match('/^[0-9]+$/', trim($phone))){
                throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
            } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12 && substr(trim($phone),0, 3) != "996") {
                throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
            } elseif (!empty(trim($phone))) {
                $user->setPhone(trim($phone));
            }

            if (is_null($serial)){
                throw new CustomException("Серийный номер документа не был указан", 1);
            }
            if (is_null($inn)){
                throw new CustomException("ИНН не был указан", 1);
            }
            if (is_null($startDate)){
                throw new CustomException("Дата выдачи не была введена", 1);
            } elseif (!empty(trim($startDate))) {
                $passport->setstartDate(\DateTime::createFromFormat('Y-m-d', $startDate));
            } else {
                $passport->setstartDate(null);
            }
            if (is_null($operator)){
                throw new CustomException("Не было указано кем выдан документ ", 1);
            }

            $passport->setserial($serial);
            $passport->setinn($inn);
            $passport->setoperator($operator);
            $user->setEmail($email);
            $user->setName($name);
            $user->setCurrentGroup($group);
            $user->setAbout(trim($about));
            $cryptedPassword = crypt(trim($password), Config::PASSWORD_SALT);
            $user->setPassword($cryptedPassword);
            $user->setActivated($activated);

            if (!is_null(trim($address)) && !empty(trim($address))){
                $user->setAddress($address);
            }

            if (!is_null($activated)) $user->setActivated($activated);

            $user->save();

            $user->setPassport($passport);
            $passport->save();

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