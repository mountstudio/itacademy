<?php

namespace Models;

use Models\Base\User as BaseUser;
use \Core\Functions;
use \Core\Image;
use App\Config;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
  public function getLogo()
  {
    if (is_null($this->getLogoName())){
        $logoName = Config::DEFAULT_IMAGE_NAME;
    } else {
      $logoName = $this->getLogoName();
    }
    $validLogoUploadsDirName = explode('public', Config::USER_LOGO_UPLOADS_DIR)[1];
    return array(   'thumb'     => $validLogoUploadsDirName . Config::USER_LOGO_THUMBNAIL_DIR_NAME . "/" . $logoName,
                    'original'  => $validLogoUploadsDirName . Config::USER_LOGO_ORIGINAL_DIR_NAME . "/" . $logoName);
  }

  public function getCover()
  {
    if (is_null($this->getLogoName())){
        $coverName = Config::DEFAULT_IMAGE_NAME;
    } else {
      $coverName = $this->getCoverName();
    }
    $validCoverUploadsDirName = explode('public', Config::USER_COVER_UPLOADS_DIR)[1];
    return array(   'normal'     => $validCoverUploadsDirName . Config::USER_COVER_NORMAL_DIR_NAME . "/" . $logoName,
                    'original'  => $validCoverUploadsDirName . Config::USER_COVER_ORIGINAL_DIR_NAME . "/" . $logoName);
  }

  public function setSocialLogo($photoURL) {
    $image = new Image(null, null, $photoURL);
    $this->setLogoName($image->createSocialUserLogo());
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
             $imageName = $image->createUserLogo();

             if (!is_null($this->getLogoName())) {
                 Image::deleteUserLogo($this->getLogoName());
             }

             $this->setLogoName($imageName);
         } else {
             throw new CustomException("Формат фотографии неподдерживается", 1);
         }
     } else {
         throw new CustomException("Формат файла неподдерживается", 1);
     }
  }

    public function getAge()
    {
        if (is_null($this->getBirthDate())){
            return null;
        }
        $interval = (new \DateTime())->setTimestamp($this->getBirthDate()->getTimestamp())->diff(new \DateTime());
        if ($interval->y == 0){
            return null;
        }

        return $interval->y;
    }

    public function getAgePostFix()
    {
        if (is_null($this->getAge())){
            return null;
        }
        $age = $this->getAge();

        $str='';
        $num=$age>100 ? substr($age, -2) : $age;
        if($num>=5&&$num<=14) $str = "лет";
        else
        {
            $num=substr($age, -1);
            if($num==0||($num>=5&&$num<=9)) $str='лет';
            if($num==1) $str='год';
            if($num>=2&&$num<=4) $str='года';
        }
        return $age.' '.$str;
    }

    public function convertCurrency(float $value, Currency $currency = null)
    {


        $activeCurrency = null;

        $userCurrency = $this->getCurrentUserCurrency();
        if (is_null($userCurrency)){
            $defaultCurrencyConf = ConfigQuery::create()->findOneByKey('default_currency_id');
            $activeCurrency = CurrencyQuery::create()->findPk(intval($defaultCurrencyConf->getValue()));
        } else {
            $activeCurrency = $userCurrency;
        }

        if ($currency instanceof $activeCurrency){
            return array(
                'value' => $value,
                'currency' => $activeCurrency
            );
        }

        $currencyRate = CurrencyRateQuery::create()->filterByCurrentDefaultCurrency($activeCurrency)->filterByCurrentToCurrency($currency)->orderByCreatedAt(Criteria::DESC)->findOne();
        $currentValue = $currencyRate->getValue();

        return array(
            'value' => $value * $currentValue,
            'currency' => $activeCurrency
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
                $imageName = $image->createUserCover();

                if (!is_null($this->getCoverName())) {
                    Image::deleteUserCover($this->getLogoName());
                }

                $this->setCoverName($imageName);
            } else {
                throw new CustomException("Формат фотографии неподдерживается", 1);
            }
        } else {
            throw new CustomException("Формат файла неподдерживается", 1);
        }
    }

    public function isInstructor()
    {
        $instructorGroup = ConfigQuery::create()->findOneByKey('default_instructor_group');

        if (is_null($instructorGroup)){
            throw new CustomException("Группа иструктора не было настроено в настройках", 1);
        }

        if ($this->getCurrentGroupId() == intval($instructorGroup->getValue())){
            return true;
        }
        return false;
    }

    public function preDelete(ConnectionInterface $con = null)
    {
        if (!is_null($this->getLogoName())){
            Image::deleteUserLogo($this->getLogoName());
        }
        if (!is_null($this->getCoverName())){
          Image::deleteUserCover($this->getCoverName());
        }
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

}
