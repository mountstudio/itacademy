<?php

namespace App\Controllers\API;

use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;


use Propel\Runtime\Propel;
use \Models\UserQuery;
use Propel\Runtime\Formatter\ObjectFormatter;
use \Models\Group;
use \Models\TagQuery;
use \Models\GroupQuery;
use \Models\ProductQuery;
use \Models\ConfigQuery;

use \Models\DeliveryTimeTypeQuery;
use \Models\PlaceQuery;
use \Models\ProductCategoryQuery;


use \Models\RecallStatusQuery;
use \Models\PaymentTypeQuery;
use \Models\OrderQuery;

use \Models\OrderStatusQuery;


use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class MainData extends Base
{

    public function indexAction()
    {
        try {
            $token = (isset($_GET['token']) ? $_GET['token'] : null);

            $this->response = new JsonResponse();
            if (!is_null($token) && $token != 'Y72UCezqKE'){
                throw new CustomException("Токен указан не правильно", 403);
            }

            $tags = TagQuery::create()->find();
            $defaultKitchenCategoryId = ConfigQuery::create()->findOneByKey('default_kitchen_type_id');
            $tagsData = array();
            foreach ($tags as $tag) {

                $tagsData[] = array('id' => $tag->getId(),
                                    'name' => $tag->getName(),
                                    'isKitchen' => (($tag->getTagTypeId() == $defaultKitchenCategoryId->getValue()) ? true : false)
                                    );
            }

            $paymentTypes = PaymentTypeQuery::create()->find();

            $paymentTypesData = array();
            foreach ($paymentTypes as $paymentType) {

                $paymentTypesData[] = array('id' => $paymentType->getId(),
                                            'name' => $paymentType->getName(),
                                            'description' => $paymentType->getDescription()
                                            );
            }


            $deliveryTimeTypes = DeliveryTimeTypeQuery::create()->find();

            $deliveryTimeTypesData = array();
            foreach ($deliveryTimeTypes as $deliveryTimeType) {

                $deliveryTimeTypesData[] = array(   'id' => $deliveryTimeType->getId(),
                                                    'name' => $deliveryTimeType->getName(),
                                                    'time' => $deliveryTimeType->getTime(),
                                                    'description' => $deliveryTimeType->getDescription(),
                                            );
            }


            $this->response->setData(array( 'tags' => $tagsData,
                                            'paymentTypes' => $paymentTypesData
                                            )
                                        );
            $this->response->setMessage("Выполнен успешно");
            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
