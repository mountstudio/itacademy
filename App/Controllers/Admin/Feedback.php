<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\Group;
use \Models\CurrencyQuery;


use \Models\FeedbackQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Feedback extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('LEAVE_FEEDBACK');

            View::renderTemplate('Admin/Feedback/all.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }
    }


    public function indexAdminAction()
    {
        try {
            $this->helper->shouldHavePrivilege('FEEDBACK_ADMIN');

            View::renderTemplate('Admin/Feedback/Admin/all.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }
    }

    public function editAction()
    {
        try {
            $this->helper->shouldHaveOneOfPrivileges(array(
                'LEAVE_FEEDBACK',
                'FEEDBACK_ADMIN'
            ));



            $feedbackId = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($feedbackId) || intval($feedbackId) == 0){
                throw new CustomException("ID не был указан", 403);
            }
            $feedback = null;
            if ($this->helper->hasPrivilege('FEEDBACK_ADMIN')){
                $feedback = FeedbackQuery::create()->findPk(intval($feedbackId));
            } else {
                $feedback = FeedbackQuery::create()->filterByCurrentFeedbackUser($this->helper->getCurrentUser())->findOneById(intval($feedbackId));
            }

            if (is_null($feedback)){
                throw new CustomException("Отзыв не найден", 404);
            }

            $currencies = CurrencyQuery::create()->find();

            $this->data = array_merge( $this->data,
                array(
                    'feedback' => $feedback,
                    'currencies' => $currencies
                )
            );
            View::renderTemplate('Admin/Feedback/edit.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(
                    'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }
    }

    public function addAction()
    {

        try {
            $this->helper->shouldHavePrivilege('LEAVE_FEEDBACK');

            $currencies = CurrencyQuery::create()->find();

            $this->data = array_merge( $this->data,
                array(
                    'currencies' => $currencies
                )
            );
            View::renderTemplate('Admin/Feedback/add.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }

    }
}
