<?php

namespace Models\Map;

use Models\AdminStyle;
use Models\AdminStyleQuery;
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
 * This class defines the structure of the 'admin_style' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AdminStyleTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.AdminStyleTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'admin_style';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\AdminStyle';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.AdminStyle';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'admin_style.id';

    /**
     * the column name for the b_layout field
     */
    const COL_B_LAYOUT = 'admin_style.b_layout';

    /**
     * the column name for the c_menu field
     */
    const COL_C_MENU = 'admin_style.c_menu';

    /**
     * the column name for the f_header field
     */
    const COL_F_HEADER = 'admin_style.f_header';

    /**
     * the column name for the f_sidebar field
     */
    const COL_F_SIDEBAR = 'admin_style.f_sidebar';

    /**
     * the column name for the h_bar field
     */
    const COL_H_BAR = 'admin_style.h_bar';

    /**
     * the column name for the h_menu field
     */
    const COL_H_MENU = 'admin_style.h_menu';

    /**
     * the column name for the t_sidebar field
     */
    const COL_T_SIDEBAR = 'admin_style.t_sidebar';

    /**
     * the column name for the custom_style field
     */
    const COL_CUSTOM_STYLE = 'admin_style.custom_style';

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
        self::TYPE_PHPNAME       => array('Id', 'AllowBLayout', 'AllowCMenu', 'AllowFHeader', 'AllowFSidebar', 'AllowHBar', 'AllowHMenu', 'AllowTSidebar', 'CustomStyle', ),
        self::TYPE_CAMELNAME     => array('id', 'allowBLayout', 'allowCMenu', 'allowFHeader', 'allowFSidebar', 'allowHBar', 'allowHMenu', 'allowTSidebar', 'customStyle', ),
        self::TYPE_COLNAME       => array(AdminStyleTableMap::COL_ID, AdminStyleTableMap::COL_B_LAYOUT, AdminStyleTableMap::COL_C_MENU, AdminStyleTableMap::COL_F_HEADER, AdminStyleTableMap::COL_F_SIDEBAR, AdminStyleTableMap::COL_H_BAR, AdminStyleTableMap::COL_H_MENU, AdminStyleTableMap::COL_T_SIDEBAR, AdminStyleTableMap::COL_CUSTOM_STYLE, ),
        self::TYPE_FIELDNAME     => array('id', 'b_layout', 'c_menu', 'f_header', 'f_sidebar', 'h_bar', 'h_menu', 't_sidebar', 'custom_style', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'AllowBLayout' => 1, 'AllowCMenu' => 2, 'AllowFHeader' => 3, 'AllowFSidebar' => 4, 'AllowHBar' => 5, 'AllowHMenu' => 6, 'AllowTSidebar' => 7, 'CustomStyle' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'allowBLayout' => 1, 'allowCMenu' => 2, 'allowFHeader' => 3, 'allowFSidebar' => 4, 'allowHBar' => 5, 'allowHMenu' => 6, 'allowTSidebar' => 7, 'customStyle' => 8, ),
        self::TYPE_COLNAME       => array(AdminStyleTableMap::COL_ID => 0, AdminStyleTableMap::COL_B_LAYOUT => 1, AdminStyleTableMap::COL_C_MENU => 2, AdminStyleTableMap::COL_F_HEADER => 3, AdminStyleTableMap::COL_F_SIDEBAR => 4, AdminStyleTableMap::COL_H_BAR => 5, AdminStyleTableMap::COL_H_MENU => 6, AdminStyleTableMap::COL_T_SIDEBAR => 7, AdminStyleTableMap::COL_CUSTOM_STYLE => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'b_layout' => 1, 'c_menu' => 2, 'f_header' => 3, 'f_sidebar' => 4, 'h_bar' => 5, 'h_menu' => 6, 't_sidebar' => 7, 'custom_style' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
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
        $this->setName('admin_style');
        $this->setPhpName('AdminStyle');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Models\\AdminStyle');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('b_layout', 'AllowBLayout', 'BOOLEAN', true, 1, false);
        $this->addColumn('c_menu', 'AllowCMenu', 'BOOLEAN', true, 1, false);
        $this->addColumn('f_header', 'AllowFHeader', 'BOOLEAN', true, 1, true);
        $this->addColumn('f_sidebar', 'AllowFSidebar', 'BOOLEAN', true, 1, false);
        $this->addColumn('h_bar', 'AllowHBar', 'BOOLEAN', true, 1, false);
        $this->addColumn('h_menu', 'AllowHMenu', 'BOOLEAN', true, 1, false);
        $this->addColumn('t_sidebar', 'AllowTSidebar', 'BOOLEAN', true, 1, false);
        $this->addColumn('custom_style', 'CustomStyle', 'VARCHAR', true, 15, 'green');
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CurrentAdminStyleUser', '\\Models\\User', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':admin_style_id',
    1 => ':id',
  ),
), 'SET NULL', null, 'CurrentAdminStyleUsers', false);
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
            'query_cache' => array('backend' => 'apc', 'lifetime' => '3600', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to admin_style     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        UserTableMap::clearInstancePool();
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
        return $withPrefix ? AdminStyleTableMap::CLASS_DEFAULT : AdminStyleTableMap::OM_CLASS;
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
     * @return array           (AdminStyle object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AdminStyleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AdminStyleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AdminStyleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AdminStyleTableMap::OM_CLASS;
            /** @var AdminStyle $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AdminStyleTableMap::addInstanceToPool($obj, $key);
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
            $key = AdminStyleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AdminStyleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AdminStyle $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AdminStyleTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AdminStyleTableMap::COL_ID);
            $criteria->addSelectColumn(AdminStyleTableMap::COL_B_LAYOUT);
            $criteria->addSelectColumn(AdminStyleTableMap::COL_C_MENU);
            $criteria->addSelectColumn(AdminStyleTableMap::COL_F_HEADER);
            $criteria->addSelectColumn(AdminStyleTableMap::COL_F_SIDEBAR);
            $criteria->addSelectColumn(AdminStyleTableMap::COL_H_BAR);
            $criteria->addSelectColumn(AdminStyleTableMap::COL_H_MENU);
            $criteria->addSelectColumn(AdminStyleTableMap::COL_T_SIDEBAR);
            $criteria->addSelectColumn(AdminStyleTableMap::COL_CUSTOM_STYLE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.b_layout');
            $criteria->addSelectColumn($alias . '.c_menu');
            $criteria->addSelectColumn($alias . '.f_header');
            $criteria->addSelectColumn($alias . '.f_sidebar');
            $criteria->addSelectColumn($alias . '.h_bar');
            $criteria->addSelectColumn($alias . '.h_menu');
            $criteria->addSelectColumn($alias . '.t_sidebar');
            $criteria->addSelectColumn($alias . '.custom_style');
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
        return Propel::getServiceContainer()->getDatabaseMap(AdminStyleTableMap::DATABASE_NAME)->getTable(AdminStyleTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AdminStyleTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AdminStyleTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AdminStyleTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a AdminStyle or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or AdminStyle object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AdminStyleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\AdminStyle) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AdminStyleTableMap::DATABASE_NAME);
            $criteria->add(AdminStyleTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AdminStyleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AdminStyleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AdminStyleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the admin_style table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AdminStyleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AdminStyle or Criteria object.
     *
     * @param mixed               $criteria Criteria or AdminStyle object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AdminStyleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AdminStyle object
        }

        if ($criteria->containsKey(AdminStyleTableMap::COL_ID) && $criteria->keyContainsValue(AdminStyleTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AdminStyleTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AdminStyleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AdminStyleTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AdminStyleTableMap::buildTableMap();
