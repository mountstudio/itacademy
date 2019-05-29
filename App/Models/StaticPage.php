<?php

namespace Models;

use App\Config;
use \Core\Image;
use \Core\CustomException;


use Models\Map\StaticPageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Cocur\Slugify\Slugify;
use Models\Base\StaticPage as BaseStaticPage;

/**
 * Skeleton subclass for representing a row from the 'static_page' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class StaticPage extends BaseStaticPage
{

    protected function setAltUrlByTitleRecursively($try = 1){
        $sluggedTitle = Slugify::create()->slugify($this->getTitle());

        if ($try > 1){
            $sluggedTitle .= '-' . $try;
        }
        $staticPageByTitle = StaticPageQuery::create()->filterById($this->getId(), Criteria::NOT_EQUAL)->findOneByAltUrl($sluggedTitle);
        if (is_null($staticPageByTitle)){
            $this->setAltUrl($sluggedTitle);
        } else {
            $try++;
            return self::setAltUrlByTitleRecursively($try);
        }
    }

    public function preSave(ConnectionInterface $con = null)
    {
        if ($this->isColumnModified(StaticPageTableMap::COL_TITLE)){
            self::setAltUrlByTitleRecursively();
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

        $validLogoUploadsDirName = explode('public', Config::STATIC_PAGE_LOGO_UPLOADS_DIR)[1];
        return array(   'thumb'     => $validLogoUploadsDirName . Config::STATIC_PAGE_LOGO_THUMBNAIL_DIR_NAME . "/" . $imageName,
            'original'  => $validLogoUploadsDirName . Config::STATIC_PAGE_LOGO_ORIGINAL_DIR_NAME . "/" . $imageName,
            'normal'     => $validLogoUploadsDirName . Config::STATIC_PAGE_LOGO_NORMAL_DIR_NAME . "/" . $imageName,
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
                $imageName = $image->createStaticPageLogo();

                if (!is_null($this->getLogoName())) {
                    Image::deleteStaticPageLogo($this->getLogoName());
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

        $validCoverUploadsDirName = explode('public', Config::STATIC_PAGE_COVER_UPLOADS_DIR)[1];
        return array(
            'normal'     => $validCoverUploadsDirName . Config::STATIC_PAGE_COVER_NORMAL_DIR_NAME . "/" . $imageName,
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
                $imageName = $image->createStaticPageCover();

                if (!is_null($this->getCoverName())) {
                    Image::deleteStaticPageCover($this->getCoverName());
                }

                $this->setCoverName($imageName);
            } else {
                throw new CustomException("Формат фотографии неподдерживается", 1);
            }
        } else {
            throw new CustomException("Формат файла неподдерживается", 1);
        }
    }

    public function preDelete(ConnectionInterface $con = null)
    {
        if (!is_null($this->getLogoName())){
            Image::deleteStaticPageLogo($this->getLogoName());
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
