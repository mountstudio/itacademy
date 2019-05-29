<?php

namespace Core;

class OpenGraph {
    private $title, $description, $image, $type, $url;

    function __construct(){

    }

    public function setTitle($title) : void
    {
        $this->title = $title;
    }

    public function setDescription($description) : void
    {
        $this->description = $description;
    }

    public function setImage($image) : void
    {
        $this->image = $image;
    }

    public function setType($type) : void
    {
        $this->type = $type;
    }

    public function setUrl($url) : void
    {
        $this->url = $url;
    }


    public function getTitle($title)
    {
        return $this->title;
    }

    public function getDescription($description)
    {
        return $this->description;
    }

    public function getImage($image)
    {
        return $this->image;
    }

    public function getType($type)
    {
        return $this->type;
    }

    public function getUrl($url)
    {
        return $this->url;
    }

    public function build()
    {
        $buffer = '';

        if (isset($this->title)){
            $buffer .= '<meta name="og:title" content="' . $this->title . '" />';
        }
        if (isset($this->description)){
            $buffer .= '<meta name="og:description" content="' . $this->description . '" />';
        }
        if (isset($this->image)){
            $buffer .= '<meta name="og:image" content="' . $this->image . '" />';
        }
        if (isset($this->type)){
            $buffer .= '<meta name="og:type" content="' . $this->type . '" />';
        }
        if (isset($this->url)){
            $buffer .= '<meta name="og:url" content="' . $this->url . '" />';
        }

        return $buffer;
    }
}

class Meta
{
    private $description, $keywords, $copyright, $author, $openGraph;

    function __construct(){
        $this->keywords = array();
    }

    public function setDescription($description) : void
    {
        $this->description = $description;
    }

    public function setKeywords($keywords) : void
    {

      if (is_string($keywords)){
        $this->keywords = array_map('trim', explode(",", $keywords));
      } elseif (is_array($keywords)) {
        $this->keywords = $keywords;
      }

    }

    public function addKeyword($keyword) : void
    {
        $this->keywords[] = $keyword;
    }

    public function setCopyright($copyright) : void
    {
        $this->copyright = $copyright;
    }

    public function setAuthor($author) : void
    {
        $this->author = $author;
    }

    public function setOpenGraph() : void
    {
        $this->openGraph = new OpenGraph();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getCopyright()
    {
        return $this->copyright;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getOpenGraph()
    {
        return $this->openGraph;
    }

    public function build()
    {
        $buffer = '';

        if (isset($this->description)){
            $buffer .= '<meta name="description" content="' . $this->description . '" />';
        }
        if (sizeof($this->keywords) > 0){
            $buffer .= '<meta name="keywords" content="' . implode(", ", $this->keywords) . '" />';
        }
        if (isset($this->copyright)){
            $buffer .= '<meta name="copyright" content="' . $this->copyright . '" />';
        }
        if (isset($this->author)){
            $buffer .= '<meta name="author" content="' . $this->author . '" />';
        }
        if (isset($this->url)){
            $buffer .= '<meta name="og:url" content="' . $this->url . '" />';
        }

        if (isset($this->openGraph)){
            $buffer .= $this->openGraph->build();
        }

        return $buffer;
    }
}
