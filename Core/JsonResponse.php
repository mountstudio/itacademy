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

// Init Monolog Logger
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Init Propel
use Propel\Runtime\Propel;

use App\Config;
use Core\Mail;
use Core\Functions;
use Core\CustomException;
use Propel\Runtime\Util\PropelModelPager;

/**
 *
 */
class JsonResponse
{
    const SUCCESS = "success";
    const FAIL = "fail";
    const ERROR = "error";

    protected $status, $message, $data, $redirect, $isPagination, $paginationDetails;

    function __construct(bool $pagination = false)
    {
        $this->status = self::ERROR;
        $this->message = "no action is defined";
        $this->data = null;
        $this->redirect = null;
        $this->isPagination = $pagination;
        if ($this->isPagination) $this->paginationDetails = null;
    }

    public function setStatus($status) : void
    {
        $this->status = $status;
    }

    public function setMessage($message) : void
    {
        $this->message = $message;
    }

    public function setData(array $data) : void
    {
        $this->data = $data;
    }

    public function mergeData(array $data) : void
    {
        if (is_null($this->data)){
            $this->setData($data);
        } else {
            $this->setData(array_merge($this->data, $data));
        }
    }

    public function setRedirect($redirect) : void
    {
        $this->redirect = $redirect;
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }

    public function setPaginationDetails($paginationDetails) : void
    {
        if (is_integer($paginationDetails)) {
            $this->paginationDetails = array( 'total_count' => $paginationDetails,
                                        'incomplete_results' => false,
                                        'firstIndex' => 1,
                                        'lastIndex' => $paginationDetails
                                        );
        } elseif (is_array($paginationDetails)) {
            $this->paginationDetails = array( 'total_count' => $paginationDetails['total_count'],
                                        'incomplete_results' => $paginationDetails['incomplete_results'],
                                        'firstIndex' => $paginationDetails['firstIndex'],
                                        'lastIndex' => $paginationDetails['lastIndex']
                                        );
        } elseif ($this->isPagination){
            $this->paginationDetails = array( 'total_count' => $paginationDetails->getNbResults(),
                                        'incomplete_results' => $paginationDetails->getNbResults() != $paginationDetails->getLastIndex(),
                                        'firstIndex' => $paginationDetails->getFirstIndex(),
                                        'lastIndex' => $paginationDetails->getLastIndex()
                                        );
        } else {
            throw new CustomException("Установите JsonResponse сперва как пагинатор", 1);
        }
    }



    public function __toString()
    {
        $response = array(  'status' => $this->status,
                            'data' => $this->data,
                            'time' => time()
                        );

        if (!is_null($this->message) || $this->status == self::FAIL || $this->status == self::ERROR) $response = array_merge($response, array('message' => $this->message));
        if (!is_null($this->redirect)) $response = array_merge($response, array('redirect' => $this->redirect));
        if ($this->isPagination && !is_null($this->paginationDetails)) $response = array_merge($response, $this->paginationDetails);
        return json_encode($response);
    }


    public function setException(CustomException $e) : void
    {
        if ($e->getCode() == 0){
            http_response_code(403);
            $this->status = self::FAIL;
        } elseif ($e->getCode() == 1){
            http_response_code(400);
            $this->status = self::ERROR;
        }
        $this->message = $e->getMessage();
    }




    public function show() : void
    {

        header("Content-Type:application/json");
        echo $this;
    }

}
