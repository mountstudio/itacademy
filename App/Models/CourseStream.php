<?php

namespace Models;



use Core\Functions;
use Core\Helper;
use Models\Base\CourseStream as BaseCourseStream;

/**
 * Skeleton subclass for representing a row from the 'course_stream' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CourseStream extends BaseCourseStream
{
    public function getDuration()
    {
        $interval = $this->getStartsAt()->diff($this->getEndsAt());
        $addition = 0;
        if ($interval->d >= 8 && $interval->d < 21){
            $addition = 0.5;
        } elseif ($interval->d >= 21){
            $addition = 1;
        }


        if ($interval->m == 0){
            $dTitles = array('день', 'дня', 'дней');
            if ($interval->d == 0){
                return '0 ' . $dTitles[2];
            }
            return Functions::getRuNumToStr($interval->d, $dTitles);
        }
        $titles = array('месяц', 'месяца', 'месяцев');

        if ($interval->m == 1 && $addition == 0.5){
            return '1.5 ' . $titles[1];
        } else {
            $months = $interval->m;
            if ($addition == 1) {
                return Functions::getRuNumToStr($months + $addition, $titles);
            }
            return ($months + $addition) . ' ' . Functions::getRuNumToStr($months, $titles, false);
        }
    }

    public function getStartDate(){
        $day = intval($this->getStartsAt()->format('d'));
        $monthId = intval($this->getStartsAt()->format('m'));

        return sprintf('%d %s', $day, \App\Config::DATE_NAMINGS[$monthId]);
    }

    public function getEndDate(){
        $day = $this->getEndsAt()->format('d');
        $monthId = intval($this->getEndsAt()->format('m'));

        return sprintf('%s %s', $day, \App\Config::DATE_NAMINGS[$monthId]);
    }


    public function getIsOnRecruitment(){
        $getDefaultOnRecruitment = ConfigQuery::create()->findOneByKey('course_stream_recruitment_status');
        return intval($getDefaultOnRecruitment->getValue()) == $this->getCurrentCourseStreamStatusId();
    }
}
