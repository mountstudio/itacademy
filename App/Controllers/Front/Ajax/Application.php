<?php

namespace App\Controllers\Front\Ajax;

use Core\Telegram;
use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;

use Models\ConfigQuery;
use Models\CourseQuery;
use Models\CourseStreamQuery;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\Product;
use \Models\ProductQuery;

use \Models\StaticPageQuery;


use \Models\ApplicationStatusQuery;
use \Models\ApplicationQuery;

use \Models\PaymentTypeQuery;
use \Models\PlaceQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Application extends Base
{

    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $phone = (isset($_POST['tel']) ? $_POST['tel'] : null);
        $description = (isset($_POST['description']) ? $_POST['description'] : null);
        $courseId = (isset($_POST['courseId']) ? $_POST['courseId'] : null);
        $courseStreamId = (isset($_POST['courseStreamId']) ? $_POST['courseStreamId'] : null);
        try {

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Имя заявителя не была введена", 1);
            }

            if (is_null($phone) || empty(trim($phone)) ){
                throw new CustomException("Номер телефона заявителя не был введен", 1);
            }

            $application = new \Models\Application();

            if (!is_null($description) && !empty(trim($description)) ){
                $application->setDescription(trim($description));
            }


            $configDefaultApplicationStatus = ConfigQuery::create()->findOneByKey('default_application_status');

            if (is_null($configDefaultApplicationStatus)){
                throw new CustomException("Состояние заявки по умолчанию не была настроена. Свяжитесь с администратором", 1);
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPK(intval($configDefaultApplicationStatus->getValue()));
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 1);
            }

            $application->setCurrentApplicationStatus($applicationStatus);

            if (!is_null($courseId) && intval($courseId) != 0){
                $course = CourseQuery::create()->findPk(intval($courseId));
                if (is_null($course)){
                    throw new CustomException("Курс не найден", 1);
                }
                $application->setCurrentCourseApplication($course);

                if (!is_null($courseStreamId) && intval($courseStreamId) != 0){
                    $courseStream = CourseStreamQuery::create()->findPk(intval($courseStreamId));
                    if (is_null($courseStream)){
                        throw new CustomException("Поток курса не найден", 1);
                    }
                    $application->setCurrentCourseStreamApplication($courseStream);
                }
            }


            $application->setName(trim($name));
            $application->setPhone(trim($phone));
            $application->save();


            $telegram = new Telegram();
            $telegram->set();
            $telegram->setContent(
                View::renderTemplate('Telegram/Application/new.twig', array(
                    'application' => $application
                ), false)
            );
            $telegram->send();

            $mail = new Mail();
            $mail->sendApplication($application);
            $mail->send();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Заявка создана успешо");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
