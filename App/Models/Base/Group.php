<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Group as ChildGroup;
use Models\GroupPrivelege as ChildGroupPrivelege;
use Models\GroupPrivelegeQuery as ChildGroupPrivelegeQuery;
use Models\GroupQuery as ChildGroupQuery;
use Models\Privilege as ChildPrivilege;
use Models\PrivilegeQuery as ChildPrivilegeQuery;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\Map\GroupPrivelegeTableMap;
use Models\Map\GroupTableMap;
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
 * Base class that represents a row from the 'group' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class Group implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\GroupTableMap';


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
     * The value for the alt field.
     *
     * @var        string
     */
    protected $alt;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the allow_choose_group field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $allow_choose_group;

    /**
     * The value for the sortable_rank field.
     *
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        ObjectCollection|ChildGroupPrivelege[] Collection to store aggregation of ChildGroupPrivelege objects.
     */
    protected $collCurrentGroupGroupPriveleges;
    protected $collCurrentGroupGroupPrivelegesPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Collection to store aggregation of ChildUser objects.
     */
    protected $collCurrentGroupUsers;
    protected $collCurrentGroupUsersPartial;

    /**
     * @var        ObjectCollection|ChildPrivilege[] Cross Collection to store aggregation of ChildPrivilege objects.
     */
    protected $collCurrentPrivilegeGroupPriveleges;

    /**
     * @var bool
     */
    protected $collCurrentPrivilegeGroupPrivelegesPartial;

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
     * @var ObjectCollection|ChildPrivilege[]
     */
    protected $currentPrivilegeGroupPrivelegesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroupPrivelege[]
     */
    protected $currentGroupGroupPrivelegesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $currentGroupUsersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->allow_choose_group = false;
    }

    /**
     * Initializes internal state of Models\Base\Group object.
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
     * Compares this with another <code>Group</code> instance.  If
     * <code>obj</code> is an instance of <code>Group</code>, delegates to
     * <code>equals(Group)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Group The current object, for fluid interface
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
     * Get the [alt] column value.
     *
     * @return string
     */
    public function getAltName()
    {
        return $this->alt;
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
     * Get the [allow_choose_group] column value.
     *
     * @return boolean
     */
    public function getAllowChooseGroup()
    {
        return $this->allow_choose_group;
    }

    /**
     * Get the [allow_choose_group] column value.
     *
     * @return boolean
     */
    public function isAllowChooseGroup()
    {
        return $this->getAllowChooseGroup();
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
     * @return $this|\Models\Group The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[GroupTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [alt] column.
     *
     * @param string $v new value
     * @return $this|\Models\Group The current object (for fluent API support)
     */
    public function setAltName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alt !== $v) {
            $this->alt = $v;
            $this->modifiedColumns[GroupTableMap::COL_ALT] = true;
        }

        return $this;
    } // setAltName()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Group The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[GroupTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Sets the value of the [allow_choose_group] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Models\Group The current object (for fluent API support)
     */
    public function setAllowChooseGroup($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->allow_choose_group !== $v) {
            $this->allow_choose_group = $v;
            $this->modifiedColumns[GroupTableMap::COL_ALLOW_CHOOSE_GROUP] = true;
        }

        return $this;
    } // setAllowChooseGroup()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param int $v new value
     * @return $this|\Models\Group The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[GroupTableMap::COL_SORTABLE_RANK] = true;
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
            if ($this->allow_choose_group !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GroupTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GroupTableMap::translateFieldName('AltName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alt = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : GroupTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : GroupTableMap::translateFieldName('AllowChooseGroup', TableMap::TYPE_PHPNAME, $indexType)];
            $this->allow_choose_group = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : GroupTableMap::translateFieldName('SortableRank', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sortable_rank = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = GroupTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Group'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(GroupTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGroupQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCurrentGroupGroupPriveleges = null;

            $this->collCurrentGroupUsers = null;

            $this->collCurrentPrivilegeGroupPriveleges = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Group::setDeleted()
     * @see Group::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildGroupQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            ChildGroupQuery::sortableShiftRank(-1, $this->getSortableRank() + 1, null, $con);
            GroupTableMap::clearInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(GroupTableMap::RANK_COL)) {
                    $this->setSortableRank(ChildGroupQuery::create()->getMaxRankArray($con) + 1);
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
                GroupTableMap::addInstanceToPool($this);
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

            if ($this->currentPrivilegeGroupPrivelegesScheduledForDeletion !== null) {
                if (!$this->currentPrivilegeGroupPrivelegesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->currentPrivilegeGroupPrivelegesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\GroupPrivelegeQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->currentPrivilegeGroupPrivelegesScheduledForDeletion = null;
                }

            }

            if ($this->collCurrentPrivilegeGroupPriveleges) {
                foreach ($this->collCurrentPrivilegeGroupPriveleges as $currentPrivilegeGroupPrivelege) {
                    if (!$currentPrivilegeGroupPrivelege->isDeleted() && ($currentPrivilegeGroupPrivelege->isNew() || $currentPrivilegeGroupPrivelege->isModified())) {
                        $currentPrivilegeGroupPrivelege->save($con);
                    }
                }
            }


            if ($this->currentGroupGroupPrivelegesScheduledForDeletion !== null) {
                if (!$this->currentGroupGroupPrivelegesScheduledForDeletion->isEmpty()) {
                    \Models\GroupPrivelegeQuery::create()
                        ->filterByPrimaryKeys($this->currentGroupGroupPrivelegesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentGroupGroupPrivelegesScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentGroupGroupPriveleges !== null) {
                foreach ($this->collCurrentGroupGroupPriveleges as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->currentGroupUsersScheduledForDeletion !== null) {
                if (!$this->currentGroupUsersScheduledForDeletion->isEmpty()) {
                    \Models\UserQuery::create()
                        ->filterByPrimaryKeys($this->currentGroupUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentGroupUsersScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentGroupUsers !== null) {
                foreach ($this->collCurrentGroupUsers as $referrerFK) {
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

        $this->modifiedColumns[GroupTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GroupTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GroupTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(GroupTableMap::COL_ALT)) {
            $modifiedColumns[':p' . $index++]  = '`alt`';
        }
        if ($this->isColumnModified(GroupTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(GroupTableMap::COL_ALLOW_CHOOSE_GROUP)) {
            $modifiedColumns[':p' . $index++]  = '`allow_choose_group`';
        }
        if ($this->isColumnModified(GroupTableMap::COL_SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `group` (%s) VALUES (%s)',
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
                    case '`alt`':
                        $stmt->bindValue($identifier, $this->alt, PDO::PARAM_STR);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`allow_choose_group`':
                        $stmt->bindValue($identifier, (int) $this->allow_choose_group, PDO::PARAM_INT);
                        break;
                    case '`sortable_rank`':
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
        $pos = GroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAltName();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getAllowChooseGroup();
                break;
            case 4:
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

        if (isset($alreadyDumpedObjects['Group'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Group'][$this->hashCode()] = true;
        $keys = GroupTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAltName(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getAllowChooseGroup(),
            $keys[4] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCurrentGroupGroupPriveleges) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'groupPriveleges';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'group_privileges';
                        break;
                    default:
                        $key = 'CurrentGroupGroupPriveleges';
                }

                $result[$key] = $this->collCurrentGroupGroupPriveleges->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCurrentGroupUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'CurrentGroupUsers';
                }

                $result[$key] = $this->collCurrentGroupUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\Group
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = GroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Group
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setAltName($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setAllowChooseGroup($value);
                break;
            case 4:
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
        $keys = GroupTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAltName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAllowChooseGroup($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setSortableRank($arr[$keys[4]]);
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
     * @return $this|\Models\Group The current object, for fluid interface
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
        $criteria = new Criteria(GroupTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GroupTableMap::COL_ID)) {
            $criteria->add(GroupTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(GroupTableMap::COL_ALT)) {
            $criteria->add(GroupTableMap::COL_ALT, $this->alt);
        }
        if ($this->isColumnModified(GroupTableMap::COL_NAME)) {
            $criteria->add(GroupTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(GroupTableMap::COL_ALLOW_CHOOSE_GROUP)) {
            $criteria->add(GroupTableMap::COL_ALLOW_CHOOSE_GROUP, $this->allow_choose_group);
        }
        if ($this->isColumnModified(GroupTableMap::COL_SORTABLE_RANK)) {
            $criteria->add(GroupTableMap::COL_SORTABLE_RANK, $this->sortable_rank);
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
        $criteria = ChildGroupQuery::create();
        $criteria->add(GroupTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Group (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAltName($this->getAltName());
        $copyObj->setName($this->getName());
        $copyObj->setAllowChooseGroup($this->getAllowChooseGroup());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCurrentGroupGroupPriveleges() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentGroupGroupPrivelege($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCurrentGroupUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentGroupUser($relObj->copy($deepCopy));
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
     * @return \Models\Group Clone of current object.
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
        if ('CurrentGroupGroupPrivelege' == $relationName) {
            $this->initCurrentGroupGroupPriveleges();
            return;
        }
        if ('CurrentGroupUser' == $relationName) {
            $this->initCurrentGroupUsers();
            return;
        }
    }

    /**
     * Clears out the collCurrentGroupGroupPriveleges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentGroupGroupPriveleges()
     */
    public function clearCurrentGroupGroupPriveleges()
    {
        $this->collCurrentGroupGroupPriveleges = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentGroupGroupPriveleges collection loaded partially.
     */
    public function resetPartialCurrentGroupGroupPriveleges($v = true)
    {
        $this->collCurrentGroupGroupPrivelegesPartial = $v;
    }

    /**
     * Initializes the collCurrentGroupGroupPriveleges collection.
     *
     * By default this just sets the collCurrentGroupGroupPriveleges collection to an empty array (like clearcollCurrentGroupGroupPriveleges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentGroupGroupPriveleges($overrideExisting = true)
    {
        if (null !== $this->collCurrentGroupGroupPriveleges && !$overrideExisting) {
            return;
        }

        $collectionClassName = GroupPrivelegeTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentGroupGroupPriveleges = new $collectionClassName;
        $this->collCurrentGroupGroupPriveleges->setModel('\Models\GroupPrivelege');
    }

    /**
     * Gets an array of ChildGroupPrivelege objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGroupPrivelege[] List of ChildGroupPrivelege objects
     * @throws PropelException
     */
    public function getCurrentGroupGroupPriveleges(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentGroupGroupPrivelegesPartial && !$this->isNew();
        if (null === $this->collCurrentGroupGroupPriveleges || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentGroupGroupPriveleges) {
                // return empty collection
                $this->initCurrentGroupGroupPriveleges();
            } else {
                $collCurrentGroupGroupPriveleges = ChildGroupPrivelegeQuery::create(null, $criteria)
                    ->filterByCurrentGroupGroupPrivelege($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentGroupGroupPrivelegesPartial && count($collCurrentGroupGroupPriveleges)) {
                        $this->initCurrentGroupGroupPriveleges(false);

                        foreach ($collCurrentGroupGroupPriveleges as $obj) {
                            if (false == $this->collCurrentGroupGroupPriveleges->contains($obj)) {
                                $this->collCurrentGroupGroupPriveleges->append($obj);
                            }
                        }

                        $this->collCurrentGroupGroupPrivelegesPartial = true;
                    }

                    return $collCurrentGroupGroupPriveleges;
                }

                if ($partial && $this->collCurrentGroupGroupPriveleges) {
                    foreach ($this->collCurrentGroupGroupPriveleges as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentGroupGroupPriveleges[] = $obj;
                        }
                    }
                }

                $this->collCurrentGroupGroupPriveleges = $collCurrentGroupGroupPriveleges;
                $this->collCurrentGroupGroupPrivelegesPartial = false;
            }
        }

        return $this->collCurrentGroupGroupPriveleges;
    }

    /**
     * Sets a collection of ChildGroupPrivelege objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentGroupGroupPriveleges A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setCurrentGroupGroupPriveleges(Collection $currentGroupGroupPriveleges, ConnectionInterface $con = null)
    {
        /** @var ChildGroupPrivelege[] $currentGroupGroupPrivelegesToDelete */
        $currentGroupGroupPrivelegesToDelete = $this->getCurrentGroupGroupPriveleges(new Criteria(), $con)->diff($currentGroupGroupPriveleges);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->currentGroupGroupPrivelegesScheduledForDeletion = clone $currentGroupGroupPrivelegesToDelete;

        foreach ($currentGroupGroupPrivelegesToDelete as $currentGroupGroupPrivelegeRemoved) {
            $currentGroupGroupPrivelegeRemoved->setCurrentGroupGroupPrivelege(null);
        }

        $this->collCurrentGroupGroupPriveleges = null;
        foreach ($currentGroupGroupPriveleges as $currentGroupGroupPrivelege) {
            $this->addCurrentGroupGroupPrivelege($currentGroupGroupPrivelege);
        }

        $this->collCurrentGroupGroupPriveleges = $currentGroupGroupPriveleges;
        $this->collCurrentGroupGroupPrivelegesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GroupPrivelege objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GroupPrivelege objects.
     * @throws PropelException
     */
    public function countCurrentGroupGroupPriveleges(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentGroupGroupPrivelegesPartial && !$this->isNew();
        if (null === $this->collCurrentGroupGroupPriveleges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentGroupGroupPriveleges) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentGroupGroupPriveleges());
            }

            $query = ChildGroupPrivelegeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentGroupGroupPrivelege($this)
                ->count($con);
        }

        return count($this->collCurrentGroupGroupPriveleges);
    }

    /**
     * Method called to associate a ChildGroupPrivelege object to this object
     * through the ChildGroupPrivelege foreign key attribute.
     *
     * @param  ChildGroupPrivelege $l ChildGroupPrivelege
     * @return $this|\Models\Group The current object (for fluent API support)
     */
    public function addCurrentGroupGroupPrivelege(ChildGroupPrivelege $l)
    {
        if ($this->collCurrentGroupGroupPriveleges === null) {
            $this->initCurrentGroupGroupPriveleges();
            $this->collCurrentGroupGroupPrivelegesPartial = true;
        }

        if (!$this->collCurrentGroupGroupPriveleges->contains($l)) {
            $this->doAddCurrentGroupGroupPrivelege($l);

            if ($this->currentGroupGroupPrivelegesScheduledForDeletion and $this->currentGroupGroupPrivelegesScheduledForDeletion->contains($l)) {
                $this->currentGroupGroupPrivelegesScheduledForDeletion->remove($this->currentGroupGroupPrivelegesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGroupPrivelege $currentGroupGroupPrivelege The ChildGroupPrivelege object to add.
     */
    protected function doAddCurrentGroupGroupPrivelege(ChildGroupPrivelege $currentGroupGroupPrivelege)
    {
        $this->collCurrentGroupGroupPriveleges[]= $currentGroupGroupPrivelege;
        $currentGroupGroupPrivelege->setCurrentGroupGroupPrivelege($this);
    }

    /**
     * @param  ChildGroupPrivelege $currentGroupGroupPrivelege The ChildGroupPrivelege object to remove.
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function removeCurrentGroupGroupPrivelege(ChildGroupPrivelege $currentGroupGroupPrivelege)
    {
        if ($this->getCurrentGroupGroupPriveleges()->contains($currentGroupGroupPrivelege)) {
            $pos = $this->collCurrentGroupGroupPriveleges->search($currentGroupGroupPrivelege);
            $this->collCurrentGroupGroupPriveleges->remove($pos);
            if (null === $this->currentGroupGroupPrivelegesScheduledForDeletion) {
                $this->currentGroupGroupPrivelegesScheduledForDeletion = clone $this->collCurrentGroupGroupPriveleges;
                $this->currentGroupGroupPrivelegesScheduledForDeletion->clear();
            }
            $this->currentGroupGroupPrivelegesScheduledForDeletion[]= clone $currentGroupGroupPrivelege;
            $currentGroupGroupPrivelege->setCurrentGroupGroupPrivelege(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related CurrentGroupGroupPriveleges from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGroupPrivelege[] List of ChildGroupPrivelege objects
     */
    public function getCurrentGroupGroupPrivelegesJoinCurrentPrivilegeGroupPrivelege(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGroupPrivelegeQuery::create(null, $criteria);
        $query->joinWith('CurrentPrivilegeGroupPrivelege', $joinBehavior);

        return $this->getCurrentGroupGroupPriveleges($query, $con);
    }

    /**
     * Clears out the collCurrentGroupUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentGroupUsers()
     */
    public function clearCurrentGroupUsers()
    {
        $this->collCurrentGroupUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCurrentGroupUsers collection loaded partially.
     */
    public function resetPartialCurrentGroupUsers($v = true)
    {
        $this->collCurrentGroupUsersPartial = $v;
    }

    /**
     * Initializes the collCurrentGroupUsers collection.
     *
     * By default this just sets the collCurrentGroupUsers collection to an empty array (like clearcollCurrentGroupUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentGroupUsers($overrideExisting = true)
    {
        if (null !== $this->collCurrentGroupUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentGroupUsers = new $collectionClassName;
        $this->collCurrentGroupUsers->setModel('\Models\User');
    }

    /**
     * Gets an array of ChildUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     * @throws PropelException
     */
    public function getCurrentGroupUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentGroupUsersPartial && !$this->isNew();
        if (null === $this->collCurrentGroupUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentGroupUsers) {
                // return empty collection
                $this->initCurrentGroupUsers();
            } else {
                $collCurrentGroupUsers = ChildUserQuery::create(null, $criteria)
                    ->filterByCurrentGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentGroupUsersPartial && count($collCurrentGroupUsers)) {
                        $this->initCurrentGroupUsers(false);

                        foreach ($collCurrentGroupUsers as $obj) {
                            if (false == $this->collCurrentGroupUsers->contains($obj)) {
                                $this->collCurrentGroupUsers->append($obj);
                            }
                        }

                        $this->collCurrentGroupUsersPartial = true;
                    }

                    return $collCurrentGroupUsers;
                }

                if ($partial && $this->collCurrentGroupUsers) {
                    foreach ($this->collCurrentGroupUsers as $obj) {
                        if ($obj->isNew()) {
                            $collCurrentGroupUsers[] = $obj;
                        }
                    }
                }

                $this->collCurrentGroupUsers = $collCurrentGroupUsers;
                $this->collCurrentGroupUsersPartial = false;
            }
        }

        return $this->collCurrentGroupUsers;
    }

    /**
     * Sets a collection of ChildUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentGroupUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setCurrentGroupUsers(Collection $currentGroupUsers, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $currentGroupUsersToDelete */
        $currentGroupUsersToDelete = $this->getCurrentGroupUsers(new Criteria(), $con)->diff($currentGroupUsers);


        $this->currentGroupUsersScheduledForDeletion = $currentGroupUsersToDelete;

        foreach ($currentGroupUsersToDelete as $currentGroupUserRemoved) {
            $currentGroupUserRemoved->setCurrentGroup(null);
        }

        $this->collCurrentGroupUsers = null;
        foreach ($currentGroupUsers as $currentGroupUser) {
            $this->addCurrentGroupUser($currentGroupUser);
        }

        $this->collCurrentGroupUsers = $currentGroupUsers;
        $this->collCurrentGroupUsersPartial = false;

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
    public function countCurrentGroupUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentGroupUsersPartial && !$this->isNew();
        if (null === $this->collCurrentGroupUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentGroupUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentGroupUsers());
            }

            $query = ChildUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentGroup($this)
                ->count($con);
        }

        return count($this->collCurrentGroupUsers);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\Models\Group The current object (for fluent API support)
     */
    public function addCurrentGroupUser(ChildUser $l)
    {
        if ($this->collCurrentGroupUsers === null) {
            $this->initCurrentGroupUsers();
            $this->collCurrentGroupUsersPartial = true;
        }

        if (!$this->collCurrentGroupUsers->contains($l)) {
            $this->doAddCurrentGroupUser($l);

            if ($this->currentGroupUsersScheduledForDeletion and $this->currentGroupUsersScheduledForDeletion->contains($l)) {
                $this->currentGroupUsersScheduledForDeletion->remove($this->currentGroupUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUser $currentGroupUser The ChildUser object to add.
     */
    protected function doAddCurrentGroupUser(ChildUser $currentGroupUser)
    {
        $this->collCurrentGroupUsers[]= $currentGroupUser;
        $currentGroupUser->setCurrentGroup($this);
    }

    /**
     * @param  ChildUser $currentGroupUser The ChildUser object to remove.
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function removeCurrentGroupUser(ChildUser $currentGroupUser)
    {
        if ($this->getCurrentGroupUsers()->contains($currentGroupUser)) {
            $pos = $this->collCurrentGroupUsers->search($currentGroupUser);
            $this->collCurrentGroupUsers->remove($pos);
            if (null === $this->currentGroupUsersScheduledForDeletion) {
                $this->currentGroupUsersScheduledForDeletion = clone $this->collCurrentGroupUsers;
                $this->currentGroupUsersScheduledForDeletion->clear();
            }
            $this->currentGroupUsersScheduledForDeletion[]= clone $currentGroupUser;
            $currentGroupUser->setCurrentGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related CurrentGroupUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getCurrentGroupUsersJoinCurrentUserCurrency(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserQuery::create(null, $criteria);
        $query->joinWith('CurrentUserCurrency', $joinBehavior);

        return $this->getCurrentGroupUsers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related CurrentGroupUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getCurrentGroupUsersJoinCurrentAdminStyle(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserQuery::create(null, $criteria);
        $query->joinWith('CurrentAdminStyle', $joinBehavior);

        return $this->getCurrentGroupUsers($query, $con);
    }

    /**
     * Clears out the collCurrentPrivilegeGroupPriveleges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCurrentPrivilegeGroupPriveleges()
     */
    public function clearCurrentPrivilegeGroupPriveleges()
    {
        $this->collCurrentPrivilegeGroupPriveleges = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collCurrentPrivilegeGroupPriveleges crossRef collection.
     *
     * By default this just sets the collCurrentPrivilegeGroupPriveleges collection to an empty collection (like clearCurrentPrivilegeGroupPriveleges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCurrentPrivilegeGroupPriveleges()
    {
        $collectionClassName = GroupPrivelegeTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentPrivilegeGroupPriveleges = new $collectionClassName;
        $this->collCurrentPrivilegeGroupPrivelegesPartial = true;
        $this->collCurrentPrivilegeGroupPriveleges->setModel('\Models\Privilege');
    }

    /**
     * Checks if the collCurrentPrivilegeGroupPriveleges collection is loaded.
     *
     * @return bool
     */
    public function isCurrentPrivilegeGroupPrivelegesLoaded()
    {
        return null !== $this->collCurrentPrivilegeGroupPriveleges;
    }

    /**
     * Gets a collection of ChildPrivilege objects related by a many-to-many relationship
     * to the current object by way of the group_privilege cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildPrivilege[] List of ChildPrivilege objects
     */
    public function getCurrentPrivilegeGroupPriveleges(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentPrivilegeGroupPrivelegesPartial && !$this->isNew();
        if (null === $this->collCurrentPrivilegeGroupPriveleges || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCurrentPrivilegeGroupPriveleges) {
                    $this->initCurrentPrivilegeGroupPriveleges();
                }
            } else {

                $query = ChildPrivilegeQuery::create(null, $criteria)
                    ->filterByCurrentGroupGroupPrivelege($this);
                $collCurrentPrivilegeGroupPriveleges = $query->find($con);
                if (null !== $criteria) {
                    return $collCurrentPrivilegeGroupPriveleges;
                }

                if ($partial && $this->collCurrentPrivilegeGroupPriveleges) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collCurrentPrivilegeGroupPriveleges as $obj) {
                        if (!$collCurrentPrivilegeGroupPriveleges->contains($obj)) {
                            $collCurrentPrivilegeGroupPriveleges[] = $obj;
                        }
                    }
                }

                $this->collCurrentPrivilegeGroupPriveleges = $collCurrentPrivilegeGroupPriveleges;
                $this->collCurrentPrivilegeGroupPrivelegesPartial = false;
            }
        }

        return $this->collCurrentPrivilegeGroupPriveleges;
    }

    /**
     * Sets a collection of Privilege objects related by a many-to-many relationship
     * to the current object by way of the group_privilege cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $currentPrivilegeGroupPriveleges A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setCurrentPrivilegeGroupPriveleges(Collection $currentPrivilegeGroupPriveleges, ConnectionInterface $con = null)
    {
        $this->clearCurrentPrivilegeGroupPriveleges();
        $currentCurrentPrivilegeGroupPriveleges = $this->getCurrentPrivilegeGroupPriveleges();

        $currentPrivilegeGroupPrivelegesScheduledForDeletion = $currentCurrentPrivilegeGroupPriveleges->diff($currentPrivilegeGroupPriveleges);

        foreach ($currentPrivilegeGroupPrivelegesScheduledForDeletion as $toDelete) {
            $this->removeCurrentPrivilegeGroupPrivelege($toDelete);
        }

        foreach ($currentPrivilegeGroupPriveleges as $currentPrivilegeGroupPrivelege) {
            if (!$currentCurrentPrivilegeGroupPriveleges->contains($currentPrivilegeGroupPrivelege)) {
                $this->doAddCurrentPrivilegeGroupPrivelege($currentPrivilegeGroupPrivelege);
            }
        }

        $this->collCurrentPrivilegeGroupPrivelegesPartial = false;
        $this->collCurrentPrivilegeGroupPriveleges = $currentPrivilegeGroupPriveleges;

        return $this;
    }

    /**
     * Gets the number of Privilege objects related by a many-to-many relationship
     * to the current object by way of the group_privilege cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Privilege objects
     */
    public function countCurrentPrivilegeGroupPriveleges(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentPrivilegeGroupPrivelegesPartial && !$this->isNew();
        if (null === $this->collCurrentPrivilegeGroupPriveleges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentPrivilegeGroupPriveleges) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getCurrentPrivilegeGroupPriveleges());
                }

                $query = ChildPrivilegeQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCurrentGroupGroupPrivelege($this)
                    ->count($con);
            }
        } else {
            return count($this->collCurrentPrivilegeGroupPriveleges);
        }
    }

    /**
     * Associate a ChildPrivilege to this object
     * through the group_privilege cross reference table.
     *
     * @param ChildPrivilege $currentPrivilegeGroupPrivelege
     * @return ChildGroup The current object (for fluent API support)
     */
    public function addCurrentPrivilegeGroupPrivelege(ChildPrivilege $currentPrivilegeGroupPrivelege)
    {
        if ($this->collCurrentPrivilegeGroupPriveleges === null) {
            $this->initCurrentPrivilegeGroupPriveleges();
        }

        if (!$this->getCurrentPrivilegeGroupPriveleges()->contains($currentPrivilegeGroupPrivelege)) {
            // only add it if the **same** object is not already associated
            $this->collCurrentPrivilegeGroupPriveleges->push($currentPrivilegeGroupPrivelege);
            $this->doAddCurrentPrivilegeGroupPrivelege($currentPrivilegeGroupPrivelege);
        }

        return $this;
    }

    /**
     *
     * @param ChildPrivilege $currentPrivilegeGroupPrivelege
     */
    protected function doAddCurrentPrivilegeGroupPrivelege(ChildPrivilege $currentPrivilegeGroupPrivelege)
    {
        $groupPrivelege = new ChildGroupPrivelege();

        $groupPrivelege->setCurrentPrivilegeGroupPrivelege($currentPrivilegeGroupPrivelege);

        $groupPrivelege->setCurrentGroupGroupPrivelege($this);

        $this->addCurrentGroupGroupPrivelege($groupPrivelege);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$currentPrivilegeGroupPrivelege->isCurrentGroupGroupPrivelegesLoaded()) {
            $currentPrivilegeGroupPrivelege->initCurrentGroupGroupPriveleges();
            $currentPrivilegeGroupPrivelege->getCurrentGroupGroupPriveleges()->push($this);
        } elseif (!$currentPrivilegeGroupPrivelege->getCurrentGroupGroupPriveleges()->contains($this)) {
            $currentPrivilegeGroupPrivelege->getCurrentGroupGroupPriveleges()->push($this);
        }

    }

    /**
     * Remove currentPrivilegeGroupPrivelege of this object
     * through the group_privilege cross reference table.
     *
     * @param ChildPrivilege $currentPrivilegeGroupPrivelege
     * @return ChildGroup The current object (for fluent API support)
     */
    public function removeCurrentPrivilegeGroupPrivelege(ChildPrivilege $currentPrivilegeGroupPrivelege)
    {
        if ($this->getCurrentPrivilegeGroupPriveleges()->contains($currentPrivilegeGroupPrivelege)) {
            $groupPrivelege = new ChildGroupPrivelege();
            $groupPrivelege->setCurrentPrivilegeGroupPrivelege($currentPrivilegeGroupPrivelege);
            if ($currentPrivilegeGroupPrivelege->isCurrentGroupGroupPrivelegesLoaded()) {
                //remove the back reference if available
                $currentPrivilegeGroupPrivelege->getCurrentGroupGroupPriveleges()->removeObject($this);
            }

            $groupPrivelege->setCurrentGroupGroupPrivelege($this);
            $this->removeCurrentGroupGroupPrivelege(clone $groupPrivelege);
            $groupPrivelege->clear();

            $this->collCurrentPrivilegeGroupPriveleges->remove($this->collCurrentPrivilegeGroupPriveleges->search($currentPrivilegeGroupPrivelege));

            if (null === $this->currentPrivilegeGroupPrivelegesScheduledForDeletion) {
                $this->currentPrivilegeGroupPrivelegesScheduledForDeletion = clone $this->collCurrentPrivilegeGroupPriveleges;
                $this->currentPrivilegeGroupPrivelegesScheduledForDeletion->clear();
            }

            $this->currentPrivilegeGroupPrivelegesScheduledForDeletion->push($currentPrivilegeGroupPrivelege);
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
        $this->alt = null;
        $this->name = null;
        $this->allow_choose_group = null;
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
            if ($this->collCurrentGroupGroupPriveleges) {
                foreach ($this->collCurrentGroupGroupPriveleges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentGroupUsers) {
                foreach ($this->collCurrentGroupUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentPrivilegeGroupPriveleges) {
                foreach ($this->collCurrentPrivilegeGroupPriveleges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCurrentGroupGroupPriveleges = null;
        $this->collCurrentGroupUsers = null;
        $this->collCurrentPrivilegeGroupPriveleges = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GroupTableMap::DEFAULT_STRING_FORMAT);
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
     * @return    $this|ChildGroup
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
        return $this->getSortableRank() == ChildGroupQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildGroup
     */
    public function getNext(ConnectionInterface $con = null)
    {

        $query = ChildGroupQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildGroup
     */
    public function getPrevious(ConnectionInterface $con = null)
    {

        $query = ChildGroupQuery::create();

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
     * @return    $this|ChildGroup the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, ConnectionInterface $con = null)
    {
        $maxRank = ChildGroupQuery::create()->getMaxRankArray($con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array('\Models\GroupQuery', 'sortableShiftRank'),
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
     * @return    $this|ChildGroup the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(ConnectionInterface $con = null)
    {
        $this->setSortableRank(ChildGroupQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildGroup the current object
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
     * @return    $this|ChildGroup the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, ConnectionInterface $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > ChildGroupQuery::create()->getMaxRankArray($con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->transaction(function () use ($con, $oldRank, $newRank) {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            ChildGroupQuery::sortableShiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);
        });

        return $this;
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     ChildGroup $object
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildGroup the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
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
     * @return    $this|ChildGroup the current object
     */
    public function moveUp(ConnectionInterface $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
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
     * @return    $this|ChildGroup the current object
     */
    public function moveDown(ConnectionInterface $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
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
     * @return    $this|ChildGroup the current object
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $bottom = ChildGroupQuery::create()->getMaxRankArray($con);

            return $this->moveToRank($bottom, $con);
        });
    }

    /**
     * Removes the current object from the list.
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildGroup the current object
     */
    public function removeFromList()
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries[] = array(
            'callable'  => array('\Models\GroupQuery', 'sortableShiftRank'),
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
