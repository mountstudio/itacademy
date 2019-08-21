<?php

namespace Core;

// Setup the autoloading
require_once '../vendor/autoload.php';

// Setup Propel
require_once '../generated-conf/config.php';


// Init Monolog Logger
use Models\ConfigQuery;
use Models\Currency;
use Models\CurrencyQuery;
use Models\CurrencyRateQuery;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


use DeviceDetector\DeviceDetector;

// Init Propel
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;

use App\Config;
use Core\Mail;
use Core\Functions;
use Core\Image;
use Core\JsonResponse;
use Core\CustomException;

// Init Models
use \Models\AdminStyle;
use \Models\AdminStyleQuery;
use \Models\Group;
use \Models\GroupQuery;
use \Models\User;
use \Models\UserQuery;

use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Base controller
 *
 * PHP version 7.0
 */
class Helper
{
    public $rootDir, $uploadDir, $token, $user, $deviceInfo, $privileges;


    public function __construct() {
        $this->rootDir = realpath(__DIR__ . '/../public/');
        $this->uploadDir = realpath(__DIR__ . '/../public/uploads');

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->deviceInfo = $this->getDevice();
    }

    public function getCurrentToken()
    {
        if (is_null($this->token)){
            $tempToken = Functions::getCookie('token');
            if (!is_null($tempToken)) {
                $this->token = Functions::decodeJWT($tempToken);
                if (!is_null($this->token)){
                    return $this->token;
                }
            }
            return null;
        } else return $this->token;
    }

    public function getDevice()
    {
        $deviceInfo = Functions::getCookie('deviceInfo');
        if (is_null($deviceInfo)){
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $deviceDetector = new DeviceDetector($userAgent);
            $deviceDetector->setCache(new \Doctrine\Common\Cache\PhpFileCache('../tmp/'));
            $deviceDetector->skipBotDetection();
            $deviceDetector->parse();

            if ($deviceDetector->isMobile()){
                $deviceInfo = 'mobile';
            } else {
                $deviceInfo = 'other';
            }

            Functions::setCookie('deviceInfo', $deviceInfo, time() + (86400 * Config::DEVICE_INFO_COOKIE_EXPIRATION));
        }
        return $deviceInfo;
    }


    public function getCurrentUser()
    {
        if (!is_null($this->getCurrentToken())){
            $this->user = UserQuery::create()->findPK($this->getCurrentToken()->data->userId);
            return $this->user;
        }
        return null;
    }

    public function getDefaultCurrency($value, Currency $currency){
        if (is_null($this->getCurrentUser())){
            $defaultShownCurrencyConf = ConfigQuery::create()->findOneByKey('default_shown_currency_id');
            $defaultShownCurrency = CurrencyQuery::create()->findPk(intval($defaultShownCurrencyConf->getValue()));

            $defaultCurrencyConf = ConfigQuery::create()->findOneByKey('default_currency_id');
            $defaultCurrency = CurrencyQuery::create()->findPk(intval($defaultCurrencyConf->getValue()));

            if ($defaultShownCurrency->getId() != $currency->getId()){

                $currencyRate_ = CurrencyRateQuery::create()
                    ->filterByCurrentDefaultCurrencyId($defaultCurrency->getId())
                    ->filterByCurrentToCurrencyId($defaultShownCurrency->getId())
                    ->orderByCreatedAt(Criteria::DESC)
                    ->findOne();

                $currentValue_ = (float)$currencyRate_->getRate();
                $value *= $currentValue_;


                if ($defaultShownCurrency->getId() != $defaultCurrency->getId()){
                    $currencyRate = CurrencyRateQuery::create()
                        ->filterByCurrentDefaultCurrencyId($defaultCurrency->getId())
                        ->filterByCurrentToCurrencyId($defaultShownCurrency->getId())
                        ->orderByCreatedAt(Criteria::DESC)->findOne();
                    $currentValue = $currencyRate->getRate();
                    $value /= $currentValue;
                }
            }


            return array(
                'value' => $value,
                'currency' => $defaultShownCurrency
            );
        } else {
            return $this->getCurrentUser()->convertCurrency($value, $currency);
        }
    }

    public function getConfigConstant($string)
    {
        return Config::getConstant($string);
    }

    public function isLoggedIn()
    {
        if (!is_null($this->getCurrentToken())){
            return true;
        }
        return false;
    }

    public function getRootDir()
    {
        return $this->uploadDir;
    }

    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    public function getAdminAssetsDir()
    {
        return Config::ADMIN_ASSETS_DIR;
    }

    public function shouldBeLoggedIn()
    {
        if (is_null($this->getCurrentToken())) {
            throw new CustomException("Вы ещё не авторизовались", 1);
        }
    }

    public function getUserPrivileges(User $user = null){
        if (is_null($user)){
            $currentGroup = $this->getCurrentUser()->getCurrentGroup();
        } else {
            $currentGroup = $user->getCurrentGroup();
        }

        $userPrivilegies = $currentGroup->getCurrentPrivilegeGroupPriveleges();
        $privileges = array();
        foreach ($userPrivilegies as $privilege) {
            $privileges[] = $privilege->getAlt();
        }
        return $privileges;
    }

    public function initPrivileges()
    {
        if (is_null($this->privileges)){
            $hasPrivileges = $this->getUserPrivileges();
            $this->privileges = $hasPrivileges;
        }
    }

    public function shouldHavePrivilege(string $privilege)
    {
        $this->initPrivileges();
        if (!in_array($privilege, $this->privileges)){
            throw new CustomException("Вы не имеете достаточных прав", 0);
        }
    }

    public function hasPrivilege(string $privilege)
    {
        $this->initPrivileges();
        if (in_array($privilege, $this->privileges)){
            return true;
        } else {
            return false;
        }
    }

    public function haveNotPrivilege(string $privilege)
    {
        $this->initPrivileges();
        if (in_array($privilege, $this->privileges)){
            return false;
        } else {
            return true;
        }
    }

    public function shouldNotHavePrivilege(string $privilege)
    {
        $this->initPrivileges();
        if (in_array($privilege, $this->privileges)){
            throw new CustomException("Вы не имеете достаточных прав", 0);
        }
    }

    public function shouldHaveOneOfPrivileges(array $privileges)
    {
        $this->initPrivileges();
        $c = 0;
        foreach ($privileges as $privilege) {
            if (in_array($privilege, $this->privileges)){
                return true;
            }
        }
        throw new CustomException("Вы не имеете достаточных прав", 0);
    }

    public function shouldHavePrivileges(array $privileges)
    {
        $this->initPrivileges();
        $c = sizeof($privileges);
        foreach ($privileges as $privilege) {
            if (in_array($privilege, $this->privileges)){
                $c--;
            }
        }
        if ($c != 0){
            throw new CustomException("Вы не имеете достаточных прав", 0);
        }
    }

    public function shouldNotHavePrivileges(array $privileges)
    {
        $this->initPrivileges();
        $c = sizeof($privileges);
        foreach ($privileges as $privilege) {
            if (!in_array($privilege, $this->privileges)){
                $c--;
            }
        }
        if ($c == 0){
            throw new CustomException("Вы не имеете достаточных прав", 0);
        }
    }

    public function paginator()
    {
        $page = (isset($_POST['page']) ? $_POST['page'] : null);
        $max = (isset($_POST['max']) ? $_POST['max'] : null);


        if (is_null($page)) $page = 1;
        if (is_null($max)) $max = 10;

        if (intval($page) <= 0) throw new CustomException("Страница запроса не верна", 1);
        if (intval($max) <= 0) throw new CustomException("Максимальное число результатов не верна", 1);
        return array('page' => $page, 'max' => $max);
    }



    public function login ($userLoginOrEmail, $userPassword, $isRemember = false, $checkActivated = true)
    {
        if (!is_null($this->getCurrentToken())) {
            throw new CustomException("Вы уже вошли в систему", 0);
        }

        if (is_null($userLoginOrEmail) || empty($userLoginOrEmail)){
            throw new CustomException("Email или логин не был указан", 1);
        }
        if (is_null($userPassword) || empty($userPassword)){
            throw new CustomException("Пароль не был указан", 1);
        }
        if (filter_var(trim($userLoginOrEmail), FILTER_VALIDATE_EMAIL) == false){
            $user = UserQuery::create()->findOneByUserName(trim($userLoginOrEmail));
        } else {
            $user = UserQuery::create()->findOneByEmail(trim($userLoginOrEmail));
        }
        if (!is_null($user)){
            $userPassword = trim($userPassword);
            $cryptedPassword = $user->getPassword();

            if ($cryptedPassword == crypt($userPassword, $cryptedPassword)) {
                if ($checkActivated && !$user->getActivated()) {
                    throw new CustomException("Ваш аккаунт ещё не активирован", 1);
                }

                $expiryDateTime = time() + (($isRemember) ? (86400 * Config::LONG_LOGIN_REMEMBER_TIME) : (86400 * Config::DEFAULT_LOGIN_REMEMBER_TIME));
                $loginToken = Functions::encodeJWT($user, $expiryDateTime);
                Functions::setCookie('token', $loginToken, $expiryDateTime);
                return true;
            } else {
                throw new CustomException("Неправильный пароль", 1);
            }
        } else {
            throw new CustomException("Пользователь не найден", 1);
        }
        return false;
    }

    public function socialLogin ($socialId, $isRemember = true, $checkActivated = false)
    {
        if (!is_null($this->getCurrentToken())) {
            $this->logout();
        }

        $user = UserQuery::create()->findOneBySocialId($socialId);

        if (!is_null($user)){

            $userGroup = $user->getCurrentGroup();

            if ($checkActivated){
                if (!$user->getActivated()) {
                    throw new CustomException("Ваш аккаунт ещё не активирован", 400);
                }
            }

            $expiryDateTime = time() + (($isRemember) ? (86400 * Config::LONG_LOGIN_REMEMBER_TIME) : (86400 * Config::DEFAULT_LOGIN_REMEMBER_TIME));
            $loginToken = Functions::encodeJWT($user, $expiryDateTime);
            Functions::setCookie('token', $loginToken, $expiryDateTime);
        } else {
            throw new CustomException("Пользователь не найден", 1);
        }
        return false;
    }

    public function registration($userEmail){
        if (is_null($userEmail) || empty(trim($userEmail)) ){
            throw new CustomException("Email не был введён", 1);
        }

        if (filter_var(trim($userEmail), FILTER_VALIDATE_EMAIL) == false){
            throw new CustomException("Неверный email", 1);
        }
        $findExistEmailByUser = UserQuery::create()->findOneByEmail(trim($userEmail));
        if (!is_null($findExistEmailByUser)){
            throw new CustomException("Такой email существует", 1);
        }
        $findExistEmailByVerificationToken = VerificationTokenQuery::create()->findOneByEmail(trim($userEmail));

        if (!is_null($findExistEmailByVerificationToken)){
            if ($findExistEmailByVerificationToken->getCreatedAt()->getTimestamp() > time() - Config::EMAIL_VERIFICATION_RESENDING_INTERVAL){
                throw new CustomException("Недавно вам отправили email, попробуйте после " . abs(time() - Config::EMAIL_VERIFICATION_RESENDING_INTERVAL - $findExistEmailByVerificationToken->getCreatedAt()->getTimestamp()) . " сек.", 0);
            } else {
                $findExistEmailByVerificationToken->delete();
            }
        }

        $verificationToken = new VerificationToken();
        $verificationToken->setEmail(trim($userEmail));
        $verificationToken->setType(1);
        $verificationToken->setToken(Functions::generateToken(Config::TOKEN_LENGTH_FOR_LOGIN_AUTH));
        $verificationToken->setExpiryDateTime(time() + Config::EMAIL_VERIFICATION_EXPIRATION * 86400);
        $verificationToken->save();
        return $verificationToken;
    }

    public function confirmRegistration($userEmail, $userName, $name, $password, $passwordConfirmation, $phone, $address, Group $group, array $addressCoordinates = null)
    {
        $user = new User();

        if (is_null($userEmail) || empty(trim($userEmail)) ){
            throw new CustomException("Email не был веден", 1);
        }

        if (filter_var(trim($userEmail), FILTER_VALIDATE_EMAIL) == false){
            throw new CustomException("Неверный email", 1);
        }

        if (is_null($name) || empty(trim($name)) ){
            throw new CustomException("Имя не была введена", 1);
        } elseif (strlen(trim($name)) < 2) {
            throw new CustomException("Длина имени должна быть больше 2 символов", 1);
        } else {
            $user->setName(trim($name));
        }

        if (is_null($password) || empty(trim($password)) ){
            throw new CustomException("Не был введен пароль", 1);
        }

        if (is_null($phone)){
            throw new CustomException("Не был введен телефон", 1);
        }

        if (strlen(trim($password)) < 6){
            throw new CustomException("Длина паролей должны быть больше 6 символов", 1);
        }

        if (trim($password) != trim($passwordConfirmation)){
            throw new CustomException("Пароли не совпадают", 1);
        } else {
            $cryptedPassword = crypt(trim($password), Config::PASSWORD_SALT);
            $user->setPassword($cryptedPassword);
        }

        if (!empty(trim($phone))) {
            $phone = str_replace("(","",str_replace(")","",str_replace("-","",str_replace(" ","",$phone))));
        }

        if (!empty(trim($phone)) && !preg_match('/^[0-9]+$/', trim($phone))){
            throw new CustomException("Телефон должен содержать только цифры", 1);
        } elseif (!empty(trim($phone)) && strlen(trim($phone)) != 12 && substr(trim($phone),0, 3) != "996") {
             throw new CustomException("Телефон должен быть веден в формате 996XXXYYYYYY", 1);
        } elseif (!empty(trim($phone))) {
            $user->setPhone(trim($phone));
        }

        $userByEmail = UserQuery::create()->findOneByEmail(trim($userEmail));
        if (!is_null($userByEmail)){
            throw new CustomException("Указанный email уже зарегистрирован", 2);
        } else {
            $user->setEmail(trim($userEmail));
        }
        if (!is_null(trim($userName)) && !empty(trim($userName)) && strlen(trim($userName)) >= 5){
            $userByUsername = UserQuery::create()->findOneByUserName(trim($userName));
            if (!is_null($userByUsername)){
                throw new CustomException("Указанный имя пользователя уже зарегистрирован", 3);
            } else {
                $user->setUserName(trim($userName));
            }
        } elseif (strlen(trim($userName)) < 5) {
            throw new CustomException("Длина имени пользователя должна быть больше 4 сиволов", 3);
        }


        if (!(is_null($address) || empty(trim($address)) )){
            $user->setAddress(trim($address));
        }
        $user->setCurrentGroup($group);
        $user->setActivated(true);
        $user->save();

        return $user;
    }

    public function logout()
    {
        $token = Functions::getCookie('token');
        Functions::deleteAllCookies();
        //Functions::deleteCookie('token');
        session_destroy();
    }

    public function getAdminPanelStyles()
    {
        $style = "";
        $styles = array();

        $adminStyle = $this->getCurrentUser()->getCurrentAdminStyle();

        if (is_null($adminStyle)){
            $adminStyle = new AdminStyle();
            $this->getCurrentUser()->setCurrentAdminStyle($adminStyle);
            $this->getCurrentUser()->save();
        }

        $styles["themeCSS"] = $this->getAdminAssetsDir() . "css/themes/" . $adminStyle->getCustomStyle() . ".css";

        if ($adminStyle->getAllowFHeader()){
            $style .= " page-header-fixed";
            $styles["fHeader"] = "checked";
        } else $styles["fHeader"] = "";

        if ($adminStyle->getAllowFSidebar()){
            $style .= " page-sidebar-fixed";
            $styles["fSidebar"] = "checked";
        } else $styles["fSidebar"] = "";

        if ($adminStyle->getAllowHBar()){
            $style .= " page-horizontal-bar";
            $styles["hBar"] = "checked";
            $styles["hBar_"] = "horizontal-bar";
        } else {
            $styles["hBar"] = "";
            $styles["hBar_"] = "page-sidebar";
        }

        if ($adminStyle->getAllowTSidebar()){
            $style .= " small-sidebar";
            $styles["tSidebar"] = "checked";
        } else $styles["tSidebar"] = "";

        if ($adminStyle->getAllowCMenu()){
            $style .= " compact-menu";
            $styles["cMenu"] = "checked";
        } else $styles["cMenu"] = "";

        if ($adminStyle->getAllowHMenu()){
            $style .=  " hover-menu";
            $styles["hMenu"] = "checked";
        } else $styles["hMenu"] = "";

        if ($adminStyle->getAllowBLayout()){
            $styles["bLayout"] = "checked";
            $styles["bLayout_"] = "container";
        } else {
            $styles["bLayout"] = "";
            $styles["bLayout_"] = "";
        }

        $styles["style"] = $style;
        $styles['this'] = $adminStyle;
        return $styles;
    }

    public function buildMessagePage($code, $codeTitle, $description = '', $title = 'Ошибка', $linkName = 'Вернуться на главную', $link = '/') {
        $arguments = array('title' => $title,
                          'body' => array(   'code' => $code,
                                             'title' => $codeTitle,
                                             'description' => $description
                                             )
                         );
        return $arguments;
    }
}
