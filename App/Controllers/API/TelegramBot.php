<?php

namespace App\Controllers\API;

use Core\Telegram;
use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;


use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

use \Models\ImageArchiveQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class TelegramBot extends Base
{

    public function hookAction()
    {
        ob_start();
        var_dump($_GET);
        file_put_contents("./dump.txt", ob_get_flush());
    }

    public function sendAction()
    {
        $telegram = new Telegram();
        $telegram->set();
        $telegram->setContent($_GET['text']);
        $telegram->send();
    }
}
