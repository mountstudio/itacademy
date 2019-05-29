<?php

namespace App\Controllers\Front;

use \Core\View;
use \Core\Helper;
use \Core\Functions;
use App\Config;
use \Core\CustomException;
use Models\BranchQuery;
use Models\ContactQuery;
use Models\CourseQuery;
use Models\FAQQuery;
use Models\FeedbackQuery;
use \Models\Group;
use \Models\GroupQuery;
use \Models\ProductQuery;

use \Models\ReviewQuery;

use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

use \Models\UserQuery;
use \Models\PlaceQuery;
use \Models\TagQuery;
use \Models\ConfigQuery;
use \Models\RecallStatusQuery;
use \Models\MassTypeQuery;
use \Models\DeliveryTimeTypeQuery;
use \Models\PaymentTypeQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Index extends Base
{
    public function indexAction()
    {

        try {

            $faqs = FAQQuery::create()->find();
            $defaultInstructor = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $instructors = UserQuery::create()->filterByCurrentGroupId($defaultInstructor->getValue())->find();


            $feedbacks = FeedbackQuery::create()->filterByAvailable(true)->find();
            $this->data = array_merge( $this->data,
                                       array(
                                            'faqs' => $faqs,
                                            'instructors' => $instructors,
                                            'feedbacks' => $feedbacks
                                       )
                                   );

            View::renderTemplate('Front/index.html', $this->data);
        } catch (CustomException $e) {
            $this->data = array_merge(  $this->data,
                                        $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
              );
            View::renderTemplate('Front/messagePage.html', $this->data);
        }
    }


    public function kitchensAction()
    {
        $this->indexAction('kitchens');
    }

    public function othersAction()
    {
        $this->indexAction('others');
    }


}
