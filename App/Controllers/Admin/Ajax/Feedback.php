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

use Models\CourseQuery;
use Models\CurrencyQuery;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\Product;
use \Models\ProductQuery;

use \Models\StaticPageQuery;


use \Models\FeedbackStatusQuery;
use Models\FeedbackQuery;

use \Models\PaymentTypeQuery;
use \Models\PlaceQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Feedback extends Base
{

    public function listAction()
    {
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldBeLoggedIn();
            $paginator = $this->helper->paginator();
            $this->helper->hasPrivilege('LEAVE_FEEDBACK');
            $feedbacks = FeedbackQuery::create()->filterByCurrentFeedbackUser($this->helper->getCurrentUser())->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);


            $this->response->setPaginationDetails($feedbacks);
            $feedbacksData = array();

            foreach ($feedbacks as $feedback) {
                $currency = $feedback->getCurrentFeedbackCurrency();
                $feedbacksData[] = array(
                    'id' => $feedback->getId(),
                    'workPlace' => $feedback->getWorkPlace(),
                    'salary' => $feedback->getSalary(),
                    'currency' => (is_null($currency) ? null : array(
                        'id' => $currency->getId(),
                        'name' => $currency->getName(),
                        'ISOCode' => $currency->getISOCode(),
                        'symbol' => $currency->getSymbol()
                    )),
                    'createdAt' => $feedback->getCreatedAt(),
                    'updatedAt' => $feedback->getUpdatedAt(),
                    'isAvailable' => $feedback->getAvailable(),
                    'content' => $feedback->getContent()
                );
            }

            $this->response->setData($feedbacksData);
            $this->response->setStatus(JsonResponse::SUCCESS);
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }


    public function listAdminAction()
    {
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldBeLoggedIn();
            $paginator = $this->helper->paginator();


            $this->helper->hasPrivilege('FEEDBACK_ADMIN');

            $isAvailable = (isset($_POST['isAvailable']) ? $_POST['isAvailable'] : null);


            if (intval($isAvailable) == 0) {
                $feedbacks = FeedbackQuery::create()->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
            } else {
                if (intval($isAvailable) == 1){
                    $isAvailable = 0;
                } elseif (intval($isAvailable) == 2){
                    $isAvailable = 1;
                }
                $feedbacks = FeedbackQuery::create()->filterByAvailable($isAvailable)->orderById('desc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);
            }

            $this->response->setPaginationDetails($feedbacks);
            $feedbacksData = array();

            foreach ($feedbacks as $feedback) {
                $user = $feedback->getCurrentFeedbackUser();
                $currency = $feedback->getCurrentFeedbackCurrency();
                $feedbacksData[] = array(
                    'id' => $feedback->getId(),
                    'workPlace' => $feedback->getWorkPlace(),
                    'salary' => $feedback->getSalary(),
                    'currency' => (is_null($currency) ? null : array(
                        'id' => $currency->getId(),
                        'name' => $currency->getName(),
                        'ISOCode' => $currency->getISOCode(),
                        'symbol' => $currency->getSymbol()
                    )),
                    'user' => array(
                        'id' => $user->getId(),
                        'name' => $user->getName(),
                        'logo' => $user->getLogo()
                    ),
                    'createdAt' => $feedback->getCreatedAt(),
                    'updatedAt' => $feedback->getUpdatedAt(),
                    'isAvailable' => $feedback->getAvailable(),
                    'content' => $feedback->getContent(),
                    'notes' => $feedback->getNotes()
                );
            }

            $this->response->setData($feedbacksData);
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
            $this->helper->shouldHavePrivilege('LEAVE_FEEDBACK');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $feedback = FeedbackQuery::create()->filterByCurrentFeedbackUser($this->helper->getCurrentUser())->findOneById(intval($id));
            if (is_null($feedback)){
                throw new CustomException("Отзыв не найден", 1);
            }

            $feedback->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Отзыв была успешно удален');

            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/feedbacks/my');
            }

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function updateAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);
        $isAvailable = (isset($_POST['isAvailable']) ? (($_POST['isAvailable'] == 'true') ? true : false) : null);

        try {
            $this->helper->shouldHavePrivilege('FEEDBACK_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $feedback = FeedbackQuery::create()->findPK(intval($id));
            if (is_null($feedback)){
                throw new CustomException("Отзыв не найден", 1);
            }

            if (is_null($isAvailable)){
                throw new CustomException("Чекбокс доступ на отзыв не был указан", 1);
            }

            if (is_null($notes)){
                throw new CustomException("Заметки не были введены", 1);
            }

            $feedback->setNotes(trim($notes));
            $feedback->setAvailable($isAvailable);
            $feedback->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Отзыв успешно обновлен");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }


    public function editAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $content = (isset($_POST['content']) ? $_POST['content'] : null);
        $workPlace = (isset($_POST['workPlace']) ? $_POST['workPlace'] : null);
        $salary = (isset($_POST['salary']) ? $_POST['salary'] : null);
        $currencyId = (isset($_POST['currencyId']) ? $_POST['currencyId'] : null);

        try {
            $this->helper->shouldHavePrivilege('LEAVE_FEEDBACK');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $feedback = FeedbackQuery::create()->filterByCurrentFeedbackUser($this->helper->getCurrentUser())->findOneById(intval($id));
            if (is_null($feedback)){
                throw new CustomException("Отзыв не найден", 1);
            }

            if (!($feedback->getContent() == $content && $feedback->getWorkPlace() == $workPlace && $feedback->getSalary() == $salary && $feedback->getCurrentCurrencyId() == $currencyId)){
                if (is_null($content) || empty(trim($content)) ){
                    throw new CustomException("Контент не был введен", 1);
                }
                $feedback->setContent(trim($content));

                if (is_null($workPlace)){
                    throw new CustomException("Место работы введено неверно", 1);
                } elseif (!is_null($workPlace) && !empty(trim($workPlace))){
                    $feedback->setWorkPlace(trim($workPlace));
                }

                if (!is_null($salary) && !empty(trim($salary)) && intval($salary) == 0){
                    throw new CustomException("Зарплата введена неверно", 1);
                } elseif (!is_null($salary)) {
                    if (is_null($currencyId)){
                        throw new CustomException("Неправильное ID валюты", 1);
                    } elseif (!is_null($currencyId) && intval($currencyId) != 0){
                        $currency = CurrencyQuery::create()->findPK(intval($currencyId));
                        if (is_null($currency)){
                            throw new CustomException("Валюта не найдена", 1);
                        }
                        $feedback->setSalary(floatval($salary));
                        $feedback->setCurrentFeedbackCurrency($currency);
                    }
                }
                $feedback->setCurrentFeedbackUser($this->helper->getCurrentUser());

                $feedback->setAvailable(false);
                $feedback->save();
            }

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Отзыв успешно изменена");
            $this->response->setRedirect('/admin/feedbacks/my');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function addAction()
    {
        $content = (isset($_POST['content']) ? $_POST['content'] : null);
        $workPlace = (isset($_POST['workPlace']) ? $_POST['workPlace'] : null);
        $salary = (isset($_POST['salary']) ? $_POST['salary'] : null);
        $currencyId = (isset($_POST['currencyId']) ? $_POST['currencyId'] : null);

        try {
            $this->helper->shouldHavePrivilege('LEAVE_FEEDBACK');

            $feedback = new \Models\Feedback();
            if (is_null($content) || empty(trim($content)) ){
                throw new CustomException("Контент не был введен", 1);
            }
            $feedback->setContent(trim($content));

            if (is_null($workPlace)){
                throw new CustomException("Место работы введено неверно", 1);
            } elseif (!is_null($workPlace) && !empty(trim($workPlace))){
                $feedback->setWorkPlace(trim($workPlace));
            }

            if (!is_null($salary) && !empty(trim($salary)) && intval($salary) == 0){
                throw new CustomException("Зарплата введена неверно", 1);
            } elseif (!is_null($salary)) {
                if (!is_null($currencyId) && intval($currencyId) < 0){
                    throw new CustomException("Неправильное ID валюты", 1);
                } elseif (!is_null($currencyId) && intval($currencyId) != 0){
                    $currency = CurrencyQuery::create()->findPK(intval($currencyId));
                    if (is_null($currency)){
                        throw new CustomException("Валюта не найдена", 1);
                    }
                    $feedback->setSalary(floatval($salary));
                    $feedback->setCurrentFeedbackCurrency($currency);
                }
            }
            $feedback->setCurrentFeedbackUser($this->helper->getCurrentUser());
            $feedback->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Отзыв успешно создан");

            $this->response->setRedirect('/admin/feedbacks/my');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
