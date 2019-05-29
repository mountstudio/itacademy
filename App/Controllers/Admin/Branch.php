<?php

namespace App\Controllers\Admin;

use Core\Model\Branch\GeographicCoordinates;
use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use Models\BranchQuery;
use \Models\Group;
use \Models\ContactQuery;


use \Models\StaticPageQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Branch extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('BRANCH_ADMIN');

            View::renderTemplate('Admin/Branch/all.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }
    }

    public function editAction()
    {
        try {
            $this->helper->shouldHavePrivilege('BRANCH_ADMIN');
            $id = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID не был указан", 403);
            }

            $branch = BranchQuery::create()->findPK(intval($id));
            if (is_null($branch)){
                throw new CustomException("Контакт не найден", 1);
            }
            $this->data = array_merge( $this->data,
                array(  'branch' => $branch
                )
            );
            View::renderTemplate('Admin/Branch/edit.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }

    }

    public function addAction()
    {
        try {
            $this->helper->shouldHavePrivilege('BRANCH_ADMIN');
            View::renderTemplate('Admin/Branch/add.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge( $this->data,
                array(  'error_code' => $e->getCode(),
                    'error_title' => "Ошибка",
                    'error_message' => $e->getMessage(),
                )
            );
            View::renderTemplate('Admin/errorPage.html', $this->data);
        }

    }
}
