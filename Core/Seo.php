<?php

namespace Core;




class Seo
{
    private $list;

    function __construct(){
        $this->list = array();
    }

    public function addItem(Item $seo) : void
    {
        $this->list[] = $placeId;
    }

    public function addSeo($header, $content) : void
    {
        $this->list[] = array(  'header' => $header,
                                'content' => $content
                            );
    }


    public function getItems()
    {
        return $this->list;
    }

}
