<?php

namespace App\Controllers\Admin\Currency;

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
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Rate extends \App\Controllers\Admin\Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('CURRENCY_RATE_ADMIN');

            View::renderTemplate('Admin/Currency/Rate/all.html', $this->data);
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
            $this->helper->shouldHavePrivilege('CURRENCY_RATE_ADMIN');


            $defaultCurrencyConf = ConfigQuery::create()->findOneByKey('default_currency_id');
            $defaultCurrencyId = intval($defaultCurrencyConf->getValue());

            $currencies = CurrencyQuery::create()->orderBySortableRank()->find();

            $defaultCurrency = null;
            $currenciesData = array();


            foreach ($currencies as $currency) {
                if ($currency->getId() == $defaultCurrencyId){
                    $defaultCurrency = $currency;
                } else {
                    $currenciesData[] = $currency;
                }
            }

            $this->data = array_merge( $this->data,
                array(
                    'currencies' => $currenciesData,
                    'defaultCurrency' => $defaultCurrency
                )
            );

            View::renderTemplate('Admin/Currency/Rate/add.html', $this->data);
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
