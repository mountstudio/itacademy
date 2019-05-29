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


use \Models\ConfigQuery;
use Models\CourseStatusQuery;
use Models\CurrencyQuery;
use Models\Instructor;
use \Models\UserQuery;

use \Models\CourseQuery;
use \Models\GroupQuery;


use Models\VacancyQuery;
use Models\VacancySalaryQuery;
use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Currency extends Base
{

     public function listAction()
     {
         $this->response = new JsonResponse($pagination = true);
         try {
             $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');
             $paginator = $this->helper->paginator();
             $currencies = CurrencyQuery::create()->orderBySortableRank()->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

             $this->response->setPaginationDetails($currencies);
             $currencies_data = array();
             foreach ($currencies as $currency) {

                 $currencies_data[] = array(
                     'id' => $currency->getId(),
                     'name' => $currency->getName(),
                     'isoCode' => $currency->getISOCode(),
                     'symbol' => $currency->getSymbol(),
                     'is_symbol_before' => $currency->isSymbolBefore(),
                     'notes' => $currency->getNotes(),
                     'actions' => array(
                         'isFirst' => $currency->isFirst(),
                         'isLast' => $currency->isLast()
                     ),
                    'currentRate' => $currency->getCurrentRate()
                 );
             }
             $this->response->setData($currencies_data);
             $this->response->setStatus(JsonResponse::SUCCESS);
         } catch (CustomException $e) {
             $this->response->setException($e);
         }

         $this->response->show();
     }


    public function updateAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $action = (isset($_POST['action']) ? $_POST['action'] : null);
        try {
            $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("Неверный ID", 1);
            }

            $currency = CurrencyQuery::create()->findPK(intval($id));
            if (is_null($currency)){
                throw new CustomException("Валюта не найдена", 1);
            }


            switch ($action){
                case 'toTop':
                    $currency->moveUp();
                    $this->response->setMessage("Валюта успешно перемещен вверх", 0);
                    break;
                case 'toBottom':
                    $currency->moveDown();
                    $this->response->setMessage("Валюта успешно перемещен вниз", 0);
                    break;
                default:
                    throw new CustomException("Неправильное действие", 1);
            }
            $currency->save();
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
            $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $currency = CurrencyQuery::create()->findPK(intval($id));
            if (is_null($currency)){
                throw new CustomException("Валюта не найдена", 1);
            }

            $currency->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Валюта успешно удалена');
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/currencies/all');
            }
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }


    public function editAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $isoCode = (isset($_POST['isoCode']) ? $_POST['isoCode'] : null);
        $symbol = (isset($_POST['symbol']) ? $_POST['symbol'] : null);
        $isSymbolBefore = (isset($_POST['isSymbolBefore']) ? (($_POST['isSymbolBefore'] == 'true') ? true : false) : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);

        try {
            $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 1);
            }

            $currency = CurrencyQuery::create()->findPK(intval($id));
            if (is_null($currency)){
                throw new CustomException("Валюта не найдена", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            if (trim($name) != $currency->getName()){
                $currencyByName = CurrencyQuery::create()->findOneByName(trim($name));
                if (!is_null($currencyByName)) {
                    throw new CustomException("Такое название валюты существует", 1);
                }
            }

            if (is_null($isoCode)){
                throw new CustomException("ISO код не был указан", 1);
            }

            if (is_null($symbol) ){
                throw new CustomException("Символ не был указан", 1);
            }

            if (is_null($isSymbolBefore) ){
                throw new CustomException("Пред-символ валюты не был указан", 1);
            }
            if (is_null($notes) ){
                throw new CustomException("Заметки не были указаны", 1);
            }

            $currency->setName(trim($name));
            $currency->setISOCode(trim($isoCode));
            $currency->setSymbol(trim($symbol));
            $currency->setIsSymbolBefore($isSymbolBefore);
            $currency->setNotes(trim($notes));


            $currency->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Валюта успешно сохранена");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }




    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $isoCode = (isset($_POST['isoCode']) ? $_POST['isoCode'] : null);
        $symbol = (isset($_POST['symbol']) ? $_POST['symbol'] : null);
        $isSymbolBefore = (isset($_POST['isSymbolBefore']) ? (($_POST['isSymbolBefore'] == 'true') ? true : false) : null);
        $notes = (isset($_POST['notes']) ? $_POST['notes'] : null);

        try {
            $this->helper->shouldHavePrivilege('CURRENCY_ADMIN');


            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Название не было введено", 1);
            }

            $currencyByName = CurrencyQuery::create()->findOneByName(trim($name));
            if (!is_null($currencyByName)) {
                throw new CustomException("Такое название валюты существует", 1);
            }

            if (is_null($isoCode)){
                throw new CustomException("ISO код не был указан", 1);
            }

            if (is_null($symbol) ){
                throw new CustomException("Символ не был указан", 1);
            }

            if (is_null($isSymbolBefore) ){
                throw new CustomException("Пред-символ валюты не был указан", 1);
            }
            if (is_null($notes) ){
                throw new CustomException("Заметки не были указаны", 1);
            }
            $currency = new \Models\Currency();
            $currency->setName(trim($name));
            $currency->setISOCode(trim($isoCode));
            $currency->setSymbol(trim($symbol));
            $currency->setIsSymbolBefore($isSymbolBefore);
            $currency->setNotes(trim($notes));


            $currency->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Валюта успешно создана");
            $this->response->setRedirect('/admin/currencies/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
