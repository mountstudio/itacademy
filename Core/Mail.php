<?php

namespace Core;

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';


use Models\Application;
use PHPMailer\PHPMailer\PHPMailer;
use App\Config;
use \Models\User;
use \Models\VerificationToken;


class Mail {

    private $mail, $token;

    function __construct() {
        $this->mail = new PHPMailer();

        $this->mail->isSMTP(true);
        $this->mail->Host = Config::MAIL['host'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = Config::MAIL['email'];
        $this->mail->Password = Config::MAIL['password'];

        $this->mail->CharSet = 'UTF-8';
        $this->mail->From = Config::MAIL['from'];
        $this->mail->FromName = Config::MAIL['from'];
        $this->mail->XMailer = Config::MAIL['xMailer'];
        $this->mail->isHTML(true);
    }

    public function addAddress(string $address) {
        $this->mail->addAddress($address);
    }

    public function setToken(string $token) {
        $this->token = $token;
    }

    public function verifyAccount(VerificationToken $verificationToken, $fromFrontSide = null) : void {
        $this->mail->Subject = "Email verification";
        $this->addAddress($verificationToken->getEmail());
        $this->setToken($verificationToken->getToken());
        //$verificationToken->delete();
        $this->mail->Body = View::renderEmailTemplate('verify.html', array('link' => Config::SITE_URL . (($fromFrontSide) ? 'registrationVerification/' : 'panel/registrationVerification/') . $this->token));
    }

    public function changeEmail(User $user, VerificationToken $verificationToken, $fromFrontSide = null) {
        $this->mail->Subject = "Email verification (changing)";
        $this->addAddress($verificationToken->getEmail());
        $this->setToken($verificationToken->getToken());
        $this->mail->Body = View::renderEmailTemplate('changeEmailVerification.html', array('link' => Config::SITE_URL . (($fromFrontSide) ? 'changeEmailVerification/' : 'panel/changeEmailVerification/') . $this->token, 'user' => $user));
    }

    public function sendApplication(Application $application) {
        $this->mail->Subject = "Заявка с сайта";
        $this->addAddress(Config::MAIL['to']);
        $this->mail->Body = View::renderEmailTemplate('application.html', array('application' => $application));
    }


    public function restorePassword(User $user, VerificationToken $verificationToken, $fromFrontSide = null) {
        $this->mail->Subject = "Запрос на восстановление пароля";
        $this->addAddress($user->getEmail());
        $this->setToken($verificationToken->getToken());
        $this->mail->Body = View::renderEmailTemplate('restorePassword.html', array('link' => Config::SITE_URL . (($fromFrontSide) ? 'confirmForgotPasswordVerification/' : 'panel/confirmForgotPasswordVerification/') . $this->token, 'user' => $user));
    }

    public function send() {
        return $this->mail->send();
    }

}
