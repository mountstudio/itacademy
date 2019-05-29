<?php

namespace App\Controllers\Front;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\Group;
use \Models\GroupQuery;
use \Models\ReviewQuery;
use \Models\TagTypeQuery;
use \Models\ProductQuery;

use \Models\StaticPageQuery;

use \Models\OrderQuery;

use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

use \Models\UserQuery;
use \Models\PlaceQuery;
use \Models\TagQuery;
use \Models\ConfigQuery;
use \Models\RecallStatusQuery;
use \Models\MassTypeQuery;
use \Models\DeliveryTimeTypeQuery;
use \Models\PaymentTypeQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Profile extends Base
{
    public function editAction()
    {
        try {
            if (!$this->helper->isLoggedIn()){
                throw new CustomException("Вы не имеете право на данную страницу", 403);
            }
            $this->breadcrumbs->appendItem('Профиль', '/profile/edit');


            $this->data = array_merge( $this->data,
                                       array(
                                               'breadcrumbs' => $this->breadcrumbs
                                       ));
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/Profile/edit.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/Profile/edit.html', $this->data);
            }
        } catch (CustomException $e) {
            $this->data = array_merge(  $this->data,
                                        $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
              );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/messagePage.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/messagePage.html', $this->data);
            }
        }
    }

    public function favoriteAction()
    {
        try {
            if (!$this->helper->isLoggedIn()){
                throw new CustomException("Вы не имеете право на данную страницу", 403);
            }
            $this->breadcrumbs->appendItem('Мои избранные', '/profile/favorite');

            $favoriteProducts = $this->helper->getCurrentUser()->getCurrentProductUserFavoriteProducts();
            $favoritePlaces = $this->helper->getCurrentUser()->getCurrentPlaceUserFavoritePlaces();


            $this->data = array_merge( $this->data,
                                       array(
                                               'breadcrumbs' => $this->breadcrumbs,
                                               'favoriteProducts' => $favoriteProducts,
                                               'favoritePlaces' => $favoritePlaces
                                       ));
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/Profile/favorite.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/Profile/favorite.html', $this->data);
            }
        } catch (CustomException $e) {
            $this->data = array_merge(  $this->data,
                                        $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
              );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/messagePage.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/messagePage.html', $this->data);
            }
        }
    }

    public function ordersListAction()
    {
        $sort = (isset($_GET['period']) ? $_GET['period'] : null);

        try {
            if (!$this->helper->isLoggedIn()){
                throw new CustomException("Вы не имеете право на данную страницу", 403);
            }
            $this->breadcrumbs->appendItem('Мои заказы', '/profile/favorite');

            $filter = array();
            if (is_null($sort) || trim($sort) == 'last') {
                if (is_null($sort)) $sort = 'last';
                $filter = array('min' => time() - 60 * 60 * 24 * 1
                        );
            } elseif (trim($sort) == 'lastday') {
                $filter = array('min' => time() - 60 * 60 * 24 * 2
                        );
            } elseif (trim($sort) == 'week') {
                $filter = array('min' => time() - 60 * 60 * 24 * 7
                        );
            } elseif (trim($sort) == 'month') {
                $filter = array('min' => time() - 60 * 60 * 24 * 30
                        );
            } elseif (trim($sort) == 'quarter') {
                $filter = array('min' => time() - 60 * 60 * 24 * (3 * 30)
                        );
            }

            $getDefaults = ConfigQuery::create()->find();

            $defaultsList = array();

            foreach($getDefaults as $getDefault) {
               $defaultsList[$getDefault->getKey()] = $getDefault;
            }

            $orders = OrderQuery::create()->filterByCreatedAt($filter)->filterByCurrentUser($this->helper->getCurrentUser())->orderById('desc')->find();

            $this->data = array_merge( $this->data,
                                       array(
                                               'breadcrumbs' => $this->breadcrumbs,
                                               'orders' => $orders,
                                               'defaults' => $defaultsList,
                                               'period' => $sort
                                       ));
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/Profile/orders.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/Profile/orders.html', $this->data);
            }
        } catch (CustomException $e) {
            $this->data = array_merge(  $this->data,
                                        $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
              );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/messagePage.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/messagePage.html', $this->data);
            }
        }
    }


    public function reviewsListAction()
    {
        $sort = (isset($_GET['period']) ? $_GET['period'] : null);

        try {
            if (!$this->helper->isLoggedIn()){
                throw new CustomException("Вы не имеете право на данную страницу", 403);
            }
            $this->breadcrumbs->appendItem('Мои отзывы', '/profile/favorite');

            $filter = array();
            if (is_null($sort) || trim($sort) == 'last') {
                if (is_null($sort)) $sort = 'last';
                $filter = array('min' => time() - 60 * 60 * 24 * 1
                        );
            } elseif (trim($sort) == 'lastday') {
                $filter = array('min' => time() - 60 * 60 * 24 * 2
                        );
            } elseif (trim($sort) == 'week') {
                $filter = array('min' => time() - 60 * 60 * 24 * 7
                        );
            } elseif (trim($sort) == 'month') {
                $filter = array('min' => time() - 60 * 60 * 24 * 30
                        );
            } elseif (trim($sort) == 'quarter') {
                $filter = array('min' => time() - 60 * 60 * 24 * (3 * 30)
                        );
            }

            $reviews = ReviewQuery::create()->filterByCreatedAt($filter)->filterByCurrentUser($this->helper->getCurrentUser())->orderById('desc')->find();

            $this->data = array_merge( $this->data,
                                       array(
                                               'breadcrumbs' => $this->breadcrumbs,
                                               'reviews' => $reviews,
                                               'period' => $sort
                                       ));
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/Profile/reviews.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/Profile/reviews.html', $this->data);
            }
        } catch (CustomException $e) {
            $this->data = array_merge(  $this->data,
                                        $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
              );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/messagePage.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/messagePage.html', $this->data);
            }
        }
    }
}
