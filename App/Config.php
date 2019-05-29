<?php
namespace App;
/**
 * Application configuration
 *
 * PHP version 7.0
 */

class Config
{

    const TITLE_POSTFIX = ' | IT-Academy';
    const SHOW_ERRORS = true;
    const SITE_URL = "https://it-academy.kg/";
    const SITE_URL_NOSLASH = "https://it-academy.kg";
    const ADMIN_ASSETS_DIR = "/static/admin/assets/";

    const MAX_SECTION_TREE_DEEP = 10;

    const DEFAULT_SECTION_ROOT_ID = 1;
    const DEFAULT_IMAGE_NAME = "default.png";

    const UPLOADS_DIR = "/public/uploads/";
    const PUBLIC_UPLOADS_DIR = "/uploads/";

    const COOKIE_EXPIRATION = 30; // in days
    const TOKEN_LENGTH_FOR_LOGIN_AUTH = 50;
    const TOKEN_LENGTH_FOR_EMAIL_VERIFICATION = 50;
    const TOKEN_LENGTH_FOR_PASSWORD_QUERY = 50;
    const EMAIL_VERIFICATION_EXPIRATION = 1; // IN DAYS
    const FORGOT_PASSWORD_QUERY_EXPIRATION = 1; // IN DAYS
    const EMAIL_VERIFICATION_RESENDING_INTERVAL = 60; // IN SECONDS

    const FORGOT_PASSWORD_QUERY_RESENDING_INTERVAL = 60; // IN SECONDS
    const DEFAULT_LOGIN_REMEMBER_TIME = 1; // IN DAYS
    const LONG_LOGIN_REMEMBER_TIME = 30; // IN DAYS

    const DEVICE_INFO_COOKIE_EXPIRATION = 60; // IN DAYS

    // Image Configs

    const ALLOWED_ATTACHMENT_EXTENSIONS = array('zip', 'rar', 'png', 'jpg', 'jpeg', 'gif', 'exe', 'mov');
    const ALLOWED_ATTACHMENT_MAX_SIZE = 10000000000;
    const ATTACHMENT_UPLOADS_DIR = "../public/uploads/attachments/";
    const ATTACHMENT_GENERATED_NAME_LENGTH = 15;


    const ALLOWED_AUDIO_EXTENSIONS = array('mp3', 'wav');
    const ALLOWED_AUDIO_MAX_SIZE = 10000000000;
    const AUDIO_UPLOADS_DIR = "../public/uploads/audios/";
    const AUDIO_GENERATED_NAME_LENGTH = 15;


    const IMAGE_GENERATED_NAME_LENGTH = 15;
    const IMAGE_TYPE = "png"; // png, jpeg, gif
    const SOCIAL_IMAGE_TYPE = "jpg"; // png, jpeg, gif
    const IMAGE_QUALITY = 85; // range 1 - 100



    const STATIC_PAGE_LOGO_MAX_SIZE = array('width' => 1200, 'height' => 960);
    const STATIC_PAGE_LOGO_ORIGINAL_DIR_NAME = "original";
    const STATIC_PAGE_LOGO_UPLOADS_DIR = "../public/uploads/images/static_page/logo/";

    const STATIC_PAGE_LOGO_NORMAL_FIXED_SIZE = array('width' => 506, 'height' => 363);
    const STATIC_PAGE_LOGO_NORMAL_SIZE = array('width' => 506, 'height' => 363);
    const STATIC_PAGE_LOGO_NORMAL_EQUIVALENT = 0.3;
    const STATIC_PAGE_LOGO_NORMAL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const STATIC_PAGE_LOGO_NORMAL_DIR_NAME = "normal";


    const STATIC_PAGE_COVER_MAX_SIZE = array('width' => 3600, 'height' => 3600);
    const STATIC_PAGE_COVER_UPLOADS_DIR = "../public/uploads/images/static_page/cover/";
    const STATIC_PAGE_COVER_NORMAL_FIXED_SIZE = array('width' => 1920, 'height' => 800);
    const STATIC_PAGE_COVER_NORMAL_DIR_NAME = "normal";




    const COURSE_LOGO_MAX_SIZE = array('width' => 1200, 'height' => 960);
    const COURSE_LOGO_ORIGINAL_DIR_NAME = "original";
    const COURSE_LOGO_UPLOADS_DIR = "../public/uploads/images/course/logo/";

    const STATIC_PAGE_LOGO_THUMBNAIL_DIR_NAME = "thumb";
    const COURSE_LOGO_THUMBNAIL_SIZE = array('width' => 150, 'height' => 150);
    const COURSE_LOGO_THUMBNAIL_EQUIVALENT = 0.3;
    const COURSE_LOGO_THUMBNAIL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const COURSE_LOGO_THUMBNAIL_DIR_NAME = "thumb";


    const COURSE_LOGO_NORMAL_SIZE = array('width' => 768, 'height' => 550);
    const COURSE_LOGO_NORMAL_EQUIVALENT = 0.3;
    const COURSE_LOGO_NORMAL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const COURSE_LOGO_NORMAL_DIR_NAME = "normal";


    const COURSE_COVER_MAX_SIZE = array('width' => 3600, 'height' => 3600);
    const COURSE_COVER_ORIGINAL_DIR_NAME = "original";
    const COURSE_COVER_UPLOADS_DIR = "../public/uploads/images/course/cover/";


    const COURSE_COVER_NORMAL_FIXED_SIZE = array('width' => 1920, 'height' => 360);
    const COURSE_COVER_NORMAL_DIR_NAME = "normal";


    const COURSE_SKILL_LOGO_MAX_SIZE = array('width' => 1200, 'height' => 960);
    const COURSE_SKILL_LOGO_ORIGINAL_DIR_NAME = "original";
    const COURSE_SKILL_LOGO_UPLOADS_DIR = "../public/uploads/images/course/skill/logo/";

    const COURSE_SKILL_LOGO_THUMBNAIL_SIZE = array('width' => 150, 'height' => 150);
    const COURSE_SKILL_LOGO_THUMBNAIL_EQUIVALENT = 0.3;
    const COURSE_SKILL_LOGO_THUMBNAIL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const COURSE_SKILL_LOGO_THUMBNAIL_DIR_NAME = "thumb";


    const COURSE_SKILL_LOGO_NORMAL_SIZE = array('width' => 287, 'height' => 206);
    const COURSE_SKILL_LOGO_NORMAL_EQUIVALENT = 0.3;
    const COURSE_SKILL_LOGO_NORMAL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const COURSE_SKILL_LOGO_NORMAL_DIR_NAME = "normal";






    const PROJECT_LOGO_MAX_SIZE = array('width' => 1200, 'height' => 960);
    const PROJECT_LOGO_ORIGINAL_DIR_NAME = "original";
    const PROJECT_LOGO_UPLOADS_DIR = "../public/uploads/images/project/logo/";

    const PROJECT_LOGO_THUMBNAIL_SIZE = array('width' => 150, 'height' => 150);
    const PROJECT_LOGO_THUMBNAIL_EQUIVALENT = 0.3;
    const PROJECT_LOGO_THUMBNAIL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const PROJECT_LOGO_THUMBNAIL_DIR_NAME = "thumb";


    const PROJECT_LOGO_NORMAL_SIZE = array('width' => 506, 'height' => 363);
    const PROJECT_LOGO_NORMAL_EQUIVALENT = 0.3;
    const PROJECT_LOGO_NORMAL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const PROJECT_LOGO_NORMAL_DIR_NAME = "normal";


    const PROJECT_COVER_MAX_SIZE = array('width' => 3600, 'height' => 3600);
    const PROJECT_COVER_ORIGINAL_DIR_NAME = "original";
    const PROJECT_COVER_UPLOADS_DIR = "../public/uploads/images/project/cover/";


    const PROJECT_COVER_NORMAL_FIXED_SIZE = array('width' => 1920, 'height' => 1000);
    const PROJECT_COVER_NORMAL_DIR_NAME = "normal";



    const VACANCY_LOGO_MAX_SIZE = array('width' => 1200, 'height' => 960);
    const VACANCY_LOGO_ORIGINAL_DIR_NAME = "original";
    const VACANCY_LOGO_UPLOADS_DIR = "../public/uploads/images/vacancy/logo/";

    const VACANCY_LOGO_THUMBNAIL_SIZE = array('width' => 150, 'height' => 150);
    const VACANCY_LOGO_THUMBNAIL_EQUIVALENT = 0.3;
    const VACANCY_LOGO_THUMBNAIL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const VACANCY_LOGO_THUMBNAIL_DIR_NAME = "thumb";


    const VACANCY_LOGO_NORMAL_SIZE = array('width' => 1140, 'height' => 500);
    const VACANCY_LOGO_NORMAL_EQUIVALENT = 0.3;
    const VACANCY_LOGO_NORMAL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const VACANCY_LOGO_NORMAL_DIR_NAME = "normal";



    const ATTACHMENT_PREVIEW_FOR_TYPES = array('png', 'jpg', 'jpeg');

    const ATTACHMENT_PREVIEW_MAX_SIZE = array('width' => 1200, 'height' => 960);
    const ATTACHMENT_PREVIEW_ORIGINAL_DIR_NAME = "original";
    const ATTACHMENT_PREVIEW_UPLOADS_DIR = "../public/uploads/images/attachment/preview/";

    const ATTACHMENT_PREVIEW_THUMBNAIL_SIZE = array('width' => 150, 'height' => 150);
    const ATTACHMENT_PREVIEW_THUMBNAIL_EQUIVALENT = 0.3;
    const ATTACHMENT_PREVIEW_THUMBNAIL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const ATTACHMENT_PREVIEW_THUMBNAIL_DIR_NAME = "thumb";


    const ATTACHMENT_PREVIEW_NORMAL_SIZE = array('width' => 241, 'height' => 173);
    const ATTACHMENT_PREVIEW_NORMAL_EQUIVALENT = 0.3;
    const ATTACHMENT_PREVIEW_NORMAL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const ATTACHMENT_PREVIEW_NORMAL_DIR_NAME = "normal";






    const USER_COVER_MAX_SIZE = array('width' => 3600, 'height' => 3600);
    const USER_COVER_ORIGINAL_DIR_NAME = "original";
    const USER_COVER_UPLOADS_DIR = "../public/uploads/images/user/cover/";


    const USER_COVER_NORMAL_FIXED_SIZE = array('width' => 1920, 'height' => 360);
    const USER_COVER_NORMAL_DIR_NAME = "normal";

    const USER_LOGO_MAX_SIZE = array('width' => 1200, 'height' => 960);
    const USER_LOGO_ORIGINAL_DIR_NAME = "original";
    const USER_LOGO_UPLOADS_DIR = "../public/uploads/images/user/logo/";

    const USER_LOGO_THUMBNAIL_SIZE = array('width' => 150, 'height' => 150);
    const USER_LOGO_THUMBNAIL_EQUIVALENT = 0.3;
    const USER_LOGO_THUMBNAIL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const USER_LOGO_THUMBNAIL_DIR_NAME = "thumb";

    const USER_LOGO_NORMAL_SIZE = array('width' => 500, 'height' => 500);
    const USER_LOGO_NORMAL_EQUIVALENT = 0.3;
    const USER_LOGO_NORMAL_BLUR_EQUIVALENT = 40; // range 1 - 40
    const USER_LOGO_NORMAL_DIR_NAME = "normal";


    const REDIRECT_AFTER_LOGOUT = "/login";

    const REDIRECT_AFTER_SUCCESSFUL_REGISTRATION = "/admin/login";

    const REDIRECT_ADMIN_AFTER_LOGOUT = "/admin/login";
    const REDIRECT_ADMIN_PANEL = "/admin";
    const REDIRECT_SITE_PANEL = "/panel";

    const PASSWORD_SALT = "Sb3Lx9Ma4JLTlO67YQSe";



    const NOTIFICATION_TITLES = array(  'order' => 'У вас новый заказ',
        'recall' => 'У вас новый запрос на дозвон',

        'place_manager_request' => 'У вас новый запрос стать менеджером заведения %s от %s',
        'place_manager_fire' => 'Вы уже не менеджер заведения %s, пользователь %s уволил вас',
        'place_manager_self_fire' => 'Пользователь %s уже не менеджер вашего заведения %s, пользователь по своей воле уволился',
        'place_manager_request_declined' => 'Ваше приглашение стать менеджером заведения %s была отклонена от %s',
        'place_manager_request_accepted' => 'Ваше приглашение стать менеджером заведения %s была принята от %s',

        'place_deliverer_request' => 'У вас новый запрос стать доставщиком заведения %s от %s',
        'place_deliverer_fire' => 'Вы уже не доставщик заведения %s, пользователь %s уволил вас',
        'place_deliverer_self_fire' => 'Пользователь %s уже не доставщик вашего заведения %s, пользователь по своей воле уволился',
        'place_deliverer_request_declined' => 'Ваше приглашение стать доставщиком заведения %s была отклонена от %s',
        'place_deliverer_request_accepted' => 'Ваше приглашение стать доставщиком заведения %s была принята от %s',

        'site_manager_request' => 'У вас новый запрос стать менеджером сайта от %s',
        'site_manager_fire' => 'Вы уже не менеджер сайта, пользователь %s уволил вас',
        'site_manager_self_fire' => 'Пользователь %s уже не менеджер сайта, пользователь по своей воле уволился',
        'site_manager_request_declined' => 'Ваше приглашение стать менеджером сайта была отклонена от %s',
        'site_manager_request_accepted' => 'Ваше приглашение стать менеджером сайта была принята от %s',

    );
    const NOTIFICATION_SERVER = 'https://notification.eda.kg:1335';
    const NOTIFICATION_LOCAL_SERVER = '//192.168.10.10:1336';
    const NOTIFICATION_SERVER_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MzI4Njc2NTYsImp0aSI6Im51VFNpSFJ2dG5acWtHdTUxcjhlUzY0T0kwMldNVkVmMlMyUnVMcXZ2VGM9IiwiaXNzIjoiaHR0cDpcL1wvZWRhXC8iLCJuYmYiOjE1MzI4Njc2NTYsImV4cCI6NDY4NjQ2NzY1NiwiZGF0YSI6eyJ1c2VySWQiOjAsInVzZXJHcm91cCI6MCwibG9ja2VkIjpmYWxzZX19.9J4h7h0JXp6N2G2NEPEr0oXWafQYamHCZVsQy1d6NIw';

    const DAYS_OF_WEEK = array( 1 => array('full' => 'Понедельник', 'concated' => 'Пн.'),
        2 => array('full' => 'Вторник', 'concated' => 'Вт.'),
        3 => array('full' => 'Среда', 'concated' => 'Ср.'),
        4 => array('full' => 'Четверг', 'concated' => 'Чт.'),
        5 => array('full' => 'Пятница', 'concated' => 'Пт.'),
        6 => array('full' => 'Суббота', 'concated' => 'Сб.'),
        7 => array('full' => 'Воскресенье', 'concated' => 'Вс.')
    );

    const GOOGLE_MAPS_APIS = array(
        'AIzaSyCOPXx5tqyRmswH3slzd5vMWH9F0oSZw4k'
    );


    const LATEST_PRODUCTS_DAY = 15;

    const SOCIAL_LINKS = array( 'vk' => 'https://vk.com/eda_kg',
        'fb' => 'https://fb.com/eda.kg.bishkek',
        'twitter' => 'https://twitter.com/eda_kg_bishkek',
        'vk' => 'https://vk.com/eda_kg',
        'ig' => 'https://instagram.com/eda.kg_'
    );


    const SOCIAL_AUTH_CONFIG_PROVIDERS = [
        'Facebook' => [
            'enabled' => true,
            'keys'    => [ 'id' => '919151558264437', 'secret' => 'e2c23556898ef13463834f76feaf5e94' ],
            'photo_size' => 520,
            'access_type' => 'offline'
        ],
        'Google' => [
            'enabled' => true,
            'keys'    => [ 'id' => '701215091715-q1r7eec9bt9msn1umbj150ocgnut2kcv.apps.googleusercontent.com', 'secret' => '2qUFcCGOkaY5ZudYGExXq795' ],
            'photo_size' => 520,
            'access_type' => 'offline',
            'approval_prompt' => 'force'
        ],
        'Twitter' => [
            'enabled' => false,
            'keys'    => [ 'key' => '', 'secret' => '' ],
        ],
        'Instagram' => [
            'enabled' => false,
            'keys'    => [ 'key' => '', 'secret' => '' ],
        ],
        'LinkedIn' => [
            'enabled' => false,
            'keys'    => [ 'key' => '', 'secret' => '' ],
        ],
        // waiting for development of Framework
        'Vkontakte' => [
            'enabled' => false,
            'keys'    => [ 'key' => '', 'secret' => '' ],
        ],
        // waiting for development of Framework
        'Mailru' => [
            'enabled' => false,
            'keys'    => [ 'key' => '', 'secret' => '' ],
        ],
        'Odnoklassniki' => [
            'enabled' => false,
            'keys'    => [ 'key' => '', 'secret' => '' ],
        ]
    ];

    const DATE_NAMINGS = array(
        1 => 'января',
        2 => 'февраля',
        3 => 'марта',
        4 => 'апреля',
        5 => 'мая',
        6 => 'июня',
        7 => 'июля',
        8 => 'августа',
        9 => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря'
    );


    const TELEGRAM_BOT = array(
        'key' => '766256484:AAHAODr9g1JrwHPZ8lRkfAHLeS3N1t89ULo',
        'hook' => self::SITE_URL . 'api/telegramBot',
        'username' => 'itacademykg_bot',
        'chat_id' => '-317377999'
    );


    const MAIL = array(
        'host' => 'mx.hoster.kg',
        'email' => 'no-reply@it-academy.kg',
        'password' => 'fertfert123',
        'from' => 'no-reply@it-academy.kg',
        'xMailer' => 'IT-Academy CRM',
        'to' => 'itacademy98@gmail.com'
    );

    const SUPPORT_TEL_NUMBER = '+996 (312) 47-47-47';

    public static function getConstant($string){
        if (defined('\App\Config::'.$string)){
            return constant('\App\Config::'.$string);
        }
        return null;
    }
}
