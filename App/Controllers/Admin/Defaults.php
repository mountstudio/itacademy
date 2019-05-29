<?php

namespace App\Controllers\Admin;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;

use Models\BranchQuery;
use Models\CourseStreamStatusQuery;
use \Models\CurrencyQuery;
use \Models\Group;
use \Models\GroupQuery;


use \Models\StaticPageQuery;

use \Models\UserQuery;
use \Models\TagQuery;
use \Models\ApplicationQuery;

use \Models\ApplicationStatusQuery;
use \Models\CourseStatusQuery;
use \Models\VacancySalaryQuery;
use \Models\ConfigQuery;
use \Models\PaymentTypeQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Defaults extends Base
{
     public function indexAction()
     {
         try {
             $this->helper->shouldHavePrivilege('SUPER');
             $getDefaults = ConfigQuery::create()->find();

             $defaultsList = array();

             foreach($getDefaults as $getDefault) {
                $defaultsList[$getDefault->getKey()] = $getDefault;
             }

             $userGroups = GroupQuery::create()->find();

             $currencies = CurrencyQuery::create()->find();
             $branches = BranchQuery::create()->find();

             $applicationStatuses = ApplicationStatusQuery::create()->find();
             $courseStreamStatuses = CourseStreamStatusQuery::create()->find();


            $this->data = array_merge( $this->data,
                                       array(   'defaults' => $defaultsList,
                                                'userGroups' => $userGroups,
                                           'branches' => $branches,
                                           'applicationStatuses' => $applicationStatuses,
                                           'courseStreamStatuses' => $courseStreamStatuses,
                                           'currencies' => $currencies

                                               )
                                       );
             View::renderTemplate('Admin/Settings/Defaults/index.html', $this->data);
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
