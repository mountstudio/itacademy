<?php

namespace App\Controllers\Admin\Ajax;

use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;

use Models\BranchQuery;
use Models\CourseStreamStatusQuery;
use Models\CurrencyQuery;
use \Models\UserQuery;

use \Models\Group;
use \Models\GroupQuery;

use \Models\StaticPageQuery;

use \Models\LoginToken;
use \Models\LoginTokenQuery;

use \Models\ApplicationStatusQuery;
use \Models\CourseStatusQuery;

use \Models\ApplicationQuery;

use \Models\ConfigQuery;

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
        $defaultUserGroup = (isset($_POST['defaultUserGroup']) ? $_POST['defaultUserGroup'] : null);
        $defaultInstructorGroup = (isset($_POST['defaultInstructorGroup']) ? $_POST['defaultInstructorGroup'] : null);

        $defaultBranchId = (isset($_POST['defaultBranchId']) ? $_POST['defaultBranchId'] : null);
        $defaultApplicationStatus = (isset($_POST['defaultApplicationStatus']) ? $_POST['defaultApplicationStatus'] : null);
        $defaultCourseStreamStatus = (isset($_POST['defaultCourseStreamStatus']) ? $_POST['defaultCourseStreamStatus'] : null);
        $courseRecruitmentStatus = (isset($_POST['courseRecruitmentStatus']) ? $_POST['courseRecruitmentStatus'] : null);

        $defaultCurrency = (isset($_POST['defaultCurrency']) ? $_POST['defaultCurrency'] : null);

        $defaultShownCurrency = (isset($_POST['defaultShownCurrency']) ? $_POST['defaultShownCurrency'] : null);

        $allowChooseGroup = (isset($_POST['allowChooseGroup']) ? (($_POST['allowChooseGroup'] == 'true') ? true : false) : null);
        $allowedUserGroups = (isset($_POST['allowedUserGroups']) ? $_POST['allowedUserGroups'] : null);
        try {
            $this->helper->shouldHavePrivilege('SUPER');

            if (is_null($allowChooseGroup)){
                throw new CustomException("Управление возможности выбора группы пользователей не предоставлен", 1);
            }

            if ($allowChooseGroup && is_null($allowedUserGroups)){
                throw new CustomException("Не была выбрана группа из разрешенных групп пользователей", 1);
            }


            if (!is_array($allowedUserGroups)){
                $allowedUserGroups = array();
            }

            if ($allowChooseGroup && sizeof($allowedUserGroups) == 0){
                throw new CustomException("Выберите хотя бы одну группу", 1);
            }

            $userGroups = GroupQuery::create()->find();
            foreach ($userGroups as $userGroup_) {
                if (!$userGroup_->getAllowChooseGroup() && in_array($userGroup_->getId(), $allowedUserGroups)){
                    $userGroup_->setAllowChooseGroup(true);
                    $userGroup_->save();
                } elseif ($userGroup_->getAllowChooseGroup() && !in_array($userGroup_->getId(), $allowedUserGroups)) {
                    $userGroup_->setAllowChooseGroup(false);
                    $userGroup_->save();
                }
            }

            $userGroup = GroupQuery::create()->findPK(intval($defaultUserGroup));
            if (is_null($userGroup)){
                throw new CustomException("Группа пользователя не найдена", 1);
            }

            $instructorGroup = GroupQuery::create()->findPK(intval($defaultInstructorGroup));
            if (is_null($instructorGroup)){
                throw new CustomException("Группа инструктора не найдена", 1);
            }

            $defaultBranch = BranchQuery::create()->findPK(intval($defaultBranchId));
            if (is_null($defaultBranch)){
                throw new CustomException("Филиал по умолчанию не найден", 1);
            }

            $configUserGroup = ConfigQuery::create()->findOneByKey('default_user_group');
            $configInstructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');
            $configBranch = ConfigQuery::create()->findOneByKey('default_branch');
            $configApplicationStatus = ConfigQuery::create()->findOneByKey('default_application_status');
            $configCourseStreamStatus = ConfigQuery::create()->findOneByKey('default_course_stream_status');
            $configCourseReqruitmentStatus = ConfigQuery::create()->findOneByKey('course_stream_recruitment_status');

            $configDefaultCurrency = ConfigQuery::create()->findOneByKey('default_currency_id');
            $configDefaultShownCurrency = ConfigQuery::create()->findOneByKey('default_shown_currency_id');

            $configChooseUserGroup = ConfigQuery::create()->findOneByKey('allow_users_choose_group');

            if (is_null($configUserGroup)){
                $configUserGroup = new \Models\Config();
                $configUserGroup->setKey('default_user_group');
            }

            if (is_null($configInstructorGroup)){
                $configInstructorGroup = new \Models\Config();
                $configInstructorGroup->setKey('default_instructor_group');
            }

            if (is_null($configBranch)){
                $configBranch = new \Models\Config();
                $configBranch->setKey('default_branch');
            }


            if (is_null($configApplicationStatus)){
                $configApplicationStatus = new \Models\Config();
                $configApplicationStatus->setKey('default_application_status');
            }

            $applicationStatus = ApplicationStatusQuery::create()->findPK(intval($defaultApplicationStatus));
            if (is_null($applicationStatus)){
                throw new CustomException("Состояние заявки не найдено", 1);
            }


            if (is_null($configCourseStreamStatus)){
                $configCourseStreamStatus = new \Models\Config();
                $configCourseStreamStatus->setKey('default_course_stream_status');
            }

            $courseStreamStatus = CourseStreamStatusQuery::create()->findPK(intval($defaultCourseStreamStatus));
            if (is_null($courseStreamStatus)){
                throw new CustomException("Состояние потока курса не найдено", 1);
            }



            if (is_null($configCourseReqruitmentStatus)){
                $configCourseReqruitmentStatus = new \Models\Config();
                $configCourseReqruitmentStatus->setKey('course_stream_recruitment_status');
            }

            if (is_null($configDefaultCurrency)){
                $configDefaultCurrency = new \Models\Config();
                $configDefaultCurrency->setKey('default_currency_id');
            }

            if (is_null($configDefaultShownCurrency)){
                $configDefaultShownCurrency = new \Models\Config();
                $configDefaultShownCurrency->setKey('default_shown_currency_id');
            }


            $courseRecruitmentStatus = CourseStreamStatusQuery::create()->findPK(intval($courseRecruitmentStatus));
            if (is_null($courseRecruitmentStatus)){
                throw new CustomException("Состояние курса не найдено", 1);
            }


            $defaultCurrency = CurrencyQuery::create()->findPK(intval($defaultCurrency));
            if (is_null($defaultCurrency)){
                throw new CustomException("Валюта по умолчанию не найдена", 1);
            }


            $defaultShownCurrency = CurrencyQuery::create()->findPK(intval($defaultShownCurrency));
            if (is_null($defaultShownCurrency)){
                throw new CustomException("Валюта показа по умолчанию не найдена", 1);
            }





            if (is_null($configChooseUserGroup)){
                $configChooseUserGroup = new \Models\Config();
                $configChooseUserGroup->setKey('allow_users_choose_group');
            }




            $configUserGroup->setValue($userGroup->getId());
            $configInstructorGroup->setValue($instructorGroup->getId());
            $configBranch->setValue($defaultBranch->getId());
            $configApplicationStatus->setValue($applicationStatus->getId());
            $configCourseReqruitmentStatus->setValue($courseRecruitmentStatus->getId());

            $configDefaultCurrency->setValue($defaultCurrency->getId());
            $configDefaultShownCurrency->setValue($defaultShownCurrency->getId());

            $configCourseStreamStatus->setValue($courseStreamStatus->getId());


            $configChooseUserGroup->setValue(($allowChooseGroup) ? 1 : 0);

            $configUserGroup->save();
            $configInstructorGroup->save();
            $configBranch->save();
            $configApplicationStatus->save();
            $configCourseReqruitmentStatus->save();
            $configDefaultCurrency->save();
            $configDefaultShownCurrency->save();
            $configCourseStreamStatus->save();
            $configChooseUserGroup->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("Конфигурации успешно сохранены");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
