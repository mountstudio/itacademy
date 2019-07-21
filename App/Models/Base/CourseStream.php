<?php

namespace Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Application as ChildApplication;
use Models\ApplicationQuery as ChildApplicationQuery;
use Models\Branch as ChildBranch;
use Models\BranchQuery as ChildBranchQuery;
use Models\Course as ChildCourse;
use Models\CourseQuery as ChildCourseQuery;
use Models\CourseStream as ChildCourseStream;
use Models\CourseStreamQuery as ChildCourseStreamQuery;
use Models\CourseStreamStatus as ChildCourseStreamStatus;
use Models\CourseStreamStatusQuery as ChildCourseStreamStatusQuery;
use Models\Currency as ChildCurrency;
use Models\CurrencyQuery as ChildCurrencyQuery;
use Models\Lesson as ChildLesson;
use Models\LessonQuery as ChildLessonQuery;
use Models\StreamUser as ChildStreamUser;
use Models\StreamUserQuery as ChildStreamUserQuery;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\Map\ApplicationTableMap;
use Models\Map\CourseStreamTableMap;
use Models\Map\LessonTableMap;
use Models\Map\StreamUserTableMap;
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
 * Base class that represents a row from the 'course_stream' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class CourseStream implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\CourseStreamTableMap';


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
     * The value for the description field.
     *
     * @var        string
     */
    protected $description;

    /**
     * The value for the number_of_places field.
     *
     * @var        int
     */
    protected $number_of_places;

    /**
     * The value for the notes field.
     *
     * @var        string
     */
    protected $notes;

    /**
     * The value for the starts_at field.
     *
     * @var        DateTime
     */
    protected $starts_at;

    /**
     * The value for the ends_at field.
     *
     * @var        DateTime
     */
    protected $ends_at;

    /**
     * The value for the show_on_website field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $show_on_website;

    /**
     * The value for the cost field.
     *
     * @var        float
     */
    protected $cost;

    /**
     * The value for the branch_id field.
     *
     * @var        int
     */
    protected $branch_id;

    /**
     * The value for the currency_id field.
     *
     * @var        int
     */
    protected $currency_id;

    /**
     * The value for the course_id field.
     *
     * @var        int
     */
    protected $course_id;

    /**
     * The value for the course_stream_status_id field.
     *
     * @var        int
     */
    protected $course_stream_status_id;

    /**
     * The value for the instructor_id field.
     *
     * @var        int
     */
    protected $instructor_id;

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
     * @var        ChildBranch
     */
    protected $aCurrentCourseStreamBranch;

    /**
     * @var        ChildCurrency
     */
    protected $aCurrentCourseStreamCurrency;

    /**
     * @var        ChildCourse
     */
    protected $aCurrentCourseCourseStream;

    /**
     * @var        ChildCourseStreamStatus
     */
    protected $aCurrentCourseCourseStreamStatus;

    /**
     * @var        ChildUser
     */
    protected $aCurrentCourseStreamInstructor;

    /**
     * @var        ObjectCollection|ChildApplication[] Collection to store aggregation of ChildApplication objects.
     */
    protected $collCurrentApplicationCourseStreams;
    protected $collCurrentApplicationCourseStreamsPartial;

    /**
     * @var        ObjectCollection|ChildLesson[] Collection to store aggregation of ChildLesson objects.
     */
    protected $collCurrentStreamLessonStreams;
    protected $collCurrentStreamLessonStreamsPartial;

    /**
     * @var        ObjectCollection|ChildStreamUser[] Collection to store aggregation of ChildStreamUser objects.
     */
    protected $collStreamUsers;
    protected $collStreamUsersPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Cross Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;

    /**
     * @var bool
     */
    protected $collUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildApplication[]
     */
    protected $currentApplicationCourseStreamsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLesson[]
     */
    protected $currentStreamLessonStreamsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildStreamUser[]
     */
    protected $streamUsersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->show_on_website = false;
    }

    /**
     * Initializes internal state of Models\Base\CourseStream object.
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
     * Compares this with another <code>CourseStream</code> instance.  If
     * <code>obj</code> is an instance of <code>CourseStream</code>, delegates to
     * <code>equals(CourseStream)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|CourseStream The current object, for fluid interface
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
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [number_of_places] column value.
     *
     * @return int
     */
    public function getNumberOfPlaces()
    {
        return $this->number_of_places;
    }

    /**
     * Get the [notes] column value.
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Get the [optionally formatted] temporal [starts_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStartsAt($format = NULL)
    {
        if ($format === null) {
            return $this->starts_at;
        } else {
            return $this->starts_at instanceof \DateTimeInterface ? $this->starts_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [ends_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEndsAt($format = NULL)
    {
        if ($format === null) {
            return $this->ends_at;
        } else {
            return $this->ends_at instanceof \DateTimeInterface ? $this->ends_at->format($format) : null;
        }
    }

    /**
     * Get the [show_on_website] column value.
     *
     * @return boolean
     */
    public function getShowOnWebSite()
    {
        return $this->show_on_website;
    }

    /**
     * Get the [show_on_website] column value.
     *
     * @return boolean
     */
    public function isShowOnWebSite()
    {
        return $this->getShowOnWebSite();
    }

    /**
     * Get the [cost] column value.
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Get the [branch_id] column value.
     *
     * @return int
     */
    public function getCurrentBranchId()
    {
        return $this->branch_id;
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
     * Get the [course_id] column value.
     *
     * @return int
     */
    public function getCurrentCourseId()
    {
        return $this->course_id;
    }

    /**
     * Get the [course_stream_status_id] column value.
     *
     * @return int
     */
    public function getCurrentCourseStreamStatusId()
    {
        return $this->course_stream_status_id;
    }

    /**
     * Get the [instructor_id] column value.
     *
     * @return int
     */
    public function getCurrentCourseStreamInstructorId()
    {
        return $this->instructor_id;
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
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [number_of_places] column.
     *
     * @param int $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setNumberOfPlaces($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->number_of_places !== $v) {
            $this->number_of_places = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_NUMBER_OF_PLACES] = true;
        }

        return $this;
    } // setNumberOfPlaces()

    /**
     * Set the value of [notes] column.
     *
     * @param string $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setNotes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->notes !== $v) {
            $this->notes = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_NOTES] = true;
        }

        return $this;
    } // setNotes()

    /**
     * Sets the value of [starts_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setStartsAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->starts_at !== null || $dt !== null) {
            if ($this->starts_at === null || $dt === null || $dt->format("Y-m-d") !== $this->starts_at->format("Y-m-d")) {
                $this->starts_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CourseStreamTableMap::COL_STARTS_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setStartsAt()

    /**
     * Sets the value of [ends_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setEndsAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->ends_at !== null || $dt !== null) {
            if ($this->ends_at === null || $dt === null || $dt->format("Y-m-d") !== $this->ends_at->format("Y-m-d")) {
                $this->ends_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CourseStreamTableMap::COL_ENDS_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setEndsAt()

    /**
     * Sets the value of the [show_on_website] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setShowOnWebSite($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->show_on_website !== $v) {
            $this->show_on_website = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_SHOW_ON_WEBSITE] = true;
        }

        return $this;
    } // setShowOnWebSite()

    /**
     * Set the value of [cost] column.
     *
     * @param float $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setCost($v)
    {
        if ($v !== null) {
            $v = (float) $v;
        }

        if ($this->cost !== $v) {
            $this->cost = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_COST] = true;
        }

        return $this;
    } // setCost()

    /**
     * Set the value of [branch_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setCurrentBranchId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->branch_id !== $v) {
            $this->branch_id = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_BRANCH_ID] = true;
        }

        if ($this->aCurrentCourseStreamBranch !== null && $this->aCurrentCourseStreamBranch->getId() !== $v) {
            $this->aCurrentCourseStreamBranch = null;
        }

        return $this;
    } // setCurrentBranchId()

    /**
     * Set the value of [currency_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setCurrentCurrencyId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->currency_id !== $v) {
            $this->currency_id = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_CURRENCY_ID] = true;
        }

        if ($this->aCurrentCourseStreamCurrency !== null && $this->aCurrentCourseStreamCurrency->getId() !== $v) {
            $this->aCurrentCourseStreamCurrency = null;
        }

        return $this;
    } // setCurrentCurrencyId()

    /**
     * Set the value of [course_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setCurrentCourseId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->course_id !== $v) {
            $this->course_id = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_COURSE_ID] = true;
        }

        if ($this->aCurrentCourseCourseStream !== null && $this->aCurrentCourseCourseStream->getId() !== $v) {
            $this->aCurrentCourseCourseStream = null;
        }

        return $this;
    } // setCurrentCourseId()

    /**
     * Set the value of [course_stream_status_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setCurrentCourseStreamStatusId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->course_stream_status_id !== $v) {
            $this->course_stream_status_id = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID] = true;
        }

        if ($this->aCurrentCourseCourseStreamStatus !== null && $this->aCurrentCourseCourseStreamStatus->getId() !== $v) {
            $this->aCurrentCourseCourseStreamStatus = null;
        }

        return $this;
    } // setCurrentCourseStreamStatusId()

    /**
     * Set the value of [instructor_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setCurrentCourseStreamInstructorId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->instructor_id !== $v) {
            $this->instructor_id = $v;
            $this->modifiedColumns[CourseStreamTableMap::COL_INSTRUCTOR_ID] = true;
        }

        if ($this->aCurrentCourseStreamInstructor !== null && $this->aCurrentCourseStreamInstructor->getId() !== $v) {
            $this->aCurrentCourseStreamInstructor = null;
        }

        return $this;
    } // setCurrentCourseStreamInstructorId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CourseStreamTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CourseStreamTableMap::COL_UPDATED_AT] = true;
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
            if ($this->show_on_website !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CourseStreamTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CourseStreamTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CourseStreamTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CourseStreamTableMap::translateFieldName('NumberOfPlaces', TableMap::TYPE_PHPNAME, $indexType)];
            $this->number_of_places = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CourseStreamTableMap::translateFieldName('Notes', TableMap::TYPE_PHPNAME, $indexType)];
            $this->notes = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CourseStreamTableMap::translateFieldName('StartsAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->starts_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CourseStreamTableMap::translateFieldName('EndsAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->ends_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CourseStreamTableMap::translateFieldName('ShowOnWebSite', TableMap::TYPE_PHPNAME, $indexType)];
            $this->show_on_website = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CourseStreamTableMap::translateFieldName('Cost', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cost = (null !== $col) ? (float) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CourseStreamTableMap::translateFieldName('CurrentBranchId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->branch_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CourseStreamTableMap::translateFieldName('CurrentCurrencyId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->currency_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CourseStreamTableMap::translateFieldName('CurrentCourseId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->course_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CourseStreamTableMap::translateFieldName('CurrentCourseStreamStatusId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->course_stream_status_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : CourseStreamTableMap::translateFieldName('CurrentCourseStreamInstructorId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->instructor_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : CourseStreamTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : CourseStreamTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 16; // 16 = CourseStreamTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\CourseStream'), 0, $e);
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
        if ($this->aCurrentCourseStreamBranch !== null && $this->branch_id !== $this->aCurrentCourseStreamBranch->getId()) {
            $this->aCurrentCourseStreamBranch = null;
        }
        if ($this->aCurrentCourseStreamCurrency !== null && $this->currency_id !== $this->aCurrentCourseStreamCurrency->getId()) {
            $this->aCurrentCourseStreamCurrency = null;
        }
        if ($this->aCurrentCourseCourseStream !== null && $this->course_id !== $this->aCurrentCourseCourseStream->getId()) {
            $this->aCurrentCourseCourseStream = null;
        }
        if ($this->aCurrentCourseCourseStreamStatus !== null && $this->course_stream_status_id !== $this->aCurrentCourseCourseStreamStatus->getId()) {
            $this->aCurrentCourseCourseStreamStatus = null;
        }
        if ($this->aCurrentCourseStreamInstructor !== null && $this->instructor_id !== $this->aCurrentCourseStreamInstructor->getId()) {
            $this->aCurrentCourseStreamInstructor = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(CourseStreamTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCourseStreamQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCurrentCourseStreamBranch = null;
            $this->aCurrentCourseStreamCurrency = null;
            $this->aCurrentCourseCourseStream = null;
            $this->aCurrentCourseCourseStreamStatus = null;
            $this->aCurrentCourseStreamInstructor = null;
            $this->collCurrentApplicationCourseStreams = null;

            $this->collCurrentStreamLessonStreams = null;

            $this->collStreamUsers = null;

            $this->collUsers = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see CourseStream::setDeleted()
     * @see CourseStream::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCourseStreamQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(CourseStreamTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(CourseStreamTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CourseStreamTableMap::COL_UPDATED_AT)) {
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
                CourseStreamTableMap::addInstanceToPool($this);
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

            if ($this->aCurrentCourseStreamBranch !== null) {
                if ($this->aCurrentCourseStreamBranch->isModified() || $this->aCurrentCourseStreamBranch->isNew()) {
                    $affectedRows += $this->aCurrentCourseStreamBranch->save($con);
                }
                $this->setCurrentCourseStreamBranch($this->aCurrentCourseStreamBranch);
            }

            if ($this->aCurrentCourseStreamCurrency !== null) {
                if ($this->aCurrentCourseStreamCurrency->isModified() || $this->aCurrentCourseStreamCurrency->isNew()) {
                    $affectedRows += $this->aCurrentCourseStreamCurrency->save($con);
                }
                $this->setCurrentCourseStreamCurrency($this->aCurrentCourseStreamCurrency);
            }

            if ($this->aCurrentCourseCourseStream !== null) {
                if ($this->aCurrentCourseCourseStream->isModified() || $this->aCurrentCourseCourseStream->isNew()) {
                    $affectedRows += $this->aCurrentCourseCourseStream->save($con);
                }
                $this->setCurrentCourseCourseStream($this->aCurrentCourseCourseStream);
            }

            if ($this->aCurrentCourseCourseStreamStatus !== null) {
                if ($this->aCurrentCourseCourseStreamStatus->isModified() || $this->aCurrentCourseCourseStreamStatus->isNew()) {
                    $affectedRows += $this->aCurrentCourseCourseStreamStatus->save($con);
                }
                $this->setCurrentCourseCourseStreamStatus($this->aCurrentCourseCourseStreamStatus);
            }

            if ($this->aCurrentCourseStreamInstructor !== null) {
                if ($this->aCurrentCourseStreamInstructor->isModified() || $this->aCurrentCourseStreamInstructor->isNew()) {
                    $affectedRows += $this->aCurrentCourseStreamInstructor->save($con);
                }
                $this->setCurrentCourseStreamInstructor($this->aCurrentCourseStreamInstructor);
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

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->usersScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\StreamUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->usersScheduledForDeletion = null;
                }

            }

            if ($this->collUsers) {
                foreach ($this->collUsers as $user) {
                    if (!$user->isDeleted() && ($user->isNew() || $user->isModified())) {
                        $user->save($con);
                    }
                }
            }


            if ($this->currentApplicationCourseStreamsScheduledForDeletion !== null) {
                if (!$this->currentApplicationCourseStreamsScheduledForDeletion->isEmpty()) {
                    foreach ($this->currentApplicationCourseStreamsScheduledForDeletion as $currentApplicationCourseStream) {
                        // need to save related object because we set the relation to null
                        $currentApplicationCourseStream->save($con);
                    }
                    $this->currentApplicationCourseStreamsScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentApplicationCourseStreams !== null) {
                foreach ($this->collCurrentApplicationCourseStreams as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentStreamLessonStreamsScheduledForDeletion !== null) {
                if (!$this->currentStreamLessonStreamsScheduledForDeletion->isEmpty()) {
                    \Models\LessonQuery::create()
                        ->filterByPrimaryKeys($this->currentStreamLessonStreamsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentStreamLessonStreamsScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentStreamLessonStreams !== null) {
                foreach ($this->collCurrentStreamLessonStreams as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->streamUsersScheduledForDeletion !== null) {
                if (!$this->streamUsersScheduledForDeletion->isEmpty()) {
                    \Models\StreamUserQuery::create()
                        ->filterByPrimaryKeys($this->streamUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->streamUsersScheduledForDeletion = null;
                }
            }

            if ($this->collStreamUsers !== null) {
                foreach ($this->collStreamUsers as $referrerFK) {
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

        $this->modifiedColumns[CourseStreamTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CourseStreamTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CourseStreamTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_NUMBER_OF_PLACES)) {
            $modifiedColumns[':p' . $index++]  = '`number_of_places`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_NOTES)) {
            $modifiedColumns[':p' . $index++]  = '`notes`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_STARTS_AT)) {
            $modifiedColumns[':p' . $index++]  = '`starts_at`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_ENDS_AT)) {
            $modifiedColumns[':p' . $index++]  = '`ends_at`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_SHOW_ON_WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = '`show_on_website`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_COST)) {
            $modifiedColumns[':p' . $index++]  = '`cost`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_BRANCH_ID)) {
            $modifiedColumns[':p' . $index++]  = '`branch_id`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_CURRENCY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`currency_id`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_COURSE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`course_id`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID)) {
            $modifiedColumns[':p' . $index++]  = '`course_stream_status_id`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_INSTRUCTOR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`instructor_id`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `course_stream` (%s) VALUES (%s)',
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
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`number_of_places`':
                        $stmt->bindValue($identifier, $this->number_of_places, PDO::PARAM_INT);
                        break;
                    case '`notes`':
                        $stmt->bindValue($identifier, $this->notes, PDO::PARAM_STR);
                        break;
                    case '`starts_at`':
                        $stmt->bindValue($identifier, $this->starts_at ? $this->starts_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`ends_at`':
                        $stmt->bindValue($identifier, $this->ends_at ? $this->ends_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`show_on_website`':
                        $stmt->bindValue($identifier, (int) $this->show_on_website, PDO::PARAM_INT);
                        break;
                    case '`cost`':
                        $stmt->bindValue($identifier, $this->cost, PDO::PARAM_STR);
                        break;
                    case '`branch_id`':
                        $stmt->bindValue($identifier, $this->branch_id, PDO::PARAM_INT);
                        break;
                    case '`currency_id`':
                        $stmt->bindValue($identifier, $this->currency_id, PDO::PARAM_INT);
                        break;
                    case '`course_id`':
                        $stmt->bindValue($identifier, $this->course_id, PDO::PARAM_INT);
                        break;
                    case '`course_stream_status_id`':
                        $stmt->bindValue($identifier, $this->course_stream_status_id, PDO::PARAM_INT);
                        break;
                    case '`instructor_id`':
                        $stmt->bindValue($identifier, $this->instructor_id, PDO::PARAM_INT);
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
        $pos = CourseStreamTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getDescription();
                break;
            case 3:
                return $this->getNumberOfPlaces();
                break;
            case 4:
                return $this->getNotes();
                break;
            case 5:
                return $this->getStartsAt();
                break;
            case 6:
                return $this->getEndsAt();
                break;
            case 7:
                return $this->getShowOnWebSite();
                break;
            case 8:
                return $this->getCost();
                break;
            case 9:
                return $this->getCurrentBranchId();
                break;
            case 10:
                return $this->getCurrentCurrencyId();
                break;
            case 11:
                return $this->getCurrentCourseId();
                break;
            case 12:
                return $this->getCurrentCourseStreamStatusId();
                break;
            case 13:
                return $this->getCurrentCourseStreamInstructorId();
                break;
            case 14:
                return $this->getCreatedAt();
                break;
            case 15:
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

        if (isset($alreadyDumpedObjects['CourseStream'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['CourseStream'][$this->hashCode()] = true;
        $keys = CourseStreamTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getNumberOfPlaces(),
            $keys[4] => $this->getNotes(),
            $keys[5] => $this->getStartsAt(),
            $keys[6] => $this->getEndsAt(),
            $keys[7] => $this->getShowOnWebSite(),
            $keys[8] => $this->getCost(),
            $keys[9] => $this->getCurrentBranchId(),
            $keys[10] => $this->getCurrentCurrencyId(),
            $keys[11] => $this->getCurrentCourseId(),
            $keys[12] => $this->getCurrentCourseStreamStatusId(),
            $keys[13] => $this->getCurrentCourseStreamInstructorId(),
            $keys[14] => $this->getCreatedAt(),
            $keys[15] => $this->getUpdatedAt(),
        );
        if ($result[$keys[5]] instanceof \DateTimeInterface) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        if ($result[$keys[6]] instanceof \DateTimeInterface) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('c');
        }

        if ($result[$keys[15]] instanceof \DateTimeInterface) {
            $result[$keys[15]] = $result[$keys[15]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCurrentCourseStreamBranch) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'branch';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'branch';
                        break;
                    default:
                        $key = 'CurrentCourseStreamBranch';
                }

                $result[$key] = $this->aCurrentCourseStreamBranch->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCurrentCourseStreamCurrency) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'currency';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'currency';
                        break;
                    default:
                        $key = 'CurrentCourseStreamCurrency';
                }

                $result[$key] = $this->aCurrentCourseStreamCurrency->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCurrentCourseCourseStream) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'course';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'course';
                        break;
                    default:
                        $key = 'CurrentCourseCourseStream';
                }

                $result[$key] = $this->aCurrentCourseCourseStream->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCurrentCourseCourseStreamStatus) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'courseStreamStatus';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'course_stream_status';
                        break;
                    default:
                        $key = 'CurrentCourseCourseStreamStatus';
                }

                $result[$key] = $this->aCurrentCourseCourseStreamStatus->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCurrentCourseStreamInstructor) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'CurrentCourseStreamInstructor';
                }

                $result[$key] = $this->aCurrentCourseStreamInstructor->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCurrentApplicationCourseStreams) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'applications';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'applications';
                        break;
                    default:
                        $key = 'CurrentApplicationCourseStreams';
                }

                $result[$key] = $this->collCurrentApplicationCourseStreams->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentStreamLessonStreams) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'lessons';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'stream_lessons';
                        break;
                    default:
                        $key = 'CurrentStreamLessonStreams';
                }

                $result[$key] = $this->collCurrentStreamLessonStreams->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStreamUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'streamUsers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'stream_users';
                        break;
                    default:
                        $key = 'StreamUsers';
                }

                $result[$key] = $this->collStreamUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\CourseStream
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CourseStreamTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\CourseStream
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
                $this->setDescription($value);
                break;
            case 3:
                $this->setNumberOfPlaces($value);
                break;
            case 4:
                $this->setNotes($value);
                break;
            case 5:
                $this->setStartsAt($value);
                break;
            case 6:
                $this->setEndsAt($value);
                break;
            case 7:
                $this->setShowOnWebSite($value);
                break;
            case 8:
                $this->setCost($value);
                break;
            case 9:
                $this->setCurrentBranchId($value);
                break;
            case 10:
                $this->setCurrentCurrencyId($value);
                break;
            case 11:
                $this->setCurrentCourseId($value);
                break;
            case 12:
                $this->setCurrentCourseStreamStatusId($value);
                break;
            case 13:
                $this->setCurrentCourseStreamInstructorId($value);
                break;
            case 14:
                $this->setCreatedAt($value);
                break;
            case 15:
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
        $keys = CourseStreamTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDescription($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setNumberOfPlaces($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setNotes($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setStartsAt($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setEndsAt($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setShowOnWebSite($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCost($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCurrentBranchId($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setCurrentCurrencyId($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setCurrentCourseId($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setCurrentCourseStreamStatusId($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setCurrentCourseStreamInstructorId($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setCreatedAt($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setUpdatedAt($arr[$keys[15]]);
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
     * @return $this|\Models\CourseStream The current object, for fluid interface
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
        $criteria = new Criteria(CourseStreamTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CourseStreamTableMap::COL_ID)) {
            $criteria->add(CourseStreamTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_NAME)) {
            $criteria->add(CourseStreamTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_DESCRIPTION)) {
            $criteria->add(CourseStreamTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_NUMBER_OF_PLACES)) {
            $criteria->add(CourseStreamTableMap::COL_NUMBER_OF_PLACES, $this->number_of_places);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_NOTES)) {
            $criteria->add(CourseStreamTableMap::COL_NOTES, $this->notes);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_STARTS_AT)) {
            $criteria->add(CourseStreamTableMap::COL_STARTS_AT, $this->starts_at);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_ENDS_AT)) {
            $criteria->add(CourseStreamTableMap::COL_ENDS_AT, $this->ends_at);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_SHOW_ON_WEBSITE)) {
            $criteria->add(CourseStreamTableMap::COL_SHOW_ON_WEBSITE, $this->show_on_website);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_COST)) {
            $criteria->add(CourseStreamTableMap::COL_COST, $this->cost);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_BRANCH_ID)) {
            $criteria->add(CourseStreamTableMap::COL_BRANCH_ID, $this->branch_id);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_CURRENCY_ID)) {
            $criteria->add(CourseStreamTableMap::COL_CURRENCY_ID, $this->currency_id);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_COURSE_ID)) {
            $criteria->add(CourseStreamTableMap::COL_COURSE_ID, $this->course_id);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID)) {
            $criteria->add(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID, $this->course_stream_status_id);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_INSTRUCTOR_ID)) {
            $criteria->add(CourseStreamTableMap::COL_INSTRUCTOR_ID, $this->instructor_id);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_CREATED_AT)) {
            $criteria->add(CourseStreamTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(CourseStreamTableMap::COL_UPDATED_AT)) {
            $criteria->add(CourseStreamTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildCourseStreamQuery::create();
        $criteria->add(CourseStreamTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\CourseStream (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setNumberOfPlaces($this->getNumberOfPlaces());
        $copyObj->setNotes($this->getNotes());
        $copyObj->setStartsAt($this->getStartsAt());
        $copyObj->setEndsAt($this->getEndsAt());
        $copyObj->setShowOnWebSite($this->getShowOnWebSite());
        $copyObj->setCost($this->getCost());
        $copyObj->setCurrentBranchId($this->getCurrentBranchId());
        $copyObj->setCurrentCurrencyId($this->getCurrentCurrencyId());
        $copyObj->setCurrentCourseId($this->getCurrentCourseId());
        $copyObj->setCurrentCourseStreamStatusId($this->getCurrentCourseStreamStatusId());
        $copyObj->setCurrentCourseStreamInstructorId($this->getCurrentCourseStreamInstructorId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCurrentApplicationCourseStreams() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentApplicationCourseStream($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentStreamLessonStreams() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentStreamLessonStream($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStreamUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStreamUser($relObj->copy($deepCopy));
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
     * @return \Models\CourseStream Clone of current object.
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
     * Declares an association between this object and a ChildBranch object.
     *
     * @param  ChildBranch $v
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrentCourseStreamBranch(ChildBranch $v = null)
    {
        if ($v === null) {
            $this->setCurrentBranchId(NULL);
        } else {
            $this->setCurrentBranchId($v->getId());
        }

        $this->aCurrentCourseStreamBranch = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildBranch object, it will not be re-added.
        if ($v !== null) {
            $v->addCurrentBranchCourseStream($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildBranch object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildBranch The associated ChildBranch object.
     * @throws PropelException
     */
    public function getCurrentCourseStreamBranch(ConnectionInterface $con = null)
    {
        if ($this->aCurrentCourseStreamBranch === null && ($this->branch_id != 0)) {
            $this->aCurrentCourseStreamBranch = ChildBranchQuery::create()->findPk($this->branch_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrentCourseStreamBranch->addCurrentBranchCourseStreams($this);
             */
        }

        return $this->aCurrentCourseStreamBranch;
    }

    /**
     * Declares an association between this object and a ChildCurrency object.
     *
     * @param  ChildCurrency $v
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrentCourseStreamCurrency(ChildCurrency $v = null)
    {
        if ($v === null) {
            $this->setCurrentCurrencyId(NULL);
        } else {
            $this->setCurrentCurrencyId($v->getId());
        }

        $this->aCurrentCourseStreamCurrency = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCurrency object, it will not be re-added.
        if ($v !== null) {
            $v->addCurrentCurrencyCourseStream($this);
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
    public function getCurrentCourseStreamCurrency(ConnectionInterface $con = null)
    {
        if ($this->aCurrentCourseStreamCurrency === null && ($this->currency_id != 0)) {
            $this->aCurrentCourseStreamCurrency = ChildCurrencyQuery::create()->findPk($this->currency_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrentCourseStreamCurrency->addCurrentCurrencyCourseStreams($this);
             */
        }

        return $this->aCurrentCourseStreamCurrency;
    }

    /**
     * Declares an association between this object and a ChildCourse object.
     *
     * @param  ChildCourse $v
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrentCourseCourseStream(ChildCourse $v = null)
    {
        if ($v === null) {
            $this->setCurrentCourseId(NULL);
        } else {
            $this->setCurrentCourseId($v->getId());
        }

        $this->aCurrentCourseCourseStream = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCourse object, it will not be re-added.
        if ($v !== null) {
            $v->addCurrentCourseStreamCourse($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCourse object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCourse The associated ChildCourse object.
     * @throws PropelException
     */
    public function getCurrentCourseCourseStream(ConnectionInterface $con = null)
    {
        if ($this->aCurrentCourseCourseStream === null && ($this->course_id != 0)) {
            $this->aCurrentCourseCourseStream = ChildCourseQuery::create()->findPk($this->course_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrentCourseCourseStream->addCurrentCourseStreamCourses($this);
             */
        }

        return $this->aCurrentCourseCourseStream;
    }

    /**
     * Declares an association between this object and a ChildCourseStreamStatus object.
     *
     * @param  ChildCourseStreamStatus $v
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrentCourseCourseStreamStatus(ChildCourseStreamStatus $v = null)
    {
        if ($v === null) {
            $this->setCurrentCourseStreamStatusId(NULL);
        } else {
            $this->setCurrentCourseStreamStatusId($v->getId());
        }

        $this->aCurrentCourseCourseStreamStatus = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCourseStreamStatus object, it will not be re-added.
        if ($v !== null) {
            $v->addCurrentCourseStreamCourseStatus($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCourseStreamStatus object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCourseStreamStatus The associated ChildCourseStreamStatus object.
     * @throws PropelException
     */
    public function getCurrentCourseCourseStreamStatus(ConnectionInterface $con = null)
    {
        if ($this->aCurrentCourseCourseStreamStatus === null && ($this->course_stream_status_id != 0)) {
            $this->aCurrentCourseCourseStreamStatus = ChildCourseStreamStatusQuery::create()->findPk($this->course_stream_status_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrentCourseCourseStreamStatus->addCurrentCourseStreamCourseStatuses($this);
             */
        }

        return $this->aCurrentCourseCourseStreamStatus;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCurrentCourseStreamInstructor(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setCurrentCourseStreamInstructorId(NULL);
        } else {
            $this->setCurrentCourseStreamInstructorId($v->getId());
        }

        $this->aCurrentCourseStreamInstructor = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addCurrentInstructorCourseStream($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getCurrentCourseStreamInstructor(ConnectionInterface $con = null)
    {
        if ($this->aCurrentCourseStreamInstructor === null && ($this->instructor_id != 0)) {
            $this->aCurrentCourseStreamInstructor = ChildUserQuery::create()->findPk($this->instructor_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCurrentCourseStreamInstructor->addCurrentInstructorCourseStreams($this);
             */
        }

        return $this->aCurrentCourseStreamInstructor;
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
        if ('CurrentApplicationCourseStream' == $relationName) {
            $this->initCurrentApplicationCourseStreams();
            return;
        }
        if ('CurrentStreamLessonStream' == $relationName) {
            $this->initCurrentStreamLessonStreams();
            return;
        }
        if ('StreamUser' == $relationName) {
            $this->initStreamUsers();
            return;
        }
    }

    /**
     * Clears out the collCurrentApplicationCourseStreams collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentApplicationCourseStreams()
     */
    public function clearCurrentApplicationCourseStreams()
    {
        $this->collCurrentApplicationCourseStreams = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentApplicationCourseStreams collection loaded partially.
     */
    public function resetPartialCurrentApplicationCourseStreams($v = true)
    {
        $this->collCurrentApplicationCourseStreamsPartial = $v;
    }

    /**
     * Initializes the collCurrentApplicationCourseStreams collection.
     *
     * By default this just sets the collCurrentApplicationCourseStreams collection to an empty array (like clearcollCurrentApplicationCourseStreams());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentApplicationCourseStreams($overrideExisting = true)
    {
        if (null !== $this->collCurrentApplicationCourseStreams && !$overrideExisting) {
            return;
        }

        $collectionClassName = ApplicationTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentApplicationCourseStreams = new $collectionClassName;
        $this->collCurrentApplicationCourseStreams->setModel('\Models\Application');
    }

    /**
     * Gets an array of ChildApplication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCourseStream is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildApplication[] List of ChildApplication objects
     * @throws PropelException
     */
    public function getCurrentApplicationCourseStreams(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentApplicationCourseStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentApplicationCourseStreams || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentApplicationCourseStreams) {
                // return empty collection
                $this->initCurrentApplicationCourseStreams();
            } else {
                $collCurrentApplicationCourseStreams = ChildApplicationQuery::create(null, $criteria)
                    ->filterByCurrentCourseStreamApplication($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentApplicationCourseStreamsPartial && count($collCurrentApplicationCourseStreams)) {
                        $this->initCurrentApplicationCourseStreams(false);

                        foreach ($collCurrentApplicationCourseStreams as $obj) {
                            if (false == $this->collCurrentApplicationCourseStreams->contains($obj)) {
                                $this->collCurrentApplicationCourseStreams->append($obj);
                            }
                        }

                        $this->collCurrentApplicationCourseStreamsPartial = true;
                    }

                    return $collCurrentApplicationCourseStreams;
                }

                if ($partial && $this->collCurrentApplicationCourseStreams) {
                    foreach ($this->collCurrentApplicationCourseStreams as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentApplicationCourseStreams[] = $obj;
                        }
                    }
                }

                $this->collCurrentApplicationCourseStreams = $collCurrentApplicationCourseStreams;
                $this->collCurrentApplicationCourseStreamsPartial = false;
            }
        }

        return $this->collCurrentApplicationCourseStreams;
    }

    /**
     * Sets a collection of ChildApplication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentApplicationCourseStreams A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCourseStream The current object (for fluent API support)
     */
    public function setCurrentApplicationCourseStreams(Collection $currentApplicationCourseStreams, ConnectionInterface $con = null)
    {
        /** @var ChildApplication[] $currentApplicationCourseStreamsToDelete */
        $currentApplicationCourseStreamsToDelete = $this->getCurrentApplicationCourseStreams(new Criteria(), $con)->diff($currentApplicationCourseStreams);


        $this->currentApplicationCourseStreamsScheduledForDeletion = $currentApplicationCourseStreamsToDelete;

        foreach ($currentApplicationCourseStreamsToDelete as $currentApplicationCourseStreamRemoved) {
            $currentApplicationCourseStreamRemoved->setCurrentCourseStreamApplication(null);
        }

        $this->collCurrentApplicationCourseStreams = null;
        foreach ($currentApplicationCourseStreams as $currentApplicationCourseStream) {
            $this->addCurrentApplicationCourseStream($currentApplicationCourseStream);
        }

        $this->collCurrentApplicationCourseStreams = $currentApplicationCourseStreams;
        $this->collCurrentApplicationCourseStreamsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Application objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Application objects.
     * @throws PropelException
     */
    public function countCurrentApplicationCourseStreams(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentApplicationCourseStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentApplicationCourseStreams || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentApplicationCourseStreams) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentApplicationCourseStreams());
            }

            $query = ChildApplicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentCourseStreamApplication($this)
                ->count($con);
        }

        return count($this->collCurrentApplicationCourseStreams);
    }

    /**
     * Method called to associate a ChildApplication object to this object
     * through the ChildApplication foreign key attribute.
     *
     * @param  ChildApplication $l ChildApplication
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function addCurrentApplicationCourseStream(ChildApplication $l)
    {
        if ($this->collCurrentApplicationCourseStreams === null) {
            $this->initCurrentApplicationCourseStreams();
            $this->collCurrentApplicationCourseStreamsPartial = true;
        }

        if (!$this->collCurrentApplicationCourseStreams->contains($l)) {
            $this->doAddCurrentApplicationCourseStream($l);

            if ($this->currentApplicationCourseStreamsScheduledForDeletion and $this->currentApplicationCourseStreamsScheduledForDeletion->contains($l)) {
                $this->currentApplicationCourseStreamsScheduledForDeletion->remove($this->currentApplicationCourseStreamsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildApplication $currentApplicationCourseStream The ChildApplication object to add.
     */
    protected function doAddCurrentApplicationCourseStream(ChildApplication $currentApplicationCourseStream)
    {
        $this->collCurrentApplicationCourseStreams[]= $currentApplicationCourseStream;
        $currentApplicationCourseStream->setCurrentCourseStreamApplication($this);
    }

    /**
     * @param  ChildApplication $currentApplicationCourseStream The ChildApplication object to remove.
     * @return $this|ChildCourseStream The current object (for fluent API support)
     */
    public function removeCurrentApplicationCourseStream(ChildApplication $currentApplicationCourseStream)
    {
        if ($this->getCurrentApplicationCourseStreams()->contains($currentApplicationCourseStream)) {
            $pos = $this->collCurrentApplicationCourseStreams->search($currentApplicationCourseStream);
            $this->collCurrentApplicationCourseStreams->remove($pos);
            if (null === $this->currentApplicationCourseStreamsScheduledForDeletion) {
                $this->currentApplicationCourseStreamsScheduledForDeletion = clone $this->collCurrentApplicationCourseStreams;
                $this->currentApplicationCourseStreamsScheduledForDeletion->clear();
            }
            $this->currentApplicationCourseStreamsScheduledForDeletion[]= $currentApplicationCourseStream;
            $currentApplicationCourseStream->setCurrentCourseStreamApplication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this CourseStream is new, it will return
     * an empty collection; or if this CourseStream has previously
     * been saved, it will retrieve related CurrentApplicationCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in CourseStream.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildApplication[] List of ChildApplication objects
     */
    public function getCurrentApplicationCourseStreamsJoinCurrentApplicationStatus(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildApplicationQuery::create(null, $criteria);
        $query->joinWith('CurrentApplicationStatus', $joinBehavior);

        return $this->getCurrentApplicationCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this CourseStream is new, it will return
     * an empty collection; or if this CourseStream has previously
     * been saved, it will retrieve related CurrentApplicationCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in CourseStream.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildApplication[] List of ChildApplication objects
     */
    public function getCurrentApplicationCourseStreamsJoinCurrentCourseApplication(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildApplicationQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseApplication', $joinBehavior);

        return $this->getCurrentApplicationCourseStreams($query, $con);
    }

    /**
     * Clears out the collCurrentStreamLessonStreams collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentStreamLessonStreams()
     */
    public function clearCurrentStreamLessonStreams()
    {
        $this->collCurrentStreamLessonStreams = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentStreamLessonStreams collection loaded partially.
     */
    public function resetPartialCurrentStreamLessonStreams($v = true)
    {
        $this->collCurrentStreamLessonStreamsPartial = $v;
    }

    /**
     * Initializes the collCurrentStreamLessonStreams collection.
     *
     * By default this just sets the collCurrentStreamLessonStreams collection to an empty array (like clearcollCurrentStreamLessonStreams());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentStreamLessonStreams($overrideExisting = true)
    {
        if (null !== $this->collCurrentStreamLessonStreams && !$overrideExisting) {
            return;
        }

        $collectionClassName = LessonTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentStreamLessonStreams = new $collectionClassName;
        $this->collCurrentStreamLessonStreams->setModel('\Models\Lesson');
    }

    /**
     * Gets an array of ChildLesson objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCourseStream is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLesson[] List of ChildLesson objects
     * @throws PropelException
     */
    public function getCurrentStreamLessonStreams(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentStreamLessonStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentStreamLessonStreams || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentStreamLessonStreams) {
                // return empty collection
                $this->initCurrentStreamLessonStreams();
            } else {
                $collCurrentStreamLessonStreams = ChildLessonQuery::create(null, $criteria)
                    ->filterByCurrentStreamStreamLesson($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentStreamLessonStreamsPartial && count($collCurrentStreamLessonStreams)) {
                        $this->initCurrentStreamLessonStreams(false);

                        foreach ($collCurrentStreamLessonStreams as $obj) {
                            if (false == $this->collCurrentStreamLessonStreams->contains($obj)) {
                                $this->collCurrentStreamLessonStreams->append($obj);
                            }
                        }

                        $this->collCurrentStreamLessonStreamsPartial = true;
                    }

                    return $collCurrentStreamLessonStreams;
                }

                if ($partial && $this->collCurrentStreamLessonStreams) {
                    foreach ($this->collCurrentStreamLessonStreams as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentStreamLessonStreams[] = $obj;
                        }
                    }
                }

                $this->collCurrentStreamLessonStreams = $collCurrentStreamLessonStreams;
                $this->collCurrentStreamLessonStreamsPartial = false;
            }
        }

        return $this->collCurrentStreamLessonStreams;
    }

    /**
     * Sets a collection of ChildLesson objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentStreamLessonStreams A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCourseStream The current object (for fluent API support)
     */
    public function setCurrentStreamLessonStreams(Collection $currentStreamLessonStreams, ConnectionInterface $con = null)
    {
        /** @var ChildLesson[] $currentStreamLessonStreamsToDelete */
        $currentStreamLessonStreamsToDelete = $this->getCurrentStreamLessonStreams(new Criteria(), $con)->diff($currentStreamLessonStreams);


        $this->currentStreamLessonStreamsScheduledForDeletion = $currentStreamLessonStreamsToDelete;

        foreach ($currentStreamLessonStreamsToDelete as $currentStreamLessonStreamRemoved) {
            $currentStreamLessonStreamRemoved->setCurrentStreamStreamLesson(null);
        }

        $this->collCurrentStreamLessonStreams = null;
        foreach ($currentStreamLessonStreams as $currentStreamLessonStream) {
            $this->addCurrentStreamLessonStream($currentStreamLessonStream);
        }

        $this->collCurrentStreamLessonStreams = $currentStreamLessonStreams;
        $this->collCurrentStreamLessonStreamsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Lesson objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Lesson objects.
     * @throws PropelException
     */
    public function countCurrentStreamLessonStreams(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentStreamLessonStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentStreamLessonStreams || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentStreamLessonStreams) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentStreamLessonStreams());
            }

            $query = ChildLessonQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentStreamStreamLesson($this)
                ->count($con);
        }

        return count($this->collCurrentStreamLessonStreams);
    }

    /**
     * Method called to associate a ChildLesson object to this object
     * through the ChildLesson foreign key attribute.
     *
     * @param  ChildLesson $l ChildLesson
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function addCurrentStreamLessonStream(ChildLesson $l)
    {
        if ($this->collCurrentStreamLessonStreams === null) {
            $this->initCurrentStreamLessonStreams();
            $this->collCurrentStreamLessonStreamsPartial = true;
        }

        if (!$this->collCurrentStreamLessonStreams->contains($l)) {
            $this->doAddCurrentStreamLessonStream($l);

            if ($this->currentStreamLessonStreamsScheduledForDeletion and $this->currentStreamLessonStreamsScheduledForDeletion->contains($l)) {
                $this->currentStreamLessonStreamsScheduledForDeletion->remove($this->currentStreamLessonStreamsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLesson $currentStreamLessonStream The ChildLesson object to add.
     */
    protected function doAddCurrentStreamLessonStream(ChildLesson $currentStreamLessonStream)
    {
        $this->collCurrentStreamLessonStreams[]= $currentStreamLessonStream;
        $currentStreamLessonStream->setCurrentStreamStreamLesson($this);
    }

    /**
     * @param  ChildLesson $currentStreamLessonStream The ChildLesson object to remove.
     * @return $this|ChildCourseStream The current object (for fluent API support)
     */
    public function removeCurrentStreamLessonStream(ChildLesson $currentStreamLessonStream)
    {
        if ($this->getCurrentStreamLessonStreams()->contains($currentStreamLessonStream)) {
            $pos = $this->collCurrentStreamLessonStreams->search($currentStreamLessonStream);
            $this->collCurrentStreamLessonStreams->remove($pos);
            if (null === $this->currentStreamLessonStreamsScheduledForDeletion) {
                $this->currentStreamLessonStreamsScheduledForDeletion = clone $this->collCurrentStreamLessonStreams;
                $this->currentStreamLessonStreamsScheduledForDeletion->clear();
            }
            $this->currentStreamLessonStreamsScheduledForDeletion[]= clone $currentStreamLessonStream;
            $currentStreamLessonStream->setCurrentStreamStreamLesson(null);
        }

        return $this;
    }

    /**
     * Clears out the collStreamUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addStreamUsers()
     */
    public function clearStreamUsers()
    {
        $this->collStreamUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collStreamUsers collection loaded partially.
     */
    public function resetPartialStreamUsers($v = true)
    {
        $this->collStreamUsersPartial = $v;
    }

    /**
     * Initializes the collStreamUsers collection.
     *
     * By default this just sets the collStreamUsers collection to an empty array (like clearcollStreamUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStreamUsers($overrideExisting = true)
    {
        if (null !== $this->collStreamUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = StreamUserTableMap::getTableMap()->getCollectionClassName();

        $this->collStreamUsers = new $collectionClassName;
        $this->collStreamUsers->setModel('\Models\StreamUser');
    }

    /**
     * Gets an array of ChildStreamUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCourseStream is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildStreamUser[] List of ChildStreamUser objects
     * @throws PropelException
     */
    public function getStreamUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collStreamUsersPartial && !$this->isNew();
        if (null === $this->collStreamUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStreamUsers) {
                // return empty collection
                $this->initStreamUsers();
            } else {
                $collStreamUsers = ChildStreamUserQuery::create(null, $criteria)
                    ->filterByCourseStream($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collStreamUsersPartial && count($collStreamUsers)) {
                        $this->initStreamUsers(false);

                        foreach ($collStreamUsers as $obj) {
                            if (false == $this->collStreamUsers->contains($obj)) {
                                $this->collStreamUsers->append($obj);
                            }
                        }

                        $this->collStreamUsersPartial = true;
                    }

                    return $collStreamUsers;
                }

                if ($partial && $this->collStreamUsers) {
                    foreach ($this->collStreamUsers as $obj) {
                        if ($obj->isNew()) {
                            $collStreamUsers[] = $obj;
                        }
                    }
                }

                $this->collStreamUsers = $collStreamUsers;
                $this->collStreamUsersPartial = false;
            }
        }

        return $this->collStreamUsers;
    }

    /**
     * Sets a collection of ChildStreamUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $streamUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCourseStream The current object (for fluent API support)
     */
    public function setStreamUsers(Collection $streamUsers, ConnectionInterface $con = null)
    {
        /** @var ChildStreamUser[] $streamUsersToDelete */
        $streamUsersToDelete = $this->getStreamUsers(new Criteria(), $con)->diff($streamUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->streamUsersScheduledForDeletion = clone $streamUsersToDelete;

        foreach ($streamUsersToDelete as $streamUserRemoved) {
            $streamUserRemoved->setCourseStream(null);
        }

        $this->collStreamUsers = null;
        foreach ($streamUsers as $streamUser) {
            $this->addStreamUser($streamUser);
        }

        $this->collStreamUsers = $streamUsers;
        $this->collStreamUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StreamUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related StreamUser objects.
     * @throws PropelException
     */
    public function countStreamUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collStreamUsersPartial && !$this->isNew();
        if (null === $this->collStreamUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStreamUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStreamUsers());
            }

            $query = ChildStreamUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCourseStream($this)
                ->count($con);
        }

        return count($this->collStreamUsers);
    }

    /**
     * Method called to associate a ChildStreamUser object to this object
     * through the ChildStreamUser foreign key attribute.
     *
     * @param  ChildStreamUser $l ChildStreamUser
     * @return $this|\Models\CourseStream The current object (for fluent API support)
     */
    public function addStreamUser(ChildStreamUser $l)
    {
        if ($this->collStreamUsers === null) {
            $this->initStreamUsers();
            $this->collStreamUsersPartial = true;
        }

        if (!$this->collStreamUsers->contains($l)) {
            $this->doAddStreamUser($l);

            if ($this->streamUsersScheduledForDeletion and $this->streamUsersScheduledForDeletion->contains($l)) {
                $this->streamUsersScheduledForDeletion->remove($this->streamUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildStreamUser $streamUser The ChildStreamUser object to add.
     */
    protected function doAddStreamUser(ChildStreamUser $streamUser)
    {
        $this->collStreamUsers[]= $streamUser;
        $streamUser->setCourseStream($this);
    }

    /**
     * @param  ChildStreamUser $streamUser The ChildStreamUser object to remove.
     * @return $this|ChildCourseStream The current object (for fluent API support)
     */
    public function removeStreamUser(ChildStreamUser $streamUser)
    {
        if ($this->getStreamUsers()->contains($streamUser)) {
            $pos = $this->collStreamUsers->search($streamUser);
            $this->collStreamUsers->remove($pos);
            if (null === $this->streamUsersScheduledForDeletion) {
                $this->streamUsersScheduledForDeletion = clone $this->collStreamUsers;
                $this->streamUsersScheduledForDeletion->clear();
            }
            $this->streamUsersScheduledForDeletion[]= clone $streamUser;
            $streamUser->setCourseStream(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this CourseStream is new, it will return
     * an empty collection; or if this CourseStream has previously
     * been saved, it will retrieve related StreamUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in CourseStream.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStreamUser[] List of ChildStreamUser objects
     */
    public function getStreamUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStreamUserQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getStreamUsers($query, $con);
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collUsers crossRef collection.
     *
     * By default this just sets the collUsers collection to an empty collection (like clearUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUsers()
    {
        $collectionClassName = StreamUserTableMap::getTableMap()->getCollectionClassName();

        $this->collUsers = new $collectionClassName;
        $this->collUsersPartial = true;
        $this->collUsers->setModel('\Models\User');
    }

    /**
     * Checks if the collUsers collection is loaded.
     *
     * @return bool
     */
    public function isUsersLoaded()
    {
        return null !== $this->collUsers;
    }

    /**
     * Gets a collection of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the stream_user cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCourseStream is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUsers) {
                    $this->initUsers();
                }
            } else {

                $query = ChildUserQuery::create(null, $criteria)
                    ->filterByCourseStream($this);
                $collUsers = $query->find($con);
                if (null !== $criteria) {
                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collUsers as $obj) {
                        if (!$collUsers->contains($obj)) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the stream_user cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $users A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildCourseStream The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        $this->clearUsers();
        $currentUsers = $this->getUsers();

        $usersScheduledForDeletion = $currentUsers->diff($users);

        foreach ($usersScheduledForDeletion as $toDelete) {
            $this->removeUser($toDelete);
        }

        foreach ($users as $user) {
            if (!$currentUsers->contains($user)) {
                $this->doAddUser($user);
            }
        }

        $this->collUsersPartial = false;
        $this->collUsers = $users;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the stream_user cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUsers());
                }

                $query = ChildUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCourseStream($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a ChildUser to this object
     * through the stream_user cross reference table.
     *
     * @param ChildUser $user
     * @return ChildCourseStream The current object (for fluent API support)
     */
    public function addUser(ChildUser $user)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
        }

        if (!$this->getUsers()->contains($user)) {
            // only add it if the **same** object is not already associated
            $this->collUsers->push($user);
            $this->doAddUser($user);
        }

        return $this;
    }

    /**
     *
     * @param ChildUser $user
     */
    protected function doAddUser(ChildUser $user)
    {
        $streamUser = new ChildStreamUser();

        $streamUser->setUser($user);

        $streamUser->setCourseStream($this);

        $this->addStreamUser($streamUser);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->isCourseStreamsLoaded()) {
            $user->initCourseStreams();
            $user->getCourseStreams()->push($this);
        } elseif (!$user->getCourseStreams()->contains($this)) {
            $user->getCourseStreams()->push($this);
        }

    }

    /**
     * Remove user of this object
     * through the stream_user cross reference table.
     *
     * @param ChildUser $user
     * @return ChildCourseStream The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) {
            $streamUser = new ChildStreamUser();
            $streamUser->setUser($user);
            if ($user->isCourseStreamsLoaded()) {
                //remove the back reference if available
                $user->getCourseStreams()->removeObject($this);
            }

            $streamUser->setCourseStream($this);
            $this->removeStreamUser(clone $streamUser);
            $streamUser->clear();

            $this->collUsers->remove($this->collUsers->search($user));

            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }

            $this->usersScheduledForDeletion->push($user);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aCurrentCourseStreamBranch) {
            $this->aCurrentCourseStreamBranch->removeCurrentBranchCourseStream($this);
        }
        if (null !== $this->aCurrentCourseStreamCurrency) {
            $this->aCurrentCourseStreamCurrency->removeCurrentCurrencyCourseStream($this);
        }
        if (null !== $this->aCurrentCourseCourseStream) {
            $this->aCurrentCourseCourseStream->removeCurrentCourseStreamCourse($this);
        }
        if (null !== $this->aCurrentCourseCourseStreamStatus) {
            $this->aCurrentCourseCourseStreamStatus->removeCurrentCourseStreamCourseStatus($this);
        }
        if (null !== $this->aCurrentCourseStreamInstructor) {
            $this->aCurrentCourseStreamInstructor->removeCurrentInstructorCourseStream($this);
        }
        $this->id = null;
        $this->name = null;
        $this->description = null;
        $this->number_of_places = null;
        $this->notes = null;
        $this->starts_at = null;
        $this->ends_at = null;
        $this->show_on_website = null;
        $this->cost = null;
        $this->branch_id = null;
        $this->currency_id = null;
        $this->course_id = null;
        $this->course_stream_status_id = null;
        $this->instructor_id = null;
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
            if ($this->collCurrentApplicationCourseStreams) {
                foreach ($this->collCurrentApplicationCourseStreams as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentStreamLessonStreams) {
                foreach ($this->collCurrentStreamLessonStreams as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStreamUsers) {
                foreach ($this->collStreamUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCurrentApplicationCourseStreams = null;
        $this->collCurrentStreamLessonStreams = null;
        $this->collStreamUsers = null;
        $this->collUsers = null;
        $this->aCurrentCourseStreamBranch = null;
        $this->aCurrentCourseStreamCurrency = null;
        $this->aCurrentCourseCourseStream = null;
        $this->aCurrentCourseCourseStreamStatus = null;
        $this->aCurrentCourseStreamInstructor = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CourseStreamTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildCourseStream The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[CourseStreamTableMap::COL_UPDATED_AT] = true;

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
