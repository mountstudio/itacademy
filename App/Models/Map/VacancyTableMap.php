<?php

namespace Models\Map;

use Models\Vacancy;
use Models\VacancyQuery;
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
 * This class defines the structure of the 'vacancy' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class VacancyTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.VacancyTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'vacancy';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Vacancy';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Vacancy';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the id field
     */
    const COL_ID = 'vacancy.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'vacancy.name';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'vacancy.description';

    /**
     * the column name for the context field
     */
    const COL_CONTEXT = 'vacancy.context';

    /**
     * the column name for the alt_url field
     */
    const COL_ALT_URL = 'vacancy.alt_url';

    /**
     * the column name for the logo_name field
     */
    const COL_LOGO_NAME = 'vacancy.logo_name';

    /**
     * the column name for the vacancy_salary_id field
     */
    const COL_VACANCY_SALARY_ID = 'vacancy.vacancy_salary_id';

    /**
     * the column name for the meta_description field
     */
    const COL_META_DESCRIPTION = 'vacancy.meta_description';

    /**
     * the column name for the meta_keywords field
     */
    const COL_META_KEYWORDS = 'vacancy.meta_keywords';

    /**
     * the column name for the sortable_rank field
     */
    const COL_SORTABLE_RANK = 'vacancy.sortable_rank';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'vacancy.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'vacancy.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    // sortable behavior
    /**
     * rank column
     */
    const RANK_COL = "vacancy.sortable_rank";


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Description', 'Context', 'AltUrl', 'LogoName', 'CurrentVacancySalaryId', 'MetaDescription', 'MetaKeywords', 'SortableRank', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'description', 'context', 'altUrl', 'logoName', 'currentVacancySalaryId', 'metaDescription', 'metaKeywords', 'sortableRank', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(VacancyTableMap::COL_ID, VacancyTableMap::COL_NAME, VacancyTableMap::COL_DESCRIPTION, VacancyTableMap::COL_CONTEXT, VacancyTableMap::COL_ALT_URL, VacancyTableMap::COL_LOGO_NAME, VacancyTableMap::COL_VACANCY_SALARY_ID, VacancyTableMap::COL_META_DESCRIPTION, VacancyTableMap::COL_META_KEYWORDS, VacancyTableMap::COL_SORTABLE_RANK, VacancyTableMap::COL_CREATED_AT, VacancyTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'description', 'context', 'alt_url', 'logo_name', 'vacancy_salary_id', 'meta_description', 'meta_keywords', 'sortable_rank', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Description' => 2, 'Context' => 3, 'AltUrl' => 4, 'LogoName' => 5, 'CurrentVacancySalaryId' => 6, 'MetaDescription' => 7, 'MetaKeywords' => 8, 'SortableRank' => 9, 'CreatedAt' => 10, 'UpdatedAt' => 11, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'context' => 3, 'altUrl' => 4, 'logoName' => 5, 'currentVacancySalaryId' => 6, 'metaDescription' => 7, 'metaKeywords' => 8, 'sortableRank' => 9, 'createdAt' => 10, 'updatedAt' => 11, ),
        self::TYPE_COLNAME       => array(VacancyTableMap::COL_ID => 0, VacancyTableMap::COL_NAME => 1, VacancyTableMap::COL_DESCRIPTION => 2, VacancyTableMap::COL_CONTEXT => 3, VacancyTableMap::COL_ALT_URL => 4, VacancyTableMap::COL_LOGO_NAME => 5, VacancyTableMap::COL_VACANCY_SALARY_ID => 6, VacancyTableMap::COL_META_DESCRIPTION => 7, VacancyTableMap::COL_META_KEYWORDS => 8, VacancyTableMap::COL_SORTABLE_RANK => 9, VacancyTableMap::COL_CREATED_AT => 10, VacancyTableMap::COL_UPDATED_AT => 11, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'context' => 3, 'alt_url' => 4, 'logo_name' => 5, 'vacancy_salary_id' => 6, 'meta_description' => 7, 'meta_keywords' => 8, 'sortable_rank' => 9, 'created_at' => 10, 'updated_at' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
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
        $this->setName('vacancy');
        $this->setPhpName('Vacancy');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Vacancy');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('context', 'Context', 'LONGVARCHAR', false, null, null);
        $this->addColumn('alt_url', 'AltUrl', 'VARCHAR', true, 255, null);
        $this->addColumn('logo_name', 'LogoName', 'VARCHAR', false, 255, null);
        $this->addForeignKey('vacancy_salary_id', 'CurrentVacancySalaryId', 'INTEGER', 'vacancy_salary', 'id', true, null, null);
        $this->addColumn('meta_description', 'MetaDescription', 'LONGVARCHAR', false, null, null);
        $this->addColumn('meta_keywords', 'MetaKeywords', 'LONGVARCHAR', false, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CurrentVacancyVacancySalary', '\\Models\\VacancySalary', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':vacancy_salary_id',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', null, false);
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
        return $withPrefix ? VacancyTableMap::CLASS_DEFAULT : VacancyTableMap::OM_CLASS;
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
     * @return array           (Vacancy object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = VacancyTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = VacancyTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + VacancyTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = VacancyTableMap::OM_CLASS;
            /** @var Vacancy $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            VacancyTableMap::addInstanceToPool($obj, $key);
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
            $key = VacancyTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = VacancyTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Vacancy $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                VacancyTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(VacancyTableMap::COL_ID);
            $criteria->addSelectColumn(VacancyTableMap::COL_NAME);
            $criteria->addSelectColumn(VacancyTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(VacancyTableMap::COL_CONTEXT);
            $criteria->addSelectColumn(VacancyTableMap::COL_ALT_URL);
            $criteria->addSelectColumn(VacancyTableMap::COL_LOGO_NAME);
            $criteria->addSelectColumn(VacancyTableMap::COL_VACANCY_SALARY_ID);
            $criteria->addSelectColumn(VacancyTableMap::COL_META_DESCRIPTION);
            $criteria->addSelectColumn(VacancyTableMap::COL_META_KEYWORDS);
            $criteria->addSelectColumn(VacancyTableMap::COL_SORTABLE_RANK);
            $criteria->addSelectColumn(VacancyTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(VacancyTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.context');
            $criteria->addSelectColumn($alias . '.alt_url');
            $criteria->addSelectColumn($alias . '.logo_name');
            $criteria->addSelectColumn($alias . '.vacancy_salary_id');
            $criteria->addSelectColumn($alias . '.meta_description');
            $criteria->addSelectColumn($alias . '.meta_keywords');
            $criteria->addSelectColumn($alias . '.sortable_rank');
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
        return Propel::getServiceContainer()->getDatabaseMap(VacancyTableMap::DATABASE_NAME)->getTable(VacancyTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(VacancyTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(VacancyTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new VacancyTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Vacancy or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Vacancy object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(VacancyTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Vacancy) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(VacancyTableMap::DATABASE_NAME);
            $criteria->add(VacancyTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = VacancyQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            VacancyTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                VacancyTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the vacancy table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return VacancyQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Vacancy or Criteria object.
     *
     * @param mixed               $criteria Criteria or Vacancy object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(VacancyTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Vacancy object
        }

        if ($criteria->containsKey(VacancyTableMap::COL_ID) && $criteria->keyContainsValue(VacancyTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.VacancyTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = VacancyQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // VacancyTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
VacancyTableMap::buildTableMap();
