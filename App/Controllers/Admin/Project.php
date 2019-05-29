<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;

use \Core\CustomException;

use \Models\ConfigQuery;
use \Models\InstructorQuery;

use \Models\ProjectQuery;

use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Project extends Base
{
    public function indexAction()
    {
        try {
            $this->helper->shouldHavePrivilege('PROJECT_ADMIN');

            View::renderTemplate('Admin/Project/all.html', $this->data);
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
            $this->helper->shouldHavePrivilege('PROJECT_ADMIN');
            $projectId = (isset($this->params['id']) ? $this->params['id'] : null);
            if (is_null($projectId) || intval($projectId) == 0){
                throw new CustomException("Id of project invalid", 403);
            }

            $project = ProjectQuery::create()->findPk(intval($projectId));
            if (is_null($project)){
                throw new CustomException("Проект не найден", 404);
            }

            $this->data = array_merge( $this->data,
                array(
                    'project' => $project
                )
            );
            View::renderTemplate('Admin/Project/edit.html', $this->data);
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
            $this->helper->shouldHavePrivilege('PROJECT_ADMIN');

            View::renderTemplate('Admin/Project/add.html', $this->data);
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
