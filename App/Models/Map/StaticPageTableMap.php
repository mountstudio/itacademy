<?php

namespace Models\Map;

use Models\StaticPage;
use Models\StaticPageQuery;
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
 * This class defines the structure of the 'static_page' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class StaticPageTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.StaticPageTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'static_page';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\StaticPage';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.StaticPage';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the id field
     */
    const COL_ID = 'static_page.id';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'static_page.title';

    /**
     * the column name for the logo_name field
     */
    const COL_LOGO_NAME = 'static_page.logo_name';

    /**
     * the column name for the cover_name field
     */
    const COL_COVER_NAME = 'static_page.cover_name';

    /**
     * the column name for the alt_url field
     */
    const COL_ALT_URL = 'static_page.alt_url';

    /**
     * the column name for the is_available field
     */
    const COL_IS_AVAILABLE = 'static_page.is_available';

    /**
     * the column name for the content field
     */
    const COL_CONTENT = 'static_page.content';

    /**
     * the column name for the context field
     */
    const COL_CONTEXT = 'static_page.context';

    /**
     * the column name for the notes field
     */
    const COL_NOTES = 'static_page.notes';

    /**
     * the column name for the meta_description field
     */
    const COL_META_DESCRIPTION = 'static_page.meta_description';

    /**
     * the column name for the meta_keywords field
     */
    const COL_META_KEYWORDS = 'static_page.meta_keywords';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'static_page.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'static_page.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'Title', 'LogoName', 'CoverName', 'AltUrl', 'Available', 'Content', 'Context', 'Notes', 'MetaDescription', 'MetaKeywords', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'title', 'logoName', 'coverName', 'altUrl', 'available', 'content', 'context', 'notes', 'metaDescription', 'metaKeywords', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(StaticPageTableMap::COL_ID, StaticPageTableMap::COL_TITLE, StaticPageTableMap::COL_LOGO_NAME, StaticPageTableMap::COL_COVER_NAME, StaticPageTableMap::COL_ALT_URL, StaticPageTableMap::COL_IS_AVAILABLE, StaticPageTableMap::COL_CONTENT, StaticPageTableMap::COL_CONTEXT, StaticPageTableMap::COL_NOTES, StaticPageTableMap::COL_META_DESCRIPTION, StaticPageTableMap::COL_META_KEYWORDS, StaticPageTableMap::COL_CREATED_AT, StaticPageTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'title', 'logo_name', 'cover_name', 'alt_url', 'is_available', 'content', 'context', 'notes', 'meta_description', 'meta_keywords', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Title' => 1, 'LogoName' => 2, 'CoverName' => 3, 'AltUrl' => 4, 'Available' => 5, 'Content' => 6, 'Context' => 7, 'Notes' => 8, 'MetaDescription' => 9, 'MetaKeywords' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'title' => 1, 'logoName' => 2, 'coverName' => 3, 'altUrl' => 4, 'available' => 5, 'content' => 6, 'context' => 7, 'notes' => 8, 'metaDescription' => 9, 'metaKeywords' => 10, 'createdAt' => 11, 'updatedAt' => 12, ),
        self::TYPE_COLNAME       => array(StaticPageTableMap::COL_ID => 0, StaticPageTableMap::COL_TITLE => 1, StaticPageTableMap::COL_LOGO_NAME => 2, StaticPageTableMap::COL_COVER_NAME => 3, StaticPageTableMap::COL_ALT_URL => 4, StaticPageTableMap::COL_IS_AVAILABLE => 5, StaticPageTableMap::COL_CONTENT => 6, StaticPageTableMap::COL_CONTEXT => 7, StaticPageTableMap::COL_NOTES => 8, StaticPageTableMap::COL_META_DESCRIPTION => 9, StaticPageTableMap::COL_META_KEYWORDS => 10, StaticPageTableMap::COL_CREATED_AT => 11, StaticPageTableMap::COL_UPDATED_AT => 12, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'title' => 1, 'logo_name' => 2, 'cover_name' => 3, 'alt_url' => 4, 'is_available' => 5, 'content' => 6, 'context' => 7, 'notes' => 8, 'meta_description' => 9, 'meta_keywords' => 10, 'created_at' => 11, 'updated_at' => 12, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
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
        $this->setName('static_page');
        $this->setPhpName('StaticPage');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Models\\StaticPage');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 90, null);
        $this->addColumn('logo_name', 'LogoName', 'VARCHAR', false, 255, null);
        $this->addColumn('cover_name', 'CoverName', 'VARCHAR', false, 32, null);
        $this->addColumn('alt_url', 'AltUrl', 'VARCHAR', true, 255, null);
        $this->addColumn('is_available', 'Available', 'BOOLEAN', true, 1, false);
        $this->addColumn('content', 'Content', 'LONGVARCHAR', false, null, null);
        $this->addColumn('context', 'Context', 'LONGVARCHAR', false, null, null);
        $this->addColumn('notes', 'Notes', 'VARCHAR', false, 500, null);
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
        return $withPrefix ? StaticPageTableMap::CLASS_DEFAULT : StaticPageTableMap::OM_CLASS;
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
     * @return array           (StaticPage object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = StaticPageTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = StaticPageTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + StaticPageTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StaticPageTableMap::OM_CLASS;
            /** @var StaticPage $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            StaticPageTableMap::addInstanceToPool($obj, $key);
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
            $key = StaticPageTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = StaticPageTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var StaticPage $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StaticPageTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(StaticPageTableMap::COL_ID);
            $criteria->addSelectColumn(StaticPageTableMap::COL_TITLE);
            $criteria->addSelectColumn(StaticPageTableMap::COL_LOGO_NAME);
            $criteria->addSelectColumn(StaticPageTableMap::COL_COVER_NAME);
            $criteria->addSelectColumn(StaticPageTableMap::COL_ALT_URL);
            $criteria->addSelectColumn(StaticPageTableMap::COL_IS_AVAILABLE);
            $criteria->addSelectColumn(StaticPageTableMap::COL_CONTENT);
            $criteria->addSelectColumn(StaticPageTableMap::COL_CONTEXT);
            $criteria->addSelectColumn(StaticPageTableMap::COL_NOTES);
            $criteria->addSelectColumn(StaticPageTableMap::COL_META_DESCRIPTION);
            $criteria->addSelectColumn(StaticPageTableMap::COL_META_KEYWORDS);
            $criteria->addSelectColumn(StaticPageTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(StaticPageTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.logo_name');
            $criteria->addSelectColumn($alias . '.cover_name');
            $criteria->addSelectColumn($alias . '.alt_url');
            $criteria->addSelectColumn($alias . '.is_available');
            $criteria->addSelectColumn($alias . '.content');
            $criteria->addSelectColumn($alias . '.context');
            $criteria->addSelectColumn($alias . '.notes');
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
        return Propel::getServiceContainer()->getDatabaseMap(StaticPageTableMap::DATABASE_NAME)->getTable(StaticPageTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(StaticPageTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(StaticPageTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new StaticPageTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a StaticPage or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or StaticPage object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(StaticPageTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\StaticPage) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StaticPageTableMap::DATABASE_NAME);
            $criteria->add(StaticPageTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = StaticPageQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            StaticPageTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                StaticPageTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the static_page table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return StaticPageQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a StaticPage or Criteria object.
     *
     * @param mixed               $criteria Criteria or StaticPage object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StaticPageTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from StaticPage object
        }

        if ($criteria->containsKey(StaticPageTableMap::COL_ID) && $criteria->keyContainsValue(StaticPageTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StaticPageTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = StaticPageQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // StaticPageTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
StaticPageTableMap::buildTableMap();
