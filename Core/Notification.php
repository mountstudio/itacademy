<?php

namespace Core;


if (php_sapi_name() == 'cli'){
    // Setup the autoloading
    require_once 'vendor/autoload.php';

    // Setup Propel
    require_once 'generated-conf/config.php';
} else {
    // Setup the autoloading
    require_once '../vendor/autoload.php';

    // Setup Propel
    require_once '../generated-conf/config.php';
}

// Init Monolog Logger
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Init Propel
use Propel\Runtime\Propel;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;
use ElephantIO\Exception\ServerConnectionFailureException;

use App\Config;
use \Models\Order;
use \Models\Recall;
use \Models\NotificationQuery;
use \Models\GroupQuery;
use Core\Mail;
use Core\Functions;
use Core\CustomException;
use Propel\Runtime\Util\PropelModelPager;

/**
 *
 */
class Notification
{
    const RECALL = 1;
    const ORDER = 2;

    const PLACE_MANAGER_REQUEST = 3;
    const PLACE_MANAGER_FIRE = 4;
    const PLACE_MANAGER_SELF_FIRE = 5;
    const PLACE_MANAGER_REQUEST_DECLINED = 6;
    const PLACE_MANAGER_REQUEST_ACCEPTED = 7;


    const SITE_MANAGER_REQUEST = 8;
    const SITE_MANAGER_FIRE = 9;
    const SITE_MANAGER_SELF_FIRE = 10;
    const SITE_MANAGER_REQUEST_DECLINED = 11;
    const SITE_MANAGER_REQUEST_ACCEPTED = 12;

    const PLACE_DELIVERER_REQUEST = 13;
    const PLACE_DELIVERER_FIRE = 14;
    const PLACE_DELIVERER_SELF_FIRE = 15;
    const PLACE_DELIVERER_REQUEST_DECLINED = 16;
    const PLACE_DELIVERER_REQUEST_ACCEPTED = 17;


    protected $type, $title, $message, $data, $fromUser, $toUser, $place, $order, $icon, $link = null, $action = false;

    function __construct($type)
    {
        $this->type = $type;
        $this->link = null;

        switch ($type){
            case self::RECALL:
                $this->title = Config::NOTIFICATION_TITLES['recall'];
                $this->icon = 'phone';
                $this->link = '/admin/recalls/all';
        		break;
			case self::ORDER:
                $this->title = Config::NOTIFICATION_TITLES['order'];
				$this->icon = 'cutlery';
                break;
			case self::PLACE_MANAGER_REQUEST:
                $this->title = Config::NOTIFICATION_TITLES['place_manager_request'];
				$this->icon = 'bullhorn';
                $this->action = true;
        		break;
			case self::PLACE_MANAGER_FIRE:
                $this->title = Config::NOTIFICATION_TITLES['place_manager_fire'];
				$this->icon = 'sign-out';
        		break;
			case self::PLACE_MANAGER_SELF_FIRE:
                $this->title = Config::NOTIFICATION_TITLES['place_manager_self_fire'];
				$this->icon = 'sign-out';
        		break;
			case self::PLACE_MANAGER_REQUEST_DECLINED:
                $this->title = Config::NOTIFICATION_TITLES['place_manager_request_declined'];
				$this->icon = 'times';
        		break;
			case self::PLACE_MANAGER_REQUEST_ACCEPTED:
                $this->title = Config::NOTIFICATION_TITLES['place_manager_request_accepted'];
				$this->icon = 'check';
                break;
			case self::PLACE_DELIVERER_REQUEST:
                $this->title = Config::NOTIFICATION_TITLES['place_deliverer_request'];
				$this->icon = 'bullhorn';
                $this->action = true;
        		break;
			case self::PLACE_DELIVERER_FIRE:
                $this->title = Config::NOTIFICATION_TITLES['place_deliverer_fire'];
				$this->icon = 'sign-out';
        		break;
			case self::PLACE_DELIVERER_SELF_FIRE:
                $this->title = Config::NOTIFICATION_TITLES['place_deliverer_self_fire'];
				$this->icon = 'sign-out';
        		break;
			case self::PLACE_DELIVERER_REQUEST_DECLINED:
                $this->title = Config::NOTIFICATION_TITLES['place_deliverer_request_declined'];
				$this->icon = 'times';
        		break;
			case self::PLACE_DELIVERER_REQUEST_ACCEPTED:
                $this->title = Config::NOTIFICATION_TITLES['place_deliverer_request_accepted'];
				$this->icon = 'check';
                break;
			case self::SITE_MANAGER_REQUEST:
                $this->title = Config::NOTIFICATION_TITLES['site_manager_request'];
				$this->icon = 'bullhorn';
                $this->action = true;
        		break;
			case self::SITE_MANAGER_SELF_FIRE:
                $this->title = Config::NOTIFICATION_TITLES['site_manager_self_fire'];
				$this->icon = 'sign-out';
        		break;
            case self::SITE_MANAGER_FIRE:
                $this->title = Config::NOTIFICATION_TITLES['site_manager_fire'];
				$this->icon = 'sign-out';
        		break;
			case self::SITE_MANAGER_REQUEST_DECLINED:
                $this->title = Config::NOTIFICATION_TITLES['site_manager_request_declined'];
				$this->icon = 'times';
        		break;
			case self::SITE_MANAGER_REQUEST_ACCEPTED:
                $this->title = Config::NOTIFICATION_TITLES['site_manager_request_accepted'];
				$this->icon = 'check';
                break;
            default:
                $this->title = null;
				$this->icon = 'bell';
                break;
        }
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;
    }

    public function getFromUser()
    {
        return $this->fromUser;
    }

    public function setToUser($toUser)
    {
        $this->toUser = $toUser;
    }

    public function getToUser()
    {
        return $this->toUser;
    }

    public function setPlace($place)
    {
        $this->place = $place;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }

    private function titleFormatter() : void
    {
        switch ($this->type) {
            case self::RECALL:
                $this->title = $this->title;
        		break;
        	case self::ORDER:
        	    $this->title = $this->title;
        		break;
        	case self::PLACE_MANAGER_REQUEST:
        	    $this->title = sprintf($this->title, $this->place->getName(), $this->fromUser->getName());
        		break;
        	case self::PLACE_MANAGER_REQUEST_ACCEPTED:
        	    $this->title = sprintf($this->title, $this->place->getName(), $this->fromUser->getName());
        		break;
        	case self::PLACE_MANAGER_REQUEST_DECLINED:
        	    $this->title = sprintf($this->title, $this->place->getName(), $this->fromUser->getName());
        		break;
        	case self::PLACE_MANAGER_FIRE:
        	    $this->title = sprintf($this->title, $this->place->getName(), $this->fromUser->getName());
        		break;
        	case self::PLACE_MANAGER_SELF_FIRE:
        	    $this->title = sprintf($this->title, $this->fromUser->getName(), $this->place->getName());
        		break;
        	case self::PLACE_DELIVERER_REQUEST:
        	    $this->title = sprintf($this->title, $this->place->getName(), $this->fromUser->getName());
        		break;
        	case self::PLACE_DELIVERER_REQUEST_ACCEPTED:
        	    $this->title = sprintf($this->title, $this->place->getName(), $this->fromUser->getName());
        		break;
        	case self::PLACE_DELIVERER_REQUEST_DECLINED:
        	    $this->title = sprintf($this->title, $this->place->getName(), $this->fromUser->getName());
        		break;
        	case self::PLACE_DELIVERER_FIRE:
        	    $this->title = sprintf($this->title, $this->place->getName(), $this->fromUser->getName());
        		break;
        	case self::PLACE_DELIVERER_SELF_FIRE:
        	    $this->title = sprintf($this->title, $this->fromUser->getName(), $this->place->getName());
        		break;
        	case self::SITE_MANAGER_REQUEST:
        	    $this->title = sprintf($this->title, $this->fromUser->getName());
        		break;
        	case self::SITE_MANAGER_REQUEST_ACCEPTED:
        	    $this->title = sprintf($this->title, $this->fromUser->getName());
        		break;
        	case self::SITE_MANAGER_REQUEST_DECLINED:
        	    $this->title = sprintf($this->title, $this->fromUser->getName());
        		break;
        	case self::SITE_MANAGER_FIRE:
        	    $this->title = sprintf($this->title, $this->fromUser->getName());
        		break;
        	case self::SITE_MANAGER_SELF_FIRE:
        	    $this->title = sprintf($this->title, $this->fromUser->getName());
                break;
            default:
        	    $this->title = '';
                break;
        }
    }
    public function send() : void
    {

        try {
              $groupsList = null;
              $placeManagerList = null;
              $info = null;
              $this->titleFormatter();
              if (is_null($this->link)) $this->link = 'javascript:void(0);';
              switch ($this->type){
                  case self::RECALL:
                      $groups = \Models\GroupQuery::create()->filterByAllowSiteManager(true);

                      $userList = array();
                      foreach ($groups as $group) {
                          $users = $group->getCurrentGroupUsers();
                          foreach ($users as $user) {
                              $hasNotification = NotificationQuery::create()->findOneByToUserNotificationAndType($user, $this->type);
                              if (is_null($hasNotification)) {
                                  $hasNotification = new \Models\Notification();
                                  $hasNotification->setType($this->type);
                                  $hasNotification->setToUserNotification($user);
                              } else {
                                  $hasNotification->setQuantity($hasNotification->getQuantity() + 1);
                                  $hasNotification->setSeen(false);
                                  $hasNotification->setOver(false);
                              }
                              $hasNotification->save();

                              $userList[] = $user->getId();
                          }
                      }

                      $recallStatus = $this->data->getCurrentRecallStatus();

                      $info = array(    'type' => $this->type,
                                        'id' => null,
                                        'users' => $userList,
                                        'title' => $this->title,
                                        'link' => $this->link,
                                        'from' => null,
                                        'placeId' => null,
                                        'isRequest' => false,
                                        'data' => array(   'id' => $this->data->getId(),
                                                           'name' => $this->data->getName(),
                                                           'phone' => $this->data->getPhone(),
                                                           'notes' => $this->data->getNotes(),
                                                           'description' => $this->data->getDescription(),
                                                           'status' => array(  'id' => $recallStatus->getId(),
                                                                               'name' => $recallStatus->getName(),
                                                                               'fontColor' => $recallStatus->getFontColor(),
                                                                               'backgroundColor' => $recallStatus->getBackgroundColor()
                                                                           )
                                                           )
                                );
                        break;
                  case self::ORDER:
                      $placeManagers = $this->order->getCurrentPlace()->getCurrentPlacePlaceManagers();
                      $placeManagerList = array();
                      foreach ($placeManagers as $placeManager) {
                          $hasNotification = NotificationQuery::create()->findOneByToUserNotificationAndCurrentPlaceNotificationAndType($placeManager->getCurrentManagerPlaceManager(), $this->order->getCurrentPlace(), $this->type);
                          if (is_null($hasNotification)) {
                              $hasNotification = new \Models\Notification();
                              $hasNotification->setType($this->type);
                              $hasNotification->setCurrentPlaceNotification($this->order->getCurrentPlace());
                              $hasNotification->setToUserNotification($placeManager->getCurrentManagerPlaceManager());
                          } else {
                              $hasNotification->setQuantity($hasNotification->getQuantity() + 1);
                              $hasNotification->setSeen(false);
                              $hasNotification->setOver(false);
                          }
                          $hasNotification->save();

                          $placeManagerList[] = $placeManager->getCurrentManagerPlaceManager()->getId();
                      }
                      $this->link = '/admin/places/' . $this->order->getCurrentPlace()->getId() . '/orders/all';

                      if (is_null($this->order->getCurrentUser())){
                          $userData = array( 'name' => $this->order->getClientName(),
                                             'phone'=> $this->order->getPhone(),
                                             'address'=> $this->order->getAddress(),
                                             'addressLocation'=> $this->order->getAddressCoordinates()
                                     );
                      } else {
                          $userData = array( 'name' => $this->order->getCurrentUser()->getName(),
                                             'phone'=> $this->order->getCurrentUser()->getPhone(),
                                             'address'=> $this->order->getCurrentUser()->getAddress(),
                                             'addressLocation'=> $this->order->getCurrentUser()->getAddressCoordinates()
                                     );
                      }
                        $info = array('type' => $this->type,
                                      'id' => null,
                                      'users' => $placeManagerList,
                                      'title' => $this->title,
                                      'link' => $this->link,
                                      'from' => null,
                                      'placeId' => $this->order->getCurrentPlace()->getId(),
                                      'isRequest' => false,
                                      'data' => array(   'id' => $this->order->getId(),
                                                         'user' => $userData,
                                                         'description' => $this->order->getDescription(),
                                                         'notes' => $this->order->getNotes(),
                                                         'status' => array(  'id' => $this->order->getCurrentOrderStatus()->getId(),
                                                                             'name' => $this->order->getCurrentOrderStatus()->getName(),
                                                                             'backgroundColor' => $this->order->getCurrentOrderStatus()->getBackgroundColor(),
                                                                             'fontColor' => $this->order->getCurrentOrderStatus()->getFontColor(),
                                                                         ),
                                                         'createdAt' => $this->order->getCreatedAt()
                                                         )
                                  );
                  		break;
    			case self::PLACE_MANAGER_REQUEST:

                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => true,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_MANAGER_REQUEST_ACCEPTED:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_MANAGER_REQUEST_DECLINED:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_MANAGER_FIRE:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_MANAGER_SELF_FIRE:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_DELIVERER_REQUEST:

                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => true,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_DELIVERER_REQUEST_ACCEPTED:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_DELIVERER_REQUEST_DECLINED:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_DELIVERER_FIRE:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::PLACE_DELIVERER_SELF_FIRE:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->setCurrentPlaceNotification($this->place);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => $this->place->getId(),
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::SITE_MANAGER_REQUEST:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => null,
                                    'isRequest' => true,
                                    'data' => null
                                );
                  		break;
    			case self::SITE_MANAGER_REQUEST_ACCEPTED:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => null,
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::SITE_MANAGER_REQUEST_DECLINED:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => null,
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::SITE_MANAGER_FIRE:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => null,
                                    'isRequest' => false,
                                    'data' => null
                                );
                  		break;
    			case self::SITE_MANAGER_SELF_FIRE:
                      $notification = new \Models\Notification();
                      $notification->setType($this->type);
                      $notification->setFromUserNotification($this->fromUser);
                      $notification->setToUserNotification($this->toUser);
                      $notification->save();

                      $info = array('type' => $this->type,
                                    'id' => $notification->getId(),
                                    'users' => array($this->toUser->getId()),
                                    'title' => $this->title,
                                    'link' => $this->link,
                                    'fromUserId' => $this->fromUser->getId(),
                                    'placeId' => null,
                                    'isRequest' => false,
                                    'data' => null
                                );
                  break;
          }

            $client = new Client(new Version1X(Config::NOTIFICATION_LOCAL_SERVER . '/socket.io/?token=' . Config::NOTIFICATION_SERVER_TOKEN));
            $client->initialize();
            $info = array_merge($info, array('icon' => $this->icon));
            $client->emit('distributeMessage', $info);
            $client->close();
        } catch (ServerConnectionFailureException $e) {
            throw new CustomException('Ошибка подключения к серверу сокет => ' . $e->getMessage(), 403);
        }
    }

    public static function getAllNotifications($user)
    {
        $notifications = NotificationQuery::create()->filterByToUserId($user->getId())->orderByUpdatedAt('desc')->find();
        $info = array(  'buffer' => '',
                        'quantity'=> 0
        );
        foreach ($notifications as $notification) {
            if (!$notification->getOver()){
                if (!$notification->getSeen()) $info['quantity']++;
                $info['buffer'] .= Notification::builder($notification);
            }
        }
        return $info;
    }

    public static function builder($notification)
    {
        $newInstance = new Notification($notification->getType());
        $newInstance->setFromUser($notification->getFromUserNotification());
        $newInstance->setToUser($notification->getToUserNotification());
        $newInstance->setPlace($notification->getCurrentPlaceNotification());
        $newInstance->titleFormatter();
        if ($notification->getType() == Notification::ORDER) $newInstance->link = '/admin/places/' . $newInstance->getPlace()->getId() . '/orders/all';
        if (is_null($newInstance->link)) $newInstance->link = 'javascript:void(0);';
        $from = (!is_null($notification->getFromUserNotification())) ? $notification->getFromUserNotification()->getName() : '';
        $element = '<li>
            <a data-type="notification" data-place-id="' . (!is_null($newInstance->getPlace()) ? $newInstance->getPlace()->getId() : '') . '" data-id="' . $notification->getId() . '" data-href="' . $newInstance->link . '" data-quantity="' . $notification->getQuantity() . '" data-type-id="' . $notification->getType() . '" data-from="' . $from . '" href="' . $newInstance->link . '">
                <div class="task-icon badge badge-info"><i class="fa fa-' . $newInstance->icon . '"></i></div>
                <span class="badge badge-roundless badge-default pull-right convertMoment"  data-moment-time="' . $notification->getCreatedAt()->format('Y-m-d H:i:s') . '"></span>
                <p class="task-details">' . $newInstance->title . '</p>
                <span class="badge ' . (($notification->getSeen()) ? 'badge-default' : 'badge-success') . ' pull-right notification-badge">' . (($notification->getQuantity() > 1) ? $notification->getQuantity() : '' ) . '</span>
                ' . (($newInstance->action) ? '
                    <span class="notification-actions">
                        <button type="button" data-role="accept" class="btn btn-success btn-rounded btn-xs notification"><i class="fa fa-check"></i></button>
                        <button type="button" data-role="decline" class="btn btn-danger btn-rounded btn-xs notification"><i class="fa fa-times"></i></button>
                    </span>
                ' : '') . '
            </a>
        </li>';
        return $element;
    }

}
