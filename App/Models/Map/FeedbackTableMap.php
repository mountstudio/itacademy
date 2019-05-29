<?php

namespace Models\Map;

use Models\Feedback;
use Models\FeedbackQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'feedback' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class FeedbackTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.FeedbackTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'feedback';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Feedback';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Feedback';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the id field
     */
    const COL_ID = 'feedback.id';

    /**
     * the column name for the work_place field
     */
    const COL_WORK_PLACE = 'feedback.work_place';

    /**
     * the column name for the salary field
     */
    const COL_SALARY = 'feedback.salary';

    /**
     * the column name for the currency_id field
     */
    const COL_CURRENCY_ID = 'feedback.currency_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'feedback.user_id';

    /**
     * the column name for the is_available field
     */
    const COL_IS_AVAILABLE = 'feedback.is_available';

    /**
     * the column name for the content field
     */
    const COL_CONTENT = 'feedback.content';

    /**
     * the column name for the notes field
     */
    const COL_NOTES = 'feedback.notes';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'feedback.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'feedback.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'WorkPlace', 'Salary', 'CurrentCurrencyId', 'CurrentUserId', 'Available', 'Content', 'Notes', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'workPlace', 'salary', 'currentCurrencyId', 'currentUserId', 'available', 'content', 'notes', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(FeedbackTableMap::COL_ID, FeedbackTableMap::COL_WORK_PLACE, FeedbackTableMap::COL_SALARY, FeedbackTableMap::COL_CURRENCY_ID, FeedbackTableMap::COL_USER_ID, FeedbackTableMap::COL_IS_AVAILABLE, FeedbackTableMap::COL_CONTENT, FeedbackTableMap::COL_NOTES, FeedbackTableMap::COL_CREATED_AT, FeedbackTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'work_place', 'salary', 'currency_id', 'user_id', 'is_available', 'content', 'notes', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'WorkPlace' => 1, 'Salary' => 2, 'CurrentCurrencyId' => 3, 'CurrentUserId' => 4, 'Available' => 5, 'Content' => 6, 'Notes' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'workPlace' => 1, 'salary' => 2, 'currentCurrencyId' => 3, 'currentUserId' => 4, 'available' => 5, 'content' => 6, 'notes' => 7, 'createdAt' => 8, 'updatedAt' => 9, ),
        self::TYPE_COLNAME       => array(FeedbackTableMap::COL_ID => 0, FeedbackTableMap::COL_WORK_PLACE => 1, FeedbackTableMap::COL_SALARY => 2, FeedbackTableMap::COL_CURRENCY_ID => 3, FeedbackTableMap::COL_USER_ID => 4, FeedbackTableMap::COL_IS_AVAILABLE => 5, FeedbackTableMap::COL_CONTENT => 6, FeedbackTableMap::COL_NOTES => 7, FeedbackTableMap::COL_CREATED_AT => 8, FeedbackTableMap::COL_UPDATED_AT => 9, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'work_place' => 1, 'salary' => 2, 'currency_id' => 3, 'user_id' => 4, 'is_available' => 5, 'content' => 6, 'notes' => 7, 'created_at' => 8, 'updated_at' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('feedback');
        $this->setPhpName('Feedback');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Models\\Feedback');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('work_place', 'WorkPlace', 'VARCHAR', false, 255, null);
        $this->addColumn('salary', 'Salary', 'FLOAT', false, null, null);
        $this->addForeignKey('currency_id', 'CurrentCurrencyId', 'INTEGER', 'currency', 'id', false, null, null);
        $this->addForeignKey('user_id', 'CurrentUserId', 'INTEGER', 'user', 'id', true, null, null);
        $this->addColumn('is_available', 'Available', 'BOOLEAN', true, 1, false);
        $this->addColumn('content', 'Content', 'LONGVARCHAR', false, null, null);
        $this->addColumn('notes', 'Notes', 'VARCHAR', false, 500, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CurrentFeedbackCurrency', '\\Models\\Currency', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':currency_id',
    1 => ':id',
  ),
), 'SET NULL', null, null, false);
        $this->addRelation('CurrentFeedbackUser', '\\Models\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
            'query_cache' => array('backend' => 'apc', 'lifetime' => '3600', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? FeedbackTableMap::CLASS_DEFAULT : FeedbackTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Feedback object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = FeedbackTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FeedbackTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FeedbackTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FeedbackTableMap::OM_CLASS;
            /** @var Feedback $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FeedbackTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = FeedbackTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FeedbackTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Feedback $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FeedbackTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(FeedbackTableMap::COL_ID);
            $criteria->addSelectColumn(FeedbackTableMap::COL_WORK_PLACE);
            $criteria->addSelectColumn(FeedbackTableMap::COL_SALARY);
            $criteria->addSelectColumn(FeedbackTableMap::COL_CURRENCY_ID);
            $criteria->addSelectColumn(FeedbackTableMap::COL_USER_ID);
            $criteria->addSelectColumn(FeedbackTableMap::COL_IS_AVAILABLE);
            $criteria->addSelectColumn(FeedbackTableMap::COL_CONTENT);
            $criteria->addSelectColumn(FeedbackTableMap::COL_NOTES);
            $criteria->addSelectColumn(FeedbackTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(FeedbackTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.work_place');
            $criteria->addSelectColumn($alias . '.salary');
            $criteria->addSelectColumn($alias . '.currency_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.is_available');
            $criteria->addSelectColumn($alias . '.content');
            $criteria->addSelectColumn($alias . '.notes');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(FeedbackTableMap::DATABASE_NAME)->getTable(FeedbackTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(FeedbackTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(FeedbackTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new FeedbackTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Feedback or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Feedback object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FeedbackTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Feedback) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FeedbackTableMap::DATABASE_NAME);
            $criteria->add(FeedbackTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = FeedbackQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FeedbackTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FeedbackTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the feedback table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return FeedbackQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Feedback or Criteria object.
     *
     * @param mixed               $criteria Criteria or Feedback object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FeedbackTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Feedback object
        }

        if ($criteria->containsKey(FeedbackTableMap::COL_ID) && $criteria->keyContainsValue(FeedbackTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FeedbackTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = FeedbackQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // FeedbackTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
FeedbackTableMap::buildTableMap();
