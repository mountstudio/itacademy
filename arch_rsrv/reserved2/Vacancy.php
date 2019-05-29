<?php

namespace Models;


use \Core\Image;
use App\Config;
use Cocur\Slugify\Slugify;

use Models\Map\VacancyTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use \Core\CustomException;


use Models\Base\Vacancy as BaseVacancy;

/**
 * Skeleton subclass for representing a row from the 'vacancy' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Vacancy extends BaseVacancy
{

    protected function setAltUrlByNameRecursively($try = 1){
        $sluggedTitle = Slugify::create()->slugify($this->getName());

        if ($try > 1){
            $sluggedTitle .= '-' . $try;
        }
        $staticPageByTitle = VacancyQuery::create()->filterById($this->getId(), Criteria::NOT_EQUAL)->findOneByAltUrl($sluggedTitle);
        if (is_null($staticPageByTitle)){
            $this->setAltUrl($sluggedTitle);
        } else {
            $try++;
            return self::setAltUrlByNameRecursively($try);
        }
    }

    public function preSave(ConnectionInterface $con = null)
    {
        if ($this->isColumnModified(VacancyTableMap::COL_NAME)){
            self::setAltUrlByNameRecursively();
        }
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    public function getLogo()
    {

        if (is_null($this->getLogoName())){
            $imageName = Config::DEFAULT_IMAGE_NAME;
        } else {
            $imageName = $this->getLogoName();
        }

        $validLogoUploadsDirName = explode('public', Config::VACANCY_LOGO_UPLOADS_DIR)[1];
        return array(   'thumb'     => $validLogoUploadsDirName . Config::VACANCY_LOGO_THUMBNAIL_DIR_NAME . "/" . $imageName,
            'original'  => $validLogoUploadsDirName . Config::VACANCY_LOGO_ORIGINAL_DIR_NAME . "/" . $imageName,
            'normal'     => $validLogoUploadsDirName . Config::VACANCY_LOGO_NORMAL_DIR_NAME . "/" . $imageName,
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
                $imageName = $image->createVacancyLogo();

                if (!is_null($this->getLogoName())) {
                    Image::deleteVacancyLogo($this->getLogoName());
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

        $validCoverUploadsDirName = explode('public', Config::VACANCY_COVER_UPLOADS_DIR)[1];
        return array(
            'original'  => $validCoverUploadsDirName . Config::VACANCY_COVER_ORIGINAL_DIR_NAME . "/" . $imageName,
            'normal'     => $validCoverUploadsDirName . Config::VACANCY_COVER_NORMAL_DIR_NAME . "/" . $imageName,
        );
    }

    public function preDelete(ConnectionInterface $con = null)
    {
        if (!is_null($this->getLogoName())){
            Image::deleteVacancyLogo($this->getLogoName());
        }
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }
}
