<?php

namespace App\Controllers\Admin\Ajax\Currency;

use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;


use \Models\ConfigQuery;
use Models\CourseStatusQuery;
use Models\CurrencyQuery;
use Models\CurrencyRate;
use Models\CurrencyRateQuery;
use Models\Instructor;
use \Models\UserQuery;

use \Models\CourseQuery;
use \Models\GroupQuery;


use Models\VacancyQuery;
use Models\VacancySalaryQuery;
use \Models\VerificationToken;
use \Models\VerificationTokenQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Rate extends \App\Controllers\Admin\Ajax\Base
{

     public function listAction()
     {
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');
             $paginator = $this->helper->paginator();
             $currencyRates = CurrencyRateQuery::create()->orderByCreatedAt(Criteria::DESC)->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

             $this->response->setPaginationDetails($currencyRates);
             $currencies_data = array();
             foreach ($currencyRates as $currencyRate) {
                 $defaultCurrency = $currencyRate->getCurrentDefaultCurrency();
                 $toCurrency = $currencyRate->getCurrentToCurrency();
                 $currencies_data[] = array(
                     'id' => $currencyRate->getId(),
                     'defaultCurrency' => array(
                         'id' => $defaultCurrency->getId(),
                         'isoCode' => $defaultCurrency->getISOCode(),
                         'name' => $defaultCurrency->getName()
                     ),
                     'toCurrency' => array(
                         'id' => $toCurrency->getId(),
                         'isoCode' => $toCurrency->getISOCode(),
                         'name' => $toCurrency->getName()
                     ),
                     'value' => $currencyRate->getRate(),
                     'createdAt' => date_format($currencyRate->getCreatedAt(), 'Y-m-d H:i:s')
                 );
             }
             $this->response->setData($currencies_data);
             $this->response->setStatus(JsonResponse::SUCCESS);
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }


    public function addAction()
    {
        $rates = (isset($_POST['rates']) ? $_POST['rates'] : null);

        try {
            $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');


            if (is_null($rates) || !is_array($rates) ){
                throw new CustomException("Курсы валют не были введены", 1);
            }

            $defaultCurrencyConf = ConfigQuery::create()->findOneByKey('default_currency_id');
            $defaultCurrencyId = intval($defaultCurrencyConf->getValue());
            $defaultCurrency = CurrencyQuery::create()->findPk($defaultCurrencyId);

            foreach ($rates as $rateId => $rateValue) {
                $currencyRate = new CurrencyRate();
                $currencyRate->setCurrentDefaultCurrency($defaultCurrency);

                $toCurrency = CurrencyQuery::create()->findPk(intval($rateId));
                $currencyRate->setCurrentToCurrency($toCurrency);

                $currencyRate->setRate(floatval($rateValue));
                $currencyRate->save();
            }

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Курсы валют успешно созданы");
            $this->response->setRedirect('/admin/currencies/rates');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
