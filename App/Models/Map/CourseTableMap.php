<?php

namespace Models\Map;

use Models\Course;
use Models\CourseQuery;
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
 * This class defines the structure of the 'course' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CourseTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.CourseTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'course';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Course';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Course';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 15;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 15;

    /**
     * the column name for the id field
     */
    const COL_ID = 'course.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'course.name';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'course.description';

    /**
     * the column name for the alt_url field
     */
    const COL_ALT_URL = 'course.alt_url';

    /**
     * the column name for the logo_name field
     */
    const COL_LOGO_NAME = 'course.logo_name';

    /**
     * the column name for the cover_name field
     */
    const COL_COVER_NAME = 'course.cover_name';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'course.title';

    /**
     * the column name for the context field
     */
    const COL_CONTEXT = 'course.context';

    /**
     * the column name for the notes field
     */
    const COL_NOTES = 'course.notes';

    /**
     * the column name for the use_notes field
     */
    const COL_USE_NOTES = 'course.use_notes';

    /**
     * the column name for the uses field
     */
    const COL_USES = 'course.uses';

    /**
     * the column name for the meta_description field
     */
    const COL_META_DESCRIPTION = 'course.meta_description';

    /**
     * the column name for the meta_keywords field
     */
    const COL_META_KEYWORDS = 'course.meta_keywords';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'course.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'course.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Description', 'AltUrl', 'LogoName', 'CoverName', 'Title', 'Context', 'Notes', 'UseNotes', 'Uses', 'MetaDescription', 'MetaKeywords', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'description', 'altUrl', 'logoName', 'coverName', 'title', 'context', 'notes', 'useNotes', 'uses', 'metaDescription', 'metaKeywords', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(CourseTableMap::COL_ID, CourseTableMap::COL_NAME, CourseTableMap::COL_DESCRIPTION, CourseTableMap::COL_ALT_URL, CourseTableMap::COL_LOGO_NAME, CourseTableMap::COL_COVER_NAME, CourseTableMap::COL_TITLE, CourseTableMap::COL_CONTEXT, CourseTableMap::COL_NOTES, CourseTableMap::COL_USE_NOTES, CourseTableMap::COL_USES, CourseTableMap::COL_META_DESCRIPTION, CourseTableMap::COL_META_KEYWORDS, CourseTableMap::COL_CREATED_AT, CourseTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'description', 'alt_url', 'logo_name', 'cover_name', 'title', 'context', 'notes', 'use_notes', 'uses', 'meta_description', 'meta_keywords', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Description' => 2, 'AltUrl' => 3, 'LogoName' => 4, 'CoverName' => 5, 'Title' => 6, 'Context' => 7, 'Notes' => 8, 'UseNotes' => 9, 'Uses' => 10, 'MetaDescription' => 11, 'MetaKeywords' => 12, 'CreatedAt' => 13, 'UpdatedAt' => 14, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'altUrl' => 3, 'logoName' => 4, 'coverName' => 5, 'title' => 6, 'context' => 7, 'notes' => 8, 'useNotes' => 9, 'uses' => 10, 'metaDescription' => 11, 'metaKeywords' => 12, 'createdAt' => 13, 'updatedAt' => 14, ),
        self::TYPE_COLNAME       => array(CourseTableMap::COL_ID => 0, CourseTableMap::COL_NAME => 1, CourseTableMap::COL_DESCRIPTION => 2, CourseTableMap::COL_ALT_URL => 3, CourseTableMap::COL_LOGO_NAME => 4, CourseTableMap::COL_COVER_NAME => 5, CourseTableMap::COL_TITLE => 6, CourseTableMap::COL_CONTEXT => 7, CourseTableMap::COL_NOTES => 8, CourseTableMap::COL_USE_NOTES => 9, CourseTableMap::COL_USES => 10, CourseTableMap::COL_META_DESCRIPTION => 11, CourseTableMap::COL_META_KEYWORDS => 12, CourseTableMap::COL_CREATED_AT => 13, CourseTableMap::COL_UPDATED_AT => 14, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'alt_url' => 3, 'logo_name' => 4, 'cover_name' => 5, 'title' => 6, 'context' => 7, 'notes' => 8, 'use_notes' => 9, 'uses' => 10, 'meta_description' => 11, 'meta_keywords' => 12, 'created_at' => 13, 'updated_at' => 14, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
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
        $this->setName('course');
        $this->setPhpName('Course');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Models\\Course');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        $this->setIsCrossRef(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 20, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('alt_url', 'AltUrl', 'VARCHAR', true, 255, null);
        $this->addColumn('logo_name', 'LogoName', 'VARCHAR', false, 255, null);
        $this->addColumn('cover_name', 'CoverName', 'VARCHAR', false, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('context', 'Context', 'LONGVARCHAR', false, null, null);
        $this->addColumn('notes', 'Notes', 'VARCHAR', false, 300, null);
        $this->addColumn('use_notes', 'UseNotes', 'VARCHAR', true, 700, null);
        $this->addColumn('uses', 'Uses', 'OBJECT', false, null, null);
        $this->addColumn('meta_description', 'MetaDescription', 'LONGVARCHAR', false, null, null);
        $this->addColumn('meta_keywords', 'MetaKeywords', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CurrentApplicationCourse', '\\Models\\Application', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':course_id',
    1 => ':id',
  ),
), 'SET NULL', null, 'CurrentApplicationCourses', false);
        $this->addRelation('CurrentCourseStreamCourse', '\\Models\\CourseStream', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':course_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'CurrentCourseStreamCourses', false);
        $this->addRelation('CurrentCourseCourseSkill', '\\Models\\CourseSkill', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':course_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'CurrentCourseCourseSkills', false);
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
     * Method to invalidate the instance pool of all tables related to course     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ApplicationTableMap::clearInstancePool();
        CourseStreamTableMap::clearInstancePool();
        CourseSkillTableMap::clearInstancePool();
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
        return $withPrefix ? CourseTableMap::CLASS_DEFAULT : CourseTableMap::OM_CLASS;
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
     * @return array           (Course object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CourseTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CourseTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CourseTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CourseTableMap::OM_CLASS;
            /** @var Course $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CourseTableMap::addInstanceToPool($obj, $key);
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
            $key = CourseTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CourseTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Course $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CourseTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CourseTableMap::COL_ID);
            $criteria->addSelectColumn(CourseTableMap::COL_NAME);
            $criteria->addSelectColumn(CourseTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(CourseTableMap::COL_ALT_URL);
            $criteria->addSelectColumn(CourseTableMap::COL_LOGO_NAME);
            $criteria->addSelectColumn(CourseTableMap::COL_COVER_NAME);
            $criteria->addSelectColumn(CourseTableMap::COL_TITLE);
            $criteria->addSelectColumn(CourseTableMap::COL_CONTEXT);
            $criteria->addSelectColumn(CourseTableMap::COL_NOTES);
            $criteria->addSelectColumn(CourseTableMap::COL_USE_NOTES);
            $criteria->addSelectColumn(CourseTableMap::COL_USES);
            $criteria->addSelectColumn(CourseTableMap::COL_META_DESCRIPTION);
            $criteria->addSelectColumn(CourseTableMap::COL_META_KEYWORDS);
            $criteria->addSelectColumn(CourseTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(CourseTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.alt_url');
            $criteria->addSelectColumn($alias . '.logo_name');
            $criteria->addSelectColumn($alias . '.cover_name');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.context');
            $criteria->addSelectColumn($alias . '.notes');
            $criteria->addSelectColumn($alias . '.use_notes');
            $criteria->addSelectColumn($alias . '.uses');
            $criteria->addSelectColumn($alias . '.meta_description');
            $criteria->addSelectColumn($alias . '.meta_keywords');
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
        return Propel::getServiceContainer()->getDatabaseMap(CourseTableMap::DATABASE_NAME)->getTable(CourseTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CourseTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CourseTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CourseTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Course or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Course object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Course) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CourseTableMap::DATABASE_NAME);
            $criteria->add(CourseTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = CourseQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CourseTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CourseTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the course table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CourseQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Course or Criteria object.
     *
     * @param mixed               $criteria Criteria or Course object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CourseTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Course object
        }

        if ($criteria->containsKey(CourseTableMap::COL_ID) && $criteria->keyContainsValue(CourseTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CourseTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = CourseQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CourseTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CourseTableMap::buildTableMap();
