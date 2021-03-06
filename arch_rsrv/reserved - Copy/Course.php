<?php

namespace Models;


use \Core\Image;
use App\Config;

use Models\Map\CourseSkillTableMap;
use Models\Map\CourseStreamTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use \Core\CustomException;


use Models\Base\Course as BaseCourse;

/**
 * Skeleton subclass for representing a row from the 'course' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Course extends BaseCourse
{
    public function getLogo()
    {

        if (is_null($this->getLogoName())){
            $imageName = Config::DEFAULT_IMAGE_NAME;
        } else {
            $imageName = $this->getLogoName();
        }

        $validLogoUploadsDirName = explode('public', Config::COURSE_LOGO_UPLOADS_DIR)[1];
        return array(   'thumb'     => $validLogoUploadsDirName . Config::COURSE_LOGO_THUMBNAIL_DIR_NAME . "/" . $imageName,
            'original'  => $validLogoUploadsDirName . Config::COURSE_LOGO_ORIGINAL_DIR_NAME . "/" . $imageName,
            'normal'     => $validLogoUploadsDirName . Config::COURSE_LOGO_NORMAL_DIR_NAME . "/" . $imageName,
        );
    }

    public function setLogo($file)
    {
        $isImage = false;
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            switch ($check["mime"]) {
                case 'image/png':
                    $isImage = true;
                    break;
                case 'image/jpeg':
                    $isImage = true;
                    break;
            }
            if ($isImage){
                $image = new Image($file['tmp_name']);
                $imageName = $image->createCourseLogo();

                if (!is_null($this->getLogoName())) {
                    Image::deleteCourseLogo($this->getLogoName());
                }

                $this->setLogoName($imageName);
            } else {
                throw new CustomException("Формат фотографии неподдерживается", 1);
            }
        } else {
            throw new CustomException("Формат файла неподдерживается", 1);
        }
    }

    public function getCover()
    {
        if (is_null($this->getCoverName())){
            $imageName = Config::DEFAULT_IMAGE_NAME;
        } else {
            $imageName = $this->getCoverName();
        }

        $validCoverUploadsDirName = explode('public', Config::COURSE_COVER_UPLOADS_DIR)[1];
        return array(
            'original'  => $validCoverUploadsDirName . Config::COURSE_COVER_ORIGINAL_DIR_NAME . "/" . $imageName,
            'normal'     => $validCoverUploadsDirName . Config::COURSE_COVER_NORMAL_DIR_NAME . "/" . $imageName,
        );
    }

    public function setCover($file)
    {
        $isImage = false;
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            switch ($check["mime"]) {
                case 'image/png':
                    $isImage = true;
                    break;
                case 'image/jpeg':
                    $isImage = true;
                    break;
            }
            if ($isImage){
                $image = new Image($file['tmp_name']);
                $imageName = $image->createCourseCover();

                if (!is_null($this->getCoverName())) {
                    Image::deleteCourseCover($this->getCoverName());
                }

                $this->setCoverName($imageName);
            } else {
                throw new CustomException("Формат фотографии неподдерживается", 1);
            }
        } else {
            throw new CustomException("Формат файла неподдерживается", 1);
        }
    }


    public function getActiveStreams()
    {
        return $this->getCurrentCourseStreamCourses(CourseStreamTableMap::COL_ENDS_AT, time(), Criteria::GREATER_THAN);
    }



    public function preDelete(ConnectionInterface $con = null)
    {
        if (!is_null($this->getLogoName())){
            Image::deleteCourseLogo($this->getLogoName());
        }
        if (!is_null($this->getCoverName())){
            Image::deleteCourseCover($this->getCoverName());
        }
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }
}
