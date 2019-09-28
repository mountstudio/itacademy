<?php


namespace App\Controllers\Admin;


use Core\CustomException;
use Core\View;

class Finance extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            View::renderTemplate('Admin/Finance/index.html', $this->data);
        } catch (CustomException $exception) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $exception->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $exception->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }
    }
}