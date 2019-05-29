<?php

namespace Core\Model\Course;

 class Uses
 {
     private $useList;

     function __construct($useList = array()) {
         $this->useList = & $useList;
     }
     
     public function setUse($useList)
     {
         $this->useList = array();
         foreach ($useList as $use) {
             $this->addUse($use);
         }
     }

     public function getUses()
     {
         return $this->useList;
     }

     public function addUse($use)
     {
         $this->useList[] = $use;
     }
     public function deleteUse($use)
     {
         $this->useList = array_diff($this->useList, array(
             $use
         ));
     }
 }
