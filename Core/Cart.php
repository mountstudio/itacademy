<?php

namespace Core;
if (php_sapi_name() == 'cli'){
    // Setup the autoloading
    require_once 'vendor/autoload.php';

    // Setup Propel
    require_once 'generated-conf/config.php';
} else {
    // Setup the autoloading
    require_once '../vendor/autoload.php';

    // Setup Propel
    require_once '../generated-conf/config.php';
}

use \Models\ProductQuery;
use \Models\PlaceQuery;
 class Cart
 {
     private $session, $placeId;

     function __construct($placeId = null){
         $this->session = & $_SESSION['cart'];
         $this->placeId = $placeId;
         if (!isset($this->session)) {
             $_SESSION['cart'] = array();
             $this->session = & $_SESSION['cart'];
         }
         if (!is_null($this->placeId) && !isset($this->session[$this->placeId])){
             $this->session[$this->placeId] = array();
         }
     }
     
     public function setPlaceId($placeId) : void
     {
         $this->placeId = $placeId;
     }

     public function getPlaceId()
     {
         return $this->placeId;
     }


     private function checkPlaceId() : void
     {
         if (is_null($this->placeId)){
             throw new CustomException("PlaceId не был указан", 0);
         }
     }

     public function addProduct($productId, $quantity = 1) : void
     {
         $this->checkPlaceId();
         if (isset($this->session[$this->placeId][$productId])){
             $this->session[$this->placeId][$productId] = $quantity;
         } else {
             $this->session[$this->placeId][$productId] = $quantity;
         }
     }

     public function increaseProduct($productId, $quantity = 1) : void
     {
         $this->checkPlaceId();
         if (isset($this->session[$this->placeId][$productId])){
             $this->session[$this->placeId][$productId] += $quantity;
         } else {
             $this->session[$this->placeId][$productId] = $quantity;
         }
     }

     public function decreaseProduct($productId, $quantity = 1) : void
     {
         $this->checkPlaceId();
         if (isset($this->session[$this->placeId][$productId])){
             if ($this->session[$this->placeId][$productId] <= $quantity){
                 unset($this->session[$this->placeId][$productId]);
                 if (sizeof(array_keys($this->session[$this->placeId])) == 0){
                     unset($this->session[$this->placeId]);
                 }
             } else {
                 $this->session[$this->placeId][$productId] -= $quantity;
             }

         } else {
             throw new CustomException("Продукта итак нет в корзине", 403);
         }
     }

     public function getProductQuantity($productId) : int
     {
         $this->checkPlaceId();
         if (isset($this->session[$this->placeId][$productId])){
             return $this->session[$this->placeId][$productId];
         }
         return 0;
     }

     public function removeProduct($productId) : void
     {
         $this->checkPlaceId();
         if (isset($this->session[$this->placeId][$productId])){
             unset($this->session[$this->placeId][$productId]);
             if (sizeof(array_keys($this->session[$this->placeId])) == 0){
                 unset($this->session[$this->placeId]);
             }
         }
     }

     public function removePlace() : void
     {
         $this->checkPlaceId();
         if (isset($this->session[$this->placeId])){
             unset($this->session[$this->placeId]);
         }
     }

     public function reset() : void
     {
         unset($_SESSION['cart']);
         //$this->session = array();
     }

     public function getPlaces()
     {
         return $this->session;
     }

     public function getPlaceData()
     {
         $this->checkPlaceId();
         return $this->session[$this->placeId];
     }

     public function getPlaceAmount() : int
     {
        $this->checkPlaceId();
         $amount = 0;
         if (isset($this->session[$this->placeId])){
             $productsId = array_keys($this->session[$this->placeId]);
             if (sizeof($productsId) > 0){
                 $products = ProductQuery::create()->findPKs($productsId);
                 foreach ($products as $product) {
                     $amount += $this->session[$this->placeId][$product->getId()] * $product->getPrice();
                 }
             }
         }
         return $amount;
     }

     public function getAmount() : int
     {
         $amount = 0;
         $productsId = array();
         foreach ($this->session as $placeId => $products) {
             $placeProductsId = array_keys($this->session[$this->placeId]);
             if (sizeof($placeProductsId) > 0){
                 array_merge($productsId, $placeProductsId);
             }
         }
         if (sizeof($productsId) > 0){
             $products = ProductQuery::create()->findPKs($productsId);
             foreach ($products as $product) {
                 $amount += $this->session[$product->getCurrentPlaceId()][$product->getId()] * $product->getPrice();
             }
         }
         return $amount;
     }

     public function getProductData($productId)
     {
         $this->checkPlaceId();

        if (isset($this->session[$this->placeId]) && isset($this->session[$this->placeId][$productId])){
            return array(   'isOnCart' => true,
                            'quantity' => $this->session[$this->placeId][$productId],
                        );
        } else {
            return array(   'isOnCart' => false,
                            'quantity' => 1,
                        );
        }
     }

     public function getData()
     {

         $cartData = array( 'amount' => 0,
                            'quantity' => 0,
                            'places' => array()
                            );

        $placesId = array_keys($this->getPlaces());
        $places = array();
        $allAmount = 0;
        $allQuantity = 0;
        if (sizeof($placesId) > 0){
            $places = PlaceQuery::create()->findPks($placesId);
            foreach ($places as $place) {
                $productsId = array_keys($this->getPlaces()[$place->getId()]);

                $products = array();
                $productsQ = ProductQuery::create()->findPks($productsId);
                $productsAmount = 0;
                $productsQuantity = 0;
                
                if (sizeof($productsQ) > 0) {
                    foreach ($productsQ as $prod) {
                        $quantity = $this->getPlaces()[$place->getId()][$prod->getId()];
                        $productAmount = $prod->getPrice() * $quantity;
                        $products[] = array('id' => $prod->getId(),
                                            'name' => $prod->getName(),
                                            'altName' => $prod->getAltName(),
                                            'price' => $prod->getPrice(),
                                            'image' => $prod->getImage(),
                                            'quantity' => $quantity,
                                            'amount' => $productAmount
                                            );
                        $productsAmount += $productAmount;
                        $productsQuantity += $quantity;
                    }
                    if (!($place->getFreeDelivery() && $place->getFreeDeliveryAmount() <= $productsAmount)) {
                        $productsAmount += $place->getDeliveryCost();
                    }
                    $cartData['places'][] = array(  'id' => $place->getId(),
                                                    'name' => $place->getName(),
                                                    'altName' => $place->getAltName(),
                                                    'logo' => $place->getLogo(),
                                                    'products' => $products,
                                                    'quantity' => $productsQuantity,
                                                    'amount' => $productsAmount,
                                                    'deliveryCost' => (($place->getFreeDelivery()) ? (($productsAmount - $place->getDeliveryCost() >= $place->getFreeDeliveryAmount()) ? 0 : $place->getDeliveryCost()) : $place->getDeliveryCost()),
                                                    'minOrderAmount' => $place->getMinOrderAmount(),
                                                    'canOrder' => ($place->isOpen() || $place->getPreOrder()),
                                                    'isOpen' => $place->isOpen(),
                                                    'preOrder' => $place->getPreOrder(),
                                                    'opensIn' => $place->opensIn(),
                                                    'preOrderDetails' => $place->getPreOrderDetails(),
                                                    'selfDelivery' => $place->getSelfDelivery()
                                                    );
                    $allAmount += $productsAmount;
                    $allQuantity += $productsQuantity;
                }
            }

            $cartData['amount'] = $allAmount;
            $cartData['quantity'] = $allQuantity;
        }
        return $cartData;
     }


 }
