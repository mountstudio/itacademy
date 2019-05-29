<?php

namespace App\Controllers\Front;

use \Core\View;
use \Core\Helper;
use \Core\Breadcrumb;
use \Core\Functions;
use \Core\Meta;
use App\Config;
use \Core\CustomException;


use Models\BranchQuery;
use Models\ConfigQuery;
use Models\CourseQuery;
use \Models\User;
use \Models\PlaceQuery;

use \Models\Group;
use \Models\GroupQuery;
use \Models\StaticPageQuery;


use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Base extends \Core\Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
     protected $helper, $data, $params, $breadcrumbs;

     function __construct($params) {
         $this->params = $params;
         $this->helper = new Helper();
         $this->breadcrumbs = new Breadcrumb();
         $meta = new Meta();

         $defaultBranchId = ConfigQuery::create()->findOneByKey('default_branch');
         $defaultBranch = BranchQuery::create()->findPk(intval($defaultBranchId->getValue()));

         $courses = CourseQuery::create()->find();
         $branches = BranchQuery::create()->find();
         $staticPages = StaticPageQuery::create()->filterByAvailable(true)->find();
         try {
             $this->data  =  array(
                                    'siteName' => Config::SITE_URL_NOSLASH,
                                    'user'  =>  $this->helper->getCurrentUser(),
                                    'isLoggedIn' => $this->helper->isLoggedIn(),
                                    'encodedToken'  =>  Functions::getCookie('token'),
                                    'helper' => $this->helper,
                                    'currentYear' => date('Y'),
                                    'breadcrumbs' => $this->breadcrumbs,
                                    'titlePostfix' => Config::TITLE_POSTFIX,
                                    'meta' => $meta,
                                    'socialLinks' => Config::SOCIAL_LINKS,
                                    'staticPages' => $staticPages,
                                     'courses' => $courses,
                                     'branches' => $branches,
                                     'defaultBranch' => $defaultBranch
                                    );
         } catch (CustomException $e) {
             View::renderTemplate('Front/500.html');
         }
     }

}
