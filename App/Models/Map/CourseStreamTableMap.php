<?php

namespace Models\Map;

use Models\CourseStream;
use Models\CourseStreamQuery;
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
 * This class defines the structure of the 'course_stream' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CourseStreamTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.CourseStreamTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'course_stream';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\CourseStream';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.CourseStream';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 16;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 16;

    /**
     * the column name for the id field
     */
    const COL_ID = 'course_stream.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'course_stream.name';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'course_stream.description';

    /**
     * the column name for the number_of_places field
     */
    const COL_NUMBER_OF_PLACES = 'course_stream.number_of_places';

    /**
     * the column name for the notes field
     */
    const COL_NOTES = 'course_stream.notes';

    /**
     * the column name for the starts_at field
     */
    const COL_STARTS_AT = 'course_stream.starts_at';

    /**
     * the column name for the ends_at field
     */
    const COL_ENDS_AT = 'course_stream.ends_at';

    /**
     * the column name for the show_on_website field
     */
    const COL_SHOW_ON_WEBSITE = 'course_stream.show_on_website';

    /**
     * the column name for the cost field
     */
    const COL_COST = 'course_stream.cost';

    /**
     * the column name for the branch_id field
     */
    const COL_BRANCH_ID = 'course_stream.branch_id';

    /**
     * the column name for the currency_id field
     */
    const COL_CURRENCY_ID = 'course_stream.currency_id';

    /**
     * the column name for the course_id field
     */
    const COL_COURSE_ID = 'course_stream.course_id';

    /**
     * the column name for the course_stream_status_id field
     */
    const COL_COURSE_STREAM_STATUS_ID = 'course_stream.course_stream_status_id';

    /**
     * the column name for the instructor_id field
     */
    const COL_INSTRUCTOR_ID = 'course_stream.instructor_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'course_stream.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'course_stream.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Description', 'NumberOfPlaces', 'Notes', 'StartsAt', 'EndsAt', 'ShowOnWebSite', 'Cost', 'CurrentBranchId', 'CurrentCurrencyId', 'CurrentCourseId', 'CurrentCourseStreamStatusId', 'CurrentCourseStreamInstructorId', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'description', 'numberOfPlaces', 'notes', 'startsAt', 'endsAt', 'showOnWebSite', 'cost', 'currentBranchId', 'currentCurrencyId', 'currentCourseId', 'currentCourseStreamStatusId', 'currentCourseStreamInstructorId', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(CourseStreamTableMap::COL_ID, CourseStreamTableMap::COL_NAME, CourseStreamTableMap::COL_DESCRIPTION, CourseStreamTableMap::COL_NUMBER_OF_PLACES, CourseStreamTableMap::COL_NOTES, CourseStreamTableMap::COL_STARTS_AT, CourseStreamTableMap::COL_ENDS_AT, CourseStreamTableMap::COL_SHOW_ON_WEBSITE, CourseStreamTableMap::COL_COST, CourseStreamTableMap::COL_BRANCH_ID, CourseStreamTableMap::COL_CURRENCY_ID, CourseStreamTableMap::COL_COURSE_ID, CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID, CourseStreamTableMap::COL_INSTRUCTOR_ID, CourseStreamTableMap::COL_CREATED_AT, CourseStreamTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'description', 'number_of_places', 'notes', 'starts_at', 'ends_at', 'show_on_website', 'cost', 'branch_id', 'currency_id', 'course_id', 'course_stream_status_id', 'instructor_id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Description' => 2, 'NumberOfPlaces' => 3, 'Notes' => 4, 'StartsAt' => 5, 'EndsAt' => 6, 'ShowOnWebSite' => 7, 'Cost' => 8, 'CurrentBranchId' => 9, 'CurrentCurrencyId' => 10, 'CurrentCourseId' => 11, 'CurrentCourseStreamStatusId' => 12, 'CurrentCourseStreamInstructorId' => 13, 'CreatedAt' => 14, 'UpdatedAt' => 15, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'numberOfPlaces' => 3, 'notes' => 4, 'startsAt' => 5, 'endsAt' => 6, 'showOnWebSite' => 7, 'cost' => 8, 'currentBranchId' => 9, 'currentCurrencyId' => 10, 'currentCourseId' => 11, 'currentCourseStreamStatusId' => 12, 'currentCourseStreamInstructorId' => 13, 'createdAt' => 14, 'updatedAt' => 15, ),
        self::TYPE_COLNAME       => array(CourseStreamTableMap::COL_ID => 0, CourseStreamTableMap::COL_NAME => 1, CourseStreamTableMap::COL_DESCRIPTION => 2, CourseStreamTableMap::COL_NUMBER_OF_PLACES => 3, CourseStreamTableMap::COL_NOTES => 4, CourseStreamTableMap::COL_STARTS_AT => 5, CourseStreamTableMap::COL_ENDS_AT => 6, CourseStreamTableMap::COL_SHOW_ON_WEBSITE => 7, CourseStreamTableMap::COL_COST => 8, CourseStreamTableMap::COL_BRANCH_ID => 9, CourseStreamTableMap::COL_CURRENCY_ID => 10, CourseStreamTableMap::COL_COURSE_ID => 11, CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID => 12, CourseStreamTableMap::COL_INSTRUCTOR_ID => 13, CourseStreamTableMap::COL_CREATED_AT => 14, CourseStreamTableMap::COL_UPDATED_AT => 15, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'number_of_places' => 3, 'notes' => 4, 'starts_at' => 5, 'ends_at' => 6, 'show_on_website' => 7, 'cost' => 8, 'branch_id' => 9, 'currency_id' => 10, 'course_id' => 11, 'course_stream_status_id' => 12, 'instructor_id' => 13, 'created_at' => 14, 'updated_at' => 15, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
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
        $this->setName('course_stream');
        $this->setPhpName('CourseStream');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Models\\CourseStream');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        $this->setIsCrossRef(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 20, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('number_of_places', 'NumberOfPlaces', 'INTEGER', true, null, null);
        $this->addColumn('notes', 'Notes', 'VARCHAR', false, 300, null);
        $this->addColumn('starts_at', 'StartsAt', 'DATE', true, null, null);
        $this->addColumn('ends_at', 'EndsAt', 'DATE', true, null, null);
        $this->addColumn('show_on_website', 'ShowOnWebSite', 'BOOLEAN', true, 1, false);
        $this->addColumn('cost', 'Cost', 'FLOAT', true, null, null);
        $this->addForeignKey('branch_id', 'CurrentBranchId', 'INTEGER', 'branch', 'id', true, null, null);
        $this->addForeignKey('currency_id', 'CurrentCurrencyId', 'INTEGER', 'currency', 'id', true, null, null);
        $this->addForeignKey('course_id', 'CurrentCourseId', 'INTEGER', 'course', 'id', true, null, null);
        $this->addForeignKey('course_stream_status_id', 'CurrentCourseStreamStatusId', 'INTEGER', 'course_stream_status', 'id', true, null, null);
        $this->addForeignKey('instructor_id', 'CurrentCourseStreamInstructorId', 'INTEGER', 'user', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CurrentCourseStreamBranch', '\\Models\\Branch', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':branch_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('CurrentCourseStreamCurrency', '\\Models\\Currency', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':currency_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('CurrentCourseCourseStream', '\\Models\\Course', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':course_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('CurrentCourseCourseStreamStatus', '\\Models\\CourseStreamStatus', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':course_stream_status_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('CurrentCourseStreamInstructor', '\\Models\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':instructor_id',
    1 => ':id',
  ),
), 'SET NULL', null, null, false);
        $this->addRelation('CurrentApplicationCourseStream', '\\Models\\Application', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':course_stream_id',
    1 => ':id',
  ),
), 'SET NULL', null, 'CurrentApplicationCourseStreams', false);
        $this->addRelation('CurrentStreamLessonStream', '\\Models\\Lesson', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':stream_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'CurrentStreamLessonStreams', false);
        $this->addRelation('StreamUser', '\\Models\\StreamUser', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':stream_id',
    1 => ':id',
  ),
), null, null, 'StreamUsers', false);
        $this->addRelation('User', '\\Models\\User', RelationMap::MANY_TO_MANY, array(), null, null, 'Users');
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
     * Method to invalidate the instance pool of all tables related to course_stream     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ApplicationTableMap::clearInstancePool();
        LessonTableMap::clearInstancePool();
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
        return $withPrefix ? CourseStreamTableMap::CLASS_DEFAULT : CourseStreamTableMap::OM_CLASS;
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
     * @return array           (CourseStream object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CourseStreamTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CourseStreamTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CourseStreamTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CourseStreamTableMap::OM_CLASS;
            /** @var CourseStream $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CourseStreamTableMap::addInstanceToPool($obj, $key);
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
            $key = CourseStreamTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CourseStreamTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var CourseStream $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CourseStreamTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CourseStreamTableMap::COL_ID);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_NAME);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_NUMBER_OF_PLACES);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_NOTES);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_STARTS_AT);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_ENDS_AT);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_SHOW_ON_WEBSITE);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_COST);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_BRANCH_ID);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_CURRENCY_ID);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_COURSE_ID);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_INSTRUCTOR_ID);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(CourseStreamTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.number_of_places');
            $criteria->addSelectColumn($alias . '.notes');
            $criteria->addSelectColumn($alias . '.starts_at');
            $criteria->addSelectColumn($alias . '.ends_at');
            $criteria->addSelectColumn($alias . '.show_on_website');
            $criteria->addSelectColumn($alias . '.cost');
            $criteria->addSelectColumn($alias . '.branch_id');
            $criteria->addSelectColumn($alias . '.currency_id');
            $criteria->addSelectColumn($alias . '.course_id');
            $criteria->addSelectColumn($alias . '.course_stream_status_id');
            $criteria->addSelectColumn($alias . '.instructor_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(CourseStreamTableMap::DATABASE_NAME)->getTable(CourseStreamTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CourseStreamTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CourseStreamTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CourseStreamTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a CourseStream or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or CourseStream object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\CourseStream) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CourseStreamTableMap::DATABASE_NAME);
            $criteria->add(CourseStreamTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = CourseStreamQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CourseStreamTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CourseStreamTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the course_stream table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CourseStreamQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a CourseStream or Criteria object.
     *
     * @param mixed               $criteria Criteria or CourseStream object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from CourseStream object
        }

        if ($criteria->containsKey(CourseStreamTableMap::COL_ID) && $criteria->keyContainsValue(CourseStreamTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CourseStreamTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = CourseStreamQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CourseStreamTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CourseStreamTableMap::buildTableMap();
