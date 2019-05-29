<?php

namespace App\Controllers\Front;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use \Models\Group;
use \Models\GroupQuery;
use \Models\ProductQuery;

use Propel\Runtime\ActiveQuery\Criteria;

use \Models\StaticPageQuery;

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


class Place extends Base
{
    public function indexAction()
    {
        $restaurantAlt = (isset($this->params['restaurant']) ? $this->params['restaurant'] : null);
        $productTagAlt = (isset($this->params['tag']) ? $this->params['tag'] : null);
        $tagsId = (isset($_GET['tag']) ? $_GET['tag'] : null);
        $query = (isset($_GET['search']) ? $_GET['search'] : null);
        $sort = (isset($_GET['sortBy']) ? $_GET['sortBy'] : null);
        try {
            $place = PlaceQuery::create()->findOneByAltName(trim($restaurantAlt));
            if (is_null($place)){
                throw new CustomException("Заведение не найдено", 404);
            }

            $this->breadcrumbs->appendItem('Заведении', '/restaurants');
            $this->breadcrumbs->appendItem(sprintf('Меню заведения "%s"', $place->getName()), '/' . $place->getAltName() . '-menu');

            if (isset($productTagAlt) || (is_array($tagsId) && sizeof($tagsId) > 0) || $this->helper->getDevice() == 'other'){

                $con = Propel::getWriteConnection(\Models\Map\PlaceTableMap::DATABASE_NAME);
                $sql = "SELECT %s FROM product p %s %s %s %s";

                $selects = array();

                $selects[] = 'p.*';
                $clauses = array();
                $leftJoins = array();
                $groupBys = array();
                $orderBys = array();

                if (!is_null($productTagAlt) && trim($productTagAlt) != 'all' || (is_array($tagsId) && sizeof($tagsId) > 0)){
                    if (!(is_array($tagsId) && sizeof($tagsId) > 0)){
                        $productTag = TagQuery::create()->filterByCurrentPlaceIdTag($place->getId())->_or()->filterByCurrentPlaceIdTag(null)->filterByAltName(trim($productTagAlt))->filterByKitchen(false)->findOne();
                    }

                    if (isset($productTag) && is_null($productTag)){
                        throw new CustomException("Категория заведении не найдено", 404);
                    }

                    $leftJoins[] = 'LEFT JOIN tag_product tp ON tp.product_id = p.id';

                    if (is_array($tagsId)){
                        $clauses[] = 'tp.tag_id IN (' . implode(", ", $tagsId) . ')';

                        $this->data = array_merge( $this->data,
                                                   array(  'tagIds_' => $tagsId)
                                                   );
                    } else {
                        $tagsArr = array();
                        $tagsArr[] = $productTag->getId();

                        foreach ($productTag->getCurrentChildren() as $chld) {
                            $tagsArr[] = $chld->getId();
                        }
                        $clauses[] = 'tp.tag_id IN (' . implode(", ", $tagsArr) . ')';
                        $this->data = array_merge( $this->data,
                                                   array(  'productTag' => $productTag)
                                                   );
                        $this->breadcrumbs->appendItem($productTag->getName(), $place->getAltName() . '-//' . $productTag->getAltName());
                    }


                }

                if (!is_null($query) && !empty(trim($query))){
                    $this->data = array_merge( $this->data,
                                               array(  'searchQuery' => trim($query))
                                               );
                    $clauses[] = '(p.name LIKE \'%' . trim($query) . '%\' OR p.description LIKE \'%' . trim($query) . '%\')';
                    $this->breadcrumbs->appendItem('Поиск', '');

                }

                if (is_null($sort) || trim($sort) == 'alphabet_asc') {
                    $orderBys[] = 'p.name ASC';
                } elseif (trim($sort) == 'alphabet_desc') {
                    $orderBys[] = 'p.name DESC';
                } elseif (trim($sort) == 'price_asc') {
                    $orderBys[] = 'p.price DESC';
                } elseif (trim($sort) == 'price_desc') {
                    $orderBys[] = 'p.price DESC';
                }

                if (is_null($sort)){
                    $this->data = array_merge( $this->data,
                                               array(  'sortBy' => 'alphabet_asc'
                                                       )
                                               );
                } else {
                    $this->data = array_merge( $this->data,
                                               array(  'sortBy' => trim($sort)
                                                       )
                                               );
                }


                $groupBys[] = 'p.id';

                $clauses[] = 'p.place_id = ' . $place->getId();

                $stmt = $con->prepare(sprintf($sql, ' '. implode(', ', $selects) .' ', ' '. implode(' ', $leftJoins) .' ', ((sizeof($clauses) > 0) ? ' WHERE ' . implode(' AND ', $clauses) . ' ' : ''), ((sizeof($groupBys) > 0) ? ' GROUP BY ' . implode(', ', $groupBys) . ' ' : ''), ((sizeof($orderBys) > 0) ? ' ORDER BY ' . implode(', ', $orderBys) . ' ' : '')) . ' LIMIT 0, 20');
                $stmt->execute();
                $formatter = new ObjectFormatter();
                $formatter->setClass('\Models\Product'); //full qualified class name
                $products = $formatter->format($con->getDataFetcher($stmt));


                $this->data = array_merge( $this->data,
                                           array(  'products' => $products
                                           )
                                       );
               $this->data = array_merge( $this->data,
                                          array( 'firstIndex' => 0,
                                                  'lastIndex' => sizeof($products),
                                                  'loadMore' => sizeof($products) == 20
                                                  )
                                          );
            }

            $menuData = $this->getMenuData($place);

            $this->data = array_merge( $this->data,
                                       array(  'place' => $place,
                                               'breadcrumbs' => $this->breadcrumbs,
                                               'placeMenu' => $menuData['placeMenu'], // Depreciated will be remove soon
                                               'rootMenu' => $menuData['rootMenu'],
                                               'menuTagsById' => $menuData['menuTagsById']
                                               )
                                       );

            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/Place/products.html', $this->data);
            } else {

                if (isset($productTagAlt)){
                    View::renderTemplate('Front/Mobile/Place/products.html', $this->data);
                } else {
                    View::renderTemplate('Front/Mobile/Place/index.html', $this->data);
                }

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

    private function getMenuData($place)
    {
      $placeMenu = TagQuery::create()->filterByCurrentPlaceIdTag($place->getId())->_or()->filterByCurrentPlaceIdTag(null)->filterByKitchen(false)->useCurrentTagTagProductQuery()->useCurrentProductQuery()->filterByCurrentPlace($place)->endUse()->endUse()->groupById()->find();


      $clonedMenuById = array();
      $modifiedPlaceMenu = array();
      $modifiedPlaceMenu[null] = array();

      foreach ($placeMenu as $currMenu) {
        $parent = $currMenu->getCurrentParent();


        if(!is_null($parent) && is_null($parentOfParent = $parent->getCurrentParent())){
          $parent = null;
        }

        if (is_null($parent)){
            $modifiedPlaceMenu[null][$currMenu->getId()] = array();
        } else {
            if (!array_key_exists($parent->getId(), $clonedMenuById)){
                $clonedMenuById[$parent->getId()] = $parent;
            }

            if(!is_null($parentOfParent) && is_null($parentOfParentOfParent = $parentOfParent->getCurrentParent())){
              $parentOfParent = null;
            }
            if (is_null($parentOfParent)){
                if (!array_key_exists($parent->getId(), $modifiedPlaceMenu[null])){
                    $modifiedPlaceMenu[null][$parent->getId()] = array();
                }
                $modifiedPlaceMenu[null][$parent->getId()][] = $currMenu->getId();

            } else {
                if (!array_key_exists($parentOfParent->getId(), $clonedMenuById)){
                    $clonedMenuById[$parentOfParent->getId()] = $parentOfParent;
                }
                if (!array_key_exists($parentOfParent->getId(), $modifiedPlaceMenu)){
                    $modifiedPlaceMenu[$parentOfParent->getId()] = array();
                }
                if (!array_key_exists($parent->getId(), $modifiedPlaceMenu[$parentOfParent->getId()])){
                    $modifiedPlaceMenu[$parentOfParent->getId()][$parent->getId()] = array();
                }
                $modifiedPlaceMenu[$parentOfParent->getId()][$parent->getId()][] = $currMenu->getId();
            }
        }
        $clonedMenuById[$currMenu->getId()] = $currMenu;
      }
      return array(
                'placeMenu' => $placeMenu,
                'rootMenu' => $modifiedPlaceMenu,
                'menuTagsById' => $clonedMenuById
              );
    }


    public function infoAction()
    {
        $restaurantAlt = (isset($this->params['restaurant']) ? $this->params['restaurant'] : null);
        try {
            $place = PlaceQuery::create()->findOneByAltName(trim($restaurantAlt));
            if (is_null($place)){
                throw new CustomException("Заведение не найдено", 404);
            }
            $this->breadcrumbs->appendItem('Заведении', '/restaurants');
            $this->breadcrumbs->appendItem(sprintf('Информация о заведении "%s"', $place->getName()), '');


            /*
            $this->breadcrumbs->appendItem(sprintf('Заведение "%s"', $place->getName()), '');
            $this->breadcrumbs->appendItem(sprintf('Все меню', $place->getName()), '');*/

            $weekDays = $place->getWeekDays();
            $workDays = array();
            foreach (Config::DAYS_OF_WEEK as $day => $dayData) {
                $workDays[] = array('day' => $dayData['concated'],
                                    'doesWork' => ($weekDays[$day - 1] == 'true') ? true : false,
                                    'from' => (!is_null($place->getWorkStartTime())) ? $place->getWorkStartTime()->format('H:i') : null,
                                    'to' => (!is_null($place->getWorkStartTime())) ? date('H:i', $place->getWorkEndTime()->getTimestamp() - $place->getCancelOrderBefore() * 60) : null
                                );
            }

            $this->data = array_merge( $this->data,
                                       array(  'place' => $place,
                                               'breadcrumbs' => $this->breadcrumbs,
                                               'reviews' => $place->getRating(),
                                               'siteUrl' => Config::SITE_URL,
                                               'workDays' => $workDays,
                                               'canOrder' => Functions::canOrder($place)

                                               )
                                       );

            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/Place/info.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/Place/info.html', $this->data);
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


    public function productAction()
    {
        $restaurantAlt = (isset($this->params['restaurant']) ? $this->params['restaurant'] : null);
        $productAlt = (isset($this->params['product']) ? $this->params['product'] : null);
        $productId = (isset($this->params['productid']) ? $this->params['productid'] : null);

        try {
            $place = PlaceQuery::create()->findOneByAltName(trim($restaurantAlt));
            if (is_null($place)){
                throw new CustomException("Заведение не найдено", 404);
            }

            $product = ProductQuery::create()->findOneByAltNameAndCurrentPlaceIdAndId(trim($productAlt), $place->getId(), intval($productId));
            if (is_null($product)){
                throw new CustomException("Продукт не найден", 404);
            }

            $this->breadcrumbs->appendItem('Заведении', '/restaurants');
            $this->breadcrumbs->appendItem(sprintf('Меню заведения "%s"', $place->getName()), '/' . $place->getAltName() . '-menu');
            $this->breadcrumbs->appendItem($product->getName(), '/' . $place->getAltName() . '-/product/' . $product->getAltName());

            $this->data = array_merge( $this->data,
                                       array(  'place' => $place,
                                               'breadcrumbs' => $this->breadcrumbs,
                                               'reviews' => $place->getRating(),
                                               'siteUrl' => Config::SITE_URL,
                                               'canOrder' => Functions::canOrder($place),
                                               'product' => $product
                                               )
                                       );

            if ($this->helper->getDevice() == 'other'){

              $menuData = $this->getMenuData($place);
              $this->data = array_merge( $this->data,
                                         array(  'place' => $place,
                                                 'breadcrumbs' => $this->breadcrumbs,
                                                 'reviews' => $place->getRating(),
                                                 'siteUrl' => Config::SITE_URL,
                                                 'canOrder' => Functions::canOrder($place),
                                                 'product' => $product,
                                                 'placeMenu' => $menuData['placeMenu'], // Depreciated will be remove soon
                                                 'rootMenu' => $menuData['rootMenu'],
                                                 'menuTagsById' => $menuData['menuTagsById']
                                                 )
                                         );

                View::renderTemplate('Front/Desktop/Place/Product/index.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/Place/Product/index.html', $this->data);
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

    public function reviewAction()
    {
        $restaurantAlt = (isset($this->params['restaurant']) ? $this->params['restaurant'] : null);
        $type = (isset($this->params['type']) ? $this->params['type'] : null);

        try {
            $place = PlaceQuery::create()->findOneByAltName(trim($restaurantAlt));
            if (is_null($place)){
                throw new CustomException("Заведение не найдено", 404);
            }

            $this->breadcrumbs->appendItem('Заведении', '/restaurants');

            if (!is_null($type) && !(trim($type) == 'positive' || trim($type) == 'negative')){
                throw new CustomException("Страница не найдена", 404);
            } elseif (trim($type) == 'positive') {
                $this->breadcrumbs->appendItem(sprintf('Позитивные отзывы о заведении "%s"', $place->getName()), '');
            } elseif (trim($type) == 'negative') {
                $this->breadcrumbs->appendItem(sprintf('Негативные отзывы о заведении "%s"', $place->getName()), '');
            } else {
                $this->breadcrumbs->appendItem(sprintf('Отзывы о заведении "%s"', $place->getName()), '');
            }

            $allReviews = $place->getCurrentPlaceReviews();
            $positiveReviews = array();
            $negativeReviews = array();
            foreach ($allReviews as $review) {
                if ((($review->getPlaceValue() + $review->getOrderValue()) / 2) > 2.5){
                    $positiveReviews[] = $review;
                } else {
                    $negativeReviews[] = $review;
                }
            }

            $this->data = array_merge( $this->data,
                                       array(  'place' => $place,
                                               'breadcrumbs' => $this->breadcrumbs,
                                               'reviews' => array_merge($place->getRating(), array('all' => $allReviews,
                                                                   'positive' => $positiveReviews,
                                                                   'negative' => $negativeReviews,
                                                                   'action' => (is_null($type)) ? 'all' : ((trim($type) == 'positive') ? 'positive' : 'negative')
                                                               )),
                                                'siteUrl' => Config::SITE_URL
                                               )
                                       );

            if ($this->helper->getDevice() == 'other'){
    View::renderTemplate('Front/Desktop/Place/reviews.html', $this->data);
} else {
    View::renderTemplate('Front/Mobile/Place/reviews.html', $this->data);
}
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                                       array(  'error_code' => $e->getCode(),
                                               'error_title' => "Ошибка",
                                               'error_message' => $e->getMessage(),
                                               )
                                       );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/404.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/404.html', $this->data);
            }
        }
    }

    public function restaurantsAction()
    {
        $tag = (isset($this->params['tag']) ? $this->params['tag'] : null);

        $tagsId = (isset($_GET['tag']) ? $_GET['tag'] : null);
        $query = (isset($_GET['search']) ? $_GET['search'] : null);
        $sort = (isset($_GET['sortBy']) ? $_GET['sortBy'] : null);
        $openNow = (isset($_GET['openNow']) ? (($_GET['openNow'] == 'true') ? true : false) : null);
        $freeDelivery = (isset($_GET['freeDelivery']) ? (($_GET['freeDelivery'] == 'true') ? true : false) : null);
        $minOrder = (isset($_GET['minOrder']) ? $_GET['minOrder'] : null);
        $averageCheque = (isset($_GET['averageCheque']) ? $_GET['averageCheque'] : null);


        try {
            $this->breadcrumbs->appendItem('Заведении', '/restaurants');

            if (!is_null($minOrder) && (!is_array($minOrder) || sizeof($minOrder) != 2)){
                throw new CustomException("Мин сумма заказа заведения указана неправильно", 403);
            }

            if (!is_null($averageCheque) && (!is_array($averageCheque) || sizeof($averageCheque) != 2)){
                throw new CustomException("Средний чек заведения указан неправильно", 403);
            }

            $con = Propel::getWriteConnection(\Models\Map\PlaceTableMap::DATABASE_NAME);

            $sql = "SELECT %s FROM place p %s %s %s %s";

            $selects = array();

            $selects[] = 'p.*';
            $clauses = array();
            $leftJoins = array();
            $groupBys = array();
            $orderBys = array();

            $leftJoins[] = 'LEFT JOIN product pr ON pr.place_id = p.id';

            if (!is_null($tagsId) && !is_array($tagsId)){
                throw new CustomException("Id категории указаны не правильно", 403);
            }

            if (!is_null($tag)){
                $tag_ = TagQuery::create()->findOneByAltName(trim($tag));
                if (is_null($tag_)){
                    throw new CustomException("Тег не найден", 404);
                }

                $leftJoins[] = 'LEFT JOIN tag_place tp ON tp.place_id = p.id';
                $leftJoins[] = 'LEFT JOIN tag_product tpr ON tpr.product_id = pr.id';

                $clauses[] = 'tp.tag_id  = ' . $tag_->getId() . ' OR tpr.tag_id  = ' . $tag_->getId();

                $this->data = array_merge( $this->data,
                                           array(  'tag_' => $tag_)
                                           );
                $this->breadcrumbs->appendItem($tag_->getName(), '/restaurants//' . $tag_->getAltName());
            } elseif (!is_null($tagsId) && sizeof($tagsId) > 0){
                $leftJoins[] = 'LEFT JOIN tag_place tp ON tp.place_id = p.id';
                $leftJoins[] = 'LEFT JOIN tag_product tpr ON tpr.product_id = pr.id';

                $clauses[] = 'tp.tag_id IN (' . implode(", ", $tagsId) . ') OR tpr.tag_id IN (' . implode(", ", $tagsId) . ')';

                $this->data = array_merge( $this->data,
                                           array(  'tags_' => implode(", ", $tagsId))
                                           );
            }

            if (!is_null($query) && !empty(trim($query))){
                $this->data = array_merge( $this->data,
                                           array(  'query' => trim($query))
                                           );
                $clauses[] = '(pr.name LIKE \'%' . trim($query) . '%\' OR pr.description LIKE \'%' . trim($query) . '%\' OR p.name LIKE \'%' . trim($query) . '%\' OR p.description LIKE \'%' . trim($query) . '%\')';
                $this->breadcrumbs->appendItem('Поиск', '');
            }

            if (!is_null($freeDelivery)){
                $clauses[] = 'p.delivery_cost = 0';
                $this->data = array_merge( $this->data,
                                           array(  'freeDelivery' => true)
                                           );
            }


            if (!is_null($minOrder)){
                $this->data = array_merge( $this->data,
                                           array(  'minOrder' => array( 'min' => $minOrder[0],
                                                                        'max' => $minOrder[1]
                                                                    )
                                                   )
                                           );
                $clauses[] = sprintf('p.min_order_amount BETWEEN %s AND %s', $minOrder[0], $minOrder[1]);
            }

            if (!is_null($averageCheque)){
                $this->data = array_merge( $this->data,
                                           array(  'averageCheque' => array('min' => $averageCheque[0],
                                                                            'max' => $averageCheque[1]
                                                                            )
                                                   )
                                           );
                $clauses[] = sprintf('p.average_cheque BETWEEN %s AND %s', $averageCheque[0], $averageCheque[1]);
            }


            if (is_null($sort) || trim($sort) == 'rating_desc'){
                $selects[] = 'AVG((r.place_value + r.order_value) / 2) as rating';
                $leftJoins[] = 'LEFT JOIN review r ON r.place_id = p.id';
                $orderBys[] = 'rating DESC';
            } elseif (trim($sort) == 'delivery_time') {
                $leftJoins[] = 'LEFT JOIN delivery_time_type dt ON dt.id = p.delivery_time_type_id';
                $orderBys[] = 'dt.time ASC';
            } elseif (trim($sort) == 'average_cheque_price_asc') {
                $orderBys[] = 'p.average_cheque ASC';
            } elseif (trim($sort) == 'average_cheque_price_desc') {
                $orderBys[] = 'p.average_cheque DESC';
            } elseif (trim($sort) == 'alphabet_asc') {
                $orderBys[] = 'p.name ASC';
            } elseif (trim($sort) == 'alphabet_desc') {
                $orderBys[] = 'p.name DESC';
            }

            if (is_null($sort)){
                $this->data = array_merge( $this->data,
                                           array(  'sortBy' => 'rating_desc'
                                                   )
                                           );
            } else {
                $this->data = array_merge( $this->data,
                                           array(  'sortBy' => trim($sort)
                                                   )
                                           );
            }


            $groupBys[] = 'p.id';

            if (!is_null($openNow) && $openNow){
                $this->data = array_merge( $this->data,
                                           array(  'openNow' => true
                                                   )
                                           );
                $clauses[] = "IF (p.around_the_clock = 1, 1, IF (SUBSTRING_INDEX( SUBSTRING_INDEX(SUBSTRING(REPLACE(p.week_days, ' | ', ','), 2, LENGTH(REPLACE(p.week_days, ' | ', ',')) - 2), ',', IF (DAYOFWEEK(now()) = 1, 7, DAYOFWEEK(now()) - 1) ), ',', -1 ) = 'true', CURTIME() BETWEEN p.work_start_time AND p.work_end_time, 0)) = 1";
            }

            $stmt = $con->prepare(sprintf($sql, ' '. implode(', ', $selects) .' ', ' '. implode(' ', $leftJoins) .' ', ((sizeof($clauses) > 0) ? ' WHERE ' . implode(' AND ', $clauses) . ' ' : ''), ((sizeof($groupBys) > 0) ? ' GROUP BY ' . implode(', ', $groupBys) . ' ' : ''), ((sizeof($orderBys) > 0) ? ' ORDER BY ' . implode(', ', $orderBys) . ' ' : '')) . ' LIMIT 0, 20');
            $stmt->execute();
            $formatter = new ObjectFormatter();
            $formatter->setClass('\Models\Place'); //full qualified class name
            $places = $formatter->format($con->getDataFetcher($stmt));

            $placeDefaults = ConfigQuery::create()->filterByKey(array('default_places_seo', 'default_places_description'))->find();
            $defaultsList = array();

            foreach($placeDefaults as $getDefault) {
               $defaultsList[$getDefault->getKey()] = $getDefault;
            }



            $placesData = array();
            foreach ($places as $place) {
                $productsData = array();



                if (!is_null($query) && !empty(trim($query)) || (isset($tag_) && !is_null($tag_)) || (!is_null($tagsId) && sizeof($tagsId) > 0)){

                    if (isset($tag_) && !is_null($tag_) && !is_null($query)){
                        $placeProducts = ProductQuery::create()->useCurrentProductTagProductQuery()->where('TagProduct.TagId = ?', $tag_->getId())->endUse()->filterByCurrentPlace($place)->where('Product.Name LIKE ?', '%' . $query . '%')->_or()->where('Product.Description LIKE ?', '%' . $query . '%')->limit(3)->find();
                    } elseif (isset($tag_) && !is_null($tag_) && is_null($query)){

                        $placeProducts = ProductQuery::create()->filterByCurrentPlace($place)->useCurrentProductTagProductQuery()->filterByTagId($tag_->getId())->endUse()->limit(3)->find();
                    } elseif (!is_null($tagsId) && sizeof($tagsId) > 0 && !is_null($query)){

                        $placeProducts = ProductQuery::create()->filterByCurrentPlace($place)->where('Product.Name LIKE ?', '%' . $query . '%')->_or()->where('Product.Description LIKE ?', '%' . $query . '%')->useCurrentProductTagProductQuery()->filterByTagId($tagsId)->endUse()->limit(3)->find();
                    } elseif (!is_null($tagsId) && sizeof($tagsId) > 0 && is_null($query)){

                        $placeProducts = ProductQuery::create()->filterByCurrentPlace($place)->useCurrentProductTagProductQuery()->filterByTagId($tagsId)->endUse()->limit(3)->find();
                    } elseif (!is_null($query)){
                        $placeProducts = ProductQuery::create()->filterByCurrentPlace($place)->where('Product.Name LIKE ?', '%' . $query . '%')->_or()->where('Product.Description LIKE ?', '%' . $query . '%')->limit(3)->find();
                    }

                    foreach ($placeProducts as $product) {


                        $productsData[] = array(   'id' => $product->getId(),
                                                   'placeId' => $place->getId(),
                                                   'name' => $product->getName(),
                                                   'altName' => $product->getAltName(),
                                                   'image' => $product->getImage(),
                                                   'price' => $product->getPrice(),
                                                   'description' => $product->getDescription(),
                                                   'isFavorite' => $this->helper->isLoggedIn() ? $product->isFavorite($this->helper->getCurrentUser()) : false,
                                                   'cartData' => $product->getCartData()
                       );
                    }
                }
                $placeData = array( 'place' => $place,
                                    'products' => $productsData
                                );
                $placesData[] = $placeData;
            }

            $kitchenTags = TagQuery::create()->filterByKitchen(true)->filterByCurrentPlaceIdTag(null);
            $otherTags = TagQuery::create()->filterByKitchen(false)->filterByCurrentPlaceIdTag(null);

            $this->data = array_merge( $this->data,
                                       array(   'places' => $placesData,
                                                'breadcrumbs' => $this->breadcrumbs,
                                                'firstIndex' => 0,
                                                'lastIndex' => sizeof($placesData),
                                                'loadMore' => sizeof($places) == 20,
                                                'defaults' => $defaultsList,
                                                'kitchenTags' => $kitchenTags,
                                                'otherTags' => $otherTags
                                               )
                                       );

            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/Place/list.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/Place/list.html', $this->data);
            }
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                                       array(  'error_code' => $e->getCode(),
                                               'error_title' => "Ошибка",
                                               'error_message' => $e->getMessage(),
                                               )
                                       );
            if ($this->helper->getDevice() == 'other'){
                View::renderTemplate('Front/Desktop/404.html', $this->data);
            } else {
                View::renderTemplate('Front/Mobile/404.html', $this->data);
            }
        }
    }
}
