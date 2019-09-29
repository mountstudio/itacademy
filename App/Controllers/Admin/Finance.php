<?php


namespace App\Controllers\Admin;


use Core\CustomException;
use Core\View;
use Models\UserQuery;

class Finance extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('SUPER');
            $students = UserQuery::create()->filterByCurrentGroupId(4);
            $this->data = array_merge($this->data, ['students' => $students]);

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