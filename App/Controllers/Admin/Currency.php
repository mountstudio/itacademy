<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;

use \Core\CustomException;

use \Models\ConfigQuery;
use Models\CurrencySalaryQuery;
use \Models\CurrencyStatusQuery;
use \Models\InstructorQuery;

use \Models\CurrencyQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Currency extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');

            View::renderTemplate('Admin/Currency/all.html', $this->data);
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
            $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');
            $currencyId = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($currencyId) || intval($currencyId) == 0){
                throw new CustomException("ID не был указан", 403);
            }

            $currency = CurrencyQuery::create()->findPk(intval($currencyId));
            if (is_null($currency)){
                throw new CustomException("Валюта не найдена", 404);
            }

            $this->data = array_merge( $this->data,
                array(
                    'currency' => $currency
                )
            );
            View::renderTemplate('Admin/Currency/edit.html', $this->data);
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
            $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');

            View::renderTemplate('Admin/Currency/add.html', $this->data);
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
