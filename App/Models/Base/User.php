<?php

namespace Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\AdminStyle as ChildAdminStyle;
use Models\AdminStyleQuery as ChildAdminStyleQuery;
use Models\CourseStream as ChildCourseStream;
use Models\CourseStreamQuery as ChildCourseStreamQuery;
use Models\Currency as ChildCurrency;
use Models\CurrencyQuery as ChildCurrencyQuery;
use Models\Feedback as ChildFeedback;
use Models\FeedbackQuery as ChildFeedbackQuery;
use Models\Group as ChildGroup;
use Models\GroupQuery as ChildGroupQuery;
use Models\Notification as ChildNotification;
use Models\NotificationQuery as ChildNotificationQuery;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\VerificationToken as ChildVerificationToken;
use Models\VerificationTokenQuery as ChildVerificationTokenQuery;
use Models\Map\CourseStreamTableMap;
use Models\Map\FeedbackTableMap;
use Models\Map\NotificationTableMap;
use Models\Map\UserTableMap;
use Models\Map\VerificationTokenTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'user' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\UserTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the user_name field.
     *
     * @var        string
     */
    protected $user_name;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the about field.
     *
     * @var        string
     */
    protected $about;

    /**
     * The value for the birth_date field.
     *
     * @var        DateTime
     */
    protected $birth_date;

    /**
     * The value for the password field.
     *
     * @var        string
     */
    protected $password;

    /**
     * The value for the phone field.
     *
     * @var        string
     */
    protected $phone;

    /**
     * The value for the logo_name field.
     *
     * @var        string
     */
    protected $logo_name;

    /**
     * The value for the cover_name field.
     *
     * @var        string
     */
    protected $cover_name;

    /**
     * The value for the address field.
     *
     * @var        string
     */
    protected $address;

    /**
     * The value for the address_coordinates field.
     *
     * @var        array
     */
    protected $address_coordinates;

    /**
     * The unserialized $address_coordinates value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $address_coordinates_unserialized;

    /**
     * The value for the is_activated field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_activated;

    /**
     * The value for the social_id field.
     *
     * @var        string
     */
    protected $social_id;

    /**
     * The value for the social_token field.
     *
     * @var        string
     */
    protected $social_token;

    /**
     * The value for the group_id field.
     *
     * @var        int
     */
    protected $group_id;

    /**
     * The value for the currency_id field.
     *
     * @var        int
     */
    protected $currency_id;

    /**
     * The value for the admin_style_id field.
     *
     * @var        int
     */
    protected $admin_style_id;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildGroup
     */
    protected $aCurrentGroup;

    /**
     * @var        ChildCurrency
     */
    protected $aCurrentUserCurrency;

    /**
     * @var        ChildAdminStyle
     */
    protected $aCurrentAdminStyle;

    /**
     * @var        ObjectCollection|ChildVerificationToken[] Collection to store aggregation of ChildVerificationToken objects.
     */
    protected $collCurrentUserVerificationTokens;
    protected $collCurrentUserVerificationTokensPartial;

    /**
     * @var        ObjectCollection|ChildCourseStream[] Collection to store aggregation of ChildCourseStream objects.
     */
    protected $collCurrentInstructorCourseStreams;
    protected $collCurrentInstructorCourseStreamsPartial;

    /**
     * @var        ObjectCollection|ChildNotification[] Collection to store aggregation of ChildNotification objects.
     */
    protected $collToUserNotifications;
    protected $collToUserNotificationsPartial;

    /**
     * @var        ObjectCollection|ChildNotification[] Collection to store aggregation of ChildNotification objects.
     */
    protected $collFromUserNotifications;
    protected $collFromUserNotificationsPartial;

    /**
     * @var        ObjectCollection|ChildFeedback[] Collection to store aggregation of ChildFeedback objects.
     */
    protected $collCurrentUserFeedbacks;
    protected $collCurrentUserFeedbacksPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildVerificationToken[]
     */
    protected $currentUserVerificationTokensScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCourseStream[]
     */
    protected $currentInstructorCourseStreamsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildNotification[]
     */
    protected $toUserNotificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildNotification[]
     */
    protected $fromUserNotificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFeedback[]
     */
    protected $currentUserFeedbacksScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_activated = false;
    }

    /**
     * Initializes internal state of Models\Base\User object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|User The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [user_name] column value.
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [about] column value.
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Get the [optionally formatted] temporal [birth_date] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBirthDate($format = NULL)
    {
        if ($format === null) {
            return $this->birth_date;
        } else {
            return $this->birth_date instanceof \DateTimeInterface ? $this->birth_date->format($format) : null;
        }
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get the [logo_name] column value.
     *
     * @return string
     */
    public function getLogoName()
    {
        return $this->logo_name;
    }

    /**
     * Get the [cover_name] column value.
     *
     * @return string
     */
    public function getCoverName()
    {
        return $this->cover_name;
    }

    /**
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get the [address_coordinates] column value.
     *
     * @return array
     */
    public function getAddressCoordinates()
    {
        if (null === $this->address_coordinates_unserialized) {
            $this->address_coordinates_unserialized = array();
        }
        if (!$this->address_coordinates_unserialized && null !== $this->address_coordinates) {
            $address_coordinates_unserialized = substr($this->address_coordinates, 2, -2);
            $this->address_coordinates_unserialized = '' !== $address_coordinates_unserialized ? explode(' | ', $address_coordinates_unserialized) : array();
        }

        return $this->address_coordinates_unserialized;
    }

    /**
     * Test the presence of a value in the [address_coordinates] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasAddressCoordinate($value)
    {
        return in_array($value, $this->getAddressCoordinates());
    } // hasAddressCoordinate()

    /**
     * Get the [is_activated] column value.
     *
     * @return boolean
     */
    public function getActivated()
    {
        return $this->is_activated;
    }

    /**
     * Get the [is_activated] column value.
     *
     * @return boolean
     */
    public function isActivated()
    {
        return $this->getActivated();
    }

    /**
     * Get the [social_id] column value.
     *
     * @return string
     */
    public function getSocialId()
    {
        return $this->social_id;
    }

    /**
     * Get the [social_token] column value.
     *
     * @return string
     */
    public function getSocialToken()
    {
        return $this->social_token;
    }

    /**
     * Get the [group_id] column value.
     *
     * @return int
     */
    public function getCurrentGroupId()
    {
        return $this->group_id;
    }

    /**
     * Get the [currency_id] column value.
     *
     * @return int
     */
    public function getCurrentCurrencyId()
    {
        return $this->currency_id;
    }

    /**
     * Get the [admin_style_id] column value.
     *
     * @return int
     */
    public function getCurrentAdminStyleId()
    {
        return $this->admin_style_id;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[UserTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [user_name] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setUserName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_name !== $v) {
            $this->user_name = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_NAME] = true;
        }

        return $this;
    } // setUserName()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [about] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setAbout($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->about !== $v) {
            $this->about = $v;
            $this->modifiedColumns[UserTableMap::COL_ABOUT] = true;
        }

        return $this;
    } // setAbout()

    /**
     * Sets the value of [birth_date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setBirthDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->birth_date !== null || $dt !== null) {
            if ($this->birth_date === null || $dt === null || $dt->format("Y-m-d") !== $this->birth_date->format("Y-m-d")) {
                $this->birth_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_BIRTH_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setBirthDate()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [phone] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[UserTableMap::COL_PHONE] = true;
        }

        return $this;
    } // setPhone()

    /**
     * Set the value of [logo_name] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setLogoName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->logo_name !== $v) {
            $this->logo_name = $v;
            $this->modifiedColumns[UserTableMap::COL_LOGO_NAME] = true;
        }

        return $this;
    } // setLogoName()

    /**
     * Set the value of [cover_name] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setCoverName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cover_name !== $v) {
            $this->cover_name = $v;
            $this->modifiedColumns[UserTableMap::COL_COVER_NAME] = true;
        }

        return $this;
    } // setCoverName()

    /**
     * Set the value of [address] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[UserTableMap::COL_ADDRESS] = true;
        }

        return $this;
    } // setAddress()

    /**
     * Set the value of [address_coordinates] column.
     *
     * @param array $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setAddressCoordinates($v)
    {
        if ($this->address_coordinates_unserialized !== $v) {
            $this->address_coordinates_unserialized = $v;
            $this->address_coordinates = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[UserTableMap::COL_ADDRESS_COORDINATES] = true;
        }

        return $this;
    } // setAddressCoordinates()

    /**
     * Adds a value to the [address_coordinates] array column value.
     * @param  mixed $value
     *
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addAddressCoordinate($value)
    {
        $currentArray = $this->getAddressCoordinates();
        $currentArray []= $value;
        $this->setAddressCoordinates($currentArray);

        return $this;
    } // addAddressCoordinate()

    /**
     * Removes a value from the [address_coordinates] array column value.
     * @param  mixed $value
     *
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function removeAddressCoordinate($value)
    {
        $targetArray = array();
        foreach ($this->getAddressCoordinates() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setAddressCoordinates($targetArray);

        return $this;
    } // removeAddressCoordinate()

    /**
     * Sets the value of the [is_activated] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setActivated($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_activated !== $v) {
            $this->is_activated = $v;
            $this->modifiedColumns[UserTableMap::COL_IS_ACTIVATED] = true;
        }

        return $this;
    } // setActivated()

    /**
     * Set the value of [social_id] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setSocialId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->social_id !== $v) {
            $this->social_id = $v;
            $this->modifiedColumns[UserTableMap::COL_SOCIAL_ID] = true;
        }

        return $this;
    } // setSocialId()

    /**
     * Set the value of [social_token] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setSocialToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->social_token !== $v) {
            $this->social_token = $v;
            $this->modifiedColumns[UserTableMap::COL_SOCIAL_TOKEN] = true;
        }

        return $this;
    } // setSocialToken()

    /**
     * Set the value of [group_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setCurrentGroupId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->group_id !== $v) {
            $this->group_id = $v;
            $this->modifiedColumns[UserTableMap::COL_GROUP_ID] = true;
        }

        if ($this->aCurrentGroup !== null && $this->aCurrentGroup->getId() !== $v) {
            $this->aCurrentGroup = null;
        }

        return $this;
    } // setCurrentGroupId()

    /**
     * Set the value of [currency_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setCurrentCurrencyId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->currency_id !== $v) {
            $this->currency_id = $v;
            $this->modifiedColumns[UserTableMap::COL_CURRENCY_ID] = true;
        }

        if ($this->aCurrentUserCurrency !== null && $this->aCurrentUserCurrency->getId() !== $v) {
            $this->aCurrentUserCurrency = null;
        }

        return $this;
    } // setCurrentCurrencyId()

    /**
     * Set the value of [admin_style_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setCurrentAdminStyleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->admin_style_id !== $v) {
            $this->admin_style_id = $v;
            $this->modifiedColumns[UserTableMap::COL_ADMIN_STYLE_ID] = true;
        }

        if ($this->aCurrentAdminStyle !== null && $this->aCurrentAdminStyle->getId() !== $v) {
            $this->aCurrentAdminStyle = null;
        }

        return $this;
    } // setCurrentAdminStyleId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->is_activated !== false) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('UserName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('About', TableMap::TYPE_PHPNAME, $indexType)];
            $this->about = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('BirthDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->birth_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('LogoName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->logo_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('CoverName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cover_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('AddressCoordinates', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address_coordinates = $col;
            $this->address_coordinates_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('Activated', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_activated = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : UserTableMap::translateFieldName('SocialId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->social_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : UserTableMap::translateFieldName('SocialToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->social_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : UserTableMap::translateFieldName('CurrentGroupId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->group_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : UserTableMap::translateFieldName('CurrentCurrencyId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->currency_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : UserTableMap::translateFieldName('CurrentAdminStyleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->admin_style_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : UserTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : UserTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 20; // 20 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\User'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aCurrentGroup !== null && $this->group_id !== $this->aCurrentGroup->getId()) {
            $this->aCurrentGroup = null;
        }
        if ($this->aCurrentUserCurrency !== null && $this->currency_id !== $this->aCurrentUserCurrency->getId()) {
            $this->aCurrentUserCurrency = null;
        }
        if ($this->aCurrentAdminStyle !== null && $this->admin_style_id !== $this->aCurrentAdminStyle->getId()) {
            $this->aCurrentAdminStyle = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCurrentGroup = null;
            $this->aCurrentUserCurrency = null;
            $this->aCurrentAdminStyle = null;
            $this->collCurrentUserVerificationTokens = null;

            $this->collCurrentInstructorCourseStreams = null;

            $this->collToUserNotifications = null;

            $this->collFromUserNotifications = null;

            $this->collCurrentUserFeedbacks = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCurrentGroup !== null) {
                if ($this->aCurrentGroup->isModified() || $this->aCurrentGroup->isNew()) {
                    $affectedRows += $this->aCurrentGroup->save($con);
                }
                $this->setCurrentGroup($this->aCurrentGroup);
            }

            if ($this->aCurrentUserCurrency !== null) {
                if ($this->aCurrentUserCurrency->isModified() || $this->aCurrentUserCurrency->isNew()) {
                    $affectedRows += $this->aCurrentUserCurrency->save($con);
                }
                $this->setCurrentUserCurrency($this->aCurrentUserCurrency);
            }

            if ($this->aCurrentAdminStyle !== null) {
                if ($this->aCurrentAdminStyle->isModified() || $this->aCurrentAdminStyle->isNew()) {
                    $affectedRows += $this->aCurrentAdminStyle->save($con);
                }
                $this->setCurrentAdminStyle($this->aCurrentAdminStyle);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->currentUserVerificationTokensScheduledForDeletion !== null) {
                if (!$this->currentUserVerificationTokensScheduledForDeletion->isEmpty()) {
                    foreach ($this->currentUserVerificationTokensScheduledForDeletion as $currentUserVerificationToken) {
                        // need to save related object because we set the relation to null
                        $currentUserVerificationToken->save($con);
                    }
                    $this->currentUserVerificationTokensScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentUserVerificationTokens !== null) {
                foreach ($this->collCurrentUserVerificationTokens as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentInstructorCourseStreamsScheduledForDeletion !== null) {
                if (!$this->currentInstructorCourseStreamsScheduledForDeletion->isEmpty()) {
                    foreach ($this->currentInstructorCourseStreamsScheduledForDeletion as $currentInstructorCourseStream) {
                        // need to save related object because we set the relation to null
                        $currentInstructorCourseStream->save($con);
                    }
                    $this->currentInstructorCourseStreamsScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentInstructorCourseStreams !== null) {
                foreach ($this->collCurrentInstructorCourseStreams as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->toUserNotificationsScheduledForDeletion !== null) {
                if (!$this->toUserNotificationsScheduledForDeletion->isEmpty()) {
                    \Models\NotificationQuery::create()
                        ->filterByPrimaryKeys($this->toUserNotificationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->toUserNotificationsScheduledForDeletion = null;
                }
            }

            if ($this->collToUserNotifications !== null) {
                foreach ($this->collToUserNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->fromUserNotificationsScheduledForDeletion !== null) {
                if (!$this->fromUserNotificationsScheduledForDeletion->isEmpty()) {
                    \Models\NotificationQuery::create()
                        ->filterByPrimaryKeys($this->fromUserNotificationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->fromUserNotificationsScheduledForDeletion = null;
                }
            }

            if ($this->collFromUserNotifications !== null) {
                foreach ($this->collFromUserNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentUserFeedbacksScheduledForDeletion !== null) {
                if (!$this->currentUserFeedbacksScheduledForDeletion->isEmpty()) {
                    \Models\FeedbackQuery::create()
                        ->filterByPrimaryKeys($this->currentUserFeedbacksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentUserFeedbacksScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentUserFeedbacks !== null) {
                foreach ($this->collCurrentUserFeedbacks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`user_name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(UserTableMap::COL_ABOUT)) {
            $modifiedColumns[':p' . $index++]  = '`about`';
        }
        if ($this->isColumnModified(UserTableMap::COL_BIRTH_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`birth_date`';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(UserTableMap::COL_PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGO_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`logo_name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_COVER_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`cover_name`';
        }
        if ($this->isColumnModified(UserTableMap::COL_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = '`address`';
        }
        if ($this->isColumnModified(UserTableMap::COL_ADDRESS_COORDINATES)) {
            $modifiedColumns[':p' . $index++]  = '`address_coordinates`';
        }
        if ($this->isColumnModified(UserTableMap::COL_IS_ACTIVATED)) {
            $modifiedColumns[':p' . $index++]  = '`is_activated`';
        }
        if ($this->isColumnModified(UserTableMap::COL_SOCIAL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`social_id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_SOCIAL_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = '`social_token`';
        }
        if ($this->isColumnModified(UserTableMap::COL_GROUP_ID)) {
            $modifiedColumns[':p' . $index++]  = '`group_id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_CURRENCY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`currency_id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_ADMIN_STYLE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`admin_style_id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `user` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`user_name`':
                        $stmt->bindValue($identifier, $this->user_name, PDO::PARAM_STR);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`about`':
                        $stmt->bindValue($identifier, $this->about, PDO::PARAM_STR);
                        break;
                    case '`birth_date`':
                        $stmt->bindValue($identifier, $this->birth_date ? $this->birth_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`password`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`phone`':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case '`logo_name`':
                        $stmt->bindValue($identifier, $this->logo_name, PDO::PARAM_STR);
                        break;
                    case '`cover_name`':
                        $stmt->bindValue($identifier, $this->cover_name, PDO::PARAM_STR);
                        break;
                    case '`address`':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case '`address_coordinates`':
                        $stmt->bindValue($identifier, $this->address_coordinates, PDO::PARAM_STR);
                        break;
                    case '`is_activated`':
                        $stmt->bindValue($identifier, (int) $this->is_activated, PDO::PARAM_INT);
                        break;
                    case '`social_id`':
                        $stmt->bindValue($identifier, $this->social_id, PDO::PARAM_STR);
                        break;
                    case '`social_token`':
                        $stmt->bindValue($identifier, $this->social_token, PDO::PARAM_STR);
                        break;
                    case '`group_id`':
                        $stmt->bindValue($identifier, $this->group_id, PDO::PARAM_INT);
                        break;
                    case '`currency_id`':
                        $stmt->bindValue($identifier, $this->currency_id, PDO::PARAM_INT);
                        break;
                    case '`admin_style_id`':
                        $stmt->bindValue($identifier, $this->admin_style_id, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getUserName();
                break;
            case 3:
                return $this->getEmail();
                break;
            case 4:
                return $this->getAbout();
                break;
            case 5:
                return $this->getBirthDate();
                break;
            case 6:
                return $this->getPassword();
                break;
            case 7:
                return $this->getPhone();
                break;
            case 8:
                return $this->getLogoName();
                break;
            case 9:
                return $this->getCoverName();
                break;
            case 10:
                return $this->getAddress();
                break;
            case 11:
                return $this->getAddressCoordinates();
                break;
            case 12:
                return $this->getActivated();
                break;
            case 13:
                return $this->getSocialId();
                break;
            case 14:
                return $this->getSocialToken();
                break;
            case 15:
                return $this->getCurrentGroupId();
                break;
            case 16:
                return $this->getCurrentCurrencyId();
                break;
            case 17:
                return $this->getCurrentAdminStyleId();
                break;
            case 18:
                return $this->getCreatedAt();
                break;
            case 19:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getUserName(),
            $keys[3] => $this->getEmail(),
            $keys[4] => $this->getAbout(),
            $keys[5] => $this->getBirthDate(),
            $keys[6] => $this->getPassword(),
            $keys[7] => $this->getPhone(),
            $keys[8] => $this->getLogoName(),
            $keys[9] => $this->getCoverName(),
            $keys[10] => $this->getAddress(),
            $keys[11] => $this->getAddressCoordinates(),
            $keys[12] => $this->getActivated(),
            $keys[13] => $this->getSocialId(),
            $keys[14] => $this->getSocialToken(),
            $keys[15] => $this->getCurrentGroupId(),
            $keys[16] => $this->getCurrentCurrencyId(),
            $keys[17] => $this->getCurrentAdminStyleId(),
            $keys[18] => $this->getCreatedAt(),
            $keys[19] => $this->getUpdatedAt(),
        );
        if ($result[$keys[5]] instanceof \DateTimeInterface) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        if ($result[$keys[18]] instanceof \DateTimeInterface) {
            $result[$keys[18]] = $result[$keys[18]]->format('c');
        }

        if ($result[$keys[19]] instanceof \DateTimeInterface) {
            $result[$keys[19]] = $result[$keys[19]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCurrentGroup) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'group';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'group';
                        break;
                    default:
                        $key = 'CurrentGroup';
                }

                $result[$key] = $this->aCurrentGroup->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCurrentUserCurrency) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'currency';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'currency';
                        break;
                    default:
                        $key = 'CurrentUserCurrency';
                }

                $result[$key] = $this->aCurrentUserCurrency->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCurrentAdminStyle) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'adminStyle';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'admin_style';
                        break;
                    default:
                        $key = 'CurrentAdminStyle';
                }

                $result[$key] = $this->aCurrentAdminStyle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCurrentUserVerificationTokens) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'verificationTokens';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'verification_tokens';
                        break;
                    default:
                        $key = 'CurrentUserVerificationTokens';
                }

                $result[$key] = $this->collCurrentUserVerificationTokens->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentInstructorCourseStreams) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'courseStreams';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'course_streams';
                        break;
                    default:
                        $key = 'CurrentInstructorCourseStreams';
                }

                $result[$key] = $this->collCurrentInstructorCourseStreams->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collToUserNotifications) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'notifications';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'notifications';
                        break;
                    default:
                        $key = 'ToUserNotifications';
                }

                $result[$key] = $this->collToUserNotifications->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFromUserNotifications) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'notifications';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'notifications';
                        break;
                    default:
                        $key = 'FromUserNotifications';
                }

                $result[$key] = $this->collFromUserNotifications->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentUserFeedbacks) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'feedbacks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'feedbacks';
                        break;
                    default:
                        $key = 'CurrentUserFeedbacks';
                }

                $result[$key] = $this->collCurrentUserFeedbacks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Models\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setUserName($value);
                break;
            case 3:
                $this->setEmail($value);
                break;
            case 4:
                $this->setAbout($value);
                break;
            case 5:
                $this->setBirthDate($value);
                break;
            case 6:
                $this->setPassword($value);
                break;
            case 7:
                $this->setPhone($value);
                break;
            case 8:
                $this->setLogoName($value);
                break;
            case 9:
                $this->setCoverName($value);
                break;
            case 10:
                $this->setAddress($value);
                break;
            case 11:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setAddressCoordinates($value);
                break;
            case 12:
                $this->setActivated($value);
                break;
            case 13:
                $this->setSocialId($value);
                break;
            case 14:
                $this->setSocialToken($value);
                break;
            case 15:
                $this->setCurrentGroupId($value);
                break;
            case 16:
                $this->setCurrentCurrencyId($value);
                break;
            case 17:
                $this->setCurrentAdminStyleId($value);
                break;
            case 18:
                $this->setCreatedAt($value);
                break;
            case 19:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUserName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEmail($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAbout($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setBirthDate($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPassword($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPhone($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setLogoName($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCoverName($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setAddress($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setAddressCoordinates($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setActivated($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setSocialId($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setSocialToken($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setCurrentGroupId($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setCurrentCurrencyId($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setCurrentAdminStyleId($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setCreatedAt($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setUpdatedAt($arr[$keys[19]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Models\User The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_NAME)) {
            $criteria->add(UserTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_NAME)) {
            $criteria->add(UserTableMap::COL_USER_NAME, $this->user_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_ABOUT)) {
            $criteria->add(UserTableMap::COL_ABOUT, $this->about);
        }
        if ($this->isColumnModified(UserTableMap::COL_BIRTH_DATE)) {
            $criteria->add(UserTableMap::COL_BIRTH_DATE, $this->birth_date);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_PHONE)) {
            $criteria->add(UserTableMap::COL_PHONE, $this->phone);
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGO_NAME)) {
            $criteria->add(UserTableMap::COL_LOGO_NAME, $this->logo_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_COVER_NAME)) {
            $criteria->add(UserTableMap::COL_COVER_NAME, $this->cover_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_ADDRESS)) {
            $criteria->add(UserTableMap::COL_ADDRESS, $this->address);
        }
        if ($this->isColumnModified(UserTableMap::COL_ADDRESS_COORDINATES)) {
            $criteria->add(UserTableMap::COL_ADDRESS_COORDINATES, $this->address_coordinates);
        }
        if ($this->isColumnModified(UserTableMap::COL_IS_ACTIVATED)) {
            $criteria->add(UserTableMap::COL_IS_ACTIVATED, $this->is_activated);
        }
        if ($this->isColumnModified(UserTableMap::COL_SOCIAL_ID)) {
            $criteria->add(UserTableMap::COL_SOCIAL_ID, $this->social_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_SOCIAL_TOKEN)) {
            $criteria->add(UserTableMap::COL_SOCIAL_TOKEN, $this->social_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_GROUP_ID)) {
            $criteria->add(UserTableMap::COL_GROUP_ID, $this->group_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_CURRENCY_ID)) {
            $criteria->add(UserTableMap::COL_CURRENCY_ID, $this->currency_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_ADMIN_STYLE_ID)) {
            $criteria->add(UserTableMap::COL_ADMIN_STYLE_ID, $this->admin_style_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $criteria->add(UserTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $criteria->add(UserTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Models\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setUserName($this->getUserName());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setAbout($this->getAbout());
        $copyObj->setBirthDate($this->getBirthDate());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setLogoName($this->getLogoName());
        $copyObj->setCoverName($this->getCoverName());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setAddressCoordinates($this->getAddressCoordinates());
        $copyObj->setActivated($this->getActivated());
        $copyObj->setSocialId($this->getSocialId());
        $copyObj->setSocialToken($this->getSocialToken());
        $copyObj->setCurrentGroupId($this->getCurrentGroupId());
        $copyObj->setCurrentCurrencyId($this->getCurrentCurrencyId());
        $copyObj->setCurrentAdminStyleId($this->getCurrentAdminStyleId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCurrentUserVerificationTokens() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentUserVerificationToken($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentInstructorCourseStreams() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentInstructorCourseStream($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getToUserNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addToUserNotification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFromUserNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFromUserNotification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentUserFeedbacks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentUserFeedback($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Models\User Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildGroup object.
     *
     * @param  ChildGroup $v
     * @return $this|\Models\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrentGroup(ChildGroup $v = null)
    {
        if ($v === null) {
            $this->setCurrentGroupId(NULL);
        } else {
            $this->setCurrentGroupId($v->getId());
        }

        $this->aCurrentGroup = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildGroup object, it will not be re-added.
        if ($v !== null) {
            $v->addCurrentGroupUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildGroup object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildGroup The associated ChildGroup object.
     * @throws PropelException
     */
    public function getCurrentGroup(ConnectionInterface $con = null)
    {
        if ($this->aCurrentGroup === null && ($this->group_id != 0)) {
            $this->aCurrentGroup = ChildGroupQuery::create()->findPk($this->group_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrentGroup->addCurrentGroupUsers($this);
             */
        }

        return $this->aCurrentGroup;
    }

    /**
     * Declares an association between this object and a ChildCurrency object.
     *
     * @param  ChildCurrency $v
     * @return $this|\Models\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrentUserCurrency(ChildCurrency $v = null)
    {
        if ($v === null) {
            $this->setCurrentCurrencyId(NULL);
        } else {
            $this->setCurrentCurrencyId($v->getId());
        }

        $this->aCurrentUserCurrency = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCurrency object, it will not be re-added.
        if ($v !== null) {
            $v->addCurrentCurrencyUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCurrency object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCurrency The associated ChildCurrency object.
     * @throws PropelException
     */
    public function getCurrentUserCurrency(ConnectionInterface $con = null)
    {
        if ($this->aCurrentUserCurrency === null && ($this->currency_id != 0)) {
            $this->aCurrentUserCurrency = ChildCurrencyQuery::create()->findPk($this->currency_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrentUserCurrency->addCurrentCurrencyUsers($this);
             */
        }

        return $this->aCurrentUserCurrency;
    }

    /**
     * Declares an association between this object and a ChildAdminStyle object.
     *
     * @param  ChildAdminStyle $v
     * @return $this|\Models\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrentAdminStyle(ChildAdminStyle $v = null)
    {
        if ($v === null) {
            $this->setCurrentAdminStyleId(NULL);
        } else {
            $this->setCurrentAdminStyleId($v->getId());
        }

        $this->aCurrentAdminStyle = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAdminStyle object, it will not be re-added.
        if ($v !== null) {
            $v->addCurrentAdminStyleUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAdminStyle object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAdminStyle The associated ChildAdminStyle object.
     * @throws PropelException
     */
    public function getCurrentAdminStyle(ConnectionInterface $con = null)
    {
        if ($this->aCurrentAdminStyle === null && ($this->admin_style_id != 0)) {
            $this->aCurrentAdminStyle = ChildAdminStyleQuery::create()->findPk($this->admin_style_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrentAdminStyle->addCurrentAdminStyleUsers($this);
             */
        }

        return $this->aCurrentAdminStyle;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CurrentUserVerificationToken' == $relationName) {
            $this->initCurrentUserVerificationTokens();
            return;
        }
        if ('CurrentInstructorCourseStream' == $relationName) {
            $this->initCurrentInstructorCourseStreams();
            return;
        }
        if ('ToUserNotification' == $relationName) {
            $this->initToUserNotifications();
            return;
        }
        if ('FromUserNotification' == $relationName) {
            $this->initFromUserNotifications();
            return;
        }
        if ('CurrentUserFeedback' == $relationName) {
            $this->initCurrentUserFeedbacks();
            return;
        }
    }

    /**
     * Clears out the collCurrentUserVerificationTokens collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentUserVerificationTokens()
     */
    public function clearCurrentUserVerificationTokens()
    {
        $this->collCurrentUserVerificationTokens = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentUserVerificationTokens collection loaded partially.
     */
    public function resetPartialCurrentUserVerificationTokens($v = true)
    {
        $this->collCurrentUserVerificationTokensPartial = $v;
    }

    /**
     * Initializes the collCurrentUserVerificationTokens collection.
     *
     * By default this just sets the collCurrentUserVerificationTokens collection to an empty array (like clearcollCurrentUserVerificationTokens());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentUserVerificationTokens($overrideExisting = true)
    {
        if (null !== $this->collCurrentUserVerificationTokens && !$overrideExisting) {
            return;
        }

        $collectionClassName = VerificationTokenTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentUserVerificationTokens = new $collectionClassName;
        $this->collCurrentUserVerificationTokens->setModel('\Models\VerificationToken');
    }

    /**
     * Gets an array of ChildVerificationToken objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildVerificationToken[] List of ChildVerificationToken objects
     * @throws PropelException
     */
    public function getCurrentUserVerificationTokens(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentUserVerificationTokensPartial && !$this->isNew();
        if (null === $this->collCurrentUserVerificationTokens || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentUserVerificationTokens) {
                // return empty collection
                $this->initCurrentUserVerificationTokens();
            } else {
                $collCurrentUserVerificationTokens = ChildVerificationTokenQuery::create(null, $criteria)
                    ->filterByCurrentUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentUserVerificationTokensPartial && count($collCurrentUserVerificationTokens)) {
                        $this->initCurrentUserVerificationTokens(false);

                        foreach ($collCurrentUserVerificationTokens as $obj) {
                            if (false == $this->collCurrentUserVerificationTokens->contains($obj)) {
                                $this->collCurrentUserVerificationTokens->append($obj);
                            }
                        }

                        $this->collCurrentUserVerificationTokensPartial = true;
                    }

                    return $collCurrentUserVerificationTokens;
                }

                if ($partial && $this->collCurrentUserVerificationTokens) {
                    foreach ($this->collCurrentUserVerificationTokens as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentUserVerificationTokens[] = $obj;
                        }
                    }
                }

                $this->collCurrentUserVerificationTokens = $collCurrentUserVerificationTokens;
                $this->collCurrentUserVerificationTokensPartial = false;
            }
        }

        return $this->collCurrentUserVerificationTokens;
    }

    /**
     * Sets a collection of ChildVerificationToken objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentUserVerificationTokens A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setCurrentUserVerificationTokens(Collection $currentUserVerificationTokens, ConnectionInterface $con = null)
    {
        /** @var ChildVerificationToken[] $currentUserVerificationTokensToDelete */
        $currentUserVerificationTokensToDelete = $this->getCurrentUserVerificationTokens(new Criteria(), $con)->diff($currentUserVerificationTokens);


        $this->currentUserVerificationTokensScheduledForDeletion = $currentUserVerificationTokensToDelete;

        foreach ($currentUserVerificationTokensToDelete as $currentUserVerificationTokenRemoved) {
            $currentUserVerificationTokenRemoved->setCurrentUser(null);
        }

        $this->collCurrentUserVerificationTokens = null;
        foreach ($currentUserVerificationTokens as $currentUserVerificationToken) {
            $this->addCurrentUserVerificationToken($currentUserVerificationToken);
        }

        $this->collCurrentUserVerificationTokens = $currentUserVerificationTokens;
        $this->collCurrentUserVerificationTokensPartial = false;

        return $this;
    }

    /**
     * Returns the number of related VerificationToken objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related VerificationToken objects.
     * @throws PropelException
     */
    public function countCurrentUserVerificationTokens(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentUserVerificationTokensPartial && !$this->isNew();
        if (null === $this->collCurrentUserVerificationTokens || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentUserVerificationTokens) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentUserVerificationTokens());
            }

            $query = ChildVerificationTokenQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentUser($this)
                ->count($con);
        }

        return count($this->collCurrentUserVerificationTokens);
    }

    /**
     * Method called to associate a ChildVerificationToken object to this object
     * through the ChildVerificationToken foreign key attribute.
     *
     * @param  ChildVerificationToken $l ChildVerificationToken
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addCurrentUserVerificationToken(ChildVerificationToken $l)
    {
        if ($this->collCurrentUserVerificationTokens === null) {
            $this->initCurrentUserVerificationTokens();
            $this->collCurrentUserVerificationTokensPartial = true;
        }

        if (!$this->collCurrentUserVerificationTokens->contains($l)) {
            $this->doAddCurrentUserVerificationToken($l);

            if ($this->currentUserVerificationTokensScheduledForDeletion and $this->currentUserVerificationTokensScheduledForDeletion->contains($l)) {
                $this->currentUserVerificationTokensScheduledForDeletion->remove($this->currentUserVerificationTokensScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildVerificationToken $currentUserVerificationToken The ChildVerificationToken object to add.
     */
    protected function doAddCurrentUserVerificationToken(ChildVerificationToken $currentUserVerificationToken)
    {
        $this->collCurrentUserVerificationTokens[]= $currentUserVerificationToken;
        $currentUserVerificationToken->setCurrentUser($this);
    }

    /**
     * @param  ChildVerificationToken $currentUserVerificationToken The ChildVerificationToken object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeCurrentUserVerificationToken(ChildVerificationToken $currentUserVerificationToken)
    {
        if ($this->getCurrentUserVerificationTokens()->contains($currentUserVerificationToken)) {
            $pos = $this->collCurrentUserVerificationTokens->search($currentUserVerificationToken);
            $this->collCurrentUserVerificationTokens->remove($pos);
            if (null === $this->currentUserVerificationTokensScheduledForDeletion) {
                $this->currentUserVerificationTokensScheduledForDeletion = clone $this->collCurrentUserVerificationTokens;
                $this->currentUserVerificationTokensScheduledForDeletion->clear();
            }
            $this->currentUserVerificationTokensScheduledForDeletion[]= $currentUserVerificationToken;
            $currentUserVerificationToken->setCurrentUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collCurrentInstructorCourseStreams collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentInstructorCourseStreams()
     */
    public function clearCurrentInstructorCourseStreams()
    {
        $this->collCurrentInstructorCourseStreams = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentInstructorCourseStreams collection loaded partially.
     */
    public function resetPartialCurrentInstructorCourseStreams($v = true)
    {
        $this->collCurrentInstructorCourseStreamsPartial = $v;
    }

    /**
     * Initializes the collCurrentInstructorCourseStreams collection.
     *
     * By default this just sets the collCurrentInstructorCourseStreams collection to an empty array (like clearcollCurrentInstructorCourseStreams());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentInstructorCourseStreams($overrideExisting = true)
    {
        if (null !== $this->collCurrentInstructorCourseStreams && !$overrideExisting) {
            return;
        }

        $collectionClassName = CourseStreamTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentInstructorCourseStreams = new $collectionClassName;
        $this->collCurrentInstructorCourseStreams->setModel('\Models\CourseStream');
    }

    /**
     * Gets an array of ChildCourseStream objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     * @throws PropelException
     */
    public function getCurrentInstructorCourseStreams(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentInstructorCourseStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentInstructorCourseStreams || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentInstructorCourseStreams) {
                // return empty collection
                $this->initCurrentInstructorCourseStreams();
            } else {
                $collCurrentInstructorCourseStreams = ChildCourseStreamQuery::create(null, $criteria)
                    ->filterByCurrentCourseStreamInstructor($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentInstructorCourseStreamsPartial && count($collCurrentInstructorCourseStreams)) {
                        $this->initCurrentInstructorCourseStreams(false);

                        foreach ($collCurrentInstructorCourseStreams as $obj) {
                            if (false == $this->collCurrentInstructorCourseStreams->contains($obj)) {
                                $this->collCurrentInstructorCourseStreams->append($obj);
                            }
                        }

                        $this->collCurrentInstructorCourseStreamsPartial = true;
                    }

                    return $collCurrentInstructorCourseStreams;
                }

                if ($partial && $this->collCurrentInstructorCourseStreams) {
                    foreach ($this->collCurrentInstructorCourseStreams as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentInstructorCourseStreams[] = $obj;
                        }
                    }
                }

                $this->collCurrentInstructorCourseStreams = $collCurrentInstructorCourseStreams;
                $this->collCurrentInstructorCourseStreamsPartial = false;
            }
        }

        return $this->collCurrentInstructorCourseStreams;
    }

    /**
     * Sets a collection of ChildCourseStream objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentInstructorCourseStreams A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setCurrentInstructorCourseStreams(Collection $currentInstructorCourseStreams, ConnectionInterface $con = null)
    {
        /** @var ChildCourseStream[] $currentInstructorCourseStreamsToDelete */
        $currentInstructorCourseStreamsToDelete = $this->getCurrentInstructorCourseStreams(new Criteria(), $con)->diff($currentInstructorCourseStreams);


        $this->currentInstructorCourseStreamsScheduledForDeletion = $currentInstructorCourseStreamsToDelete;

        foreach ($currentInstructorCourseStreamsToDelete as $currentInstructorCourseStreamRemoved) {
            $currentInstructorCourseStreamRemoved->setCurrentCourseStreamInstructor(null);
        }

        $this->collCurrentInstructorCourseStreams = null;
        foreach ($currentInstructorCourseStreams as $currentInstructorCourseStream) {
            $this->addCurrentInstructorCourseStream($currentInstructorCourseStream);
        }

        $this->collCurrentInstructorCourseStreams = $currentInstructorCourseStreams;
        $this->collCurrentInstructorCourseStreamsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CourseStream objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CourseStream objects.
     * @throws PropelException
     */
    public function countCurrentInstructorCourseStreams(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentInstructorCourseStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentInstructorCourseStreams || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentInstructorCourseStreams) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentInstructorCourseStreams());
            }

            $query = ChildCourseStreamQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentCourseStreamInstructor($this)
                ->count($con);
        }

        return count($this->collCurrentInstructorCourseStreams);
    }

    /**
     * Method called to associate a ChildCourseStream object to this object
     * through the ChildCourseStream foreign key attribute.
     *
     * @param  ChildCourseStream $l ChildCourseStream
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addCurrentInstructorCourseStream(ChildCourseStream $l)
    {
        if ($this->collCurrentInstructorCourseStreams === null) {
            $this->initCurrentInstructorCourseStreams();
            $this->collCurrentInstructorCourseStreamsPartial = true;
        }

        if (!$this->collCurrentInstructorCourseStreams->contains($l)) {
            $this->doAddCurrentInstructorCourseStream($l);

            if ($this->currentInstructorCourseStreamsScheduledForDeletion and $this->currentInstructorCourseStreamsScheduledForDeletion->contains($l)) {
                $this->currentInstructorCourseStreamsScheduledForDeletion->remove($this->currentInstructorCourseStreamsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCourseStream $currentInstructorCourseStream The ChildCourseStream object to add.
     */
    protected function doAddCurrentInstructorCourseStream(ChildCourseStream $currentInstructorCourseStream)
    {
        $this->collCurrentInstructorCourseStreams[]= $currentInstructorCourseStream;
        $currentInstructorCourseStream->setCurrentCourseStreamInstructor($this);
    }

    /**
     * @param  ChildCourseStream $currentInstructorCourseStream The ChildCourseStream object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeCurrentInstructorCourseStream(ChildCourseStream $currentInstructorCourseStream)
    {
        if ($this->getCurrentInstructorCourseStreams()->contains($currentInstructorCourseStream)) {
            $pos = $this->collCurrentInstructorCourseStreams->search($currentInstructorCourseStream);
            $this->collCurrentInstructorCourseStreams->remove($pos);
            if (null === $this->currentInstructorCourseStreamsScheduledForDeletion) {
                $this->currentInstructorCourseStreamsScheduledForDeletion = clone $this->collCurrentInstructorCourseStreams;
                $this->currentInstructorCourseStreamsScheduledForDeletion->clear();
            }
            $this->currentInstructorCourseStreamsScheduledForDeletion[]= $currentInstructorCourseStream;
            $currentInstructorCourseStream->setCurrentCourseStreamInstructor(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CurrentInstructorCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentInstructorCourseStreamsJoinCurrentCourseStreamBranch(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamBranch', $joinBehavior);

        return $this->getCurrentInstructorCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CurrentInstructorCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentInstructorCourseStreamsJoinCurrentCourseStreamCurrency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamCurrency', $joinBehavior);

        return $this->getCurrentInstructorCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CurrentInstructorCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentInstructorCourseStreamsJoinCurrentCourseCourseStream(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseCourseStream', $joinBehavior);

        return $this->getCurrentInstructorCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CurrentInstructorCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentInstructorCourseStreamsJoinCurrentCourseCourseStreamStatus(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseCourseStreamStatus', $joinBehavior);

        return $this->getCurrentInstructorCourseStreams($query, $con);
    }

    /**
     * Clears out the collToUserNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addToUserNotifications()
     */
    public function clearToUserNotifications()
    {
        $this->collToUserNotifications = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collToUserNotifications collection loaded partially.
     */
    public function resetPartialToUserNotifications($v = true)
    {
        $this->collToUserNotificationsPartial = $v;
    }

    /**
     * Initializes the collToUserNotifications collection.
     *
     * By default this just sets the collToUserNotifications collection to an empty array (like clearcollToUserNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initToUserNotifications($overrideExisting = true)
    {
        if (null !== $this->collToUserNotifications && !$overrideExisting) {
            return;
        }

        $collectionClassName = NotificationTableMap::getTableMap()->getCollectionClassName();

        $this->collToUserNotifications = new $collectionClassName;
        $this->collToUserNotifications->setModel('\Models\Notification');
    }

    /**
     * Gets an array of ChildNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildNotification[] List of ChildNotification objects
     * @throws PropelException
     */
    public function getToUserNotifications(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collToUserNotificationsPartial && !$this->isNew();
        if (null === $this->collToUserNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collToUserNotifications) {
                // return empty collection
                $this->initToUserNotifications();
            } else {
                $collToUserNotifications = ChildNotificationQuery::create(null, $criteria)
                    ->filterByToUserNotification($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collToUserNotificationsPartial && count($collToUserNotifications)) {
                        $this->initToUserNotifications(false);

                        foreach ($collToUserNotifications as $obj) {
                            if (false == $this->collToUserNotifications->contains($obj)) {
                                $this->collToUserNotifications->append($obj);
                            }
                        }

                        $this->collToUserNotificationsPartial = true;
                    }

                    return $collToUserNotifications;
                }

                if ($partial && $this->collToUserNotifications) {
                    foreach ($this->collToUserNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collToUserNotifications[] = $obj;
                        }
                    }
                }

                $this->collToUserNotifications = $collToUserNotifications;
                $this->collToUserNotificationsPartial = false;
            }
        }

        return $this->collToUserNotifications;
    }

    /**
     * Sets a collection of ChildNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $toUserNotifications A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setToUserNotifications(Collection $toUserNotifications, ConnectionInterface $con = null)
    {
        /** @var ChildNotification[] $toUserNotificationsToDelete */
        $toUserNotificationsToDelete = $this->getToUserNotifications(new Criteria(), $con)->diff($toUserNotifications);


        $this->toUserNotificationsScheduledForDeletion = $toUserNotificationsToDelete;

        foreach ($toUserNotificationsToDelete as $toUserNotificationRemoved) {
            $toUserNotificationRemoved->setToUserNotification(null);
        }

        $this->collToUserNotifications = null;
        foreach ($toUserNotifications as $toUserNotification) {
            $this->addToUserNotification($toUserNotification);
        }

        $this->collToUserNotifications = $toUserNotifications;
        $this->collToUserNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Notification objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Notification objects.
     * @throws PropelException
     */
    public function countToUserNotifications(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collToUserNotificationsPartial && !$this->isNew();
        if (null === $this->collToUserNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collToUserNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getToUserNotifications());
            }

            $query = ChildNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByToUserNotification($this)
                ->count($con);
        }

        return count($this->collToUserNotifications);
    }

    /**
     * Method called to associate a ChildNotification object to this object
     * through the ChildNotification foreign key attribute.
     *
     * @param  ChildNotification $l ChildNotification
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addToUserNotification(ChildNotification $l)
    {
        if ($this->collToUserNotifications === null) {
            $this->initToUserNotifications();
            $this->collToUserNotificationsPartial = true;
        }

        if (!$this->collToUserNotifications->contains($l)) {
            $this->doAddToUserNotification($l);

            if ($this->toUserNotificationsScheduledForDeletion and $this->toUserNotificationsScheduledForDeletion->contains($l)) {
                $this->toUserNotificationsScheduledForDeletion->remove($this->toUserNotificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildNotification $toUserNotification The ChildNotification object to add.
     */
    protected function doAddToUserNotification(ChildNotification $toUserNotification)
    {
        $this->collToUserNotifications[]= $toUserNotification;
        $toUserNotification->setToUserNotification($this);
    }

    /**
     * @param  ChildNotification $toUserNotification The ChildNotification object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeToUserNotification(ChildNotification $toUserNotification)
    {
        if ($this->getToUserNotifications()->contains($toUserNotification)) {
            $pos = $this->collToUserNotifications->search($toUserNotification);
            $this->collToUserNotifications->remove($pos);
            if (null === $this->toUserNotificationsScheduledForDeletion) {
                $this->toUserNotificationsScheduledForDeletion = clone $this->collToUserNotifications;
                $this->toUserNotificationsScheduledForDeletion->clear();
            }
            $this->toUserNotificationsScheduledForDeletion[]= clone $toUserNotification;
            $toUserNotification->setToUserNotification(null);
        }

        return $this;
    }

    /**
     * Clears out the collFromUserNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFromUserNotifications()
     */
    public function clearFromUserNotifications()
    {
        $this->collFromUserNotifications = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFromUserNotifications collection loaded partially.
     */
    public function resetPartialFromUserNotifications($v = true)
    {
        $this->collFromUserNotificationsPartial = $v;
    }

    /**
     * Initializes the collFromUserNotifications collection.
     *
     * By default this just sets the collFromUserNotifications collection to an empty array (like clearcollFromUserNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFromUserNotifications($overrideExisting = true)
    {
        if (null !== $this->collFromUserNotifications && !$overrideExisting) {
            return;
        }

        $collectionClassName = NotificationTableMap::getTableMap()->getCollectionClassName();

        $this->collFromUserNotifications = new $collectionClassName;
        $this->collFromUserNotifications->setModel('\Models\Notification');
    }

    /**
     * Gets an array of ChildNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildNotification[] List of ChildNotification objects
     * @throws PropelException
     */
    public function getFromUserNotifications(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFromUserNotificationsPartial && !$this->isNew();
        if (null === $this->collFromUserNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFromUserNotifications) {
                // return empty collection
                $this->initFromUserNotifications();
            } else {
                $collFromUserNotifications = ChildNotificationQuery::create(null, $criteria)
                    ->filterByFromUserNotification($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFromUserNotificationsPartial && count($collFromUserNotifications)) {
                        $this->initFromUserNotifications(false);

                        foreach ($collFromUserNotifications as $obj) {
                            if (false == $this->collFromUserNotifications->contains($obj)) {
                                $this->collFromUserNotifications->append($obj);
                            }
                        }

                        $this->collFromUserNotificationsPartial = true;
                    }

                    return $collFromUserNotifications;
                }

                if ($partial && $this->collFromUserNotifications) {
                    foreach ($this->collFromUserNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collFromUserNotifications[] = $obj;
                        }
                    }
                }

                $this->collFromUserNotifications = $collFromUserNotifications;
                $this->collFromUserNotificationsPartial = false;
            }
        }

        return $this->collFromUserNotifications;
    }

    /**
     * Sets a collection of ChildNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $fromUserNotifications A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setFromUserNotifications(Collection $fromUserNotifications, ConnectionInterface $con = null)
    {
        /** @var ChildNotification[] $fromUserNotificationsToDelete */
        $fromUserNotificationsToDelete = $this->getFromUserNotifications(new Criteria(), $con)->diff($fromUserNotifications);


        $this->fromUserNotificationsScheduledForDeletion = $fromUserNotificationsToDelete;

        foreach ($fromUserNotificationsToDelete as $fromUserNotificationRemoved) {
            $fromUserNotificationRemoved->setFromUserNotification(null);
        }

        $this->collFromUserNotifications = null;
        foreach ($fromUserNotifications as $fromUserNotification) {
            $this->addFromUserNotification($fromUserNotification);
        }

        $this->collFromUserNotifications = $fromUserNotifications;
        $this->collFromUserNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Notification objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Notification objects.
     * @throws PropelException
     */
    public function countFromUserNotifications(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFromUserNotificationsPartial && !$this->isNew();
        if (null === $this->collFromUserNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFromUserNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFromUserNotifications());
            }

            $query = ChildNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFromUserNotification($this)
                ->count($con);
        }

        return count($this->collFromUserNotifications);
    }

    /**
     * Method called to associate a ChildNotification object to this object
     * through the ChildNotification foreign key attribute.
     *
     * @param  ChildNotification $l ChildNotification
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addFromUserNotification(ChildNotification $l)
    {
        if ($this->collFromUserNotifications === null) {
            $this->initFromUserNotifications();
            $this->collFromUserNotificationsPartial = true;
        }

        if (!$this->collFromUserNotifications->contains($l)) {
            $this->doAddFromUserNotification($l);

            if ($this->fromUserNotificationsScheduledForDeletion and $this->fromUserNotificationsScheduledForDeletion->contains($l)) {
                $this->fromUserNotificationsScheduledForDeletion->remove($this->fromUserNotificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildNotification $fromUserNotification The ChildNotification object to add.
     */
    protected function doAddFromUserNotification(ChildNotification $fromUserNotification)
    {
        $this->collFromUserNotifications[]= $fromUserNotification;
        $fromUserNotification->setFromUserNotification($this);
    }

    /**
     * @param  ChildNotification $fromUserNotification The ChildNotification object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeFromUserNotification(ChildNotification $fromUserNotification)
    {
        if ($this->getFromUserNotifications()->contains($fromUserNotification)) {
            $pos = $this->collFromUserNotifications->search($fromUserNotification);
            $this->collFromUserNotifications->remove($pos);
            if (null === $this->fromUserNotificationsScheduledForDeletion) {
                $this->fromUserNotificationsScheduledForDeletion = clone $this->collFromUserNotifications;
                $this->fromUserNotificationsScheduledForDeletion->clear();
            }
            $this->fromUserNotificationsScheduledForDeletion[]= $fromUserNotification;
            $fromUserNotification->setFromUserNotification(null);
        }

        return $this;
    }

    /**
     * Clears out the collCurrentUserFeedbacks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentUserFeedbacks()
     */
    public function clearCurrentUserFeedbacks()
    {
        $this->collCurrentUserFeedbacks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentUserFeedbacks collection loaded partially.
     */
    public function resetPartialCurrentUserFeedbacks($v = true)
    {
        $this->collCurrentUserFeedbacksPartial = $v;
    }

    /**
     * Initializes the collCurrentUserFeedbacks collection.
     *
     * By default this just sets the collCurrentUserFeedbacks collection to an empty array (like clearcollCurrentUserFeedbacks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentUserFeedbacks($overrideExisting = true)
    {
        if (null !== $this->collCurrentUserFeedbacks && !$overrideExisting) {
            return;
        }

        $collectionClassName = FeedbackTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentUserFeedbacks = new $collectionClassName;
        $this->collCurrentUserFeedbacks->setModel('\Models\Feedback');
    }

    /**
     * Gets an array of ChildFeedback objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFeedback[] List of ChildFeedback objects
     * @throws PropelException
     */
    public function getCurrentUserFeedbacks(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentUserFeedbacksPartial && !$this->isNew();
        if (null === $this->collCurrentUserFeedbacks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentUserFeedbacks) {
                // return empty collection
                $this->initCurrentUserFeedbacks();
            } else {
                $collCurrentUserFeedbacks = ChildFeedbackQuery::create(null, $criteria)
                    ->filterByCurrentFeedbackUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentUserFeedbacksPartial && count($collCurrentUserFeedbacks)) {
                        $this->initCurrentUserFeedbacks(false);

                        foreach ($collCurrentUserFeedbacks as $obj) {
                            if (false == $this->collCurrentUserFeedbacks->contains($obj)) {
                                $this->collCurrentUserFeedbacks->append($obj);
                            }
                        }

                        $this->collCurrentUserFeedbacksPartial = true;
                    }

                    return $collCurrentUserFeedbacks;
                }

                if ($partial && $this->collCurrentUserFeedbacks) {
                    foreach ($this->collCurrentUserFeedbacks as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentUserFeedbacks[] = $obj;
                        }
                    }
                }

                $this->collCurrentUserFeedbacks = $collCurrentUserFeedbacks;
                $this->collCurrentUserFeedbacksPartial = false;
            }
        }

        return $this->collCurrentUserFeedbacks;
    }

    /**
     * Sets a collection of ChildFeedback objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentUserFeedbacks A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setCurrentUserFeedbacks(Collection $currentUserFeedbacks, ConnectionInterface $con = null)
    {
        /** @var ChildFeedback[] $currentUserFeedbacksToDelete */
        $currentUserFeedbacksToDelete = $this->getCurrentUserFeedbacks(new Criteria(), $con)->diff($currentUserFeedbacks);


        $this->currentUserFeedbacksScheduledForDeletion = $currentUserFeedbacksToDelete;

        foreach ($currentUserFeedbacksToDelete as $currentUserFeedbackRemoved) {
            $currentUserFeedbackRemoved->setCurrentFeedbackUser(null);
        }

        $this->collCurrentUserFeedbacks = null;
        foreach ($currentUserFeedbacks as $currentUserFeedback) {
            $this->addCurrentUserFeedback($currentUserFeedback);
        }

        $this->collCurrentUserFeedbacks = $currentUserFeedbacks;
        $this->collCurrentUserFeedbacksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Feedback objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Feedback objects.
     * @throws PropelException
     */
    public function countCurrentUserFeedbacks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentUserFeedbacksPartial && !$this->isNew();
        if (null === $this->collCurrentUserFeedbacks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentUserFeedbacks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentUserFeedbacks());
            }

            $query = ChildFeedbackQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentFeedbackUser($this)
                ->count($con);
        }

        return count($this->collCurrentUserFeedbacks);
    }

    /**
     * Method called to associate a ChildFeedback object to this object
     * through the ChildFeedback foreign key attribute.
     *
     * @param  ChildFeedback $l ChildFeedback
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addCurrentUserFeedback(ChildFeedback $l)
    {
        if ($this->collCurrentUserFeedbacks === null) {
            $this->initCurrentUserFeedbacks();
            $this->collCurrentUserFeedbacksPartial = true;
        }

        if (!$this->collCurrentUserFeedbacks->contains($l)) {
            $this->doAddCurrentUserFeedback($l);

            if ($this->currentUserFeedbacksScheduledForDeletion and $this->currentUserFeedbacksScheduledForDeletion->contains($l)) {
                $this->currentUserFeedbacksScheduledForDeletion->remove($this->currentUserFeedbacksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildFeedback $currentUserFeedback The ChildFeedback object to add.
     */
    protected function doAddCurrentUserFeedback(ChildFeedback $currentUserFeedback)
    {
        $this->collCurrentUserFeedbacks[]= $currentUserFeedback;
        $currentUserFeedback->setCurrentFeedbackUser($this);
    }

    /**
     * @param  ChildFeedback $currentUserFeedback The ChildFeedback object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeCurrentUserFeedback(ChildFeedback $currentUserFeedback)
    {
        if ($this->getCurrentUserFeedbacks()->contains($currentUserFeedback)) {
            $pos = $this->collCurrentUserFeedbacks->search($currentUserFeedback);
            $this->collCurrentUserFeedbacks->remove($pos);
            if (null === $this->currentUserFeedbacksScheduledForDeletion) {
                $this->currentUserFeedbacksScheduledForDeletion = clone $this->collCurrentUserFeedbacks;
                $this->currentUserFeedbacksScheduledForDeletion->clear();
            }
            $this->currentUserFeedbacksScheduledForDeletion[]= clone $currentUserFeedback;
            $currentUserFeedback->setCurrentFeedbackUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CurrentUserFeedbacks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFeedback[] List of ChildFeedback objects
     */
    public function getCurrentUserFeedbacksJoinCurrentFeedbackCurrency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFeedbackQuery::create(null, $criteria);
        $query->joinWith('CurrentFeedbackCurrency', $joinBehavior);

        return $this->getCurrentUserFeedbacks($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aCurrentGroup) {
            $this->aCurrentGroup->removeCurrentGroupUser($this);
        }
        if (null !== $this->aCurrentUserCurrency) {
            $this->aCurrentUserCurrency->removeCurrentCurrencyUser($this);
        }
        if (null !== $this->aCurrentAdminStyle) {
            $this->aCurrentAdminStyle->removeCurrentAdminStyleUser($this);
        }
        $this->id = null;
        $this->name = null;
        $this->user_name = null;
        $this->email = null;
        $this->about = null;
        $this->birth_date = null;
        $this->password = null;
        $this->phone = null;
        $this->logo_name = null;
        $this->cover_name = null;
        $this->address = null;
        $this->address_coordinates = null;
        $this->address_coordinates_unserialized = null;
        $this->is_activated = null;
        $this->social_id = null;
        $this->social_token = null;
        $this->group_id = null;
        $this->currency_id = null;
        $this->admin_style_id = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collCurrentUserVerificationTokens) {
                foreach ($this->collCurrentUserVerificationTokens as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentInstructorCourseStreams) {
                foreach ($this->collCurrentInstructorCourseStreams as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collToUserNotifications) {
                foreach ($this->collToUserNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFromUserNotifications) {
                foreach ($this->collFromUserNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentUserFeedbacks) {
                foreach ($this->collCurrentUserFeedbacks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCurrentUserVerificationTokens = null;
        $this->collCurrentInstructorCourseStreams = null;
        $this->collToUserNotifications = null;
        $this->collFromUserNotifications = null;
        $this->collCurrentUserFeedbacks = null;
        $this->aCurrentGroup = null;
        $this->aCurrentUserCurrency = null;
        $this->aCurrentAdminStyle = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildUser The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
