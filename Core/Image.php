<?php

namespace Core;

// Setup the autoloading
require_once '../vendor/autoload.php';

use App\Config;
use \Gumlet\ImageResize;
use Core\Functions;

class Image {
    protected $filePath, $fileName, $fileGeneratedName, $image, $width, $height, $minSize, $outputType, $mimeType, $isAllowed = false, $fileUrl;

    public function __construct(string $filePath = null, $fileName = null, $fileUrl = null) {
        if ($filePath != null){
            $this->filePath = $filePath;
            $this->mimeType = mime_content_type($this->filePath);
            switch ($this->mimeType) {
                case 'image/png':
                    $this->isAllowed = true;
                    break;
                case 'image/jpeg':
                    $this->isAllowed = true;
                    break;
                case 'image/gif':
                    $this->isAllowed = true;
                    break;
            }

            if (!$this->isAllowed) throw new \Exception("ImageTypeIsNOTAllowed", 1);

            $this->fileGeneratedName = (is_null($fileName)) ? Functions::generateToken(Config::IMAGE_GENERATED_NAME_LENGTH) : $fileName;
            switch(Config::IMAGE_TYPE){
                case 'png':
                    $this->outputType = IMAGETYPE_PNG;
                    break;
                case 'jpeg':
                    $this->outputType = IMAGETYPE_JPEG;
                    break;
                case 'gif':
                    $this->outputType = IMAGETYPE_GIF;
                    break;
                default:
                    $this->outputType = IMAGETYPE_PNG;
            }
            $image = new ImageResize($this->filePath);
            $this->width = $image->getSourceWidth();
            $this->height = $image->getSourceHeight();
            $this->minSize = ($image->getSourceWidth() < $image->getSourceHeight()) ? $image->getSourceWidth() : $image->getSourceHeight();
        }
        if (!is_null($fileName)){
            $this->fileName = $fileName;
        }
        if (!is_null($fileUrl)){
            $this->fileGeneratedName = Functions::generateToken(Config::IMAGE_GENERATED_NAME_LENGTH);
            $this->outputType = IMAGETYPE_JPEG;
            $this->fileUrl = $fileUrl;
        }
    }

    public function getSize()
    {
        return array(   'width' => $this->width,
            'height'=> $this->height
        );
    }

    public function createCourseOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::COURSE_LOGO_MAX_SIZE['width'] || $this->height > Config::COURSE_LOGO_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::COURSE_LOGO_MAX_SIZE['width'], Config::COURSE_LOGO_MAX_SIZE['height']);
        }
        $tempImage->save(Config::COURSE_LOGO_UPLOADS_DIR . Config::COURSE_LOGO_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createCourseNormal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::COURSE_LOGO_NORMAL_SIZE['width'] || $this->height < Config::COURSE_LOGO_NORMAL_SIZE['height']) {
            if ($this->width < (Config::COURSE_LOGO_NORMAL_SIZE['width'] * Config::COURSE_LOGO_NORMAL_EQUIVALENT) || $this->height < (Config::COURSE_LOGO_NORMAL_SIZE['height'] * Config::COURSE_LOGO_NORMAL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::COURSE_LOGO_NORMAL_SIZE['width'] * Config::COURSE_LOGO_NORMAL_EQUIVALENT), (Config::COURSE_LOGO_NORMAL_SIZE['height'] * Config::COURSE_LOGO_NORMAL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::COURSE_LOGO_NORMAL_SIZE['width'], Config::COURSE_LOGO_NORMAL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::COURSE_LOGO_NORMAL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::COURSE_LOGO_NORMAL_SIZE['width'], Config::COURSE_LOGO_NORMAL_SIZE['height']);
        }
        $tempImage->save(Config::COURSE_LOGO_UPLOADS_DIR . Config::COURSE_LOGO_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createCourseThumbnail(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::COURSE_LOGO_THUMBNAIL_SIZE['width'] || $this->height < Config::COURSE_LOGO_THUMBNAIL_SIZE['height']) {
            if ($this->width < (Config::COURSE_LOGO_THUMBNAIL_SIZE['width'] * Config::COURSE_LOGO_THUMBNAIL_EQUIVALENT) || $this->height < (Config::COURSE_LOGO_THUMBNAIL_SIZE['height'] * Config::COURSE_LOGO_THUMBNAIL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::COURSE_LOGO_THUMBNAIL_SIZE['width'] * Config::COURSE_LOGO_THUMBNAIL_EQUIVALENT), (Config::COURSE_LOGO_THUMBNAIL_SIZE['height'] * Config::COURSE_LOGO_THUMBNAIL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::COURSE_LOGO_THUMBNAIL_SIZE['width'], Config::COURSE_LOGO_THUMBNAIL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::COURSE_LOGO_THUMBNAIL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::COURSE_LOGO_THUMBNAIL_SIZE['width'], Config::COURSE_LOGO_THUMBNAIL_SIZE['height']);
        }
        $tempImage->save(Config::COURSE_LOGO_UPLOADS_DIR . Config::COURSE_LOGO_THUMBNAIL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createCourseLogo()
    {
        $this->createCourseOriginal();
        $this->createCourseNormal();
        $this->createCourseThumbnail();
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteCourseLogo($fileName)
    {
        @unlink(Config::COURSE_LOGO_UPLOADS_DIR . Config::COURSE_LOGO_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::COURSE_LOGO_UPLOADS_DIR . Config::COURSE_LOGO_NORMAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::COURSE_LOGO_UPLOADS_DIR . Config::COURSE_LOGO_THUMBNAIL_DIR_NAME . "/" . $fileName);
    }




    public function createStaticPageLogoNormal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::STATIC_PAGE_LOGO_NORMAL_SIZE['width'] || $this->height < Config::STATIC_PAGE_LOGO_NORMAL_SIZE['height']) {
            if ($this->width < (Config::STATIC_PAGE_LOGO_NORMAL_SIZE['width'] * Config::STATIC_PAGE_LOGO_NORMAL_EQUIVALENT) || $this->height < (Config::STATIC_PAGE_LOGO_NORMAL_SIZE['height'] * Config::STATIC_PAGE_LOGO_NORMAL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::STATIC_PAGE_LOGO_NORMAL_SIZE['width'] * Config::STATIC_PAGE_LOGO_NORMAL_EQUIVALENT), (Config::STATIC_PAGE_LOGO_NORMAL_SIZE['height'] * Config::STATIC_PAGE_LOGO_NORMAL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::STATIC_PAGE_LOGO_NORMAL_SIZE['width'], Config::STATIC_PAGE_LOGO_NORMAL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::STATIC_PAGE_LOGO_NORMAL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::STATIC_PAGE_LOGO_NORMAL_SIZE['width'], Config::STATIC_PAGE_LOGO_NORMAL_SIZE['height']);
        }
        $tempImage->save(Config::STATIC_PAGE_LOGO_UPLOADS_DIR . Config::STATIC_PAGE_LOGO_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }



    public function createStaticPageCoverNormal(){
        $tempImage = new ImageResize($this->filePath);

        if ($this->width != Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['width'] && $this->height != Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['height']) {
            $tempImage->resizeToWidth(Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['width']);
            $ratioWidth = $tempImage->getDestWidth();
            $ratioHeight = $tempImage->getDestHeight();
            $coeff = $this->width / Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['width'];
            $coeffHeight = $this->height / $coeff;

            if (!(Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['height'] - 1 > $coeffHeight || $coeffHeight < Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['height'] + 1)){
                $tempImage->crop($ratioWidth, Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['height'], true, ImageResize::CROPCENTER);
            }
        }
        $tempImage->save(Config::STATIC_PAGE_COVER_UPLOADS_DIR . Config::STATIC_PAGE_COVER_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }


    public function createStaticPageLogo()
    {
        if ($this->width >= Config::STATIC_PAGE_LOGO_NORMAL_FIXED_SIZE['width'] && $this->height >= Config::STATIC_PAGE_LOGO_NORMAL_FIXED_SIZE['height']) {
            $this->createStaticPageLogoNormal();
        } else {
            throw new CustomException("Размер logo меньше чем " . Config::STATIC_PAGE_LOGO_NORMAL_FIXED_SIZE['width'] . 'x' . Config::STATIC_PAGE_LOGO_NORMAL_FIXED_SIZE['height'], 1);
        }
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteStaticPageLogo($fileName)
    {
        @unlink(Config::STATIC_PAGE_LOGO_UPLOADS_DIR . Config::STATIC_PAGE_LOGO_NORMAL_DIR_NAME . "/" . $fileName);
    }



    public function createStaticPageCover()
    {
        if ($this->width >= Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['width'] && $this->height >= Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['height']) {
            $this->createStaticPageCoverNormal();
        } else {
            throw new CustomException("Размер обложки меньше чем " . Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['width'] . 'x' . Config::STATIC_PAGE_COVER_NORMAL_FIXED_SIZE['height'], 1);
        }
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteStaticPageCover($fileName)
    {
        @unlink(Config::STATIC_PAGE_COVER_UPLOADS_DIR . Config::STATIC_PAGE_COVER_NORMAL_DIR_NAME . "/" . $fileName);
    }













    public function createCourseCoverOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::COURSE_COVER_MAX_SIZE['width'] || $this->height > Config::COURSE_COVER_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::COURSE_COVER_MAX_SIZE['width'], Config::COURSE_COVER_MAX_SIZE['height']);
        }
        $tempImage->save(Config::COURSE_COVER_UPLOADS_DIR . Config::COURSE_COVER_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }


    public function createCourseCoverNormal(){
        $tempImage = new ImageResize($this->filePath);

        if ($this->width != Config::COURSE_COVER_NORMAL_FIXED_SIZE['width'] && $this->height != Config::COURSE_COVER_NORMAL_FIXED_SIZE['height']) {
            $tempImage->resizeToWidth(Config::COURSE_COVER_NORMAL_FIXED_SIZE['width']);
            $ratioWidth = $tempImage->getDestWidth();
            $ratioHeight = $tempImage->getDestHeight();
            $coeff = $this->width / Config::COURSE_COVER_NORMAL_FIXED_SIZE['width'];
            $coeffHeight = $this->height / $coeff;

            if (!(Config::COURSE_COVER_NORMAL_FIXED_SIZE['height'] - 1 > $coeffHeight || $coeffHeight < Config::COURSE_COVER_NORMAL_FIXED_SIZE['height'] + 1)){
                $tempImage->crop($ratioWidth, Config::COURSE_COVER_NORMAL_FIXED_SIZE['height'], true, ImageResize::CROPCENTER);
            }
        }
        $tempImage->save(Config::COURSE_COVER_UPLOADS_DIR . Config::COURSE_COVER_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createCourseCover()
    {
        if ($this->width >= Config::COURSE_COVER_NORMAL_FIXED_SIZE['width'] && $this->height >= Config::COURSE_COVER_NORMAL_FIXED_SIZE['height']) {
            $this->createCourseCoverOriginal();
            $this->createCourseCoverNormal();
        } else {
            throw new CustomException("Размер обложки меньше чем " . Config::COURSE_COVER_NORMAL_FIXED_SIZE['width'] . 'x' . Config::COURSE_COVER_NORMAL_FIXED_SIZE['height'], 1);
        }
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteCourseCover($fileName)
    {
        @unlink(Config::COURSE_COVER_UPLOADS_DIR . Config::COURSE_COVER_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::COURSE_COVER_UPLOADS_DIR . Config::COURSE_COVER_NORMAL_DIR_NAME . "/" . $fileName);
    }









    public function createCourseSkillOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::COURSE_SKILL_LOGO_MAX_SIZE['width'] || $this->height > Config::COURSE_SKILL_LOGO_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::COURSE_SKILL_LOGO_MAX_SIZE['width'], Config::COURSE_SKILL_LOGO_MAX_SIZE['height']);
        }
        $tempImage->save(Config::COURSE_SKILL_LOGO_UPLOADS_DIR . Config::COURSE_SKILL_LOGO_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createCourseSkillNormal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::COURSE_SKILL_LOGO_NORMAL_SIZE['width'] || $this->height < Config::COURSE_SKILL_LOGO_NORMAL_SIZE['height']) {
            if ($this->width < (Config::COURSE_SKILL_LOGO_NORMAL_SIZE['width'] * Config::COURSE_SKILL_LOGO_NORMAL_EQUIVALENT) || $this->height < (Config::COURSE_SKILL_LOGO_NORMAL_SIZE['height'] * Config::COURSE_SKILL_LOGO_NORMAL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::COURSE_SKILL_LOGO_NORMAL_SIZE['width'] * Config::COURSE_SKILL_LOGO_NORMAL_EQUIVALENT), (Config::COURSE_SKILL_LOGO_NORMAL_SIZE['height'] * Config::COURSE_SKILL_LOGO_NORMAL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::COURSE_SKILL_LOGO_NORMAL_SIZE['width'], Config::COURSE_SKILL_LOGO_NORMAL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::COURSE_SKILL_LOGO_NORMAL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::COURSE_SKILL_LOGO_NORMAL_SIZE['width'], Config::COURSE_SKILL_LOGO_NORMAL_SIZE['height']);
        }
        $tempImage->save(Config::COURSE_SKILL_LOGO_UPLOADS_DIR . Config::COURSE_SKILL_LOGO_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createCourseSkillThumbnail(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['width'] || $this->height < Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['height']) {
            if ($this->width < (Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['width'] * Config::COURSE_SKILL_LOGO_THUMBNAIL_EQUIVALENT) || $this->height < (Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['height'] * Config::COURSE_SKILL_LOGO_THUMBNAIL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['width'] * Config::COURSE_SKILL_LOGO_THUMBNAIL_EQUIVALENT), (Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['height'] * Config::COURSE_SKILL_LOGO_THUMBNAIL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['width'], Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::COURSE_SKILL_LOGO_THUMBNAIL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['width'], Config::COURSE_SKILL_LOGO_THUMBNAIL_SIZE['height']);
        }
        $tempImage->save(Config::COURSE_SKILL_LOGO_UPLOADS_DIR . Config::COURSE_SKILL_LOGO_THUMBNAIL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createCourseSkillLogo()
    {
        $this->createCourseSkillOriginal();
        $this->createCourseSkillNormal();
        $this->createCourseSkillThumbnail();
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteCourseSkillLogo($fileName)
    {
        @unlink(Config::COURSE_SKILL_LOGO_UPLOADS_DIR . Config::COURSE_SKILL_LOGO_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::COURSE_SKILL_LOGO_UPLOADS_DIR . Config::COURSE_SKILL_LOGO_NORMAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::COURSE_SKILL_LOGO_UPLOADS_DIR . Config::COURSE_SKILL_LOGO_THUMBNAIL_DIR_NAME . "/" . $fileName);
    }


    public function createVacancyOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::VACANCY_LOGO_MAX_SIZE['width'] || $this->height > Config::VACANCY_LOGO_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::VACANCY_LOGO_MAX_SIZE['width'], Config::VACANCY_LOGO_MAX_SIZE['height']);
        }
        $tempImage->save(Config::VACANCY_LOGO_UPLOADS_DIR . Config::VACANCY_LOGO_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createVacancyNormal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::VACANCY_LOGO_NORMAL_SIZE['width'] || $this->height < Config::VACANCY_LOGO_NORMAL_SIZE['height']) {
            if ($this->width < (Config::VACANCY_LOGO_NORMAL_SIZE['width'] * Config::VACANCY_LOGO_NORMAL_EQUIVALENT) || $this->height < (Config::VACANCY_LOGO_NORMAL_SIZE['height'] * Config::VACANCY_LOGO_NORMAL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::VACANCY_LOGO_NORMAL_SIZE['width'] * Config::VACANCY_LOGO_NORMAL_EQUIVALENT), (Config::VACANCY_LOGO_NORMAL_SIZE['height'] * Config::VACANCY_LOGO_NORMAL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::VACANCY_LOGO_NORMAL_SIZE['width'], Config::VACANCY_LOGO_NORMAL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::VACANCY_LOGO_NORMAL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::VACANCY_LOGO_NORMAL_SIZE['width'], Config::VACANCY_LOGO_NORMAL_SIZE['height']);
        }
        $tempImage->save(Config::VACANCY_LOGO_UPLOADS_DIR . Config::VACANCY_LOGO_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createVacancyThumbnail(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::VACANCY_LOGO_THUMBNAIL_SIZE['width'] || $this->height < Config::VACANCY_LOGO_THUMBNAIL_SIZE['height']) {
            if ($this->width < (Config::VACANCY_LOGO_THUMBNAIL_SIZE['width'] * Config::VACANCY_LOGO_THUMBNAIL_EQUIVALENT) || $this->height < (Config::VACANCY_LOGO_THUMBNAIL_SIZE['height'] * Config::VACANCY_LOGO_THUMBNAIL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::VACANCY_LOGO_THUMBNAIL_SIZE['width'] * Config::VACANCY_LOGO_THUMBNAIL_EQUIVALENT), (Config::VACANCY_LOGO_THUMBNAIL_SIZE['height'] * Config::VACANCY_LOGO_THUMBNAIL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::VACANCY_LOGO_THUMBNAIL_SIZE['width'], Config::VACANCY_LOGO_THUMBNAIL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::VACANCY_LOGO_THUMBNAIL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::VACANCY_LOGO_THUMBNAIL_SIZE['width'], Config::VACANCY_LOGO_THUMBNAIL_SIZE['height']);
        }
        $tempImage->save(Config::VACANCY_LOGO_UPLOADS_DIR . Config::VACANCY_LOGO_THUMBNAIL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createVacancyLogo()
    {
        $this->createVacancyOriginal();
        $this->createVacancyNormal();
        $this->createVacancyThumbnail();
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteVacancyLogo($fileName)
    {
        @unlink(Config::VACANCY_LOGO_UPLOADS_DIR . Config::VACANCY_LOGO_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::VACANCY_LOGO_UPLOADS_DIR . Config::VACANCY_LOGO_NORMAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::VACANCY_LOGO_UPLOADS_DIR . Config::VACANCY_LOGO_THUMBNAIL_DIR_NAME . "/" . $fileName);
    }

    public function createProjectOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::PROJECT_LOGO_MAX_SIZE['width'] || $this->height > Config::PROJECT_LOGO_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::PROJECT_LOGO_MAX_SIZE['width'], Config::PROJECT_LOGO_MAX_SIZE['height']);
        }
        $tempImage->save(Config::PROJECT_LOGO_UPLOADS_DIR . Config::PROJECT_LOGO_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createProjectNormal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::PROJECT_LOGO_NORMAL_SIZE['width'] || $this->height < Config::PROJECT_LOGO_NORMAL_SIZE['height']) {
            if ($this->width < (Config::PROJECT_LOGO_NORMAL_SIZE['width'] * Config::PROJECT_LOGO_NORMAL_EQUIVALENT) || $this->height < (Config::PROJECT_LOGO_NORMAL_SIZE['height'] * Config::PROJECT_LOGO_NORMAL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::PROJECT_LOGO_NORMAL_SIZE['width'] * Config::PROJECT_LOGO_NORMAL_EQUIVALENT), (Config::PROJECT_LOGO_NORMAL_SIZE['height'] * Config::PROJECT_LOGO_NORMAL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::PROJECT_LOGO_NORMAL_SIZE['width'], Config::PROJECT_LOGO_NORMAL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::PROJECT_LOGO_NORMAL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::PROJECT_LOGO_NORMAL_SIZE['width'], Config::PROJECT_LOGO_NORMAL_SIZE['height']);
        }
        $tempImage->save(Config::PROJECT_LOGO_UPLOADS_DIR . Config::PROJECT_LOGO_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createProjectThumbnail(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::PROJECT_LOGO_THUMBNAIL_SIZE['width'] || $this->height < Config::PROJECT_LOGO_THUMBNAIL_SIZE['height']) {
            if ($this->width < (Config::PROJECT_LOGO_THUMBNAIL_SIZE['width'] * Config::PROJECT_LOGO_THUMBNAIL_EQUIVALENT) || $this->height < (Config::PROJECT_LOGO_THUMBNAIL_SIZE['height'] * Config::PROJECT_LOGO_THUMBNAIL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::PROJECT_LOGO_THUMBNAIL_SIZE['width'] * Config::PROJECT_LOGO_THUMBNAIL_EQUIVALENT), (Config::PROJECT_LOGO_THUMBNAIL_SIZE['height'] * Config::PROJECT_LOGO_THUMBNAIL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::PROJECT_LOGO_THUMBNAIL_SIZE['width'], Config::PROJECT_LOGO_THUMBNAIL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::PROJECT_LOGO_THUMBNAIL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::PROJECT_LOGO_THUMBNAIL_SIZE['width'], Config::PROJECT_LOGO_THUMBNAIL_SIZE['height']);
        }
        $tempImage->save(Config::PROJECT_LOGO_UPLOADS_DIR . Config::PROJECT_LOGO_THUMBNAIL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createProjectLogo()
    {
        $this->createProjectOriginal();
        $this->createProjectNormal();
        $this->createProjectThumbnail();
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteProjectLogo($fileName)
    {
        @unlink(Config::PROJECT_LOGO_UPLOADS_DIR . Config::PROJECT_LOGO_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::PROJECT_LOGO_UPLOADS_DIR . Config::PROJECT_LOGO_NORMAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::PROJECT_LOGO_UPLOADS_DIR . Config::PROJECT_LOGO_THUMBNAIL_DIR_NAME . "/" . $fileName);
    }









    public function createProjectCoverOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::PROJECT_COVER_MAX_SIZE['width'] || $this->height > Config::PROJECT_COVER_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::PROJECT_COVER_MAX_SIZE['width'], Config::PROJECT_COVER_MAX_SIZE['height']);
        }
        $tempImage->save(Config::PROJECT_COVER_UPLOADS_DIR . Config::PROJECT_COVER_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }


    public function createProjectCoverNormal(){
        $tempImage = new ImageResize($this->filePath);

        if ($this->width != Config::PROJECT_COVER_NORMAL_FIXED_SIZE['width'] && $this->height != Config::PROJECT_COVER_NORMAL_FIXED_SIZE['height']) {
            $tempImage->resizeToWidth(Config::PROJECT_COVER_NORMAL_FIXED_SIZE['width']);
            $ratioWidth = $tempImage->getDestWidth();
            $ratioHeight = $tempImage->getDestHeight();
            $coeff = $this->width / Config::PROJECT_COVER_NORMAL_FIXED_SIZE['width'];
            $coeffHeight = $this->height / $coeff;

            if (!(Config::PROJECT_COVER_NORMAL_FIXED_SIZE['height'] - 1 > $coeffHeight || $coeffHeight < Config::PROJECT_COVER_NORMAL_FIXED_SIZE['height'] + 1)){
                $tempImage->crop($ratioWidth, Config::PROJECT_COVER_NORMAL_FIXED_SIZE['height'], true, ImageResize::CROPCENTER);
            }
        }
        $tempImage->save(Config::PROJECT_COVER_UPLOADS_DIR . Config::PROJECT_COVER_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createProjectCover()
    {
        if ($this->width >= Config::PROJECT_COVER_NORMAL_FIXED_SIZE['width'] && $this->height >= Config::PROJECT_COVER_NORMAL_FIXED_SIZE['height']) {
            $this->createProjectCoverOriginal();
            $this->createProjectCoverNormal();
        } else {
            throw new CustomException("Размер обложки меньше чем " . Config::PROJECT_COVER_NORMAL_FIXED_SIZE['width'] . 'x' . Config::PROJECT_COVER_NORMAL_FIXED_SIZE['height'], 1);
        }
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteProjectCover($fileName)
    {
        @unlink(Config::PROJECT_COVER_UPLOADS_DIR . Config::PROJECT_COVER_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::PROJECT_COVER_UPLOADS_DIR . Config::PROJECT_COVER_NORMAL_DIR_NAME . "/" . $fileName);
    }




    public function createAttachmentPreviewOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::ATTACHMENT_PREVIEW_MAX_SIZE['width'] || $this->height > Config::ATTACHMENT_PREVIEW_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::ATTACHMENT_PREVIEW_MAX_SIZE['width'], Config::ATTACHMENT_PREVIEW_MAX_SIZE['height']);
        }
        $tempImage->save(Config::ATTACHMENT_PREVIEW_UPLOADS_DIR . Config::ATTACHMENT_PREVIEW_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createAttachmentPreviewNormal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['width'] || $this->height < Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['height']) {
            if ($this->width < (Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['width'] * Config::ATTACHMENT_PREVIEW_NORMAL_EQUIVALENT) || $this->height < (Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['height'] * Config::ATTACHMENT_PREVIEW_NORMAL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['width'] * Config::ATTACHMENT_PREVIEW_NORMAL_EQUIVALENT), (Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['height'] * Config::ATTACHMENT_PREVIEW_NORMAL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['width'], Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::ATTACHMENT_PREVIEW_NORMAL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['width'], Config::ATTACHMENT_PREVIEW_NORMAL_SIZE['height']);
        }
        $tempImage->save(Config::ATTACHMENT_PREVIEW_UPLOADS_DIR . Config::ATTACHMENT_PREVIEW_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createAttachmentPreviewThumbnail(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['width'] || $this->height < Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['height']) {
            if ($this->width < (Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['width'] * Config::ATTACHMENT_PREVIEW_THUMBNAIL_EQUIVALENT) || $this->height < (Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['height'] * Config::ATTACHMENT_PREVIEW_THUMBNAIL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['width'] * Config::ATTACHMENT_PREVIEW_THUMBNAIL_EQUIVALENT), (Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['height'] * Config::ATTACHMENT_PREVIEW_THUMBNAIL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['width'], Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::ATTACHMENT_PREVIEW_THUMBNAIL_BLUR_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['width'], Config::ATTACHMENT_PREVIEW_THUMBNAIL_SIZE['height']);
        }
        $tempImage->save(Config::ATTACHMENT_PREVIEW_UPLOADS_DIR . Config::ATTACHMENT_PREVIEW_THUMBNAIL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createAttachmentPreview()
    {
        $this->createAttachmentPreviewOriginal();
        $this->createAttachmentPreviewNormal();
        $this->createAttachmentPreviewThumbnail();
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteAttachmentPreview($fileName)
    {
        @unlink(Config::ATTACHMENT_PREVIEW_UPLOADS_DIR . Config::ATTACHMENT_PREVIEW_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::ATTACHMENT_PREVIEW_UPLOADS_DIR . Config::ATTACHMENT_PREVIEW_NORMAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::ATTACHMENT_PREVIEW_UPLOADS_DIR . Config::ATTACHMENT_PREVIEW_THUMBNAIL_DIR_NAME . "/" . $fileName);
    }




    public function createUserCoverOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::USER_COVER_MAX_SIZE['width'] || $this->height > Config::USER_COVER_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::USER_COVER_MAX_SIZE['width'], Config::USER_COVER_MAX_SIZE['height']);
        }
        $tempImage->save(Config::USER_COVER_UPLOADS_DIR . Config::USER_COVER_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }


    public function createUserCoverNormal(){
        $tempImage = new ImageResize($this->filePath);

        if ($this->width != Config::USER_COVER_NORMAL_FIXED_SIZE['width'] && $this->height != Config::USER_COVER_NORMAL_FIXED_SIZE['height']) {
            $tempImage->resizeToWidth(Config::USER_COVER_NORMAL_FIXED_SIZE['width']);
            $ratioWidth = $tempImage->getDestWidth();
            $ratioHeight = $tempImage->getDestHeight();
            $coeff = $this->width / Config::USER_COVER_NORMAL_FIXED_SIZE['width'];
            $coeffHeight = $this->height / $coeff;

            if (!(Config::USER_COVER_NORMAL_FIXED_SIZE['height'] - 1 > $coeffHeight || $coeffHeight < Config::USER_COVER_NORMAL_FIXED_SIZE['height'] + 1)){
                $tempImage->crop($ratioWidth, Config::USER_COVER_NORMAL_FIXED_SIZE['height'], true, ImageResize::CROPCENTER);
            }
        }
        $tempImage->save(Config::USER_COVER_UPLOADS_DIR . Config::USER_COVER_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createUserCover()
    {
        if ($this->width >= Config::PLACE_COVER_NORMAL_FIXED_SIZE['width'] && $this->height >= Config::PLACE_COVER_NORMAL_FIXED_SIZE['height']) {
            $this->createUserCoverOriginal();
            $this->createUserCoverNormal();
        } else {
            throw new CustomException("Размер обложки меньше чем " . Config::USER_COVER_NORMAL_FIXED_SIZE['width'] . 'x' . Config::USER_COVER_NORMAL_FIXED_SIZE['height'], 1);
        }
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteUserCover($fileName)
    {
        @unlink(Config::USER_COVER_UPLOADS_DIR . Config::USER_COVER_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::USER_COVER_UPLOADS_DIR . Config::USER_COVER_NORMAL_DIR_NAME . "/" . $fileName);
    }














    public function createUserLogoOriginal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width > Config::USER_LOGO_MAX_SIZE['width'] || $this->height > Config::USER_LOGO_MAX_SIZE['height']) {
            $tempImage->resizeToBestFit(Config::USER_LOGO_MAX_SIZE['width'], Config::USER_LOGO_MAX_SIZE['height']);
        }
        $tempImage->save(Config::USER_LOGO_UPLOADS_DIR . Config::USER_LOGO_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createUserLogoNormal(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::USER_LOGO_NORMAL_SIZE['width'] || $this->height < Config::USER_LOGO_NORMAL_SIZE['height']) {
            if ($this->width < (Config::USER_LOGO_NORMAL_SIZE['width'] * Config::USER_LOGO_NORMAL_EQUIVALENT) || $this->height < (Config::USER_LOGO_NORMAL_SIZE['height'] * Config::USER_LOGO_NORMAL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::USER_LOGO_NORMAL_SIZE['width'] * Config::USER_LOGO_NORMAL_EQUIVALENT), (Config::USER_LOGO_NORMAL_SIZE['height'] * Config::USER_LOGO_NORMAL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::USER_LOGO_NORMAL_SIZE['width'], Config::USER_LOGO_NORMAL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::USER_LOGO_NORMAL_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::USER_LOGO_NORMAL_SIZE['width'], Config::USER_LOGO_NORMAL_SIZE['height']);
        }
        $tempImage->save(Config::USER_LOGO_UPLOADS_DIR . Config::USER_LOGO_NORMAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createUserLogoThumbnail(){
        $tempImage = new ImageResize($this->filePath);
        if ($this->width < Config::USER_LOGO_THUMBNAIL_SIZE['width'] || $this->height < Config::USER_LOGO_THUMBNAIL_SIZE['height']) {
            if ($this->width < (Config::USER_LOGO_THUMBNAIL_SIZE['width'] * Config::USER_LOGO_THUMBNAIL_EQUIVALENT) || $this->height < (Config::USER_LOGO_THUMBNAIL_SIZE['height'] * Config::USER_LOGO_THUMBNAIL_EQUIVALENT)){
                if ($this->minSize == $this->width){
                    $tempImage->crop($this->width, intval($this->width / 4 * 3), $allow_enlarge = true);
                } else {
                    $tempImage->crop(intval($this->height / 3 * 4), $this->height, $allow_enlarge =true);
                }
            } else {
                $tempImage->crop((Config::USER_LOGO_THUMBNAIL_SIZE['width'] * Config::USER_LOGO_THUMBNAIL_EQUIVALENT), (Config::USER_LOGO_THUMBNAIL_SIZE['height'] * Config::USER_LOGO_THUMBNAIL_EQUIVALENT), $allow_enlarge = true);
            }
            $tempImage->resize(Config::USER_LOGO_THUMBNAIL_SIZE['width'], Config::USER_LOGO_THUMBNAIL_SIZE['height'], $allow_enlarge = true);
            // Add blur
            $blurEquivalent = Config::USER_LOGO_THUMBNAIL_EQUIVALENT;
            $tempImage->addFilter(function ($imageDesc) use ($blurEquivalent) {
                for ($x=1; $x <= $blurEquivalent; $x++){
                    imagefilter($imageDesc, IMG_FILTER_GAUSSIAN_BLUR, 999);
                }
                imagefilter($imageDesc, IMG_FILTER_SMOOTH,99);
                imagefilter($imageDesc, IMG_FILTER_BRIGHTNESS, 10);
            });

            // Add banner on bottom left corner
            $imageWidth = $tempImage->getDestWidth();
            $imageHeight = $tempImage->getDestHeight();
            $imageFileName = $this->filePath;

            $tempImage->addFilter(function ($imageDesc) use ($imageFileName, $imageWidth, $imageHeight) {
                switch ($this->mimeType) {
                    case 'image/png':
                        $logo = imagecreatefrompng($imageFileName);
                        break;
                    case 'image/jpeg':
                        $logo = imagecreatefromjpeg($imageFileName);
                        break;
                    case 'image/gif':
                        $logo = imagecreatefromgif($imageFileName);
                        break;
                }
                $logo_width = imagesx($logo);
                $logo_height = imagesy($logo);
                $image_x = intval($imageWidth / 2) - intval($logo_width / 2) - 10;
                $image_y = intval($imageHeight / 2) - intval($logo_height / 2) - 10;
                imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
            });
        } else {
            $tempImage->crop(Config::USER_LOGO_THUMBNAIL_SIZE['width'], Config::USER_LOGO_THUMBNAIL_SIZE['height']);
        }
        $tempImage->save(Config::USER_LOGO_UPLOADS_DIR . Config::USER_LOGO_THUMBNAIL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::IMAGE_TYPE, $this->outputType);
    }

    public function createUserLogo() : string
    {
        $this->createUserLogoOriginal();
        $this->createUserLogoNormal();
        $this->createUserLogoThumbnail();
        return $this->fileGeneratedName . "." . Config::IMAGE_TYPE;
    }

    public static function deleteUserLogo($fileName)
    {
        @unlink(Config::USER_LOGO_UPLOADS_DIR . Config::USER_LOGO_ORIGINAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::USER_LOGO_UPLOADS_DIR . Config::USER_LOGO_NORMAL_DIR_NAME . "/" . $fileName);
        @unlink(Config::USER_LOGO_UPLOADS_DIR . Config::USER_LOGO_THUMBNAIL_DIR_NAME . "/" . $fileName);
    }



    public function createSocialUserLogoOriginal(){
        $file_headers = @get_headers($this->fileUrl);
        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            throw new CustomException('Фото не существует', 404);
        }
        $tempImage = ImageResize::createFromString(file_get_contents($this->fileUrl));
        $tempImage->save(Config::USER_LOGO_UPLOADS_DIR . Config::USER_LOGO_ORIGINAL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::SOCIAL_IMAGE_TYPE, $this->outputType);
    }

    public function createSocialUserLogoThumbnail(){
        $file_headers = @get_headers($this->fileUrl);
        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            throw new CustomException('Фото не существует', 404);
        }
        $tempImage = ImageResize::createFromString(file_get_contents($this->fileUrl));

        $tempImage->resize(Config::USER_LOGO_THUMBNAIL_SIZE['width'], Config::USER_LOGO_THUMBNAIL_SIZE['height'], $allow_enlarge = true);
        $tempImage->save(Config::USER_LOGO_UPLOADS_DIR . Config::USER_LOGO_THUMBNAIL_DIR_NAME . "/" . $this->fileGeneratedName . "." . Config::SOCIAL_IMAGE_TYPE, $this->outputType);
    }

    public function createSocialUserLogo()
    {
        $this->createSocialUserLogoOriginal();
        $this->createSocialUserLogoThumbnail();
        return $this->fileGeneratedName . "." . Config::SOCIAL_IMAGE_TYPE;
    }

}
