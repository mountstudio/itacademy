<?php

namespace Core;

/**
 * Error and exception handler
 *
 * PHP version 7.0
 */
 class Breadcrumb
 {
     private $list = array();
     private $isOnRightSide = false;

     function __construct($mainItemName = 'Главная', $mainItemUrl = '/'){
         $mainItem = new Item($mainItemName, $mainItemUrl);
         $this->list[] = $mainItem;
     }

     public function appendItem($name, $url, $isActive = false) : void
     {
         $this->list[] = new Item($name, $url, $isActive);
     }

     public function setOnRightSide($onRightSide){
         $this->isOnRightSide = $onRightSide;
     }

     public function isOnRightSide(){
         return $this->isOnRightSide;
     }

     public function popItem() : void
     {
         array_pop($this->list);
     }

     public function getItems()
     {
         $newList = $this->list;
         $newList[sizeof($this->list) - 1]->setActive(true);
         return $newList;
     }

 }

 class Item
 {
     private $name, $url, $isActive;

     function __construct($name, $url, $isActive = false){
         $this->name = $name;
         $this->url = $url;
         $this->isActive = $isActive;
     }

     public function getName() : String
     {
         return $this->name;
     }

     public function setName($name) : void
     {
         $this->name = $name;
     }

     public function getUrl() : String
     {
         return $this->url;
     }

     public function setUrl($url) : void
     {
         $this->url = $url;
     }

     public function isActive() : bool
     {
         return $this->isActive;
     }

     public function setActive($isActive) : void
     {
         $this->isActive = $isActive;
     }

 }
