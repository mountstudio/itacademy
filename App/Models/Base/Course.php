<?php

namespace Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Application as ChildApplication;
use Models\ApplicationQuery as ChildApplicationQuery;
use Models\Course as ChildCourse;
use Models\CourseQuery as ChildCourseQuery;
use Models\CourseSkill as ChildCourseSkill;
use Models\CourseSkillQuery as ChildCourseSkillQuery;
use Models\CourseStream as ChildCourseStream;
use Models\CourseStreamQuery as ChildCourseStreamQuery;
use Models\Map\ApplicationTableMap;
use Models\Map\CourseSkillTableMap;
use Models\Map\CourseStreamTableMap;
use Models\Map\CourseTableMap;
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
 * Base class that represents a row from the 'course' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class Course implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\CourseTableMap';


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
     * The value for the alt_url field.
     *
     * @var        string
     */
    protected $alt_url;

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
     * The value for the title field.
     *
     * @var        string
     */
    protected $title;

    /**
     * The value for the context field.
     *
     * @var        string
     */
    protected $context;

    /**
     * The value for the notes field.
     *
     * @var        string
     */
    protected $notes;

    /**
     * The value for the use_notes field.
     *
     * @var        string
     */
    protected $use_notes;

    /**
     * The value for the uses field.
     *
     * @var        \Core\Course\Uses
     */
    protected $uses;

    /**
     * The unserialized $uses value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $uses_unserialized;

    /**
     * The value for the meta_description field.
     *
     * @var        string
     */
    protected $meta_description;

    /**
     * The value for the meta_keywords field.
     *
     * @var        string
     */
    protected $meta_keywords;

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
     * @var        ObjectCollection|ChildApplication[] Collection to store aggregation of ChildApplication objects.
     */
    protected $collCurrentApplicationCourses;
    protected $collCurrentApplicationCoursesPartial;

    /**
     * @var        ObjectCollection|ChildCourseStream[] Collection to store aggregation of ChildCourseStream objects.
     */
    protected $collCurrentCourseStreamCourses;
    protected $collCurrentCourseStreamCoursesPartial;

    /**
     * @var        ObjectCollection|ChildCourseSkill[] Collection to store aggregation of ChildCourseSkill objects.
     */
    protected $collCurrentCourseCourseSkills;
    protected $collCurrentCourseCourseSkillsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildApplication[]
     */
    protected $currentApplicationCoursesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCourseStream[]
     */
    protected $currentCourseStreamCoursesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCourseSkill[]
     */
    protected $currentCourseCourseSkillsScheduledForDeletion = null;

    /**
     * Initializes internal state of Models\Base\Course object.
     */
    public function __construct()
    {
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
     * Compares this with another <code>Course</code> instance.  If
     * <code>obj</code> is an instance of <code>Course</code>, delegates to
     * <code>equals(Course)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Course The current object, for fluid interface
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
     * Get the [alt_url] column value.
     *
     * @return string
     */
    public function getAltUrl()
    {
        return $this->alt_url;
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
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [context] column value.
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
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
     * Get the [use_notes] column value.
     *
     * @return string
     */
    public function getUseNotes()
    {
        return $this->use_notes;
    }

    /**
     * Get the [uses] column value.
     *
     * @return \Core\Course\Uses
     */
    public function getUses()
    {
        if (null == $this->uses_unserialized && is_resource($this->uses)) {
            if ($serialisedString = stream_get_contents($this->uses)) {
                $this->uses_unserialized = unserialize($serialisedString);
            }
        }

        return $this->uses_unserialized;
    }

    /**
     * Get the [meta_description] column value.
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Get the [meta_keywords] column value.
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
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
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CourseTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[CourseTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[CourseTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [alt_url] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setAltUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alt_url !== $v) {
            $this->alt_url = $v;
            $this->modifiedColumns[CourseTableMap::COL_ALT_URL] = true;
        }

        return $this;
    } // setAltUrl()

    /**
     * Set the value of [logo_name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setLogoName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->logo_name !== $v) {
            $this->logo_name = $v;
            $this->modifiedColumns[CourseTableMap::COL_LOGO_NAME] = true;
        }

        return $this;
    } // setLogoName()

    /**
     * Set the value of [cover_name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setCoverName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cover_name !== $v) {
            $this->cover_name = $v;
            $this->modifiedColumns[CourseTableMap::COL_COVER_NAME] = true;
        }

        return $this;
    } // setCoverName()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[CourseTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [context] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setContext($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->context !== $v) {
            $this->context = $v;
            $this->modifiedColumns[CourseTableMap::COL_CONTEXT] = true;
        }

        return $this;
    } // setContext()

    /**
     * Set the value of [notes] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setNotes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->notes !== $v) {
            $this->notes = $v;
            $this->modifiedColumns[CourseTableMap::COL_NOTES] = true;
        }

        return $this;
    } // setNotes()

    /**
     * Set the value of [use_notes] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setUseNotes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->use_notes !== $v) {
            $this->use_notes = $v;
            $this->modifiedColumns[CourseTableMap::COL_USE_NOTES] = true;
        }

        return $this;
    } // setUseNotes()

    /**
     * Set the value of [uses] column.
     *
     * @param \Core\Course\Uses $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setUses($v)
    {
        if (null === $this->uses || stream_get_contents($this->uses) !== serialize($v)) {
            $this->uses_unserialized = $v;
            $this->uses = fopen('php://memory', 'r+');
            fwrite($this->uses, serialize($v));
            $this->modifiedColumns[CourseTableMap::COL_USES] = true;
        }
        rewind($this->uses);

        return $this;
    } // setUses()

    /**
     * Set the value of [meta_description] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setMetaDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->meta_description !== $v) {
            $this->meta_description = $v;
            $this->modifiedColumns[CourseTableMap::COL_META_DESCRIPTION] = true;
        }

        return $this;
    } // setMetaDescription()

    /**
     * Set the value of [meta_keywords] column.
     *
     * @param string $v new value
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setMetaKeywords($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->meta_keywords !== $v) {
            $this->meta_keywords = $v;
            $this->modifiedColumns[CourseTableMap::COL_META_KEYWORDS] = true;
        }

        return $this;
    } // setMetaKeywords()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CourseTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CourseTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CourseTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CourseTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CourseTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CourseTableMap::translateFieldName('AltUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alt_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CourseTableMap::translateFieldName('LogoName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->logo_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CourseTableMap::translateFieldName('CoverName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cover_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CourseTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CourseTableMap::translateFieldName('Context', TableMap::TYPE_PHPNAME, $indexType)];
            $this->context = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CourseTableMap::translateFieldName('Notes', TableMap::TYPE_PHPNAME, $indexType)];
            $this->notes = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CourseTableMap::translateFieldName('UseNotes', TableMap::TYPE_PHPNAME, $indexType)];
            $this->use_notes = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CourseTableMap::translateFieldName('Uses', TableMap::TYPE_PHPNAME, $indexType)];
            if (null !== $col) {
                $this->uses = fopen('php://memory', 'r+');
                fwrite($this->uses, $col);
                rewind($this->uses);
            } else {
                $this->uses = null;
            }

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CourseTableMap::translateFieldName('MetaDescription', TableMap::TYPE_PHPNAME, $indexType)];
            $this->meta_description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CourseTableMap::translateFieldName('MetaKeywords', TableMap::TYPE_PHPNAME, $indexType)];
            $this->meta_keywords = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : CourseTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : CourseTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 15; // 15 = CourseTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Course'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(CourseTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCourseQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCurrentApplicationCourses = null;

            $this->collCurrentCourseStreamCourses = null;

            $this->collCurrentCourseCourseSkills = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Course::setDeleted()
     * @see Course::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CourseTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCourseQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(CourseTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(CourseTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CourseTableMap::COL_UPDATED_AT)) {
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
                CourseTableMap::addInstanceToPool($this);
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

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                // Rewind the uses LOB column, since PDO does not rewind after inserting value.
                if ($this->uses !== null && is_resource($this->uses)) {
                    rewind($this->uses);
                }

                $this->resetModified();
            }

            if ($this->currentApplicationCoursesScheduledForDeletion !== null) {
                if (!$this->currentApplicationCoursesScheduledForDeletion->isEmpty()) {
                    foreach ($this->currentApplicationCoursesScheduledForDeletion as $currentApplicationCourse) {
                        // need to save related object because we set the relation to null
                        $currentApplicationCourse->save($con);
                    }
                    $this->currentApplicationCoursesScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentApplicationCourses !== null) {
                foreach ($this->collCurrentApplicationCourses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentCourseStreamCoursesScheduledForDeletion !== null) {
                if (!$this->currentCourseStreamCoursesScheduledForDeletion->isEmpty()) {
                    \Models\CourseStreamQuery::create()
                        ->filterByPrimaryKeys($this->currentCourseStreamCoursesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentCourseStreamCoursesScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentCourseStreamCourses !== null) {
                foreach ($this->collCurrentCourseStreamCourses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentCourseCourseSkillsScheduledForDeletion !== null) {
                if (!$this->currentCourseCourseSkillsScheduledForDeletion->isEmpty()) {
                    \Models\CourseSkillQuery::create()
                        ->filterByPrimaryKeys($this->currentCourseCourseSkillsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentCourseCourseSkillsScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentCourseCourseSkills !== null) {
                foreach ($this->collCurrentCourseCourseSkills as $referrerFK) {
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

        $this->modifiedColumns[CourseTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CourseTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CourseTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_ALT_URL)) {
            $modifiedColumns[':p' . $index++]  = '`alt_url`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_LOGO_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`logo_name`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_COVER_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`cover_name`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_CONTEXT)) {
            $modifiedColumns[':p' . $index++]  = '`context`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_NOTES)) {
            $modifiedColumns[':p' . $index++]  = '`notes`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_USE_NOTES)) {
            $modifiedColumns[':p' . $index++]  = '`use_notes`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_USES)) {
            $modifiedColumns[':p' . $index++]  = '`uses`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_META_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`meta_description`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_META_KEYWORDS)) {
            $modifiedColumns[':p' . $index++]  = '`meta_keywords`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(CourseTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `course` (%s) VALUES (%s)',
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
                    case '`alt_url`':
                        $stmt->bindValue($identifier, $this->alt_url, PDO::PARAM_STR);
                        break;
                    case '`logo_name`':
                        $stmt->bindValue($identifier, $this->logo_name, PDO::PARAM_STR);
                        break;
                    case '`cover_name`':
                        $stmt->bindValue($identifier, $this->cover_name, PDO::PARAM_STR);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`context`':
                        $stmt->bindValue($identifier, $this->context, PDO::PARAM_STR);
                        break;
                    case '`notes`':
                        $stmt->bindValue($identifier, $this->notes, PDO::PARAM_STR);
                        break;
                    case '`use_notes`':
                        $stmt->bindValue($identifier, $this->use_notes, PDO::PARAM_STR);
                        break;
                    case '`uses`':
                        if (is_resource($this->uses)) {
                            rewind($this->uses);
                        }
                        $stmt->bindValue($identifier, $this->uses, PDO::PARAM_LOB);
                        break;
                    case '`meta_description`':
                        $stmt->bindValue($identifier, $this->meta_description, PDO::PARAM_STR);
                        break;
                    case '`meta_keywords`':
                        $stmt->bindValue($identifier, $this->meta_keywords, PDO::PARAM_STR);
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
        $pos = CourseTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAltUrl();
                break;
            case 4:
                return $this->getLogoName();
                break;
            case 5:
                return $this->getCoverName();
                break;
            case 6:
                return $this->getTitle();
                break;
            case 7:
                return $this->getContext();
                break;
            case 8:
                return $this->getNotes();
                break;
            case 9:
                return $this->getUseNotes();
                break;
            case 10:
                return $this->getUses();
                break;
            case 11:
                return $this->getMetaDescription();
                break;
            case 12:
                return $this->getMetaKeywords();
                break;
            case 13:
                return $this->getCreatedAt();
                break;
            case 14:
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

        if (isset($alreadyDumpedObjects['Course'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Course'][$this->hashCode()] = true;
        $keys = CourseTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getAltUrl(),
            $keys[4] => $this->getLogoName(),
            $keys[5] => $this->getCoverName(),
            $keys[6] => $this->getTitle(),
            $keys[7] => $this->getContext(),
            $keys[8] => $this->getNotes(),
            $keys[9] => $this->getUseNotes(),
            $keys[10] => $this->getUses(),
            $keys[11] => $this->getMetaDescription(),
            $keys[12] => $this->getMetaKeywords(),
            $keys[13] => $this->getCreatedAt(),
            $keys[14] => $this->getUpdatedAt(),
        );
        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('c');
        }

        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCurrentApplicationCourses) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'applications';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'applications';
                        break;
                    default:
                        $key = 'CurrentApplicationCourses';
                }

                $result[$key] = $this->collCurrentApplicationCourses->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentCourseStreamCourses) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'courseStreams';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'course_streams';
                        break;
                    default:
                        $key = 'CurrentCourseStreamCourses';
                }

                $result[$key] = $this->collCurrentCourseStreamCourses->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentCourseCourseSkills) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'courseSkills';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'course_skills';
                        break;
                    default:
                        $key = 'CurrentCourseCourseSkills';
                }

                $result[$key] = $this->collCurrentCourseCourseSkills->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\Course
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CourseTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Course
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
                $this->setAltUrl($value);
                break;
            case 4:
                $this->setLogoName($value);
                break;
            case 5:
                $this->setCoverName($value);
                break;
            case 6:
                $this->setTitle($value);
                break;
            case 7:
                $this->setContext($value);
                break;
            case 8:
                $this->setNotes($value);
                break;
            case 9:
                $this->setUseNotes($value);
                break;
            case 10:
                $this->setUses($value);
                break;
            case 11:
                $this->setMetaDescription($value);
                break;
            case 12:
                $this->setMetaKeywords($value);
                break;
            case 13:
                $this->setCreatedAt($value);
                break;
            case 14:
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
        $keys = CourseTableMap::getFieldNames($keyType);

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
            $this->setAltUrl($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLogoName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCoverName($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setTitle($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setContext($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setNotes($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setUseNotes($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setUses($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setMetaDescription($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setMetaKeywords($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setCreatedAt($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setUpdatedAt($arr[$keys[14]]);
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
     * @return $this|\Models\Course The current object, for fluid interface
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
        $criteria = new Criteria(CourseTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CourseTableMap::COL_ID)) {
            $criteria->add(CourseTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(CourseTableMap::COL_NAME)) {
            $criteria->add(CourseTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(CourseTableMap::COL_DESCRIPTION)) {
            $criteria->add(CourseTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(CourseTableMap::COL_ALT_URL)) {
            $criteria->add(CourseTableMap::COL_ALT_URL, $this->alt_url);
        }
        if ($this->isColumnModified(CourseTableMap::COL_LOGO_NAME)) {
            $criteria->add(CourseTableMap::COL_LOGO_NAME, $this->logo_name);
        }
        if ($this->isColumnModified(CourseTableMap::COL_COVER_NAME)) {
            $criteria->add(CourseTableMap::COL_COVER_NAME, $this->cover_name);
        }
        if ($this->isColumnModified(CourseTableMap::COL_TITLE)) {
            $criteria->add(CourseTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(CourseTableMap::COL_CONTEXT)) {
            $criteria->add(CourseTableMap::COL_CONTEXT, $this->context);
        }
        if ($this->isColumnModified(CourseTableMap::COL_NOTES)) {
            $criteria->add(CourseTableMap::COL_NOTES, $this->notes);
        }
        if ($this->isColumnModified(CourseTableMap::COL_USE_NOTES)) {
            $criteria->add(CourseTableMap::COL_USE_NOTES, $this->use_notes);
        }
        if ($this->isColumnModified(CourseTableMap::COL_USES)) {
            $criteria->add(CourseTableMap::COL_USES, $this->uses);
        }
        if ($this->isColumnModified(CourseTableMap::COL_META_DESCRIPTION)) {
            $criteria->add(CourseTableMap::COL_META_DESCRIPTION, $this->meta_description);
        }
        if ($this->isColumnModified(CourseTableMap::COL_META_KEYWORDS)) {
            $criteria->add(CourseTableMap::COL_META_KEYWORDS, $this->meta_keywords);
        }
        if ($this->isColumnModified(CourseTableMap::COL_CREATED_AT)) {
            $criteria->add(CourseTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(CourseTableMap::COL_UPDATED_AT)) {
            $criteria->add(CourseTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildCourseQuery::create();
        $criteria->add(CourseTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Course (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setAltUrl($this->getAltUrl());
        $copyObj->setLogoName($this->getLogoName());
        $copyObj->setCoverName($this->getCoverName());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setContext($this->getContext());
        $copyObj->setNotes($this->getNotes());
        $copyObj->setUseNotes($this->getUseNotes());
        $copyObj->setUses($this->getUses());
        $copyObj->setMetaDescription($this->getMetaDescription());
        $copyObj->setMetaKeywords($this->getMetaKeywords());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCurrentApplicationCourses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentApplicationCourse($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentCourseStreamCourses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentCourseStreamCourse($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentCourseCourseSkills() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentCourseCourseSkill($relObj->copy($deepCopy));
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
     * @return \Models\Course Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CurrentApplicationCourse' == $relationName) {
            $this->initCurrentApplicationCourses();
            return;
        }
        if ('CurrentCourseStreamCourse' == $relationName) {
            $this->initCurrentCourseStreamCourses();
            return;
        }
        if ('CurrentCourseCourseSkill' == $relationName) {
            $this->initCurrentCourseCourseSkills();
            return;
        }
    }

    /**
     * Clears out the collCurrentApplicationCourses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentApplicationCourses()
     */
    public function clearCurrentApplicationCourses()
    {
        $this->collCurrentApplicationCourses = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentApplicationCourses collection loaded partially.
     */
    public function resetPartialCurrentApplicationCourses($v = true)
    {
        $this->collCurrentApplicationCoursesPartial = $v;
    }

    /**
     * Initializes the collCurrentApplicationCourses collection.
     *
     * By default this just sets the collCurrentApplicationCourses collection to an empty array (like clearcollCurrentApplicationCourses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentApplicationCourses($overrideExisting = true)
    {
        if (null !== $this->collCurrentApplicationCourses && !$overrideExisting) {
            return;
        }

        $collectionClassName = ApplicationTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentApplicationCourses = new $collectionClassName;
        $this->collCurrentApplicationCourses->setModel('\Models\Application');
    }

    /**
     * Gets an array of ChildApplication objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCourse is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildApplication[] List of ChildApplication objects
     * @throws PropelException
     */
    public function getCurrentApplicationCourses(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentApplicationCoursesPartial && !$this->isNew();
        if (null === $this->collCurrentApplicationCourses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentApplicationCourses) {
                // return empty collection
                $this->initCurrentApplicationCourses();
            } else {
                $collCurrentApplicationCourses = ChildApplicationQuery::create(null, $criteria)
                    ->filterByCurrentCourseApplication($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentApplicationCoursesPartial && count($collCurrentApplicationCourses)) {
                        $this->initCurrentApplicationCourses(false);

                        foreach ($collCurrentApplicationCourses as $obj) {
                            if (false == $this->collCurrentApplicationCourses->contains($obj)) {
                                $this->collCurrentApplicationCourses->append($obj);
                            }
                        }

                        $this->collCurrentApplicationCoursesPartial = true;
                    }

                    return $collCurrentApplicationCourses;
                }

                if ($partial && $this->collCurrentApplicationCourses) {
                    foreach ($this->collCurrentApplicationCourses as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentApplicationCourses[] = $obj;
                        }
                    }
                }

                $this->collCurrentApplicationCourses = $collCurrentApplicationCourses;
                $this->collCurrentApplicationCoursesPartial = false;
            }
        }

        return $this->collCurrentApplicationCourses;
    }

    /**
     * Sets a collection of ChildApplication objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentApplicationCourses A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCourse The current object (for fluent API support)
     */
    public function setCurrentApplicationCourses(Collection $currentApplicationCourses, ConnectionInterface $con = null)
    {
        /** @var ChildApplication[] $currentApplicationCoursesToDelete */
        $currentApplicationCoursesToDelete = $this->getCurrentApplicationCourses(new Criteria(), $con)->diff($currentApplicationCourses);


        $this->currentApplicationCoursesScheduledForDeletion = $currentApplicationCoursesToDelete;

        foreach ($currentApplicationCoursesToDelete as $currentApplicationCourseRemoved) {
            $currentApplicationCourseRemoved->setCurrentCourseApplication(null);
        }

        $this->collCurrentApplicationCourses = null;
        foreach ($currentApplicationCourses as $currentApplicationCourse) {
            $this->addCurrentApplicationCourse($currentApplicationCourse);
        }

        $this->collCurrentApplicationCourses = $currentApplicationCourses;
        $this->collCurrentApplicationCoursesPartial = false;

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
    public function countCurrentApplicationCourses(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentApplicationCoursesPartial && !$this->isNew();
        if (null === $this->collCurrentApplicationCourses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentApplicationCourses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentApplicationCourses());
            }

            $query = ChildApplicationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentCourseApplication($this)
                ->count($con);
        }

        return count($this->collCurrentApplicationCourses);
    }

    /**
     * Method called to associate a ChildApplication object to this object
     * through the ChildApplication foreign key attribute.
     *
     * @param  ChildApplication $l ChildApplication
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function addCurrentApplicationCourse(ChildApplication $l)
    {
        if ($this->collCurrentApplicationCourses === null) {
            $this->initCurrentApplicationCourses();
            $this->collCurrentApplicationCoursesPartial = true;
        }

        if (!$this->collCurrentApplicationCourses->contains($l)) {
            $this->doAddCurrentApplicationCourse($l);

            if ($this->currentApplicationCoursesScheduledForDeletion and $this->currentApplicationCoursesScheduledForDeletion->contains($l)) {
                $this->currentApplicationCoursesScheduledForDeletion->remove($this->currentApplicationCoursesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildApplication $currentApplicationCourse The ChildApplication object to add.
     */
    protected function doAddCurrentApplicationCourse(ChildApplication $currentApplicationCourse)
    {
        $this->collCurrentApplicationCourses[]= $currentApplicationCourse;
        $currentApplicationCourse->setCurrentCourseApplication($this);
    }

    /**
     * @param  ChildApplication $currentApplicationCourse The ChildApplication object to remove.
     * @return $this|ChildCourse The current object (for fluent API support)
     */
    public function removeCurrentApplicationCourse(ChildApplication $currentApplicationCourse)
    {
        if ($this->getCurrentApplicationCourses()->contains($currentApplicationCourse)) {
            $pos = $this->collCurrentApplicationCourses->search($currentApplicationCourse);
            $this->collCurrentApplicationCourses->remove($pos);
            if (null === $this->currentApplicationCoursesScheduledForDeletion) {
                $this->currentApplicationCoursesScheduledForDeletion = clone $this->collCurrentApplicationCourses;
                $this->currentApplicationCoursesScheduledForDeletion->clear();
            }
            $this->currentApplicationCoursesScheduledForDeletion[]= $currentApplicationCourse;
            $currentApplicationCourse->setCurrentCourseApplication(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related CurrentApplicationCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildApplication[] List of ChildApplication objects
     */
    public function getCurrentApplicationCoursesJoinCurrentApplicationStatus(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildApplicationQuery::create(null, $criteria);
        $query->joinWith('CurrentApplicationStatus', $joinBehavior);

        return $this->getCurrentApplicationCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related CurrentApplicationCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildApplication[] List of ChildApplication objects
     */
    public function getCurrentApplicationCoursesJoinCurrentCourseStreamApplication(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildApplicationQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamApplication', $joinBehavior);

        return $this->getCurrentApplicationCourses($query, $con);
    }

    /**
     * Clears out the collCurrentCourseStreamCourses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentCourseStreamCourses()
     */
    public function clearCurrentCourseStreamCourses()
    {
        $this->collCurrentCourseStreamCourses = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentCourseStreamCourses collection loaded partially.
     */
    public function resetPartialCurrentCourseStreamCourses($v = true)
    {
        $this->collCurrentCourseStreamCoursesPartial = $v;
    }

    /**
     * Initializes the collCurrentCourseStreamCourses collection.
     *
     * By default this just sets the collCurrentCourseStreamCourses collection to an empty array (like clearcollCurrentCourseStreamCourses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentCourseStreamCourses($overrideExisting = true)
    {
        if (null !== $this->collCurrentCourseStreamCourses && !$overrideExisting) {
            return;
        }

        $collectionClassName = CourseStreamTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentCourseStreamCourses = new $collectionClassName;
        $this->collCurrentCourseStreamCourses->setModel('\Models\CourseStream');
    }

    /**
     * Gets an array of ChildCourseStream objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCourse is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     * @throws PropelException
     */
    public function getCurrentCourseStreamCourses(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCourseStreamCoursesPartial && !$this->isNew();
        if (null === $this->collCurrentCourseStreamCourses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentCourseStreamCourses) {
                // return empty collection
                $this->initCurrentCourseStreamCourses();
            } else {
                $collCurrentCourseStreamCourses = ChildCourseStreamQuery::create(null, $criteria)
                    ->filterByCurrentCourseCourseStream($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentCourseStreamCoursesPartial && count($collCurrentCourseStreamCourses)) {
                        $this->initCurrentCourseStreamCourses(false);

                        foreach ($collCurrentCourseStreamCourses as $obj) {
                            if (false == $this->collCurrentCourseStreamCourses->contains($obj)) {
                                $this->collCurrentCourseStreamCourses->append($obj);
                            }
                        }

                        $this->collCurrentCourseStreamCoursesPartial = true;
                    }

                    return $collCurrentCourseStreamCourses;
                }

                if ($partial && $this->collCurrentCourseStreamCourses) {
                    foreach ($this->collCurrentCourseStreamCourses as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentCourseStreamCourses[] = $obj;
                        }
                    }
                }

                $this->collCurrentCourseStreamCourses = $collCurrentCourseStreamCourses;
                $this->collCurrentCourseStreamCoursesPartial = false;
            }
        }

        return $this->collCurrentCourseStreamCourses;
    }

    /**
     * Sets a collection of ChildCourseStream objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentCourseStreamCourses A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCourse The current object (for fluent API support)
     */
    public function setCurrentCourseStreamCourses(Collection $currentCourseStreamCourses, ConnectionInterface $con = null)
    {
        /** @var ChildCourseStream[] $currentCourseStreamCoursesToDelete */
        $currentCourseStreamCoursesToDelete = $this->getCurrentCourseStreamCourses(new Criteria(), $con)->diff($currentCourseStreamCourses);


        $this->currentCourseStreamCoursesScheduledForDeletion = $currentCourseStreamCoursesToDelete;

        foreach ($currentCourseStreamCoursesToDelete as $currentCourseStreamCourseRemoved) {
            $currentCourseStreamCourseRemoved->setCurrentCourseCourseStream(null);
        }

        $this->collCurrentCourseStreamCourses = null;
        foreach ($currentCourseStreamCourses as $currentCourseStreamCourse) {
            $this->addCurrentCourseStreamCourse($currentCourseStreamCourse);
        }

        $this->collCurrentCourseStreamCourses = $currentCourseStreamCourses;
        $this->collCurrentCourseStreamCoursesPartial = false;

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
    public function countCurrentCourseStreamCourses(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCourseStreamCoursesPartial && !$this->isNew();
        if (null === $this->collCurrentCourseStreamCourses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentCourseStreamCourses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentCourseStreamCourses());
            }

            $query = ChildCourseStreamQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentCourseCourseStream($this)
                ->count($con);
        }

        return count($this->collCurrentCourseStreamCourses);
    }

    /**
     * Method called to associate a ChildCourseStream object to this object
     * through the ChildCourseStream foreign key attribute.
     *
     * @param  ChildCourseStream $l ChildCourseStream
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function addCurrentCourseStreamCourse(ChildCourseStream $l)
    {
        if ($this->collCurrentCourseStreamCourses === null) {
            $this->initCurrentCourseStreamCourses();
            $this->collCurrentCourseStreamCoursesPartial = true;
        }

        if (!$this->collCurrentCourseStreamCourses->contains($l)) {
            $this->doAddCurrentCourseStreamCourse($l);

            if ($this->currentCourseStreamCoursesScheduledForDeletion and $this->currentCourseStreamCoursesScheduledForDeletion->contains($l)) {
                $this->currentCourseStreamCoursesScheduledForDeletion->remove($this->currentCourseStreamCoursesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCourseStream $currentCourseStreamCourse The ChildCourseStream object to add.
     */
    protected function doAddCurrentCourseStreamCourse(ChildCourseStream $currentCourseStreamCourse)
    {
        $this->collCurrentCourseStreamCourses[]= $currentCourseStreamCourse;
        $currentCourseStreamCourse->setCurrentCourseCourseStream($this);
    }

    /**
     * @param  ChildCourseStream $currentCourseStreamCourse The ChildCourseStream object to remove.
     * @return $this|ChildCourse The current object (for fluent API support)
     */
    public function removeCurrentCourseStreamCourse(ChildCourseStream $currentCourseStreamCourse)
    {
        if ($this->getCurrentCourseStreamCourses()->contains($currentCourseStreamCourse)) {
            $pos = $this->collCurrentCourseStreamCourses->search($currentCourseStreamCourse);
            $this->collCurrentCourseStreamCourses->remove($pos);
            if (null === $this->currentCourseStreamCoursesScheduledForDeletion) {
                $this->currentCourseStreamCoursesScheduledForDeletion = clone $this->collCurrentCourseStreamCourses;
                $this->currentCourseStreamCoursesScheduledForDeletion->clear();
            }
            $this->currentCourseStreamCoursesScheduledForDeletion[]= clone $currentCourseStreamCourse;
            $currentCourseStreamCourse->setCurrentCourseCourseStream(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related CurrentCourseStreamCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentCourseStreamCoursesJoinCurrentCourseStreamBranch(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamBranch', $joinBehavior);

        return $this->getCurrentCourseStreamCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related CurrentCourseStreamCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentCourseStreamCoursesJoinCurrentCourseStreamCurrency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamCurrency', $joinBehavior);

        return $this->getCurrentCourseStreamCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related CurrentCourseStreamCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentCourseStreamCoursesJoinCurrentCourseCourseStreamStatus(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseCourseStreamStatus', $joinBehavior);

        return $this->getCurrentCourseStreamCourses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Course is new, it will return
     * an empty collection; or if this Course has previously
     * been saved, it will retrieve related CurrentCourseStreamCourses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Course.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentCourseStreamCoursesJoinCurrentCourseStreamInstructor(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamInstructor', $joinBehavior);

        return $this->getCurrentCourseStreamCourses($query, $con);
    }

    /**
     * Clears out the collCurrentCourseCourseSkills collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentCourseCourseSkills()
     */
    public function clearCurrentCourseCourseSkills()
    {
        $this->collCurrentCourseCourseSkills = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentCourseCourseSkills collection loaded partially.
     */
    public function resetPartialCurrentCourseCourseSkills($v = true)
    {
        $this->collCurrentCourseCourseSkillsPartial = $v;
    }

    /**
     * Initializes the collCurrentCourseCourseSkills collection.
     *
     * By default this just sets the collCurrentCourseCourseSkills collection to an empty array (like clearcollCurrentCourseCourseSkills());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentCourseCourseSkills($overrideExisting = true)
    {
        if (null !== $this->collCurrentCourseCourseSkills && !$overrideExisting) {
            return;
        }

        $collectionClassName = CourseSkillTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentCourseCourseSkills = new $collectionClassName;
        $this->collCurrentCourseCourseSkills->setModel('\Models\CourseSkill');
    }

    /**
     * Gets an array of ChildCourseSkill objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCourse is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCourseSkill[] List of ChildCourseSkill objects
     * @throws PropelException
     */
    public function getCurrentCourseCourseSkills(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCourseCourseSkillsPartial && !$this->isNew();
        if (null === $this->collCurrentCourseCourseSkills || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentCourseCourseSkills) {
                // return empty collection
                $this->initCurrentCourseCourseSkills();
            } else {
                $collCurrentCourseCourseSkills = ChildCourseSkillQuery::create(null, $criteria)
                    ->filterByCurrentCourseSkillCourse($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentCourseCourseSkillsPartial && count($collCurrentCourseCourseSkills)) {
                        $this->initCurrentCourseCourseSkills(false);

                        foreach ($collCurrentCourseCourseSkills as $obj) {
                            if (false == $this->collCurrentCourseCourseSkills->contains($obj)) {
                                $this->collCurrentCourseCourseSkills->append($obj);
                            }
                        }

                        $this->collCurrentCourseCourseSkillsPartial = true;
                    }

                    return $collCurrentCourseCourseSkills;
                }

                if ($partial && $this->collCurrentCourseCourseSkills) {
                    foreach ($this->collCurrentCourseCourseSkills as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentCourseCourseSkills[] = $obj;
                        }
                    }
                }

                $this->collCurrentCourseCourseSkills = $collCurrentCourseCourseSkills;
                $this->collCurrentCourseCourseSkillsPartial = false;
            }
        }

        return $this->collCurrentCourseCourseSkills;
    }

    /**
     * Sets a collection of ChildCourseSkill objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentCourseCourseSkills A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCourse The current object (for fluent API support)
     */
    public function setCurrentCourseCourseSkills(Collection $currentCourseCourseSkills, ConnectionInterface $con = null)
    {
        /** @var ChildCourseSkill[] $currentCourseCourseSkillsToDelete */
        $currentCourseCourseSkillsToDelete = $this->getCurrentCourseCourseSkills(new Criteria(), $con)->diff($currentCourseCourseSkills);


        $this->currentCourseCourseSkillsScheduledForDeletion = $currentCourseCourseSkillsToDelete;

        foreach ($currentCourseCourseSkillsToDelete as $currentCourseCourseSkillRemoved) {
            $currentCourseCourseSkillRemoved->setCurrentCourseSkillCourse(null);
        }

        $this->collCurrentCourseCourseSkills = null;
        foreach ($currentCourseCourseSkills as $currentCourseCourseSkill) {
            $this->addCurrentCourseCourseSkill($currentCourseCourseSkill);
        }

        $this->collCurrentCourseCourseSkills = $currentCourseCourseSkills;
        $this->collCurrentCourseCourseSkillsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CourseSkill objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CourseSkill objects.
     * @throws PropelException
     */
    public function countCurrentCourseCourseSkills(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCourseCourseSkillsPartial && !$this->isNew();
        if (null === $this->collCurrentCourseCourseSkills || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentCourseCourseSkills) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentCourseCourseSkills());
            }

            $query = ChildCourseSkillQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentCourseSkillCourse($this)
                ->count($con);
        }

        return count($this->collCurrentCourseCourseSkills);
    }

    /**
     * Method called to associate a ChildCourseSkill object to this object
     * through the ChildCourseSkill foreign key attribute.
     *
     * @param  ChildCourseSkill $l ChildCourseSkill
     * @return $this|\Models\Course The current object (for fluent API support)
     */
    public function addCurrentCourseCourseSkill(ChildCourseSkill $l)
    {
        if ($this->collCurrentCourseCourseSkills === null) {
            $this->initCurrentCourseCourseSkills();
            $this->collCurrentCourseCourseSkillsPartial = true;
        }

        if (!$this->collCurrentCourseCourseSkills->contains($l)) {
            $this->doAddCurrentCourseCourseSkill($l);

            if ($this->currentCourseCourseSkillsScheduledForDeletion and $this->currentCourseCourseSkillsScheduledForDeletion->contains($l)) {
                $this->currentCourseCourseSkillsScheduledForDeletion->remove($this->currentCourseCourseSkillsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCourseSkill $currentCourseCourseSkill The ChildCourseSkill object to add.
     */
    protected function doAddCurrentCourseCourseSkill(ChildCourseSkill $currentCourseCourseSkill)
    {
        $this->collCurrentCourseCourseSkills[]= $currentCourseCourseSkill;
        $currentCourseCourseSkill->setCurrentCourseSkillCourse($this);
    }

    /**
     * @param  ChildCourseSkill $currentCourseCourseSkill The ChildCourseSkill object to remove.
     * @return $this|ChildCourse The current object (for fluent API support)
     */
    public function removeCurrentCourseCourseSkill(ChildCourseSkill $currentCourseCourseSkill)
    {
        if ($this->getCurrentCourseCourseSkills()->contains($currentCourseCourseSkill)) {
            $pos = $this->collCurrentCourseCourseSkills->search($currentCourseCourseSkill);
            $this->collCurrentCourseCourseSkills->remove($pos);
            if (null === $this->currentCourseCourseSkillsScheduledForDeletion) {
                $this->currentCourseCourseSkillsScheduledForDeletion = clone $this->collCurrentCourseCourseSkills;
                $this->currentCourseCourseSkillsScheduledForDeletion->clear();
            }
            $this->currentCourseCourseSkillsScheduledForDeletion[]= clone $currentCourseCourseSkill;
            $currentCourseCourseSkill->setCurrentCourseSkillCourse(null);
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
        $this->id = null;
        $this->name = null;
        $this->description = null;
        $this->alt_url = null;
        $this->logo_name = null;
        $this->cover_name = null;
        $this->title = null;
        $this->context = null;
        $this->notes = null;
        $this->use_notes = null;
        $this->uses = null;
        $this->uses_unserialized = null;
        $this->meta_description = null;
        $this->meta_keywords = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
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
            if ($this->collCurrentApplicationCourses) {
                foreach ($this->collCurrentApplicationCourses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentCourseStreamCourses) {
                foreach ($this->collCurrentCourseStreamCourses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentCourseCourseSkills) {
                foreach ($this->collCurrentCourseCourseSkills as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCurrentApplicationCourses = null;
        $this->collCurrentCourseStreamCourses = null;
        $this->collCurrentCourseCourseSkills = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CourseTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildCourse The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[CourseTableMap::COL_UPDATED_AT] = true;

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
