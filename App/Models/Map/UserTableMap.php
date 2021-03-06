<?php

namespace Models\Map;

use Models\User;
use Models\UserQuery;
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
 * This class defines the structure of the 'user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'user';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\User';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.User';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 20;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 20;

    /**
     * the column name for the id field
     */
    const COL_ID = 'user.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'user.name';

    /**
     * the column name for the user_name field
     */
    const COL_USER_NAME = 'user.user_name';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'user.email';

    /**
     * the column name for the about field
     */
    const COL_ABOUT = 'user.about';

    /**
     * the column name for the birth_date field
     */
    const COL_BIRTH_DATE = 'user.birth_date';

    /**
     * the column name for the password field
     */
    const COL_PASSWORD = 'user.password';

    /**
     * the column name for the phone field
     */
    const COL_PHONE = 'user.phone';

    /**
     * the column name for the logo_name field
     */
    const COL_LOGO_NAME = 'user.logo_name';

    /**
     * the column name for the cover_name field
     */
    const COL_COVER_NAME = 'user.cover_name';

    /**
     * the column name for the address field
     */
    const COL_ADDRESS = 'user.address';

    /**
     * the column name for the address_coordinates field
     */
    const COL_ADDRESS_COORDINATES = 'user.address_coordinates';

    /**
     * the column name for the is_activated field
     */
    const COL_IS_ACTIVATED = 'user.is_activated';

    /**
     * the column name for the social_id field
     */
    const COL_SOCIAL_ID = 'user.social_id';

    /**
     * the column name for the social_token field
     */
    const COL_SOCIAL_TOKEN = 'user.social_token';

    /**
     * the column name for the group_id field
     */
    const COL_GROUP_ID = 'user.group_id';

    /**
     * the column name for the currency_id field
     */
    const COL_CURRENCY_ID = 'user.currency_id';

    /**
     * the column name for the admin_style_id field
     */
    const COL_ADMIN_STYLE_ID = 'user.admin_style_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'user.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'user.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'UserName', 'Email', 'About', 'BirthDate', 'Password', 'Phone', 'LogoName', 'CoverName', 'Address', 'AddressCoordinates', 'Activated', 'SocialId', 'SocialToken', 'CurrentGroupId', 'CurrentCurrencyId', 'CurrentAdminStyleId', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'userName', 'email', 'about', 'birthDate', 'password', 'phone', 'logoName', 'coverName', 'address', 'addressCoordinates', 'activated', 'socialId', 'socialToken', 'currentGroupId', 'currentCurrencyId', 'currentAdminStyleId', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_NAME, UserTableMap::COL_USER_NAME, UserTableMap::COL_EMAIL, UserTableMap::COL_ABOUT, UserTableMap::COL_BIRTH_DATE, UserTableMap::COL_PASSWORD, UserTableMap::COL_PHONE, UserTableMap::COL_LOGO_NAME, UserTableMap::COL_COVER_NAME, UserTableMap::COL_ADDRESS, UserTableMap::COL_ADDRESS_COORDINATES, UserTableMap::COL_IS_ACTIVATED, UserTableMap::COL_SOCIAL_ID, UserTableMap::COL_SOCIAL_TOKEN, UserTableMap::COL_GROUP_ID, UserTableMap::COL_CURRENCY_ID, UserTableMap::COL_ADMIN_STYLE_ID, UserTableMap::COL_CREATED_AT, UserTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'user_name', 'email', 'about', 'birth_date', 'password', 'phone', 'logo_name', 'cover_name', 'address', 'address_coordinates', 'is_activated', 'social_id', 'social_token', 'group_id', 'currency_id', 'admin_style_id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'UserName' => 2, 'Email' => 3, 'About' => 4, 'BirthDate' => 5, 'Password' => 6, 'Phone' => 7, 'LogoName' => 8, 'CoverName' => 9, 'Address' => 10, 'AddressCoordinates' => 11, 'Activated' => 12, 'SocialId' => 13, 'SocialToken' => 14, 'CurrentGroupId' => 15, 'CurrentCurrencyId' => 16, 'CurrentAdminStyleId' => 17, 'CreatedAt' => 18, 'UpdatedAt' => 19, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'userName' => 2, 'email' => 3, 'about' => 4, 'birthDate' => 5, 'password' => 6, 'phone' => 7, 'logoName' => 8, 'coverName' => 9, 'address' => 10, 'addressCoordinates' => 11, 'activated' => 12, 'socialId' => 13, 'socialToken' => 14, 'currentGroupId' => 15, 'currentCurrencyId' => 16, 'currentAdminStyleId' => 17, 'createdAt' => 18, 'updatedAt' => 19, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_NAME => 1, UserTableMap::COL_USER_NAME => 2, UserTableMap::COL_EMAIL => 3, UserTableMap::COL_ABOUT => 4, UserTableMap::COL_BIRTH_DATE => 5, UserTableMap::COL_PASSWORD => 6, UserTableMap::COL_PHONE => 7, UserTableMap::COL_LOGO_NAME => 8, UserTableMap::COL_COVER_NAME => 9, UserTableMap::COL_ADDRESS => 10, UserTableMap::COL_ADDRESS_COORDINATES => 11, UserTableMap::COL_IS_ACTIVATED => 12, UserTableMap::COL_SOCIAL_ID => 13, UserTableMap::COL_SOCIAL_TOKEN => 14, UserTableMap::COL_GROUP_ID => 15, UserTableMap::COL_CURRENCY_ID => 16, UserTableMap::COL_ADMIN_STYLE_ID => 17, UserTableMap::COL_CREATED_AT => 18, UserTableMap::COL_UPDATED_AT => 19, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'user_name' => 2, 'email' => 3, 'about' => 4, 'birth_date' => 5, 'password' => 6, 'phone' => 7, 'logo_name' => 8, 'cover_name' => 9, 'address' => 10, 'address_coordinates' => 11, 'is_activated' => 12, 'social_id' => 13, 'social_token' => 14, 'group_id' => 15, 'currency_id' => 16, 'admin_style_id' => 17, 'created_at' => 18, 'updated_at' => 19, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, )
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
        $this->setName('user');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Models\\User');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 32, null);
        $this->addColumn('user_name', 'UserName', 'VARCHAR', false, 32, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 128, null);
        $this->addColumn('about', 'About', 'LONGVARCHAR', false, null, null);
        $this->addColumn('birth_date', 'BirthDate', 'DATE', false, null, null);
        $this->addColumn('password', 'Password', 'VARCHAR', false, 100, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 12, null);
        $this->addColumn('logo_name', 'LogoName', 'VARCHAR', false, 32, null);
        $this->addColumn('cover_name', 'CoverName', 'VARCHAR', false, 32, null);
        $this->addColumn('address', 'Address', 'VARCHAR', false, 100, null);
        $this->addColumn('address_coordinates', 'AddressCoordinates', 'ARRAY', false, null, null);
        $this->addColumn('is_activated', 'Activated', 'BOOLEAN', true, 1, false);
        $this->addColumn('social_id', 'SocialId', 'VARCHAR', false, 255, null);
        $this->addColumn('social_token', 'SocialToken', 'VARCHAR', false, 255, null);
        $this->addForeignKey('group_id', 'CurrentGroupId', 'INTEGER', 'group', 'id', true, null, null);
        $this->addForeignKey('currency_id', 'CurrentCurrencyId', 'INTEGER', 'currency', 'id', false, null, null);
        $this->addForeignKey('admin_style_id', 'CurrentAdminStyleId', 'INTEGER', 'admin_style', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CurrentGroup', '\\Models\\Group', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':group_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('CurrentUserCurrency', '\\Models\\Currency', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':currency_id',
    1 => ':id',
  ),
), 'SET NULL', null, null, false);
        $this->addRelation('CurrentAdminStyle', '\\Models\\AdminStyle', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':admin_style_id',
    1 => ':id',
  ),
), 'SET NULL', null, null, false);
        $this->addRelation('CurrentUserVerificationToken', '\\Models\\VerificationToken', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'CurrentUserVerificationTokens', false);
        $this->addRelation('CurrentInstructorCourseStream', '\\Models\\CourseStream', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':instructor_id',
    1 => ':id',
  ),
), 'SET NULL', null, 'CurrentInstructorCourseStreams', false);
        $this->addRelation('Passport', '\\Models\\Passport', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('StreamUser', '\\Models\\StreamUser', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'StreamUsers', false);
        $this->addRelation('ToUserNotification', '\\Models\\Notification', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':to_user_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'ToUserNotifications', false);
        $this->addRelation('FromUserNotification', '\\Models\\Notification', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':from_user_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'FromUserNotifications', false);
        $this->addRelation('CurrentUserFeedback', '\\Models\\Feedback', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'CurrentUserFeedbacks', false);
        $this->addRelation('CourseStream', '\\Models\\CourseStream', RelationMap::MANY_TO_MANY, array(), null, null, 'CourseStreams');
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
     * Method to invalidate the instance pool of all tables related to user     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CourseStreamTableMap::clearInstancePool();
        NotificationTableMap::clearInstancePool();
        FeedbackTableMap::clearInstancePool();
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
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
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
     * @return array           (User object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
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
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_USER_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_ABOUT);
            $criteria->addSelectColumn(UserTableMap::COL_BIRTH_DATE);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_PHONE);
            $criteria->addSelectColumn(UserTableMap::COL_LOGO_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_COVER_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(UserTableMap::COL_ADDRESS_COORDINATES);
            $criteria->addSelectColumn(UserTableMap::COL_IS_ACTIVATED);
            $criteria->addSelectColumn(UserTableMap::COL_SOCIAL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_SOCIAL_TOKEN);
            $criteria->addSelectColumn(UserTableMap::COL_GROUP_ID);
            $criteria->addSelectColumn(UserTableMap::COL_CURRENCY_ID);
            $criteria->addSelectColumn(UserTableMap::COL_ADMIN_STYLE_ID);
            $criteria->addSelectColumn(UserTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(UserTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.user_name');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.about');
            $criteria->addSelectColumn($alias . '.birth_date');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.logo_name');
            $criteria->addSelectColumn($alias . '.cover_name');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.address_coordinates');
            $criteria->addSelectColumn($alias . '.is_activated');
            $criteria->addSelectColumn($alias . '.social_id');
            $criteria->addSelectColumn($alias . '.social_token');
            $criteria->addSelectColumn($alias . '.group_id');
            $criteria->addSelectColumn($alias . '.currency_id');
            $criteria->addSelectColumn($alias . '.admin_style_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or User object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\User) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed               $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserTableMap::COL_ID) && $criteria->keyContainsValue(UserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserTableMap::buildTableMap();
