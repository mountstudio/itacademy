<?php

namespace Core;
use Core\Functions;
/**
 * View
 *
 * PHP version 7.0
 */
class View
{


    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }


     public static function renderTemplate($template, $args = [], $show = true)
     {
         static $twig = null;

         if ($twig === null) {
             $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
             $twig = new \Twig_Environment($loader/*,  ['cache' => dirname(__DIR__) . '/cache/template']*/);
             $filter = new \Twig_Filter('addslashes', function ($string) {
                return addslashes($string);
             });
             $twig->addFilter($filter);

             $getTime = new \Twig_Filter('getTime', function ($date) {
                 return date("H:i:s",strtotime($date->format("H:i:s")));
             });
             $twig->addFilter($getTime);

             $getDate = new \Twig_Filter('getDate', function ($date) {
                 return date("Y-m-d",$date->getTimestamp());
             });
             $twig->addFilter($getDate);


             $getTimeFormat = new \Twig_Filter('formatDateTime', function ($date) {
                return date("Y-m-d H:i", $date->getTimestamp());
             });
             $twig->addFilter($getTimeFormat);


             $getRULocaleTimeFormat = new \Twig_Filter('formatRULocaleDate', function ($date) {
                 return Functions::dateToRULocale($date);
             });
             $twig->addFilter($getRULocaleTimeFormat);

             $getRULocaleTimeFormat = new \Twig_Filter('formatRULocaleDateFull', function ($date) {
                 return Functions::dateToRULocaleFull($date);
             });
             $twig->addFilter($getRULocaleTimeFormat);




             $toInt = new \Twig_Filter('intval', function ($toInteger) {
                return intval($toInteger);
             });
             $twig->addFilter($toInt);

             $toSplit = new \Twig_Filter('timeSplit', function ($string) {
                 return explode(":", $string);
             });
             $twig->addFilter($toSplit);

             $toReadableDuration = new \Twig_Filter('readableDuration', function ($string) {
                 $end = Functions::formatDurationTiming($string);
                 $vision = explode(":", $end);
                 $start = (sizeof($vision) == 3) ? '00:00:00' : '00:00';
                 return array(
                     'start' => $start,
                     'end' => $end
                 );
             });
             $twig->addFilter($toReadableDuration);

             $getRandomFromArray = new \Twig_Filter('getRandomFromArray', function ($arr) {
                return $arr[array_rand($arr)];
             });
             $twig->addFilter($getRandomFromArray);


         }

         if ($show){
             echo $twig->render($template, $args);
         } else {
             return $twig->render($template, $args);
         }
     }

     /**
      * Render a mail template using Twig
      *
      * @param string $template  The template file
      * @param array $args  Associative array of data to display in the view (optional)
      *
      * @return string
      */
     public static function renderEmailTemplate($template, $args = [])
     {
         static $twig = null;

         if ($twig === null) {
             $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views/Email');
             $twig = new \Twig_Environment($loader/*,  ['cache' => dirname(__DIR__) . '/cache/template']*/);
         }

         return $twig->render($template, $args);
     }
}
