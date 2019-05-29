<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\CourseStream as ChildCourseStream;
use Models\CourseStreamQuery as ChildCourseStreamQuery;
use Models\Currency as ChildCurrency;
use Models\CurrencyQuery as ChildCurrencyQuery;
use Models\CurrencyRate as ChildCurrencyRate;
use Models\CurrencyRateQuery as ChildCurrencyRateQuery;
use Models\Feedback as ChildFeedback;
use Models\FeedbackQuery as ChildFeedbackQuery;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\Map\CourseStreamTableMap;
use Models\Map\CurrencyRateTableMap;
use Models\Map\CurrencyTableMap;
use Models\Map\FeedbackTableMap;
use Models\Map\UserTableMap;
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
 * Base class that represents a row from the 'currency' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class Currency implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\CurrencyTableMap';


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
     * The value for the iso_code field.
     *
     * @var        string
     */
    protected $iso_code;

    /**
     * The value for the symbol field.
     *
     * @var        string
     */
    protected $symbol;

    /**
     * The value for the is_symbol_before field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_symbol_before;

    /**
     * The value for the notes field.
     *
     * @var        string
     */
    protected $notes;

    /**
     * The value for the sortable_rank field.
     *
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        ObjectCollection|ChildUser[] Collection to store aggregation of ChildUser objects.
     */
    protected $collCurrentCurrencyUsers;
    protected $collCurrentCurrencyUsersPartial;

    /**
     * @var        ObjectCollection|ChildCourseStream[] Collection to store aggregation of ChildCourseStream objects.
     */
    protected $collCurrentCurrencyCourseStreams;
    protected $collCurrentCurrencyCourseStreamsPartial;

    /**
     * @var        ObjectCollection|ChildCurrencyRate[] Collection to store aggregation of ChildCurrencyRate objects.
     */
    protected $collCurrentDefaultCurrencyRates;
    protected $collCurrentDefaultCurrencyRatesPartial;

    /**
     * @var        ObjectCollection|ChildCurrencyRate[] Collection to store aggregation of ChildCurrencyRate objects.
     */
    protected $collCurrentToCurrencyRates;
    protected $collCurrentToCurrencyRatesPartial;

    /**
     * @var        ObjectCollection|ChildFeedback[] Collection to store aggregation of ChildFeedback objects.
     */
    protected $collCurrentCurrencyFeedbacks;
    protected $collCurrentCurrencyFeedbacksPartial;

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
     * @var ObjectCollection|ChildUser[]
     */
    protected $currentCurrencyUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCourseStream[]
     */
    protected $currentCurrencyCourseStreamsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCurrencyRate[]
     */
    protected $currentDefaultCurrencyRatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCurrencyRate[]
     */
    protected $currentToCurrencyRatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFeedback[]
     */
    protected $currentCurrencyFeedbacksScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_symbol_before = false;
    }

    /**
     * Initializes internal state of Models\Base\Currency object.
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
     * Compares this with another <code>Currency</code> instance.  If
     * <code>obj</code> is an instance of <code>Currency</code>, delegates to
     * <code>equals(Currency)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Currency The current object, for fluid interface
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
     * Get the [iso_code] column value.
     *
     * @return string
     */
    public function getISOCode()
    {
        return $this->iso_code;
    }

    /**
     * Get the [symbol] column value.
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Get the [is_symbol_before] column value.
     *
     * @return boolean
     */
    public function getIsSymbolBefore()
    {
        return $this->is_symbol_before;
    }

    /**
     * Get the [is_symbol_before] column value.
     *
     * @return boolean
     */
    public function isSymbolBefore()
    {
        return $this->getIsSymbolBefore();
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
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [iso_code] column.
     *
     * @param string $v new value
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function setISOCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->iso_code !== $v) {
            $this->iso_code = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_ISO_CODE] = true;
        }

        return $this;
    } // setISOCode()

    /**
     * Set the value of [symbol] column.
     *
     * @param string $v new value
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function setSymbol($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->symbol !== $v) {
            $this->symbol = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_SYMBOL] = true;
        }

        return $this;
    } // setSymbol()

    /**
     * Sets the value of the [is_symbol_before] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function setIsSymbolBefore($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_symbol_before !== $v) {
            $this->is_symbol_before = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_IS_SYMBOL_BEFORE] = true;
        }

        return $this;
    } // setIsSymbolBefore()

    /**
     * Set the value of [notes] column.
     *
     * @param string $v new value
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function setNotes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->notes !== $v) {
            $this->notes = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_NOTES] = true;
        }

        return $this;
    } // setNotes()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param int $v new value
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[CurrencyTableMap::COL_SORTABLE_RANK] = true;
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
            if ($this->is_symbol_before !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CurrencyTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CurrencyTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CurrencyTableMap::translateFieldName('ISOCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iso_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CurrencyTableMap::translateFieldName('Symbol', TableMap::TYPE_PHPNAME, $indexType)];
            $this->symbol = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CurrencyTableMap::translateFieldName('IsSymbolBefore', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_symbol_before = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CurrencyTableMap::translateFieldName('Notes', TableMap::TYPE_PHPNAME, $indexType)];
            $this->notes = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CurrencyTableMap::translateFieldName('SortableRank', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sortable_rank = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = CurrencyTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Currency'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCurrencyQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCurrentCurrencyUsers = null;

            $this->collCurrentCurrencyCourseStreams = null;

            $this->collCurrentDefaultCurrencyRates = null;

            $this->collCurrentToCurrencyRates = null;

            $this->collCurrentCurrencyFeedbacks = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Currency::setDeleted()
     * @see Currency::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCurrencyQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            ChildCurrencyQuery::sortableShiftRank(-1, $this->getSortableRank() + 1, null, $con);
            CurrencyTableMap::clearInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(CurrencyTableMap::RANK_COL)) {
                    $this->setSortableRank(ChildCurrencyQuery::create()->getMaxRankArray($con) + 1);
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
                CurrencyTableMap::addInstanceToPool($this);
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
                $this->resetModified();
            }

            if ($this->currentCurrencyUsersScheduledForDeletion !== null) {
                if (!$this->currentCurrencyUsersScheduledForDeletion->isEmpty()) {
                    foreach ($this->currentCurrencyUsersScheduledForDeletion as $currentCurrencyUser) {
                        // need to save related object because we set the relation to null
                        $currentCurrencyUser->save($con);
                    }
                    $this->currentCurrencyUsersScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentCurrencyUsers !== null) {
                foreach ($this->collCurrentCurrencyUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentCurrencyCourseStreamsScheduledForDeletion !== null) {
                if (!$this->currentCurrencyCourseStreamsScheduledForDeletion->isEmpty()) {
                    \Models\CourseStreamQuery::create()
                        ->filterByPrimaryKeys($this->currentCurrencyCourseStreamsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentCurrencyCourseStreamsScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentCurrencyCourseStreams !== null) {
                foreach ($this->collCurrentCurrencyCourseStreams as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentDefaultCurrencyRatesScheduledForDeletion !== null) {
                if (!$this->currentDefaultCurrencyRatesScheduledForDeletion->isEmpty()) {
                    \Models\CurrencyRateQuery::create()
                        ->filterByPrimaryKeys($this->currentDefaultCurrencyRatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentDefaultCurrencyRatesScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentDefaultCurrencyRates !== null) {
                foreach ($this->collCurrentDefaultCurrencyRates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentToCurrencyRatesScheduledForDeletion !== null) {
                if (!$this->currentToCurrencyRatesScheduledForDeletion->isEmpty()) {
                    \Models\CurrencyRateQuery::create()
                        ->filterByPrimaryKeys($this->currentToCurrencyRatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentToCurrencyRatesScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentToCurrencyRates !== null) {
                foreach ($this->collCurrentToCurrencyRates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentCurrencyFeedbacksScheduledForDeletion !== null) {
                if (!$this->currentCurrencyFeedbacksScheduledForDeletion->isEmpty()) {
                    foreach ($this->currentCurrencyFeedbacksScheduledForDeletion as $currentCurrencyFeedback) {
                        // need to save related object because we set the relation to null
                        $currentCurrencyFeedback->save($con);
                    }
                    $this->currentCurrencyFeedbacksScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentCurrencyFeedbacks !== null) {
                foreach ($this->collCurrentCurrencyFeedbacks as $referrerFK) {
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

        $this->modifiedColumns[CurrencyTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CurrencyTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CurrencyTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_ISO_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'iso_code';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SYMBOL)) {
            $modifiedColumns[':p' . $index++]  = 'symbol';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_IS_SYMBOL_BEFORE)) {
            $modifiedColumns[':p' . $index++]  = 'is_symbol_before';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_NOTES)) {
            $modifiedColumns[':p' . $index++]  = 'notes';
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = 'sortable_rank';
        }

        $sql = sprintf(
            'INSERT INTO currency (%s) VALUES (%s)',
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
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'iso_code':
                        $stmt->bindValue($identifier, $this->iso_code, PDO::PARAM_STR);
                        break;
                    case 'symbol':
                        $stmt->bindValue($identifier, $this->symbol, PDO::PARAM_STR);
                        break;
                    case 'is_symbol_before':
                        $stmt->bindValue($identifier, (int) $this->is_symbol_before, PDO::PARAM_INT);
                        break;
                    case 'notes':
                        $stmt->bindValue($identifier, $this->notes, PDO::PARAM_STR);
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
        $pos = CurrencyTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getISOCode();
                break;
            case 3:
                return $this->getSymbol();
                break;
            case 4:
                return $this->getIsSymbolBefore();
                break;
            case 5:
                return $this->getNotes();
                break;
            case 6:
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

        if (isset($alreadyDumpedObjects['Currency'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Currency'][$this->hashCode()] = true;
        $keys = CurrencyTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getISOCode(),
            $keys[3] => $this->getSymbol(),
            $keys[4] => $this->getIsSymbolBefore(),
            $keys[5] => $this->getNotes(),
            $keys[6] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCurrentCurrencyUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'CurrentCurrencyUsers';
                }

                $result[$key] = $this->collCurrentCurrencyUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentCurrencyCourseStreams) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'courseStreams';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'course_streams';
                        break;
                    default:
                        $key = 'CurrentCurrencyCourseStreams';
                }

                $result[$key] = $this->collCurrentCurrencyCourseStreams->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentDefaultCurrencyRates) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'currencyRates';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'currency_rates';
                        break;
                    default:
                        $key = 'CurrentDefaultCurrencyRates';
                }

                $result[$key] = $this->collCurrentDefaultCurrencyRates->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentToCurrencyRates) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'currencyRates';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'currency_rates';
                        break;
                    default:
                        $key = 'CurrentToCurrencyRates';
                }

                $result[$key] = $this->collCurrentToCurrencyRates->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentCurrencyFeedbacks) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'feedbacks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'feedbacks';
                        break;
                    default:
                        $key = 'CurrentCurrencyFeedbacks';
                }

                $result[$key] = $this->collCurrentCurrencyFeedbacks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\Currency
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CurrencyTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Currency
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
                $this->setISOCode($value);
                break;
            case 3:
                $this->setSymbol($value);
                break;
            case 4:
                $this->setIsSymbolBefore($value);
                break;
            case 5:
                $this->setNotes($value);
                break;
            case 6:
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
        $keys = CurrencyTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setISOCode($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSymbol($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setIsSymbolBefore($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setNotes($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSortableRank($arr[$keys[6]]);
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
     * @return $this|\Models\Currency The current object, for fluid interface
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
        $criteria = new Criteria(CurrencyTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CurrencyTableMap::COL_ID)) {
            $criteria->add(CurrencyTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_NAME)) {
            $criteria->add(CurrencyTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_ISO_CODE)) {
            $criteria->add(CurrencyTableMap::COL_ISO_CODE, $this->iso_code);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SYMBOL)) {
            $criteria->add(CurrencyTableMap::COL_SYMBOL, $this->symbol);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_IS_SYMBOL_BEFORE)) {
            $criteria->add(CurrencyTableMap::COL_IS_SYMBOL_BEFORE, $this->is_symbol_before);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_NOTES)) {
            $criteria->add(CurrencyTableMap::COL_NOTES, $this->notes);
        }
        if ($this->isColumnModified(CurrencyTableMap::COL_SORTABLE_RANK)) {
            $criteria->add(CurrencyTableMap::COL_SORTABLE_RANK, $this->sortable_rank);
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
        $criteria = ChildCurrencyQuery::create();
        $criteria->add(CurrencyTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Currency (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setISOCode($this->getISOCode());
        $copyObj->setSymbol($this->getSymbol());
        $copyObj->setIsSymbolBefore($this->getIsSymbolBefore());
        $copyObj->setNotes($this->getNotes());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCurrentCurrencyUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentCurrencyUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentCurrencyCourseStreams() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentCurrencyCourseStream($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentDefaultCurrencyRates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentDefaultCurrencyRate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentToCurrencyRates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentToCurrencyRate($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentCurrencyFeedbacks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentCurrencyFeedback($relObj->copy($deepCopy));
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
     * @return \Models\Currency Clone of current object.
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
        if ('CurrentCurrencyUser' == $relationName) {
            $this->initCurrentCurrencyUsers();
            return;
        }
        if ('CurrentCurrencyCourseStream' == $relationName) {
            $this->initCurrentCurrencyCourseStreams();
            return;
        }
        if ('CurrentDefaultCurrencyRate' == $relationName) {
            $this->initCurrentDefaultCurrencyRates();
            return;
        }
        if ('CurrentToCurrencyRate' == $relationName) {
            $this->initCurrentToCurrencyRates();
            return;
        }
        if ('CurrentCurrencyFeedback' == $relationName) {
            $this->initCurrentCurrencyFeedbacks();
            return;
        }
    }

    /**
     * Clears out the collCurrentCurrencyUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentCurrencyUsers()
     */
    public function clearCurrentCurrencyUsers()
    {
        $this->collCurrentCurrencyUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentCurrencyUsers collection loaded partially.
     */
    public function resetPartialCurrentCurrencyUsers($v = true)
    {
        $this->collCurrentCurrencyUsersPartial = $v;
    }

    /**
     * Initializes the collCurrentCurrencyUsers collection.
     *
     * By default this just sets the collCurrentCurrencyUsers collection to an empty array (like clearcollCurrentCurrencyUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentCurrencyUsers($overrideExisting = true)
    {
        if (null !== $this->collCurrentCurrencyUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentCurrencyUsers = new $collectionClassName;
        $this->collCurrentCurrencyUsers->setModel('\Models\User');
    }

    /**
     * Gets an array of ChildUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCurrency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     * @throws PropelException
     */
    public function getCurrentCurrencyUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCurrencyUsersPartial && !$this->isNew();
        if (null === $this->collCurrentCurrencyUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentCurrencyUsers) {
                // return empty collection
                $this->initCurrentCurrencyUsers();
            } else {
                $collCurrentCurrencyUsers = ChildUserQuery::create(null, $criteria)
                    ->filterByCurrentUserCurrency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentCurrencyUsersPartial && count($collCurrentCurrencyUsers)) {
                        $this->initCurrentCurrencyUsers(false);

                        foreach ($collCurrentCurrencyUsers as $obj) {
                            if (false == $this->collCurrentCurrencyUsers->contains($obj)) {
                                $this->collCurrentCurrencyUsers->append($obj);
                            }
                        }

                        $this->collCurrentCurrencyUsersPartial = true;
                    }

                    return $collCurrentCurrencyUsers;
                }

                if ($partial && $this->collCurrentCurrencyUsers) {
                    foreach ($this->collCurrentCurrencyUsers as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentCurrencyUsers[] = $obj;
                        }
                    }
                }

                $this->collCurrentCurrencyUsers = $collCurrentCurrencyUsers;
                $this->collCurrentCurrencyUsersPartial = false;
            }
        }

        return $this->collCurrentCurrencyUsers;
    }

    /**
     * Sets a collection of ChildUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentCurrencyUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function setCurrentCurrencyUsers(Collection $currentCurrencyUsers, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $currentCurrencyUsersToDelete */
        $currentCurrencyUsersToDelete = $this->getCurrentCurrencyUsers(new Criteria(), $con)->diff($currentCurrencyUsers);


        $this->currentCurrencyUsersScheduledForDeletion = $currentCurrencyUsersToDelete;

        foreach ($currentCurrencyUsersToDelete as $currentCurrencyUserRemoved) {
            $currentCurrencyUserRemoved->setCurrentUserCurrency(null);
        }

        $this->collCurrentCurrencyUsers = null;
        foreach ($currentCurrencyUsers as $currentCurrencyUser) {
            $this->addCurrentCurrencyUser($currentCurrencyUser);
        }

        $this->collCurrentCurrencyUsers = $currentCurrencyUsers;
        $this->collCurrentCurrencyUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countCurrentCurrencyUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCurrencyUsersPartial && !$this->isNew();
        if (null === $this->collCurrentCurrencyUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentCurrencyUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentCurrencyUsers());
            }

            $query = ChildUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentUserCurrency($this)
                ->count($con);
        }

        return count($this->collCurrentCurrencyUsers);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function addCurrentCurrencyUser(ChildUser $l)
    {
        if ($this->collCurrentCurrencyUsers === null) {
            $this->initCurrentCurrencyUsers();
            $this->collCurrentCurrencyUsersPartial = true;
        }

        if (!$this->collCurrentCurrencyUsers->contains($l)) {
            $this->doAddCurrentCurrencyUser($l);

            if ($this->currentCurrencyUsersScheduledForDeletion and $this->currentCurrencyUsersScheduledForDeletion->contains($l)) {
                $this->currentCurrencyUsersScheduledForDeletion->remove($this->currentCurrencyUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUser $currentCurrencyUser The ChildUser object to add.
     */
    protected function doAddCurrentCurrencyUser(ChildUser $currentCurrencyUser)
    {
        $this->collCurrentCurrencyUsers[]= $currentCurrencyUser;
        $currentCurrencyUser->setCurrentUserCurrency($this);
    }

    /**
     * @param  ChildUser $currentCurrencyUser The ChildUser object to remove.
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function removeCurrentCurrencyUser(ChildUser $currentCurrencyUser)
    {
        if ($this->getCurrentCurrencyUsers()->contains($currentCurrencyUser)) {
            $pos = $this->collCurrentCurrencyUsers->search($currentCurrencyUser);
            $this->collCurrentCurrencyUsers->remove($pos);
            if (null === $this->currentCurrencyUsersScheduledForDeletion) {
                $this->currentCurrencyUsersScheduledForDeletion = clone $this->collCurrentCurrencyUsers;
                $this->currentCurrencyUsersScheduledForDeletion->clear();
            }
            $this->currentCurrencyUsersScheduledForDeletion[]= $currentCurrencyUser;
            $currentCurrencyUser->setCurrentUserCurrency(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related CurrentCurrencyUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getCurrentCurrencyUsersJoinCurrentGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserQuery::create(null, $criteria);
        $query->joinWith('CurrentGroup', $joinBehavior);

        return $this->getCurrentCurrencyUsers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related CurrentCurrencyUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getCurrentCurrencyUsersJoinCurrentAdminStyle(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserQuery::create(null, $criteria);
        $query->joinWith('CurrentAdminStyle', $joinBehavior);

        return $this->getCurrentCurrencyUsers($query, $con);
    }

    /**
     * Clears out the collCurrentCurrencyCourseStreams collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentCurrencyCourseStreams()
     */
    public function clearCurrentCurrencyCourseStreams()
    {
        $this->collCurrentCurrencyCourseStreams = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentCurrencyCourseStreams collection loaded partially.
     */
    public function resetPartialCurrentCurrencyCourseStreams($v = true)
    {
        $this->collCurrentCurrencyCourseStreamsPartial = $v;
    }

    /**
     * Initializes the collCurrentCurrencyCourseStreams collection.
     *
     * By default this just sets the collCurrentCurrencyCourseStreams collection to an empty array (like clearcollCurrentCurrencyCourseStreams());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentCurrencyCourseStreams($overrideExisting = true)
    {
        if (null !== $this->collCurrentCurrencyCourseStreams && !$overrideExisting) {
            return;
        }

        $collectionClassName = CourseStreamTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentCurrencyCourseStreams = new $collectionClassName;
        $this->collCurrentCurrencyCourseStreams->setModel('\Models\CourseStream');
    }

    /**
     * Gets an array of ChildCourseStream objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCurrency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     * @throws PropelException
     */
    public function getCurrentCurrencyCourseStreams(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCurrencyCourseStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentCurrencyCourseStreams || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentCurrencyCourseStreams) {
                // return empty collection
                $this->initCurrentCurrencyCourseStreams();
            } else {
                $collCurrentCurrencyCourseStreams = ChildCourseStreamQuery::create(null, $criteria)
                    ->filterByCurrentCourseStreamCurrency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentCurrencyCourseStreamsPartial && count($collCurrentCurrencyCourseStreams)) {
                        $this->initCurrentCurrencyCourseStreams(false);

                        foreach ($collCurrentCurrencyCourseStreams as $obj) {
                            if (false == $this->collCurrentCurrencyCourseStreams->contains($obj)) {
                                $this->collCurrentCurrencyCourseStreams->append($obj);
                            }
                        }

                        $this->collCurrentCurrencyCourseStreamsPartial = true;
                    }

                    return $collCurrentCurrencyCourseStreams;
                }

                if ($partial && $this->collCurrentCurrencyCourseStreams) {
                    foreach ($this->collCurrentCurrencyCourseStreams as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentCurrencyCourseStreams[] = $obj;
                        }
                    }
                }

                $this->collCurrentCurrencyCourseStreams = $collCurrentCurrencyCourseStreams;
                $this->collCurrentCurrencyCourseStreamsPartial = false;
            }
        }

        return $this->collCurrentCurrencyCourseStreams;
    }

    /**
     * Sets a collection of ChildCourseStream objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentCurrencyCourseStreams A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function setCurrentCurrencyCourseStreams(Collection $currentCurrencyCourseStreams, ConnectionInterface $con = null)
    {
        /** @var ChildCourseStream[] $currentCurrencyCourseStreamsToDelete */
        $currentCurrencyCourseStreamsToDelete = $this->getCurrentCurrencyCourseStreams(new Criteria(), $con)->diff($currentCurrencyCourseStreams);


        $this->currentCurrencyCourseStreamsScheduledForDeletion = $currentCurrencyCourseStreamsToDelete;

        foreach ($currentCurrencyCourseStreamsToDelete as $currentCurrencyCourseStreamRemoved) {
            $currentCurrencyCourseStreamRemoved->setCurrentCourseStreamCurrency(null);
        }

        $this->collCurrentCurrencyCourseStreams = null;
        foreach ($currentCurrencyCourseStreams as $currentCurrencyCourseStream) {
            $this->addCurrentCurrencyCourseStream($currentCurrencyCourseStream);
        }

        $this->collCurrentCurrencyCourseStreams = $currentCurrencyCourseStreams;
        $this->collCurrentCurrencyCourseStreamsPartial = false;

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
    public function countCurrentCurrencyCourseStreams(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCurrencyCourseStreamsPartial && !$this->isNew();
        if (null === $this->collCurrentCurrencyCourseStreams || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentCurrencyCourseStreams) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentCurrencyCourseStreams());
            }

            $query = ChildCourseStreamQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentCourseStreamCurrency($this)
                ->count($con);
        }

        return count($this->collCurrentCurrencyCourseStreams);
    }

    /**
     * Method called to associate a ChildCourseStream object to this object
     * through the ChildCourseStream foreign key attribute.
     *
     * @param  ChildCourseStream $l ChildCourseStream
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function addCurrentCurrencyCourseStream(ChildCourseStream $l)
    {
        if ($this->collCurrentCurrencyCourseStreams === null) {
            $this->initCurrentCurrencyCourseStreams();
            $this->collCurrentCurrencyCourseStreamsPartial = true;
        }

        if (!$this->collCurrentCurrencyCourseStreams->contains($l)) {
            $this->doAddCurrentCurrencyCourseStream($l);

            if ($this->currentCurrencyCourseStreamsScheduledForDeletion and $this->currentCurrencyCourseStreamsScheduledForDeletion->contains($l)) {
                $this->currentCurrencyCourseStreamsScheduledForDeletion->remove($this->currentCurrencyCourseStreamsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCourseStream $currentCurrencyCourseStream The ChildCourseStream object to add.
     */
    protected function doAddCurrentCurrencyCourseStream(ChildCourseStream $currentCurrencyCourseStream)
    {
        $this->collCurrentCurrencyCourseStreams[]= $currentCurrencyCourseStream;
        $currentCurrencyCourseStream->setCurrentCourseStreamCurrency($this);
    }

    /**
     * @param  ChildCourseStream $currentCurrencyCourseStream The ChildCourseStream object to remove.
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function removeCurrentCurrencyCourseStream(ChildCourseStream $currentCurrencyCourseStream)
    {
        if ($this->getCurrentCurrencyCourseStreams()->contains($currentCurrencyCourseStream)) {
            $pos = $this->collCurrentCurrencyCourseStreams->search($currentCurrencyCourseStream);
            $this->collCurrentCurrencyCourseStreams->remove($pos);
            if (null === $this->currentCurrencyCourseStreamsScheduledForDeletion) {
                $this->currentCurrencyCourseStreamsScheduledForDeletion = clone $this->collCurrentCurrencyCourseStreams;
                $this->currentCurrencyCourseStreamsScheduledForDeletion->clear();
            }
            $this->currentCurrencyCourseStreamsScheduledForDeletion[]= clone $currentCurrencyCourseStream;
            $currentCurrencyCourseStream->setCurrentCourseStreamCurrency(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related CurrentCurrencyCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentCurrencyCourseStreamsJoinCurrentCourseStreamBranch(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamBranch', $joinBehavior);

        return $this->getCurrentCurrencyCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related CurrentCurrencyCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentCurrencyCourseStreamsJoinCurrentCourseCourseStream(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseCourseStream', $joinBehavior);

        return $this->getCurrentCurrencyCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related CurrentCurrencyCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentCurrencyCourseStreamsJoinCurrentCourseCourseStreamStatus(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseCourseStreamStatus', $joinBehavior);

        return $this->getCurrentCurrencyCourseStreams($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related CurrentCurrencyCourseStreams from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCourseStream[] List of ChildCourseStream objects
     */
    public function getCurrentCurrencyCourseStreamsJoinCurrentCourseStreamInstructor(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCourseStreamQuery::create(null, $criteria);
        $query->joinWith('CurrentCourseStreamInstructor', $joinBehavior);

        return $this->getCurrentCurrencyCourseStreams($query, $con);
    }

    /**
     * Clears out the collCurrentDefaultCurrencyRates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentDefaultCurrencyRates()
     */
    public function clearCurrentDefaultCurrencyRates()
    {
        $this->collCurrentDefaultCurrencyRates = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentDefaultCurrencyRates collection loaded partially.
     */
    public function resetPartialCurrentDefaultCurrencyRates($v = true)
    {
        $this->collCurrentDefaultCurrencyRatesPartial = $v;
    }

    /**
     * Initializes the collCurrentDefaultCurrencyRates collection.
     *
     * By default this just sets the collCurrentDefaultCurrencyRates collection to an empty array (like clearcollCurrentDefaultCurrencyRates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentDefaultCurrencyRates($overrideExisting = true)
    {
        if (null !== $this->collCurrentDefaultCurrencyRates && !$overrideExisting) {
            return;
        }

        $collectionClassName = CurrencyRateTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentDefaultCurrencyRates = new $collectionClassName;
        $this->collCurrentDefaultCurrencyRates->setModel('\Models\CurrencyRate');
    }

    /**
     * Gets an array of ChildCurrencyRate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCurrency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCurrencyRate[] List of ChildCurrencyRate objects
     * @throws PropelException
     */
    public function getCurrentDefaultCurrencyRates(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentDefaultCurrencyRatesPartial && !$this->isNew();
        if (null === $this->collCurrentDefaultCurrencyRates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentDefaultCurrencyRates) {
                // return empty collection
                $this->initCurrentDefaultCurrencyRates();
            } else {
                $collCurrentDefaultCurrencyRates = ChildCurrencyRateQuery::create(null, $criteria)
                    ->filterByCurrentDefaultCurrency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentDefaultCurrencyRatesPartial && count($collCurrentDefaultCurrencyRates)) {
                        $this->initCurrentDefaultCurrencyRates(false);

                        foreach ($collCurrentDefaultCurrencyRates as $obj) {
                            if (false == $this->collCurrentDefaultCurrencyRates->contains($obj)) {
                                $this->collCurrentDefaultCurrencyRates->append($obj);
                            }
                        }

                        $this->collCurrentDefaultCurrencyRatesPartial = true;
                    }

                    return $collCurrentDefaultCurrencyRates;
                }

                if ($partial && $this->collCurrentDefaultCurrencyRates) {
                    foreach ($this->collCurrentDefaultCurrencyRates as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentDefaultCurrencyRates[] = $obj;
                        }
                    }
                }

                $this->collCurrentDefaultCurrencyRates = $collCurrentDefaultCurrencyRates;
                $this->collCurrentDefaultCurrencyRatesPartial = false;
            }
        }

        return $this->collCurrentDefaultCurrencyRates;
    }

    /**
     * Sets a collection of ChildCurrencyRate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentDefaultCurrencyRates A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function setCurrentDefaultCurrencyRates(Collection $currentDefaultCurrencyRates, ConnectionInterface $con = null)
    {
        /** @var ChildCurrencyRate[] $currentDefaultCurrencyRatesToDelete */
        $currentDefaultCurrencyRatesToDelete = $this->getCurrentDefaultCurrencyRates(new Criteria(), $con)->diff($currentDefaultCurrencyRates);


        $this->currentDefaultCurrencyRatesScheduledForDeletion = $currentDefaultCurrencyRatesToDelete;

        foreach ($currentDefaultCurrencyRatesToDelete as $currentDefaultCurrencyRateRemoved) {
            $currentDefaultCurrencyRateRemoved->setCurrentDefaultCurrency(null);
        }

        $this->collCurrentDefaultCurrencyRates = null;
        foreach ($currentDefaultCurrencyRates as $currentDefaultCurrencyRate) {
            $this->addCurrentDefaultCurrencyRate($currentDefaultCurrencyRate);
        }

        $this->collCurrentDefaultCurrencyRates = $currentDefaultCurrencyRates;
        $this->collCurrentDefaultCurrencyRatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CurrencyRate objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CurrencyRate objects.
     * @throws PropelException
     */
    public function countCurrentDefaultCurrencyRates(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentDefaultCurrencyRatesPartial && !$this->isNew();
        if (null === $this->collCurrentDefaultCurrencyRates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentDefaultCurrencyRates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentDefaultCurrencyRates());
            }

            $query = ChildCurrencyRateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentDefaultCurrency($this)
                ->count($con);
        }

        return count($this->collCurrentDefaultCurrencyRates);
    }

    /**
     * Method called to associate a ChildCurrencyRate object to this object
     * through the ChildCurrencyRate foreign key attribute.
     *
     * @param  ChildCurrencyRate $l ChildCurrencyRate
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function addCurrentDefaultCurrencyRate(ChildCurrencyRate $l)
    {
        if ($this->collCurrentDefaultCurrencyRates === null) {
            $this->initCurrentDefaultCurrencyRates();
            $this->collCurrentDefaultCurrencyRatesPartial = true;
        }

        if (!$this->collCurrentDefaultCurrencyRates->contains($l)) {
            $this->doAddCurrentDefaultCurrencyRate($l);

            if ($this->currentDefaultCurrencyRatesScheduledForDeletion and $this->currentDefaultCurrencyRatesScheduledForDeletion->contains($l)) {
                $this->currentDefaultCurrencyRatesScheduledForDeletion->remove($this->currentDefaultCurrencyRatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCurrencyRate $currentDefaultCurrencyRate The ChildCurrencyRate object to add.
     */
    protected function doAddCurrentDefaultCurrencyRate(ChildCurrencyRate $currentDefaultCurrencyRate)
    {
        $this->collCurrentDefaultCurrencyRates[]= $currentDefaultCurrencyRate;
        $currentDefaultCurrencyRate->setCurrentDefaultCurrency($this);
    }

    /**
     * @param  ChildCurrencyRate $currentDefaultCurrencyRate The ChildCurrencyRate object to remove.
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function removeCurrentDefaultCurrencyRate(ChildCurrencyRate $currentDefaultCurrencyRate)
    {
        if ($this->getCurrentDefaultCurrencyRates()->contains($currentDefaultCurrencyRate)) {
            $pos = $this->collCurrentDefaultCurrencyRates->search($currentDefaultCurrencyRate);
            $this->collCurrentDefaultCurrencyRates->remove($pos);
            if (null === $this->currentDefaultCurrencyRatesScheduledForDeletion) {
                $this->currentDefaultCurrencyRatesScheduledForDeletion = clone $this->collCurrentDefaultCurrencyRates;
                $this->currentDefaultCurrencyRatesScheduledForDeletion->clear();
            }
            $this->currentDefaultCurrencyRatesScheduledForDeletion[]= clone $currentDefaultCurrencyRate;
            $currentDefaultCurrencyRate->setCurrentDefaultCurrency(null);
        }

        return $this;
    }

    /**
     * Clears out the collCurrentToCurrencyRates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentToCurrencyRates()
     */
    public function clearCurrentToCurrencyRates()
    {
        $this->collCurrentToCurrencyRates = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentToCurrencyRates collection loaded partially.
     */
    public function resetPartialCurrentToCurrencyRates($v = true)
    {
        $this->collCurrentToCurrencyRatesPartial = $v;
    }

    /**
     * Initializes the collCurrentToCurrencyRates collection.
     *
     * By default this just sets the collCurrentToCurrencyRates collection to an empty array (like clearcollCurrentToCurrencyRates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentToCurrencyRates($overrideExisting = true)
    {
        if (null !== $this->collCurrentToCurrencyRates && !$overrideExisting) {
            return;
        }

        $collectionClassName = CurrencyRateTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentToCurrencyRates = new $collectionClassName;
        $this->collCurrentToCurrencyRates->setModel('\Models\CurrencyRate');
    }

    /**
     * Gets an array of ChildCurrencyRate objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCurrency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCurrencyRate[] List of ChildCurrencyRate objects
     * @throws PropelException
     */
    public function getCurrentToCurrencyRates(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentToCurrencyRatesPartial && !$this->isNew();
        if (null === $this->collCurrentToCurrencyRates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentToCurrencyRates) {
                // return empty collection
                $this->initCurrentToCurrencyRates();
            } else {
                $collCurrentToCurrencyRates = ChildCurrencyRateQuery::create(null, $criteria)
                    ->filterByCurrentToCurrency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentToCurrencyRatesPartial && count($collCurrentToCurrencyRates)) {
                        $this->initCurrentToCurrencyRates(false);

                        foreach ($collCurrentToCurrencyRates as $obj) {
                            if (false == $this->collCurrentToCurrencyRates->contains($obj)) {
                                $this->collCurrentToCurrencyRates->append($obj);
                            }
                        }

                        $this->collCurrentToCurrencyRatesPartial = true;
                    }

                    return $collCurrentToCurrencyRates;
                }

                if ($partial && $this->collCurrentToCurrencyRates) {
                    foreach ($this->collCurrentToCurrencyRates as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentToCurrencyRates[] = $obj;
                        }
                    }
                }

                $this->collCurrentToCurrencyRates = $collCurrentToCurrencyRates;
                $this->collCurrentToCurrencyRatesPartial = false;
            }
        }

        return $this->collCurrentToCurrencyRates;
    }

    /**
     * Sets a collection of ChildCurrencyRate objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentToCurrencyRates A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function setCurrentToCurrencyRates(Collection $currentToCurrencyRates, ConnectionInterface $con = null)
    {
        /** @var ChildCurrencyRate[] $currentToCurrencyRatesToDelete */
        $currentToCurrencyRatesToDelete = $this->getCurrentToCurrencyRates(new Criteria(), $con)->diff($currentToCurrencyRates);


        $this->currentToCurrencyRatesScheduledForDeletion = $currentToCurrencyRatesToDelete;

        foreach ($currentToCurrencyRatesToDelete as $currentToCurrencyRateRemoved) {
            $currentToCurrencyRateRemoved->setCurrentToCurrency(null);
        }

        $this->collCurrentToCurrencyRates = null;
        foreach ($currentToCurrencyRates as $currentToCurrencyRate) {
            $this->addCurrentToCurrencyRate($currentToCurrencyRate);
        }

        $this->collCurrentToCurrencyRates = $currentToCurrencyRates;
        $this->collCurrentToCurrencyRatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CurrencyRate objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CurrencyRate objects.
     * @throws PropelException
     */
    public function countCurrentToCurrencyRates(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentToCurrencyRatesPartial && !$this->isNew();
        if (null === $this->collCurrentToCurrencyRates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentToCurrencyRates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentToCurrencyRates());
            }

            $query = ChildCurrencyRateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentToCurrency($this)
                ->count($con);
        }

        return count($this->collCurrentToCurrencyRates);
    }

    /**
     * Method called to associate a ChildCurrencyRate object to this object
     * through the ChildCurrencyRate foreign key attribute.
     *
     * @param  ChildCurrencyRate $l ChildCurrencyRate
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function addCurrentToCurrencyRate(ChildCurrencyRate $l)
    {
        if ($this->collCurrentToCurrencyRates === null) {
            $this->initCurrentToCurrencyRates();
            $this->collCurrentToCurrencyRatesPartial = true;
        }

        if (!$this->collCurrentToCurrencyRates->contains($l)) {
            $this->doAddCurrentToCurrencyRate($l);

            if ($this->currentToCurrencyRatesScheduledForDeletion and $this->currentToCurrencyRatesScheduledForDeletion->contains($l)) {
                $this->currentToCurrencyRatesScheduledForDeletion->remove($this->currentToCurrencyRatesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCurrencyRate $currentToCurrencyRate The ChildCurrencyRate object to add.
     */
    protected function doAddCurrentToCurrencyRate(ChildCurrencyRate $currentToCurrencyRate)
    {
        $this->collCurrentToCurrencyRates[]= $currentToCurrencyRate;
        $currentToCurrencyRate->setCurrentToCurrency($this);
    }

    /**
     * @param  ChildCurrencyRate $currentToCurrencyRate The ChildCurrencyRate object to remove.
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function removeCurrentToCurrencyRate(ChildCurrencyRate $currentToCurrencyRate)
    {
        if ($this->getCurrentToCurrencyRates()->contains($currentToCurrencyRate)) {
            $pos = $this->collCurrentToCurrencyRates->search($currentToCurrencyRate);
            $this->collCurrentToCurrencyRates->remove($pos);
            if (null === $this->currentToCurrencyRatesScheduledForDeletion) {
                $this->currentToCurrencyRatesScheduledForDeletion = clone $this->collCurrentToCurrencyRates;
                $this->currentToCurrencyRatesScheduledForDeletion->clear();
            }
            $this->currentToCurrencyRatesScheduledForDeletion[]= clone $currentToCurrencyRate;
            $currentToCurrencyRate->setCurrentToCurrency(null);
        }

        return $this;
    }

    /**
     * Clears out the collCurrentCurrencyFeedbacks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentCurrencyFeedbacks()
     */
    public function clearCurrentCurrencyFeedbacks()
    {
        $this->collCurrentCurrencyFeedbacks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentCurrencyFeedbacks collection loaded partially.
     */
    public function resetPartialCurrentCurrencyFeedbacks($v = true)
    {
        $this->collCurrentCurrencyFeedbacksPartial = $v;
    }

    /**
     * Initializes the collCurrentCurrencyFeedbacks collection.
     *
     * By default this just sets the collCurrentCurrencyFeedbacks collection to an empty array (like clearcollCurrentCurrencyFeedbacks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentCurrencyFeedbacks($overrideExisting = true)
    {
        if (null !== $this->collCurrentCurrencyFeedbacks && !$overrideExisting) {
            return;
        }

        $collectionClassName = FeedbackTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentCurrencyFeedbacks = new $collectionClassName;
        $this->collCurrentCurrencyFeedbacks->setModel('\Models\Feedback');
    }

    /**
     * Gets an array of ChildFeedback objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCurrency is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFeedback[] List of ChildFeedback objects
     * @throws PropelException
     */
    public function getCurrentCurrencyFeedbacks(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCurrencyFeedbacksPartial && !$this->isNew();
        if (null === $this->collCurrentCurrencyFeedbacks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentCurrencyFeedbacks) {
                // return empty collection
                $this->initCurrentCurrencyFeedbacks();
            } else {
                $collCurrentCurrencyFeedbacks = ChildFeedbackQuery::create(null, $criteria)
                    ->filterByCurrentFeedbackCurrency($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentCurrencyFeedbacksPartial && count($collCurrentCurrencyFeedbacks)) {
                        $this->initCurrentCurrencyFeedbacks(false);

                        foreach ($collCurrentCurrencyFeedbacks as $obj) {
                            if (false == $this->collCurrentCurrencyFeedbacks->contains($obj)) {
                                $this->collCurrentCurrencyFeedbacks->append($obj);
                            }
                        }

                        $this->collCurrentCurrencyFeedbacksPartial = true;
                    }

                    return $collCurrentCurrencyFeedbacks;
                }

                if ($partial && $this->collCurrentCurrencyFeedbacks) {
                    foreach ($this->collCurrentCurrencyFeedbacks as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentCurrencyFeedbacks[] = $obj;
                        }
                    }
                }

                $this->collCurrentCurrencyFeedbacks = $collCurrentCurrencyFeedbacks;
                $this->collCurrentCurrencyFeedbacksPartial = false;
            }
        }

        return $this->collCurrentCurrencyFeedbacks;
    }

    /**
     * Sets a collection of ChildFeedback objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentCurrencyFeedbacks A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function setCurrentCurrencyFeedbacks(Collection $currentCurrencyFeedbacks, ConnectionInterface $con = null)
    {
        /** @var ChildFeedback[] $currentCurrencyFeedbacksToDelete */
        $currentCurrencyFeedbacksToDelete = $this->getCurrentCurrencyFeedbacks(new Criteria(), $con)->diff($currentCurrencyFeedbacks);


        $this->currentCurrencyFeedbacksScheduledForDeletion = $currentCurrencyFeedbacksToDelete;

        foreach ($currentCurrencyFeedbacksToDelete as $currentCurrencyFeedbackRemoved) {
            $currentCurrencyFeedbackRemoved->setCurrentFeedbackCurrency(null);
        }

        $this->collCurrentCurrencyFeedbacks = null;
        foreach ($currentCurrencyFeedbacks as $currentCurrencyFeedback) {
            $this->addCurrentCurrencyFeedback($currentCurrencyFeedback);
        }

        $this->collCurrentCurrencyFeedbacks = $currentCurrencyFeedbacks;
        $this->collCurrentCurrencyFeedbacksPartial = false;

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
    public function countCurrentCurrencyFeedbacks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentCurrencyFeedbacksPartial && !$this->isNew();
        if (null === $this->collCurrentCurrencyFeedbacks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentCurrencyFeedbacks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentCurrencyFeedbacks());
            }

            $query = ChildFeedbackQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentFeedbackCurrency($this)
                ->count($con);
        }

        return count($this->collCurrentCurrencyFeedbacks);
    }

    /**
     * Method called to associate a ChildFeedback object to this object
     * through the ChildFeedback foreign key attribute.
     *
     * @param  ChildFeedback $l ChildFeedback
     * @return $this|\Models\Currency The current object (for fluent API support)
     */
    public function addCurrentCurrencyFeedback(ChildFeedback $l)
    {
        if ($this->collCurrentCurrencyFeedbacks === null) {
            $this->initCurrentCurrencyFeedbacks();
            $this->collCurrentCurrencyFeedbacksPartial = true;
        }

        if (!$this->collCurrentCurrencyFeedbacks->contains($l)) {
            $this->doAddCurrentCurrencyFeedback($l);

            if ($this->currentCurrencyFeedbacksScheduledForDeletion and $this->currentCurrencyFeedbacksScheduledForDeletion->contains($l)) {
                $this->currentCurrencyFeedbacksScheduledForDeletion->remove($this->currentCurrencyFeedbacksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildFeedback $currentCurrencyFeedback The ChildFeedback object to add.
     */
    protected function doAddCurrentCurrencyFeedback(ChildFeedback $currentCurrencyFeedback)
    {
        $this->collCurrentCurrencyFeedbacks[]= $currentCurrencyFeedback;
        $currentCurrencyFeedback->setCurrentFeedbackCurrency($this);
    }

    /**
     * @param  ChildFeedback $currentCurrencyFeedback The ChildFeedback object to remove.
     * @return $this|ChildCurrency The current object (for fluent API support)
     */
    public function removeCurrentCurrencyFeedback(ChildFeedback $currentCurrencyFeedback)
    {
        if ($this->getCurrentCurrencyFeedbacks()->contains($currentCurrencyFeedback)) {
            $pos = $this->collCurrentCurrencyFeedbacks->search($currentCurrencyFeedback);
            $this->collCurrentCurrencyFeedbacks->remove($pos);
            if (null === $this->currentCurrencyFeedbacksScheduledForDeletion) {
                $this->currentCurrencyFeedbacksScheduledForDeletion = clone $this->collCurrentCurrencyFeedbacks;
                $this->currentCurrencyFeedbacksScheduledForDeletion->clear();
            }
            $this->currentCurrencyFeedbacksScheduledForDeletion[]= $currentCurrencyFeedback;
            $currentCurrencyFeedback->setCurrentFeedbackCurrency(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Currency is new, it will return
     * an empty collection; or if this Currency has previously
     * been saved, it will retrieve related CurrentCurrencyFeedbacks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Currency.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFeedback[] List of ChildFeedback objects
     */
    public function getCurrentCurrencyFeedbacksJoinCurrentFeedbackUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFeedbackQuery::create(null, $criteria);
        $query->joinWith('CurrentFeedbackUser', $joinBehavior);

        return $this->getCurrentCurrencyFeedbacks($query, $con);
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
        $this->iso_code = null;
        $this->symbol = null;
        $this->is_symbol_before = null;
        $this->notes = null;
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
            if ($this->collCurrentCurrencyUsers) {
                foreach ($this->collCurrentCurrencyUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentCurrencyCourseStreams) {
                foreach ($this->collCurrentCurrencyCourseStreams as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentDefaultCurrencyRates) {
                foreach ($this->collCurrentDefaultCurrencyRates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentToCurrencyRates) {
                foreach ($this->collCurrentToCurrencyRates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentCurrencyFeedbacks) {
                foreach ($this->collCurrentCurrencyFeedbacks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCurrentCurrencyUsers = null;
        $this->collCurrentCurrencyCourseStreams = null;
        $this->collCurrentDefaultCurrencyRates = null;
        $this->collCurrentToCurrencyRates = null;
        $this->collCurrentCurrencyFeedbacks = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CurrencyTableMap::DEFAULT_STRING_FORMAT);
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
     * @return    $this|ChildCurrency
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
        return $this->getSortableRank() == ChildCurrencyQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildCurrency
     */
    public function getNext(ConnectionInterface $con = null)
    {

        $query = ChildCurrencyQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildCurrency
     */
    public function getPrevious(ConnectionInterface $con = null)
    {

        $query = ChildCurrencyQuery::create();

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
     * @return    $this|ChildCurrency the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, ConnectionInterface $con = null)
    {
        $maxRank = ChildCurrencyQuery::create()->getMaxRankArray($con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array('\Models\CurrencyQuery', 'sortableShiftRank'),
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
     * @return    $this|ChildCurrency the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(ConnectionInterface $con = null)
    {
        $this->setSortableRank(ChildCurrencyQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildCurrency the current object
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
     * @return    $this|ChildCurrency the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, ConnectionInterface $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > ChildCurrencyQuery::create()->getMaxRankArray($con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->transaction(function () use ($con, $oldRank, $newRank) {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            ChildCurrencyQuery::sortableShiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);
        });

        return $this;
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     ChildCurrency $object
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildCurrency the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
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
     * @return    $this|ChildCurrency the current object
     */
    public function moveUp(ConnectionInterface $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
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
     * @return    $this|ChildCurrency the current object
     */
    public function moveDown(ConnectionInterface $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
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
     * @return    $this|ChildCurrency the current object
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
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $bottom = ChildCurrencyQuery::create()->getMaxRankArray($con);

            return $this->moveToRank($bottom, $con);
        });
    }

    /**
     * Removes the current object from the list.
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildCurrency the current object
     */
    public function removeFromList()
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries[] = array(
            'callable'  => array('\Models\CurrencyQuery', 'sortableShiftRank'),
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
