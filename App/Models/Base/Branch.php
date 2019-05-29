<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Branch as ChildBranch;
use Models\BranchQuery as ChildBranchQuery;
use Models\CourseStream as ChildCourseStream;
use Models\CourseStreamQuery as ChildCourseStreamQuery;
use Models\Map\BranchTableMap;
use Models\Map\CourseStreamTableMap;
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

/**
 * Base class that represents a row from the 'branch' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class Branch implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\BranchTableMap';


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
     * The value for the show_on_website field.
     *
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $show_on_website;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the address field.
     *
     * @var        string
     */
    protected $address;

    /**
     * The value for the geographic_coordinates field.
     *
     * @var        \Core\Branch\GeographicCoordinates
     */
    protected $geographic_coordinates;

    /**
     * The unserialized $geographic_coordinates value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $geographic_coordinates_unserialized;

    /**
     * The value for the tel field.
     *
     * @var        string
     */
    protected $tel;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the instagram_link field.
     *
     * @var        string
     */
    protected $instagram_link;

    /**
     * The value for the facebook_link field.
     *
     * @var        string
     */
    protected $facebook_link;

    /**
     * The value for the sortable_rank field.
     *
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        ObjectCollection|ChildCourseStream[] Collection to store aggregation of ChildCourseStream objects.
     */
    protected $collCurrentBranchCourseStreams;
    protected $collCurrentBranchCourseStreamsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // sortable behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCourseStream[]
     */
    protected $currentBranchCourseStreamsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->show_on_website = true;
    }

    /**
     * Initializes internal state of Models\Base\Branch object.
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
     * Compares this with another <code>Branch</code> instance.  If
     * <code>obj</code> is an instance of <code>Branch</code>, delegates to
     * <code>equals(Branch)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Branch The current object, for fluid interface
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Get the [geographic_coordinates] column value.
     *
     * @return \Core\Branch\GeographicCoordinates
     */
    public function getGeographicCoordinates()
    {
        if (null == $this->geographic_coordinates_unserialized && is_resource($this->geographic_coordinates)) {
            if ($serialisedString = stream_get_contents($this->geographic_coordinates)) {
                $this->geographic_coordinates_unserialized = unserialize($serialisedString);
            }
        }

        return $this->geographic_coordinates_unserialized;
    }

    /**
     * Get the [tel] column value.
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
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
     * Get the [instagram_link] column value.
     *
     * @return string
     */
    public function getInstagramLink()
    {
        return $this->instagram_link;
    }

    /**
     * Get the [facebook_link] column value.
     *
     * @return string
     */
    public function getFacebookLink()
    {
        return $this->facebook_link;
    }

    /**
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[BranchTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Sets the value of the [show_on_website] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Models\Branch The current object (for fluent API support)
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
            $this->modifiedColumns[BranchTableMap::COL_SHOW_ON_WEBSITE] = true;
        }

        return $this;
    } // setShowOnWebSite()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[BranchTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [address] column.
     *
     * @param string $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[BranchTableMap::COL_ADDRESS] = true;
        }

        return $this;
    } // setAddress()

    /**
     * Set the value of [geographic_coordinates] column.
     *
     * @param \Core\Branch\GeographicCoordinates $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setGeographicCoordinates($v)
    {
        if (null === $this->geographic_coordinates || stream_get_contents($this->geographic_coordinates) !== serialize($v)) {
            $this->geographic_coordinates_unserialized = $v;
            $this->geographic_coordinates = fopen('php://memory', 'r+');
            fwrite($this->geographic_coordinates, serialize($v));
            $this->modifiedColumns[BranchTableMap::COL_GEOGRAPHIC_COORDINATES] = true;
        }
        rewind($this->geographic_coordinates);

        return $this;
    } // setGeographicCoordinates()

    /**
     * Set the value of [tel] column.
     *
     * @param string $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setTel($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->tel !== $v) {
            $this->tel = $v;
            $this->modifiedColumns[BranchTableMap::COL_TEL] = true;
        }

        return $this;
    } // setTel()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[BranchTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [instagram_link] column.
     *
     * @param string $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setInstagramLink($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->instagram_link !== $v) {
            $this->instagram_link = $v;
            $this->modifiedColumns[BranchTableMap::COL_INSTAGRAM_LINK] = true;
        }

        return $this;
    } // setInstagramLink()

    /**
     * Set the value of [facebook_link] column.
     *
     * @param string $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setFacebookLink($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->facebook_link !== $v) {
            $this->facebook_link = $v;
            $this->modifiedColumns[BranchTableMap::COL_FACEBOOK_LINK] = true;
        }

        return $this;
    } // setFacebookLink()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param int $v new value
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[BranchTableMap::COL_SORTABLE_RANK] = true;
        }

        return $this;
    } // setSortableRank()

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
            if ($this->show_on_website !== true) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : BranchTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : BranchTableMap::translateFieldName('ShowOnWebSite', TableMap::TYPE_PHPNAME, $indexType)];
            $this->show_on_website = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : BranchTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : BranchTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : BranchTableMap::translateFieldName('GeographicCoordinates', TableMap::TYPE_PHPNAME, $indexType)];
            if (null !== $col) {
                $this->geographic_coordinates = fopen('php://memory', 'r+');
                fwrite($this->geographic_coordinates, $col);
                rewind($this->geographic_coordinates);
            } else {
                $this->geographic_coordinates = null;
            }

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : BranchTableMap::translateFieldName('Tel', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tel = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : BranchTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : BranchTableMap::translateFieldName('InstagramLink', TableMap::TYPE_PHPNAME, $indexType)];
            $this->instagram_link = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : BranchTableMap::translateFieldName('FacebookLink', TableMap::TYPE_PHPNAME, $indexType)];
            $this->facebook_link = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : BranchTableMap::translateFieldName('SortableRank', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sortable_rank = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = BranchTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Branch'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(BranchTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildBranchQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCurrentBranchCourseStreams = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Branch::setDeleted()
     * @see Branch::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildBranchQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            ChildBranchQuery::sortableShiftRank(-1, $this->getSortableRank() + 1, null, $con);
            BranchTableMap::clearInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(BranchTableMap::RANK_COL)) {
                    $this->setSortableRank(ChildBranchQuery::create()->getMaxRankArray($con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                BranchTableMap::addInstanceToPool($this);
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
                // Rewind the geographic_coordinates LOB column, since PDO does not rewind after inserting value.
                if ($this->geographic_coordinates !== null && is_resource($this->geographic_coordinates)) {
                    rewind($this->geographic_coordinates);
                }

                $this->resetModified();
            }

            if ($this->currentBranchCourseStreamsScheduledForDeletion !== null) {
                if (!$this->currentBranchCourseStreamsScheduledForDeletion->isEmpty()) {
                    \Models\CourseStreamQuery::create()
                        ->filterByPrimaryKeys($this->currentBranchCourseStreamsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentBranchCourseStreamsScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentBranchCourseStreams !== null) {
                foreach ($this->collCurrentBranchCourseStreams as $referrerFK) {
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

        $this->modifiedColumns[BranchTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BranchTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BranchTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(BranchTableMap::COL_SHOW_ON_WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = 'show_on_website';
        }
        if ($this->isColumnModified(BranchTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(BranchTableMap::COL_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'address';
        }
        if ($this->isColumnModified(BranchTableMap::COL_GEOGRAPHIC_COORDINATES)) {
            $modifiedColumns[':p' . $index++]  = 'geographic_coordinates';
        }
        if ($this->isColumnModified(BranchTableMap::COL_TEL)) {
            $modifiedColumns[':p' . $index++]  = 'tel';
        }
        if ($this->isColumnModified(BranchTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(BranchTableMap::COL_INSTAGRAM_LINK)) {
            $modifiedColumns[':p' . $index++]  = 'instagram_link';
        }
        if ($this->isColumnModified(BranchTableMap::COL_FACEBOOK_LINK)) {
            $modifiedColumns[':p' . $index++]  = 'facebook_link';
        }
        if ($this->isColumnModified(BranchTableMap::COL_SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = 'sortable_rank';
        }

        $sql = sprintf(
            'INSERT INTO branch (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'show_on_website':
                        $stmt->bindValue($identifier, (int) $this->show_on_website, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'address':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case 'geographic_coordinates':
                        if (is_resource($this->geographic_coordinates)) {
                            rewind($this->geographic_coordinates);
                        }
                        $stmt->bindValue($identifier, $this->geographic_coordinates, PDO::PARAM_LOB);
                        break;
                    case 'tel':
                        $stmt->bindValue($identifier, $this->tel, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'instagram_link':
                        $stmt->bindValue($identifier, $this->instagram_link, PDO::PARAM_STR);
                        break;
                    case 'facebook_link':
                        $stmt->bindValue($identifier, $this->facebook_link, PDO::PARAM_STR);
                        break;
                    case 'sortable_rank':
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
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
        $pos = BranchTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getShowOnWebSite();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getAddress();
                break;
            case 4:
                return $this->getGeographicCoordinates();
                break;
            case 5:
                return $this->getTel();
                break;
            case 6:
                return $this->getEmail();
                break;
            case 7:
                return $this->getInstagramLink();
                break;
            case 8:
                return $this->getFacebookLink();
                break;
            case 9:
                return $this->getSortableRank();
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

        if (isset($alreadyDumpedObjects['Branch'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Branch'][$this->hashCode()] = true;
        $keys = BranchTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getShowOnWebSite(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getAddress(),
            $keys[4] => $this->getGeographicCoordinates(),
            $keys[5] => $this->getTel(),
            $keys[6] => $this->getEmail(),
            $keys[7] => $this->getInstagramLink(),
            $keys[8] => $this->getFacebookLink(),
            $keys[9] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCurrentBranchCourseStreams) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'courseStreams';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'course_streams';
                        break;
                    default:
                        $key = 'CurrentBranchCourseStreams';
                }

                $result[$key] = $this->collCurrentBranchCourseStreams->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\Branch
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = BranchTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Branch
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setShowOnWebSite($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setAddress($value);
                break;
            case 4:
                $this->setGeographicCoordinates($value);
                break;
            case 5:
                $this->setTel($value);
                break;
            case 6:
                $this->setEmail($value);
                break;
            case 7:
                $this->setInstagramLink($value);
                break;
            case 8:
                $this->setFacebookLink($value);
                break;
            case 9:
                $this->setSortableRank($value);
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
        $keys = BranchTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setShowOnWebSite($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAddress($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setGeographicCoordinates($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setTel($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setEmail($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setInstagramLink($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setFacebookLink($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setSortableRank($arr[$keys[9]]);
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
     * @return $this|\Models\Branch The current object, for fluid interface
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
        $criteria = new Criteria(BranchTableMap::DATABASE_NAME);

        if ($this->isColumnModified(BranchTableMap::COL_ID)) {
            $criteria->add(BranchTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(BranchTableMap::COL_SHOW_ON_WEBSITE)) {
            $criteria->add(BranchTableMap::COL_SHOW_ON_WEBSITE, $this->show_on_website);
        }
        if ($this->isColumnModified(BranchTableMap::COL_NAME)) {
            $criteria->add(BranchTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(BranchTableMap::COL_ADDRESS)) {
            $criteria->add(BranchTableMap::COL_ADDRESS, $this->address);
        }
        if ($this->isColumnModified(BranchTableMap::COL_GEOGRAPHIC_COORDINATES)) {
            $criteria->add(BranchTableMap::COL_GEOGRAPHIC_COORDINATES, $this->geographic_coordinates);
        }
        if ($this->isColumnModified(BranchTableMap::COL_TEL)) {
            $criteria->add(BranchTableMap::COL_TEL, $this->tel);
        }
        if ($this->isColumnModified(BranchTableMap::COL_EMAIL)) {
            $criteria->add(BranchTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(BranchTableMap::COL_INSTAGRAM_LINK)) {
            $criteria->add(BranchTableMap::COL_INSTAGRAM_LINK, $this->instagram_link);
        }
        if ($this->isColumnModified(BranchTableMap::COL_FACEBOOK_LINK)) {
            $criteria->add(BranchTableMap::COL_FACEBOOK_LINK, $this->facebook_link);
        }
        if ($this->isColumnModified(BranchTableMap::COL_SORTABLE_RANK)) {
            $criteria->add(BranchTableMap::COL_SORTABLE_RANK, $this->sortable_rank);
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
        $criteria = ChildBranchQuery::create();
        $criteria->add(BranchTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Branch (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setShowOnWebSite($this->getShowOnWebSite());
        $copyObj->setName($this->getName());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setGeographicCoordinates($this->getGeographicCoordinates());
        $copyObj->setTel($this->getTel());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setInstagramLink($this->getInstagramLink());
        $copyObj->setFacebookLink($this->getFacebookLink());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCurrentBranchCourseStreams() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentBranchCourseStream($relObj->copy($deepCopy));
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
     * @return \Models\Branch Clone of current object.
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
        if ('CurrentBranchCourseStream' == $relationName) {
            $this->initCurrentBranchCourseStreams();
            return;
        }
    }

    /**
     * Clears out the collCurrentBranchCourseStreams collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentBranchCourseStreams()
     */
    public function clearCurrentBranchCourseStreams()
    {
        $this->collCurrentBranchCourseStreams = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentBranchCourseStreams collection loaded partially.
     */
    public function resetPartialCurrentBranchCourseStreams($v = true)
    {
        $this->collCurrentBranchCourseStreamsPartial = $v;
    }

    /**
     * Initializes the collCurrentBranchCourseStreams collection.
     *
     * By default this just sets the collCurrentBranchCourseStreams collection to an empty array (like clearcollCurrentBranchCourseStreams());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentBranchCourseStreams($overrideExisting = true)
    {
        if (null !== $this->collCurrentBranchCourseStreams && !$overrideExisting) {
            return;
        }

        $collectionClassName = CourseStreamTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentBranchCourseStreams = new $collectionClassName;
        $this->collCurrentBranchCourseStreams->setModel('\Models\CourseStream');
    }

    /**
     * Gets an array of ChildCourseStream objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildBranch is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     * @throws PropelException
     */
    public function getCurrentBranchCourseStreams(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentBranchCourseStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentBranchCourseStreams || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentBranchCourseStreams) {
                // return empty collection
                $this->initCurrentBranchCourseStreams();
            } else {
                $collCurrentBranchCourseStreams = ChildCourseStreamQuery::create(null, $criteria)
                    ->filterByCurrentCourseStreamBranch($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentBranchCourseStreamsPartial && count($collCurrentBranchCourseStreams)) {
                        $this->initCurrentBranchCourseStreams(false);

                        foreach ($collCurrentBranchCourseStreams as $obj) {
                            if (false == $this->collCurrentBranchCourseStreams->contains($obj)) {
                                $this->collCurrentBranchCourseStreams->append($obj);
                            }
                        }

                        $this->collCurrentBranchCourseStreamsPartial = true;
                    }

                    return $collCurrentBranchCourseStreams;
                }

                if ($partial && $this->collCurrentBranchCourseStreams) {
                    foreach ($this->collCurrentBranchCourseStreams as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentBranchCourseStreams[] = $obj;
                        }
                    }
                }

                $this->collCurrentBranchCourseStreams = $collCurrentBranchCourseStreams;
                $this->collCurrentBranchCourseStreamsPartial = false;
            }
        }

        return $this->collCurrentBranchCourseStreams;
    }

    /**
     * Sets a collection of ChildCourseStream objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentBranchCourseStreams A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildBranch The current object (for fluent API support)
     */
    public function setCurrentBranchCourseStreams(Collection $currentBranchCourseStreams, ConnectionInterface $con = null)
    {
        /** @var ChildCourseStream[] $currentBranchCourseStreamsToDelete */
        $currentBranchCourseStreamsToDelete = $this->getCurrentBranchCourseStreams(new Criteria(), $con)->diff($currentBranchCourseStreams);


        $this->currentBranchCourseStreamsScheduledForDeletion = $currentBranchCourseStreamsToDelete;

        foreach ($currentBranchCourseStreamsToDelete as $currentBranchCourseStreamRemoved) {
            $currentBranchCourseStreamRemoved->setCurrentCourseStreamBranch(null);
        }

        $this->collCurrentBranchCourseStreams = null;
        foreach ($currentBranchCourseStreams as $currentBranchCourseStream) {
            $this->addCurrentBranchCourseStream($currentBranchCourseStream);
        }

        $this->collCurrentBranchCourseStreams = $currentBranchCourseStreams;
        $this->collCurrentBranchCourseStreamsPartial = false;

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
    public function countCurrentBranchCourseStreams(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentBranchCourseStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentBranchCourseStreams || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentBranchCourseStreams) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentBranchCourseStreams());
            }

            $query = ChildCourseStreamQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentCourseStreamBranch($this)
                ->count($con);
        }

        return count($this->collCurrentBranchCourseStreams);
    }

    /**
     * Method called to associate a ChildCourseStream object to this object
     * through the ChildCourseStream foreign key attribute.
     *
     * @param  ChildCourseStream $l ChildCourseStream
     * @return $this|\Models\Branch The current object (for fluent API support)
     */
    public function addCurrentBranchCourseStream(ChildCourseStream $l)
    {
        if ($this->collCurrentBranchCourseStreams === null) {
            $this->initCurrentBranchCourseStreams();
            $this->collCurrentBranchCourseStreamsPartial = true;
        }

        if (!$this->collCurrentBranchCourseStreams->contains($l)) {
            $this->doAddCurrentBranchCourseStream($l);

            if ($this->currentBranchCourseStreamsScheduledForDeletion and $this->currentBranchCourseStreamsScheduledForDeletion->contains($l)) {
                $this->currentBranchCourseStreamsScheduledForDeletion->remove($this->currentBranchCourseStreamsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCourseStream $currentBranchCourseStream The ChildCourseStream object to add.
     */
    protected function doAddCurrentBranchCourseStream(ChildCourseStream $currentBranchCourseStream)
    {
        $this->collCurrentBranchCourseStreams[]= $currentBranchCourseStream;
        $currentBranchCourseStream->setCurrentCourseStreamBranch($this);
    }

    /**
     * @param  ChildCourseStream $currentBranchCourseStream The ChildCourseStream object to remove.
     * @return $this|ChildBranch The current object (for fluent API support)
     */
    public function removeCurrentBranchCourseStream(ChildCourseStream $currentBranchCourseStream)
    {
        if ($this->getCurrentBranchCourseStreams()->contains($currentBranchCourseStream)) {
            $pos = $this->collCurrentBranchCourseStreams->search($currentBranchCourseStream);
            $this->collCurrentBranchCourseStreams->remove($pos);
            if (null === $this->currentBranchCourseStreamsScheduledForDeletion) {
                $this->currentBranchCourseStreamsScheduledForDeletion = clone $this->collCurrentBranchCourseStreams;
                $this->currentBranchCourseStreamsScheduledForDeletion->clear();
            }
            $this->currentBranchCourseStreamsScheduledForDeletion[]= clone $currentBranchCourseStream;
            $currentBranchCourseStream->setCurrentCourseStreamBranch(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Branch is new, it will return
     * an empty collection; or if this Branch has previously
     * been saved, it will retrieve related CurrentBranchCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Branch.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentBranchCourseStreamsJoinCurrentCourseStreamCurrency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamCurrency', $joinBehavior);

        return $this->getCurrentBranchCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Branch is new, it will return
     * an empty collection; or if this Branch has previously
     * been saved, it will retrieve related CurrentBranchCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Branch.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentBranchCourseStreamsJoinCurrentCourseCourseStream(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseCourseStream', $joinBehavior);

        return $this->getCurrentBranchCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Branch is new, it will return
     * an empty collection; or if this Branch has previously
     * been saved, it will retrieve related CurrentBranchCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Branch.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentBranchCourseStreamsJoinCurrentCourseCourseStreamStatus(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseCourseStreamStatus', $joinBehavior);

        return $this->getCurrentBranchCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Branch is new, it will return
     * an empty collection; or if this Branch has previously
     * been saved, it will retrieve related CurrentBranchCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Branch.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentBranchCourseStreamsJoinCurrentCourseStreamInstructor(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamInstructor', $joinBehavior);

        return $this->getCurrentBranchCourseStreams($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->show_on_website = null;
        $this->name = null;
        $this->address = null;
        $this->geographic_coordinates = null;
        $this->geographic_coordinates_unserialized = null;
        $this->tel = null;
        $this->email = null;
        $this->instagram_link = null;
        $this->facebook_link = null;
        $this->sortable_rank = null;
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
            if ($this->collCurrentBranchCourseStreams) {
                foreach ($this->collCurrentBranchCourseStreams as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCurrentBranchCourseStreams = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(BranchTableMap::DEFAULT_STRING_FORMAT);
    }

    // sortable behavior

    /**
     * Wrap the getter for rank value
     *
     * @return    int
     */
    public function getRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Wrap the setter for rank value
     *
     * @param     int
     * @return    $this|ChildBranch
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }

    /**
     * Check if the object is first in the list, i.e. if it has 1 for rank
     *
     * @return    boolean
     */
    public function isFirst()
    {
        return $this->getSortableRank() == 1;
    }

    /**
     * Check if the object is last in the list, i.e. if its rank is the highest rank
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    boolean
     */
    public function isLast(ConnectionInterface $con = null)
    {
        return $this->getSortableRank() == ChildBranchQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildBranch
     */
    public function getNext(ConnectionInterface $con = null)
    {

        $query = ChildBranchQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildBranch
     */
    public function getPrevious(ConnectionInterface $con = null)
    {

        $query = ChildBranchQuery::create();

        $query->filterByRank($this->getSortableRank() - 1);


        return $query->findOne($con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    $this|ChildBranch the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, ConnectionInterface $con = null)
    {
        $maxRank = ChildBranchQuery::create()->getMaxRankArray($con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array('\Models\BranchQuery', 'sortableShiftRank'),
                'arguments' => array(1, $rank, null, )
            );
        }

        return $this;
    }

    /**
     * Insert in the last rank
     * The modifications are not persisted until the object is saved.
     *
     * @param ConnectionInterface $con optional connection
     *
     * @return    $this|ChildBranch the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(ConnectionInterface $con = null)
    {
        $this->setSortableRank(ChildBranchQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildBranch the current object
     */
    public function insertAtTop()
    {
        return $this->insertAtRank(1);
    }

    /**
     * Move the object to a new rank, and shifts the rank
     * Of the objects inbetween the old and new rank accordingly
     *
     * @param     integer   $newRank rank value
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildBranch the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, ConnectionInterface $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > ChildBranchQuery::create()->getMaxRankArray($con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->transaction(function () use ($con, $oldRank, $newRank) {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            ChildBranchQuery::sortableShiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);
        });

        return $this;
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     ChildBranch $object
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildBranch the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }
        $con->transaction(function () use ($con, $object) {
            $oldRank = $this->getSortableRank();
            $newRank = $object->getSortableRank();

            $this->setSortableRank($newRank);
            $object->setSortableRank($oldRank);

            $this->save($con);
            $object->save($con);
        });

        return $this;
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the previous object
     *
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildBranch the current object
     */
    public function moveUp(ConnectionInterface $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }
        $con->transaction(function () use ($con) {
            $prev = $this->getPrevious($con);
            $this->swapWith($prev, $con);
        });

        return $this;
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the next object
     *
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildBranch the current object
     */
    public function moveDown(ConnectionInterface $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }
        $con->transaction(function () use ($con) {
            $next = $this->getNext($con);
            $this->swapWith($next, $con);
        });

        return $this;
    }

    /**
     * Move the object to the top of the list
     *
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildBranch the current object
     */
    public function moveToTop(ConnectionInterface $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }

        return $this->moveToRank(1, $con);
    }

    /**
     * Move the object to the bottom of the list
     *
     * @param     ConnectionInterface $con optional connection
     *
     * @return integer the old object's rank
     */
    public function moveToBottom(ConnectionInterface $con = null)
    {
        if ($this->isLast($con)) {
            return false;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $bottom = ChildBranchQuery::create()->getMaxRankArray($con);

            return $this->moveToRank($bottom, $con);
        });
    }

    /**
     * Removes the current object from the list.
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildBranch the current object
     */
    public function removeFromList()
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries[] = array(
            'callable'  => array('\Models\BranchQuery', 'sortableShiftRank'),
            'arguments' => array(-1, $this->getSortableRank() + 1, null)
        );
        // remove the object from the list
        $this->setSortableRank(null);


        return $this;
    }

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processSortableQueries($con)
    {
        foreach ($this->sortableQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->sortableQueries = array();
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
