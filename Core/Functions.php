<?php

namespace Core;

use App\Config;
use Firebase\JWT\JWT;

class Functions
{

    public static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public static function getRuNumToStr($number, $titles, $showNumber = true)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return (($showNumber) ? $number . ' ' : '') . $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    }


    public static function FileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
            $arBytes = array(
                0 => array(
                    "UNIT" => "TB",
                    "VALUE" => pow(1024, 4)
                ),
                1 => array(
                    "UNIT" => "GB",
                    "VALUE" => pow(1024, 3)
                ),
                2 => array(
                    "UNIT" => "MB",
                    "VALUE" => pow(1024, 2)
                ),
                3 => array(
                    "UNIT" => "KB",
                    "VALUE" => 1024
                ),
                4 => array(
                    "UNIT" => "B",
                    "VALUE" => 1
                ),
            );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    public static function dateToRULocale($date)
    {
        //переводим в нормальную дату
        $currentDate = date("d.m.Y", $date->getTimestamp());

        //список месяцев с названиями для замены
        $_monthsList = array(
            ".01." => "января",
            ".02." => "февраля",
            ".03." => "марта",
            ".04." => "апреля",
            ".05." => "мая",
            ".06." => "июня",
            ".07." => "июля",
            ".08." => "августа",
            ".09." => "сентября",
            ".10." => "октября",
            ".11." => "ноября",
            ".12." => "декабря"
        );

        //Наша задача - вывод русской даты,
        //поэтому заменяем число месяца на название:
        $_mD = $date->format('.m.');
        $currentDate = str_replace($_mD, " ".$_monthsList[$_mD]." ", $currentDate);
        return $currentDate;
    }

    public static function dateToRULocaleFull($date)
    {
        //переводим в нормальную дату
        $currentDate = date("d.m.Y г", $date->getTimestamp());

        //список месяцев с названиями для замены
        $_monthsList = array(
            ".01." => "янв",
            ".02." => "фев",
            ".03." => "мар",
            ".04." => "апр",
            ".05." => "мая",
            ".06." => "июн",
            ".07." => "июл",
            ".08." => "авг",
            ".09." => "сен",
            ".10." => "окт",
            ".11." => "ноя",
            ".12." => "дек"
        );

        //Наша задача - вывод русской даты,
        //поэтому заменяем число месяца на название:
        $_mD = $date->format('.m.');

        $currentDate = str_replace($_mD, " ".$_monthsList[$_mD]." ", $currentDate);
        return $currentDate;
    }



    public static function generateToken($length = 30) : string
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[Functions::crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }


    public static function encodeJWT(\Models\User $user, $expiryDateTime, bool $locked = false)
    {
        $data = array(  'iat' => time(),
                        'jti' => base64_encode(random_bytes(32)),
                        'iss' => Config::SITE_URL,
                        'nbf' => time(),
                        'exp' => $expiryDateTime,
                        'data' => array('userId' => $user->getId(),
                                        'userGroup' => $user->getCurrentGroup()->getId(),
                                        'locked' => $locked
                                    )
        );
        $secretKey = Config::PASSWORD_SALT;

        $jwt = JWT::encode(
            $data,      //Data to be encoded in the JWT
            $secretKey, // The signing key
            'HS256'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );
        return $jwt;
    }

    public static function modifyJWT($jwt, bool $locked)
    {

        $data = array(  'iat' => $jwt->iat,
                        'jti' => $jwt->jti,
                        'iss' => $jwt->iss,
                        'nbf' => $jwt->nbf,
                        'exp' => $jwt->exp,
                        'data' => array('userId' => $jwt->data->userId,
                                        'userGroup' => $jwt->data->userGroup,
                                        'locked' => $locked
                                    )
        );
        $secretKey = Config::PASSWORD_SALT;

        $newJwt = JWT::encode(
            $data,      //Data to be encoded in the JWT
            $secretKey, // The signing key
            'HS256'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );
        Functions::setCookie('token', $newJwt, $jwt->exp);

        return $newJwt;
    }

    public static function decodeJWT($jwt)
    {
        try {
            $secretKey = Config::PASSWORD_SALT;
            $decodedJWT = JWT::decode($jwt, $secretKey, array('HS256'));
            return $decodedJWT;
        } catch (\Exception $e){
            return null;
        }

    }

    public static function dateDifference($date2)
	{
		$date1 = time();
		$diff = abs($date1 - $date2);

		$day = $diff/(60*60*24); // in day
		$dayFix = floor($day);
		$dayPen = $day - $dayFix;
		if($dayPen > 0)
		{
			$hour = $dayPen*(24); // in hour (1 day = 24 hour)
			$hourFix = floor($hour);
			$hourPen = $hour - $hourFix;
			if($hourPen > 0)
			{
				$min = $hourPen*(60); // in hour (1 hour = 60 min)
				$minFix = floor($min);
				$minPen = $min - $minFix;
				if($minPen > 0)
				{
					$sec = $minPen*(60); // in sec (1 min = 60 sec)
					$secFix = floor($sec);
				}
			}
		}
		$str = "";
		if($dayFix > 0)
			$str.= $dayFix . (($dayFix == 1) ? ' день ' : (($dayFix > 1 && $dayFix < 5) ? ' дня ' : ' дней ' ));
		if($hourFix > 0)
			$str.= $hourFix . (($hourFix == 1) ? ' час ' : (($hourFix > 1 && $hourFix < 5) ? ' часа ' : ' часов ' ));
		if($minFix > 0)
			$str.= $minFix . (($hourFix == 1) ? ' минут ' : (($hourFix > 1 && $hourFix < 5) ? ' минут ' : ' минут ' ));
		return $str;
	}

	public static function formatDurationTiming($duration){
        $sec_num = (int)$duration;
        $hours   = floor($sec_num / 3600);
        $minutes = floor(($sec_num - ($hours * 3600)) / 60);
        $seconds = $sec_num - ($hours * 3600) - ($minutes * 60);

        if ($hours == 0) {
            $hours = null;
        } else if ($hours   < 10) {
            $hours   = "0" . $hours;
        }
        if ($minutes < 10) {$minutes = "0" . $minutes;}
        if ($seconds < 10) {$seconds = "0" . $seconds;}

        return (($hours != null) ? $hours . ':' : '') . $minutes . ':' . $seconds;
    }


    public static function getUserAgent() : string
    {
        if (!is_null($_SERVER['HTTP_USER_AGENT'])) return $_SERVER['HTTP_USER_AGENT'];
        return 'undefined';
    }

    public static function getCookie(string $cookieName)
    {
        if (isset($_COOKIE[$cookieName])){
            return $_COOKIE[$cookieName];
        }
        return null;
    }

    public static function hasCookie(string $cookieName) : bool
    {
        if (isset($_COOKIE[$cookieName])){
            return true;
        }
        return false;
    }

    public static function setCookie(string $cookieName, string $cookieValue, int $expirationDateTime = null, string $directory = "/") : void
    {
        if (is_null($expirationDateTime)) $expirationDateTime = time() + 86400 * Config::COOKIE_EXPIRATION;
        setcookie($cookieName, $cookieValue, $expirationDateTime, $directory);
    }


    public static function deleteCookie(string $cookieName) : void
    {
        unset($_COOKIE[$cookieName]);
        // empty value and expiration one hour before
        setcookie($cookieName, '', time() - 3600);
    }

    public static function deleteAllCookies() : void
    {
        foreach ( $_COOKIE as $key => $value){
            setcookie( $key, $value, time() - 3600, '/');
        }
    }

    public static function getIp() : string
    {
        $ip;
        //whether ip is from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //whether ip is from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //whether ip is from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else { //whether ip is from remote address
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (!is_null($ip)) return $ip;
        return 'undefined';
    }



    public static function transliterize($string) {

    	return (new \Denismitr\Translit\Translit)->forString($string)->getSlug();

    }

}
