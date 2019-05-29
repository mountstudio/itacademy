<?php

namespace Core\Model\Branch;

 class GeographicCoordinates
 {
     private $long, $lat;

     function __construct($long = null, $lat = null) {
         $this->long = $long;
         $this->lat = $lat;
     }

     public function setLong($long)
     {
         $this->long = $long;
     }

     public function setLat($lat)
     {
         $this->lat = $lat;
     }

     public function getLong()
     {
         return $this->long;
     }
     public function getLat()
     {
         return $this->lat;
     }
 }
