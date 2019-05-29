<?php

namespace Core;

require_once '../vendor/autoload.php';


use App\Config;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;


/**
 * Error and exception handler
 *
 * PHP version 7.0
 */
class Telegram
{
    private $key = null;
    private $username = null;
    private $chatId = null;
    private $content = null;

    function __construct($content = null){
        $this->key = Config::TELEGRAM_BOT['key'];
        $this->username = Config::TELEGRAM_BOT['username'];
        $this->chatId = Config::TELEGRAM_BOT['chat_id'];
        $this->content = $content;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getKey(){
        return $this->key;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getChatId(){
        return $this->chatId;
    }

    public function getContent()
    {
        return $this->content;
    }


    public function set()
    {
        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($this->key, $this->username);

            $result = $telegram->setWebhook(Config::TELEGRAM_BOT['hook']);
            if ($result->isOk()) {
                // $result->getDescription();
            }
        } catch (TelegramException $e) {
            // log telegram errors
            // $e->getMessage();
        }
    }

    public function hook()
    {
        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($this->key, $this->username);
            $telegram->handle();
        } catch (TelegramException $e) {
            // log telegram errors
            // $e->getMessage();
        }
    }

    public function getUpdates()
    {
        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($this->key, $this->username);

            $telegram->useGetUpdatesWithoutDatabase();
        } catch (TelegramException $e) {
            // log telegram errors
            // $e->getMessage();
        }
    }



    public function send(){
        try {

            $result = Request::sendMessage([
                'chat_id' => $this->chatId,
                'text'    => $this->content,
            ]);

            if ($result->isOk()) {
                return true;
            }

        } catch (TelegramException $e) {
            return false;
            //new CustomException($e->getMessage());
        }
        return false;

    }

}
