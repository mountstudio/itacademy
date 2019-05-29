<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\Group;
use \Models\GroupQuery;


use \Models\FAQQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class FAQ extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('FAQ_ADMIN');

            View::renderTemplate('Admin/FAQ/all.html', $this->data);
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
            $this->helper->shouldHavePrivilege('FAQ_ADMIN');
            $faqId = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($faqId) || intval($faqId) == 0){
                throw new CustomException("ID FAQ не был указан", 403);
            }

            $faq = FAQQuery::create()->findPk(intval($faqId));
            if (is_null($faq)){
                throw new CustomException("FAQ не найден", 404);
            }

            $this->data = array_merge( $this->data,
                array(  'faq' => $faq
                )
            );
            View::renderTemplate('Admin/FAQ/edit.html', $this->data);
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

    public function addAction()
    {
        try {
            $this->helper->shouldHavePrivilege('FAQ_ADMIN');
            View::renderTemplate('Admin/FAQ/add.html', $this->data);
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
