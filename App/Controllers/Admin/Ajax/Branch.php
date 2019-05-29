<?php

namespace App\Controllers\Admin\Ajax;

use Core\Model\Branch\GeographicCoordinates;
use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;

use Models\BranchQuery;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\StaticPageQuery;


use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Branch extends Base
{

    public function listAction()
    {
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldHavePrivilege('BRANCH_ADMIN');
            $paginator = $this->helper->paginator();
            $branches = BranchQuery::create()->orderBySortableRank()->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

            $this->response->setPaginationDetails($branches);
            $branchesData = array();
            foreach ($branches as $branch) {
                $branchesData[] = array(
                    'id' => $branch->getId(),
                    'name' => $branch->getName(),
                    'address' => $branch->getAddress(),
                    'geographicCoordinates' => $branch->getGeographicCoordinates(),
                    'tel' => $branch->getTel(),
                    'email' => $branch->getEmail(),
                    'actions' => array(
                        'isFirst' => $branch->isFirst(),
                        'isLast' => $branch->isLast()
                    ),
                );
            }
            $this->response->setData($branchesData);
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
            $this->helper->shouldHavePrivilege('BRANCH_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("Id не был указан", 1);
            }

            $branch = BranchQuery::create()->findPK(intval($id));
            if (is_null($branch)){
                throw new CustomException("Контакт не найден", 1);
            }

            $branch->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('Контакт успешно удален');
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/branches/all');
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
        $showOnWebSite = (isset($_POST['showOnWebSite']) ? (($_POST['showOnWebSite'] == 'true') ? true : false) : null);


        $address = (isset($_POST['address']) ? $_POST['address'] : null);
        $geographicCoordinates = (isset($_POST['geographicCoordinates']) ? $_POST['geographicCoordinates'] : null);
        $tel = (isset($_POST['tel']) ? $_POST['tel'] : null);
        $email = (isset($_POST['email']) ? $_POST['email'] : null);

        $instagramLink = (isset($_POST['instagramLink']) ? $_POST['instagramLink'] : null);
        $facebookLink = (isset($_POST['facebookLink']) ? $_POST['facebookLink'] : null);

        try {
            $this->helper->shouldHavePrivilege('BRANCH_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("Id не был указан", 1);
            }

            $branch = BranchQuery::create()->findPK(intval($id));
            if (is_null($branch)){
                throw new CustomException("Филиал не найден", 1);
            }

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Имя не был введен", 1);
            }

            if (is_null($showOnWebSite) ){
                throw new CustomException("Показать на сайте не был введен", 1);
            }

            if (is_null($address) || empty(trim($address)) ){
                throw new CustomException("Адрес не был введен", 1);
            }

            if (is_null($tel) || empty(trim($tel)) ){
                throw new CustomException("Номер телефона не был введен", 1);
            }

            if (is_null($email) || empty(trim($email)) ){
                throw new CustomException("Email не был введен", 1);
            }

            if (is_null($instagramLink)){
                throw new CustomException("Ссылка Instagram не был введен", 1);
            }

            if (is_null($facebookLink)){
                throw new CustomException("Ссылка Facebook не был введен", 1);
            }

            if (is_null($geographicCoordinates) || !is_array($geographicCoordinates) || sizeof($geographicCoordinates) != 2){
                throw new CustomException("Географические координаты введены некорректно", 1);
            }

            if (!empty(trim($instagramLink))){
                $branch->setInstagramLink(trim($instagramLink));
            } else {
                $branch->setInstagramLink(null);
            }

            if (!empty(trim($facebookLink))){
                $branch->setFacebookLink(trim($facebookLink));
            } else {
                $branch->setFacebookLink(null);
            }

            $branch->setName(trim($name));
            $branch->setShowOnWebSite($showOnWebSite);
            $branch->setAddress(trim($address));
            $branch->setEmail(trim($email));
            $branch->setTel(trim($tel));
            $branch->setGeographicCoordinates(new GeographicCoordinates($geographicCoordinates['long'], $geographicCoordinates['lat']));

            $branch->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Филиал успешно сохранен");
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
            $this->helper->shouldHavePrivilege('BRANCH_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("Неверный id", 1);
            }

            $branch = BranchQuery::create()->findPK(intval($id));
            if (is_null($branch)){
                throw new CustomException("Филиал не найден", 1);
            }

            switch ($action){
                case 'toTop':
                    $branch->moveUp();
                    $this->response->setMessage("Филиал успешно перемещен вверх", 0);
                    break;
                case 'toBottom':
                    $branch->moveDown();
                    $this->response->setMessage("Филиал успешно перемещен вверх", 0);
                    break;
                default:
                    throw new CustomException("Неправильное действие", 1);
            }

            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function addAction()
    {
        $name = (isset($_POST['name']) ? $_POST['name'] : null);
        $showOnWebSite = (isset($_POST['showOnWebSite']) ? (($_POST['showOnWebSite'] == 'true') ? true : false) : null);


        $address = (isset($_POST['address']) ? $_POST['address'] : null);
        $geographicCoordinates = (isset($_POST['geographicCoordinates']) ? $_POST['geographicCoordinates'] : null);
        $tel = (isset($_POST['tel']) ? $_POST['tel'] : null);
        $email = (isset($_POST['email']) ? $_POST['email'] : null);

        $instagramLink = (isset($_POST['instagramLink']) ? $_POST['instagramLink'] : null);
        $facebookLink = (isset($_POST['facebookLink']) ? $_POST['facebookLink'] : null);

        try {
            $this->helper->shouldHavePrivilege('BRANCH_ADMIN');

            if (is_null($name) || empty(trim($name)) ){
                throw new CustomException("Имя не был введен", 1);
            }

            if (is_null($showOnWebSite) ){
                throw new CustomException("Показать на сайте не был введен", 1);
            }

            if (is_null($address) || empty(trim($address)) ){
                throw new CustomException("Адрес не был введен", 1);
            }

            if (is_null($tel) || empty(trim($tel)) ){
                throw new CustomException("Номер телефона не был введен", 1);
            }
            if (is_null($email) || empty(trim($email)) ){
                throw new CustomException("Email не был введен", 1);
            }

            if (is_null($geographicCoordinates) || !is_array($geographicCoordinates) || sizeof($geographicCoordinates) != 2){
                throw new CustomException("Географические координаты введены некорректно", 1);
            }

            if (is_null($facebookLink)){
                throw new CustomException("Ссылка Facebook не был введен", 1);
            }

            if (is_null($geographicCoordinates) || !is_array($geographicCoordinates) || sizeof($geographicCoordinates) != 2){
                throw new CustomException("Географические координаты введены некорректно", 1);
            }

            $branch = new \Models\Branch();
            $branch->setName(trim($name));
            $branch->setShowOnWebSite($showOnWebSite);
            $branch->setAddress(trim($address));
            $branch->setEmail(trim($email));
            $branch->setTel(trim($tel));
            $branch->setGeographicCoordinates(new GeographicCoordinates($geographicCoordinates['lat'], $geographicCoordinates['long']));


            if (!empty(trim($instagramLink))){
                $branch->setInstagramLink(trim($instagramLink));
            }

            if (!empty(trim($facebookLink))){
                $branch->setFacebookLink(trim($facebookLink));
            }

            $branch->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Филиал успешно создан");
            $this->response->setRedirect('/admin/branches/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
