<?php

namespace Models\Map;

use Models\Branch;
use Models\BranchQuery;
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
 * This class defines the structure of the 'branch' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class BranchTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.BranchTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'branch';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Branch';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Branch';

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
    const COL_ID = 'branch.id';

    /**
     * the column name for the show_on_website field
     */
    const COL_SHOW_ON_WEBSITE = 'branch.show_on_website';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'branch.name';

    /**
     * the column name for the address field
     */
    const COL_ADDRESS = 'branch.address';

    /**
     * the column name for the geographic_coordinates field
     */
    const COL_GEOGRAPHIC_COORDINATES = 'branch.geographic_coordinates';

    /**
     * the column name for the tel field
     */
    const COL_TEL = 'branch.tel';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'branch.email';

    /**
     * the column name for the instagram_link field
     */
    const COL_INSTAGRAM_LINK = 'branch.instagram_link';

    /**
     * the column name for the facebook_link field
     */
    const COL_FACEBOOK_LINK = 'branch.facebook_link';

    /**
     * the column name for the sortable_rank field
     */
    const COL_SORTABLE_RANK = 'branch.sortable_rank';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    // sortable behavior
    /**
     * rank column
     */
    const RANK_COL = "branch.sortable_rank";


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'ShowOnWebSite', 'Name', 'Address', 'GeographicCoordinates', 'Tel', 'Email', 'InstagramLink', 'FacebookLink', 'SortableRank', ),
        self::TYPE_CAMELNAME     => array('id', 'showOnWebSite', 'name', 'address', 'geographicCoordinates', 'tel', 'email', 'instagramLink', 'facebookLink', 'sortableRank', ),
        self::TYPE_COLNAME       => array(BranchTableMap::COL_ID, BranchTableMap::COL_SHOW_ON_WEBSITE, BranchTableMap::COL_NAME, BranchTableMap::COL_ADDRESS, BranchTableMap::COL_GEOGRAPHIC_COORDINATES, BranchTableMap::COL_TEL, BranchTableMap::COL_EMAIL, BranchTableMap::COL_INSTAGRAM_LINK, BranchTableMap::COL_FACEBOOK_LINK, BranchTableMap::COL_SORTABLE_RANK, ),
        self::TYPE_FIELDNAME     => array('id', 'show_on_website', 'name', 'address', 'geographic_coordinates', 'tel', 'email', 'instagram_link', 'facebook_link', 'sortable_rank', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ShowOnWebSite' => 1, 'Name' => 2, 'Address' => 3, 'GeographicCoordinates' => 4, 'Tel' => 5, 'Email' => 6, 'InstagramLink' => 7, 'FacebookLink' => 8, 'SortableRank' => 9, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'showOnWebSite' => 1, 'name' => 2, 'address' => 3, 'geographicCoordinates' => 4, 'tel' => 5, 'email' => 6, 'instagramLink' => 7, 'facebookLink' => 8, 'sortableRank' => 9, ),
        self::TYPE_COLNAME       => array(BranchTableMap::COL_ID => 0, BranchTableMap::COL_SHOW_ON_WEBSITE => 1, BranchTableMap::COL_NAME => 2, BranchTableMap::COL_ADDRESS => 3, BranchTableMap::COL_GEOGRAPHIC_COORDINATES => 4, BranchTableMap::COL_TEL => 5, BranchTableMap::COL_EMAIL => 6, BranchTableMap::COL_INSTAGRAM_LINK => 7, BranchTableMap::COL_FACEBOOK_LINK => 8, BranchTableMap::COL_SORTABLE_RANK => 9, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'show_on_website' => 1, 'name' => 2, 'address' => 3, 'geographic_coordinates' => 4, 'tel' => 5, 'email' => 6, 'instagram_link' => 7, 'facebook_link' => 8, 'sortable_rank' => 9, ),
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
        $this->setName('branch');
        $this->setPhpName('Branch');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Branch');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('show_on_website', 'ShowOnWebSite', 'BOOLEAN', true, 1, true);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('address', 'Address', 'VARCHAR', false, 255, null);
        $this->addColumn('geographic_coordinates', 'GeographicCoordinates', 'OBJECT', false, null, null);
        $this->addColumn('tel', 'Tel', 'VARCHAR', false, 25, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 128, null);
        $this->addColumn('instagram_link', 'InstagramLink', 'VARCHAR', false, 255, null);
        $this->addColumn('facebook_link', 'FacebookLink', 'VARCHAR', false, 255, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CurrentBranchCourseStream', '\\Models\\CourseStream', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':branch_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'CurrentBranchCourseStreams', false);
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
            'sortable' => array('rank_column' => 'sortable_rank', 'use_scope' => 'false', 'scope_column' => '', ),
            'query_cache' => array('backend' => 'apc', 'lifetime' => '3600', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to branch     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CourseStreamTableMap::clearInstancePool();
    }

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
        return $withPrefix ? BranchTableMap::CLASS_DEFAULT : BranchTableMap::OM_CLASS;
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
     * @return array           (Branch object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = BranchTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = BranchTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + BranchTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = BranchTableMap::OM_CLASS;
            /** @var Branch $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            BranchTableMap::addInstanceToPool($obj, $key);
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
            $key = BranchTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = BranchTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Branch $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                BranchTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(BranchTableMap::COL_ID);
            $criteria->addSelectColumn(BranchTableMap::COL_SHOW_ON_WEBSITE);
            $criteria->addSelectColumn(BranchTableMap::COL_NAME);
            $criteria->addSelectColumn(BranchTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(BranchTableMap::COL_GEOGRAPHIC_COORDINATES);
            $criteria->addSelectColumn(BranchTableMap::COL_TEL);
            $criteria->addSelectColumn(BranchTableMap::COL_EMAIL);
            $criteria->addSelectColumn(BranchTableMap::COL_INSTAGRAM_LINK);
            $criteria->addSelectColumn(BranchTableMap::COL_FACEBOOK_LINK);
            $criteria->addSelectColumn(BranchTableMap::COL_SORTABLE_RANK);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.show_on_website');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.geographic_coordinates');
            $criteria->addSelectColumn($alias . '.tel');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.instagram_link');
            $criteria->addSelectColumn($alias . '.facebook_link');
            $criteria->addSelectColumn($alias . '.sortable_rank');
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
        return Propel::getServiceContainer()->getDatabaseMap(BranchTableMap::DATABASE_NAME)->getTable(BranchTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(BranchTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(BranchTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new BranchTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Branch or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Branch object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Branch) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(BranchTableMap::DATABASE_NAME);
            $criteria->add(BranchTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = BranchQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            BranchTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                BranchTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the branch table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return BranchQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Branch or Criteria object.
     *
     * @param mixed               $criteria Criteria or Branch object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BranchTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Branch object
        }

        if ($criteria->containsKey(BranchTableMap::COL_ID) && $criteria->keyContainsValue(BranchTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.BranchTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = BranchQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // BranchTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BranchTableMap::buildTableMap();
