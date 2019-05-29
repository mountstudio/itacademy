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
use Models\Map\GroupPrivelegeTableMap;
use Models\Map\PrivilegeTableMap;
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
 * Base class that represents a row from the 'privilege' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class Privilege implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\PrivilegeTableMap';


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
     * The value for the alt field.
     *
     * @var        string
     */
    protected $alt;

    /**
     * @var        ObjectCollection|ChildGroupPrivelege[] Collection to store aggregation of ChildGroupPrivelege objects.
     */
    protected $collCurrentPrivilegeGroupPriveleges;
    protected $collCurrentPrivilegeGroupPrivelegesPartial;

    /**
     * @var        ObjectCollection|ChildGroup[] Cross Collection to store aggregation of ChildGroup objects.
     */
    protected $collCurrentGroupGroupPriveleges;

    /**
     * @var bool
     */
    protected $collCurrentGroupGroupPrivelegesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroup[]
     */
    protected $currentGroupGroupPrivelegesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroupPrivelege[]
     */
    protected $currentPrivilegeGroupPrivelegesScheduledForDeletion = null;

    /**
     * Initializes internal state of Models\Base\Privilege object.
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
     * Compares this with another <code>Privilege</code> instance.  If
     * <code>obj</code> is an instance of <code>Privilege</code>, delegates to
     * <code>equals(Privilege)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Privilege The current object, for fluid interface
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
     * Get the [alt] column value.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Privilege The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PrivilegeTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Privilege The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[PrivilegeTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [alt] column.
     *
     * @param string $v new value
     * @return $this|\Models\Privilege The current object (for fluent API support)
     */
    public function setAlt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alt !== $v) {
            $this->alt = $v;
            $this->modifiedColumns[PrivilegeTableMap::COL_ALT] = true;
        }

        return $this;
    } // setAlt()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PrivilegeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PrivilegeTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PrivilegeTableMap::translateFieldName('Alt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alt = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = PrivilegeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Privilege'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PrivilegeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPrivilegeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCurrentPrivilegeGroupPriveleges = null;

            $this->collCurrentGroupGroupPriveleges = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Privilege::setDeleted()
     * @see Privilege::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PrivilegeTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPrivilegeQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PrivilegeTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
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
                PrivilegeTableMap::addInstanceToPool($this);
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

            if ($this->currentGroupGroupPrivelegesScheduledForDeletion !== null) {
                if (!$this->currentGroupGroupPrivelegesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->currentGroupGroupPrivelegesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\GroupPrivelegeQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->currentGroupGroupPrivelegesScheduledForDeletion = null;
                }

            }

            if ($this->collCurrentGroupGroupPriveleges) {
                foreach ($this->collCurrentGroupGroupPriveleges as $currentGroupGroupPrivelege) {
                    if (!$currentGroupGroupPrivelege->isDeleted() && ($currentGroupGroupPrivelege->isNew() || $currentGroupGroupPrivelege->isModified())) {
                        $currentGroupGroupPrivelege->save($con);
                    }
                }
            }


            if ($this->currentPrivilegeGroupPrivelegesScheduledForDeletion !== null) {
                if (!$this->currentPrivilegeGroupPrivelegesScheduledForDeletion->isEmpty()) {
                    \Models\GroupPrivelegeQuery::create()
                        ->filterByPrimaryKeys($this->currentPrivilegeGroupPrivelegesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->currentPrivilegeGroupPrivelegesScheduledForDeletion = null;
                }
            }

            if ($this->collCurrentPrivilegeGroupPriveleges !== null) {
                foreach ($this->collCurrentPrivilegeGroupPriveleges as $referrerFK) {
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

        $this->modifiedColumns[PrivilegeTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PrivilegeTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PrivilegeTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PrivilegeTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(PrivilegeTableMap::COL_ALT)) {
            $modifiedColumns[':p' . $index++]  = 'alt';
        }

        $sql = sprintf(
            'INSERT INTO privilege (%s) VALUES (%s)',
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
                    case 'alt':
                        $stmt->bindValue($identifier, $this->alt, PDO::PARAM_STR);
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
        $pos = PrivilegeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAlt();
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

        if (isset($alreadyDumpedObjects['Privilege'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Privilege'][$this->hashCode()] = true;
        $keys = PrivilegeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getAlt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCurrentPrivilegeGroupPriveleges) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'groupPriveleges';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'group_privileges';
                        break;
                    default:
                        $key = 'CurrentPrivilegeGroupPriveleges';
                }

                $result[$key] = $this->collCurrentPrivilegeGroupPriveleges->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\Privilege
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PrivilegeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Privilege
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
                $this->setAlt($value);
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
        $keys = PrivilegeTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAlt($arr[$keys[2]]);
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
     * @return $this|\Models\Privilege The current object, for fluid interface
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
        $criteria = new Criteria(PrivilegeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PrivilegeTableMap::COL_ID)) {
            $criteria->add(PrivilegeTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PrivilegeTableMap::COL_NAME)) {
            $criteria->add(PrivilegeTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(PrivilegeTableMap::COL_ALT)) {
            $criteria->add(PrivilegeTableMap::COL_ALT, $this->alt);
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
        $criteria = ChildPrivilegeQuery::create();
        $criteria->add(PrivilegeTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Privilege (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setAlt($this->getAlt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCurrentPrivilegeGroupPriveleges() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCurrentPrivilegeGroupPrivelege($relObj->copy($deepCopy));
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
     * @return \Models\Privilege Clone of current object.
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
        if ('CurrentPrivilegeGroupPrivelege' == $relationName) {
            $this->initCurrentPrivilegeGroupPriveleges();
            return;
        }
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
     * Reset is the collCurrentPrivilegeGroupPriveleges collection loaded partially.
     */
    public function resetPartialCurrentPrivilegeGroupPriveleges($v = true)
    {
        $this->collCurrentPrivilegeGroupPrivelegesPartial = $v;
    }

    /**
     * Initializes the collCurrentPrivilegeGroupPriveleges collection.
     *
     * By default this just sets the collCurrentPrivilegeGroupPriveleges collection to an empty array (like clearcollCurrentPrivilegeGroupPriveleges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCurrentPrivilegeGroupPriveleges($overrideExisting = true)
    {
        if (null !== $this->collCurrentPrivilegeGroupPriveleges && !$overrideExisting) {
            return;
        }

        $collectionClassName = GroupPrivelegeTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentPrivilegeGroupPriveleges = new $collectionClassName;
        $this->collCurrentPrivilegeGroupPriveleges->setModel('\Models\GroupPrivelege');
    }

    /**
     * Gets an array of ChildGroupPrivelege objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPrivilege is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGroupPrivelege[] List of ChildGroupPrivelege objects
     * @throws PropelException
     */
    public function getCurrentPrivilegeGroupPriveleges(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentPrivilegeGroupPrivelegesPartial && !$this->isNew();
        if (null === $this->collCurrentPrivilegeGroupPriveleges || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCurrentPrivilegeGroupPriveleges) {
                // return empty collection
                $this->initCurrentPrivilegeGroupPriveleges();
            } else {
                $collCurrentPrivilegeGroupPriveleges = ChildGroupPrivelegeQuery::create(null, $criteria)
                    ->filterByCurrentPrivilegeGroupPrivelege($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCurrentPrivilegeGroupPrivelegesPartial && count($collCurrentPrivilegeGroupPriveleges)) {
                        $this->initCurrentPrivilegeGroupPriveleges(false);

                        foreach ($collCurrentPrivilegeGroupPriveleges as $obj) {
                            if (false == $this->collCurrentPrivilegeGroupPriveleges->contains($obj)) {
                                $this->collCurrentPrivilegeGroupPriveleges->append($obj);
                            }
                        }

                        $this->collCurrentPrivilegeGroupPrivelegesPartial = true;
                    }

                    return $collCurrentPrivilegeGroupPriveleges;
                }

                if ($partial && $this->collCurrentPrivilegeGroupPriveleges) {
                    foreach ($this->collCurrentPrivilegeGroupPriveleges as $obj) {
                        if ($obj->isNew()) {
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
     * Sets a collection of ChildGroupPrivelege objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $currentPrivilegeGroupPriveleges A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPrivilege The current object (for fluent API support)
     */
    public function setCurrentPrivilegeGroupPriveleges(Collection $currentPrivilegeGroupPriveleges, ConnectionInterface $con = null)
    {
        /** @var ChildGroupPrivelege[] $currentPrivilegeGroupPrivelegesToDelete */
        $currentPrivilegeGroupPrivelegesToDelete = $this->getCurrentPrivilegeGroupPriveleges(new Criteria(), $con)->diff($currentPrivilegeGroupPriveleges);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->currentPrivilegeGroupPrivelegesScheduledForDeletion = clone $currentPrivilegeGroupPrivelegesToDelete;

        foreach ($currentPrivilegeGroupPrivelegesToDelete as $currentPrivilegeGroupPrivelegeRemoved) {
            $currentPrivilegeGroupPrivelegeRemoved->setCurrentPrivilegeGroupPrivelege(null);
        }

        $this->collCurrentPrivilegeGroupPriveleges = null;
        foreach ($currentPrivilegeGroupPriveleges as $currentPrivilegeGroupPrivelege) {
            $this->addCurrentPrivilegeGroupPrivelege($currentPrivilegeGroupPrivelege);
        }

        $this->collCurrentPrivilegeGroupPriveleges = $currentPrivilegeGroupPriveleges;
        $this->collCurrentPrivilegeGroupPrivelegesPartial = false;

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
    public function countCurrentPrivilegeGroupPriveleges(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentPrivilegeGroupPrivelegesPartial && !$this->isNew();
        if (null === $this->collCurrentPrivilegeGroupPriveleges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentPrivilegeGroupPriveleges) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCurrentPrivilegeGroupPriveleges());
            }

            $query = ChildGroupPrivelegeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCurrentPrivilegeGroupPrivelege($this)
                ->count($con);
        }

        return count($this->collCurrentPrivilegeGroupPriveleges);
    }

    /**
     * Method called to associate a ChildGroupPrivelege object to this object
     * through the ChildGroupPrivelege foreign key attribute.
     *
     * @param  ChildGroupPrivelege $l ChildGroupPrivelege
     * @return $this|\Models\Privilege The current object (for fluent API support)
     */
    public function addCurrentPrivilegeGroupPrivelege(ChildGroupPrivelege $l)
    {
        if ($this->collCurrentPrivilegeGroupPriveleges === null) {
            $this->initCurrentPrivilegeGroupPriveleges();
            $this->collCurrentPrivilegeGroupPrivelegesPartial = true;
        }

        if (!$this->collCurrentPrivilegeGroupPriveleges->contains($l)) {
            $this->doAddCurrentPrivilegeGroupPrivelege($l);

            if ($this->currentPrivilegeGroupPrivelegesScheduledForDeletion and $this->currentPrivilegeGroupPrivelegesScheduledForDeletion->contains($l)) {
                $this->currentPrivilegeGroupPrivelegesScheduledForDeletion->remove($this->currentPrivilegeGroupPrivelegesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGroupPrivelege $currentPrivilegeGroupPrivelege The ChildGroupPrivelege object to add.
     */
    protected function doAddCurrentPrivilegeGroupPrivelege(ChildGroupPrivelege $currentPrivilegeGroupPrivelege)
    {
        $this->collCurrentPrivilegeGroupPriveleges[]= $currentPrivilegeGroupPrivelege;
        $currentPrivilegeGroupPrivelege->setCurrentPrivilegeGroupPrivelege($this);
    }

    /**
     * @param  ChildGroupPrivelege $currentPrivilegeGroupPrivelege The ChildGroupPrivelege object to remove.
     * @return $this|ChildPrivilege The current object (for fluent API support)
     */
    public function removeCurrentPrivilegeGroupPrivelege(ChildGroupPrivelege $currentPrivilegeGroupPrivelege)
    {
        if ($this->getCurrentPrivilegeGroupPriveleges()->contains($currentPrivilegeGroupPrivelege)) {
            $pos = $this->collCurrentPrivilegeGroupPriveleges->search($currentPrivilegeGroupPrivelege);
            $this->collCurrentPrivilegeGroupPriveleges->remove($pos);
            if (null === $this->currentPrivilegeGroupPrivelegesScheduledForDeletion) {
                $this->currentPrivilegeGroupPrivelegesScheduledForDeletion = clone $this->collCurrentPrivilegeGroupPriveleges;
                $this->currentPrivilegeGroupPrivelegesScheduledForDeletion->clear();
            }
            $this->currentPrivilegeGroupPrivelegesScheduledForDeletion[]= clone $currentPrivilegeGroupPrivelege;
            $currentPrivilegeGroupPrivelege->setCurrentPrivilegeGroupPrivelege(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Privilege is new, it will return
     * an empty collection; or if this Privilege has previously
     * been saved, it will retrieve related CurrentPrivilegeGroupPriveleges from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Privilege.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGroupPrivelege[] List of ChildGroupPrivelege objects
     */
    public function getCurrentPrivilegeGroupPrivelegesJoinCurrentGroupGroupPrivelege(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGroupPrivelegeQuery::create(null, $criteria);
        $query->joinWith('CurrentGroupGroupPrivelege', $joinBehavior);

        return $this->getCurrentPrivilegeGroupPriveleges($query, $con);
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
     * Initializes the collCurrentGroupGroupPriveleges crossRef collection.
     *
     * By default this just sets the collCurrentGroupGroupPriveleges collection to an empty collection (like clearCurrentGroupGroupPriveleges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCurrentGroupGroupPriveleges()
    {
        $collectionClassName = GroupPrivelegeTableMap::getTableMap()->getCollectionClassName();

        $this->collCurrentGroupGroupPriveleges = new $collectionClassName;
        $this->collCurrentGroupGroupPrivelegesPartial = true;
        $this->collCurrentGroupGroupPriveleges->setModel('\Models\Group');
    }

    /**
     * Checks if the collCurrentGroupGroupPriveleges collection is loaded.
     *
     * @return bool
     */
    public function isCurrentGroupGroupPrivelegesLoaded()
    {
        return null !== $this->collCurrentGroupGroupPriveleges;
    }

    /**
     * Gets a collection of ChildGroup objects related by a many-to-many relationship
     * to the current object by way of the group_privilege cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPrivilege is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildGroup[] List of ChildGroup objects
     */
    public function getCurrentGroupGroupPriveleges(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentGroupGroupPrivelegesPartial && !$this->isNew();
        if (null === $this->collCurrentGroupGroupPriveleges || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCurrentGroupGroupPriveleges) {
                    $this->initCurrentGroupGroupPriveleges();
                }
            } else {

                $query = ChildGroupQuery::create(null, $criteria)
                    ->filterByCurrentPrivilegeGroupPrivelege($this);
                $collCurrentGroupGroupPriveleges = $query->find($con);
                if (null !== $criteria) {
                    return $collCurrentGroupGroupPriveleges;
                }

                if ($partial && $this->collCurrentGroupGroupPriveleges) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collCurrentGroupGroupPriveleges as $obj) {
                        if (!$collCurrentGroupGroupPriveleges->contains($obj)) {
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
     * Sets a collection of Group objects related by a many-to-many relationship
     * to the current object by way of the group_privilege cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $currentGroupGroupPriveleges A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPrivilege The current object (for fluent API support)
     */
    public function setCurrentGroupGroupPriveleges(Collection $currentGroupGroupPriveleges, ConnectionInterface $con = null)
    {
        $this->clearCurrentGroupGroupPriveleges();
        $currentCurrentGroupGroupPriveleges = $this->getCurrentGroupGroupPriveleges();

        $currentGroupGroupPrivelegesScheduledForDeletion = $currentCurrentGroupGroupPriveleges->diff($currentGroupGroupPriveleges);

        foreach ($currentGroupGroupPrivelegesScheduledForDeletion as $toDelete) {
            $this->removeCurrentGroupGroupPrivelege($toDelete);
        }

        foreach ($currentGroupGroupPriveleges as $currentGroupGroupPrivelege) {
            if (!$currentCurrentGroupGroupPriveleges->contains($currentGroupGroupPrivelege)) {
                $this->doAddCurrentGroupGroupPrivelege($currentGroupGroupPrivelege);
            }
        }

        $this->collCurrentGroupGroupPrivelegesPartial = false;
        $this->collCurrentGroupGroupPriveleges = $currentGroupGroupPriveleges;

        return $this;
    }

    /**
     * Gets the number of Group objects related by a many-to-many relationship
     * to the current object by way of the group_privilege cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Group objects
     */
    public function countCurrentGroupGroupPriveleges(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCurrentGroupGroupPrivelegesPartial && !$this->isNew();
        if (null === $this->collCurrentGroupGroupPriveleges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCurrentGroupGroupPriveleges) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getCurrentGroupGroupPriveleges());
                }

                $query = ChildGroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCurrentPrivilegeGroupPrivelege($this)
                    ->count($con);
            }
        } else {
            return count($this->collCurrentGroupGroupPriveleges);
        }
    }

    /**
     * Associate a ChildGroup to this object
     * through the group_privilege cross reference table.
     *
     * @param ChildGroup $currentGroupGroupPrivelege
     * @return ChildPrivilege The current object (for fluent API support)
     */
    public function addCurrentGroupGroupPrivelege(ChildGroup $currentGroupGroupPrivelege)
    {
        if ($this->collCurrentGroupGroupPriveleges === null) {
            $this->initCurrentGroupGroupPriveleges();
        }

        if (!$this->getCurrentGroupGroupPriveleges()->contains($currentGroupGroupPrivelege)) {
            // only add it if the **same** object is not already associated
            $this->collCurrentGroupGroupPriveleges->push($currentGroupGroupPrivelege);
            $this->doAddCurrentGroupGroupPrivelege($currentGroupGroupPrivelege);
        }

        return $this;
    }

    /**
     *
     * @param ChildGroup $currentGroupGroupPrivelege
     */
    protected function doAddCurrentGroupGroupPrivelege(ChildGroup $currentGroupGroupPrivelege)
    {
        $groupPrivelege = new ChildGroupPrivelege();

        $groupPrivelege->setCurrentGroupGroupPrivelege($currentGroupGroupPrivelege);

        $groupPrivelege->setCurrentPrivilegeGroupPrivelege($this);

        $this->addCurrentPrivilegeGroupPrivelege($groupPrivelege);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$currentGroupGroupPrivelege->isCurrentPrivilegeGroupPrivelegesLoaded()) {
            $currentGroupGroupPrivelege->initCurrentPrivilegeGroupPriveleges();
            $currentGroupGroupPrivelege->getCurrentPrivilegeGroupPriveleges()->push($this);
        } elseif (!$currentGroupGroupPrivelege->getCurrentPrivilegeGroupPriveleges()->contains($this)) {
            $currentGroupGroupPrivelege->getCurrentPrivilegeGroupPriveleges()->push($this);
        }

    }

    /**
     * Remove currentGroupGroupPrivelege of this object
     * through the group_privilege cross reference table.
     *
     * @param ChildGroup $currentGroupGroupPrivelege
     * @return ChildPrivilege The current object (for fluent API support)
     */
    public function removeCurrentGroupGroupPrivelege(ChildGroup $currentGroupGroupPrivelege)
    {
        if ($this->getCurrentGroupGroupPriveleges()->contains($currentGroupGroupPrivelege)) {
            $groupPrivelege = new ChildGroupPrivelege();
            $groupPrivelege->setCurrentGroupGroupPrivelege($currentGroupGroupPrivelege);
            if ($currentGroupGroupPrivelege->isCurrentPrivilegeGroupPrivelegesLoaded()) {
                //remove the back reference if available
                $currentGroupGroupPrivelege->getCurrentPrivilegeGroupPriveleges()->removeObject($this);
            }

            $groupPrivelege->setCurrentPrivilegeGroupPrivelege($this);
            $this->removeCurrentPrivilegeGroupPrivelege(clone $groupPrivelege);
            $groupPrivelege->clear();

            $this->collCurrentGroupGroupPriveleges->remove($this->collCurrentGroupGroupPriveleges->search($currentGroupGroupPrivelege));

            if (null === $this->currentGroupGroupPrivelegesScheduledForDeletion) {
                $this->currentGroupGroupPrivelegesScheduledForDeletion = clone $this->collCurrentGroupGroupPriveleges;
                $this->currentGroupGroupPrivelegesScheduledForDeletion->clear();
            }

            $this->currentGroupGroupPrivelegesScheduledForDeletion->push($currentGroupGroupPrivelege);
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
        $this->alt = null;
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
            if ($this->collCurrentPrivilegeGroupPriveleges) {
                foreach ($this->collCurrentPrivilegeGroupPriveleges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCurrentGroupGroupPriveleges) {
                foreach ($this->collCurrentGroupGroupPriveleges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCurrentPrivilegeGroupPriveleges = null;
        $this->collCurrentGroupGroupPriveleges = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PrivilegeTableMap::DEFAULT_STRING_FORMAT);
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
