<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\AdminStyle as ChildAdminStyle;
use Models\AdminStyleQuery as ChildAdminStyleQuery;
use Models\Map\AdminStyleTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'admin_style' table.
 *
 *
 *
 * @method     ChildAdminStyleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAdminStyleQuery orderByAllowBLayout($order = Criteria::ASC) Order by the b_layout column
 * @method     ChildAdminStyleQuery orderByAllowCMenu($order = Criteria::ASC) Order by the c_menu column
 * @method     ChildAdminStyleQuery orderByAllowFHeader($order = Criteria::ASC) Order by the f_header column
 * @method     ChildAdminStyleQuery orderByAllowFSidebar($order = Criteria::ASC) Order by the f_sidebar column
 * @method     ChildAdminStyleQuery orderByAllowHBar($order = Criteria::ASC) Order by the h_bar column
 * @method     ChildAdminStyleQuery orderByAllowHMenu($order = Criteria::ASC) Order by the h_menu column
 * @method     ChildAdminStyleQuery orderByAllowTSidebar($order = Criteria::ASC) Order by the t_sidebar column
 * @method     ChildAdminStyleQuery orderByCustomStyle($order = Criteria::ASC) Order by the custom_style column
 *
 * @method     ChildAdminStyleQuery groupById() Group by the id column
 * @method     ChildAdminStyleQuery groupByAllowBLayout() Group by the b_layout column
 * @method     ChildAdminStyleQuery groupByAllowCMenu() Group by the c_menu column
 * @method     ChildAdminStyleQuery groupByAllowFHeader() Group by the f_header column
 * @method     ChildAdminStyleQuery groupByAllowFSidebar() Group by the f_sidebar column
 * @method     ChildAdminStyleQuery groupByAllowHBar() Group by the h_bar column
 * @method     ChildAdminStyleQuery groupByAllowHMenu() Group by the h_menu column
 * @method     ChildAdminStyleQuery groupByAllowTSidebar() Group by the t_sidebar column
 * @method     ChildAdminStyleQuery groupByCustomStyle() Group by the custom_style column
 *
 * @method     ChildAdminStyleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAdminStyleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAdminStyleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAdminStyleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAdminStyleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAdminStyleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAdminStyleQuery leftJoinCurrentAdminStyleUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentAdminStyleUser relation
 * @method     ChildAdminStyleQuery rightJoinCurrentAdminStyleUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentAdminStyleUser relation
 * @method     ChildAdminStyleQuery innerJoinCurrentAdminStyleUser($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentAdminStyleUser relation
 *
 * @method     ChildAdminStyleQuery joinWithCurrentAdminStyleUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentAdminStyleUser relation
 *
 * @method     ChildAdminStyleQuery leftJoinWithCurrentAdminStyleUser() Adds a LEFT JOIN clause and with to the query using the CurrentAdminStyleUser relation
 * @method     ChildAdminStyleQuery rightJoinWithCurrentAdminStyleUser() Adds a RIGHT JOIN clause and with to the query using the CurrentAdminStyleUser relation
 * @method     ChildAdminStyleQuery innerJoinWithCurrentAdminStyleUser() Adds a INNER JOIN clause and with to the query using the CurrentAdminStyleUser relation
 *
 * @method     \Models\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAdminStyle findOne(ConnectionInterface $con = null) Return the first ChildAdminStyle matching the query
 * @method     ChildAdminStyle findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAdminStyle matching the query, or a new ChildAdminStyle object populated from the query conditions when no match is found
 *
 * @method     ChildAdminStyle findOneById(int $id) Return the first ChildAdminStyle filtered by the id column
 * @method     ChildAdminStyle findOneByAllowBLayout(boolean $b_layout) Return the first ChildAdminStyle filtered by the b_layout column
 * @method     ChildAdminStyle findOneByAllowCMenu(boolean $c_menu) Return the first ChildAdminStyle filtered by the c_menu column
 * @method     ChildAdminStyle findOneByAllowFHeader(boolean $f_header) Return the first ChildAdminStyle filtered by the f_header column
 * @method     ChildAdminStyle findOneByAllowFSidebar(boolean $f_sidebar) Return the first ChildAdminStyle filtered by the f_sidebar column
 * @method     ChildAdminStyle findOneByAllowHBar(boolean $h_bar) Return the first ChildAdminStyle filtered by the h_bar column
 * @method     ChildAdminStyle findOneByAllowHMenu(boolean $h_menu) Return the first ChildAdminStyle filtered by the h_menu column
 * @method     ChildAdminStyle findOneByAllowTSidebar(boolean $t_sidebar) Return the first ChildAdminStyle filtered by the t_sidebar column
 * @method     ChildAdminStyle findOneByCustomStyle(string $custom_style) Return the first ChildAdminStyle filtered by the custom_style column *

 * @method     ChildAdminStyle requirePk($key, ConnectionInterface $con = null) Return the ChildAdminStyle by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOne(ConnectionInterface $con = null) Return the first ChildAdminStyle matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAdminStyle requireOneById(int $id) Return the first ChildAdminStyle filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOneByAllowBLayout(boolean $b_layout) Return the first ChildAdminStyle filtered by the b_layout column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOneByAllowCMenu(boolean $c_menu) Return the first ChildAdminStyle filtered by the c_menu column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOneByAllowFHeader(boolean $f_header) Return the first ChildAdminStyle filtered by the f_header column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOneByAllowFSidebar(boolean $f_sidebar) Return the first ChildAdminStyle filtered by the f_sidebar column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOneByAllowHBar(boolean $h_bar) Return the first ChildAdminStyle filtered by the h_bar column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOneByAllowHMenu(boolean $h_menu) Return the first ChildAdminStyle filtered by the h_menu column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOneByAllowTSidebar(boolean $t_sidebar) Return the first ChildAdminStyle filtered by the t_sidebar column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdminStyle requireOneByCustomStyle(string $custom_style) Return the first ChildAdminStyle filtered by the custom_style column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAdminStyle[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAdminStyle objects based on current ModelCriteria
 * @method     ChildAdminStyle[]|ObjectCollection findById(int $id) Return ChildAdminStyle objects filtered by the id column
 * @method     ChildAdminStyle[]|ObjectCollection findByAllowBLayout(boolean $b_layout) Return ChildAdminStyle objects filtered by the b_layout column
 * @method     ChildAdminStyle[]|ObjectCollection findByAllowCMenu(boolean $c_menu) Return ChildAdminStyle objects filtered by the c_menu column
 * @method     ChildAdminStyle[]|ObjectCollection findByAllowFHeader(boolean $f_header) Return ChildAdminStyle objects filtered by the f_header column
 * @method     ChildAdminStyle[]|ObjectCollection findByAllowFSidebar(boolean $f_sidebar) Return ChildAdminStyle objects filtered by the f_sidebar column
 * @method     ChildAdminStyle[]|ObjectCollection findByAllowHBar(boolean $h_bar) Return ChildAdminStyle objects filtered by the h_bar column
 * @method     ChildAdminStyle[]|ObjectCollection findByAllowHMenu(boolean $h_menu) Return ChildAdminStyle objects filtered by the h_menu column
 * @method     ChildAdminStyle[]|ObjectCollection findByAllowTSidebar(boolean $t_sidebar) Return ChildAdminStyle objects filtered by the t_sidebar column
 * @method     ChildAdminStyle[]|ObjectCollection findByCustomStyle(string $custom_style) Return ChildAdminStyle objects filtered by the custom_style column
 * @method     ChildAdminStyle[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AdminStyleQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\AdminStyleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\AdminStyle', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAdminStyleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAdminStyleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAdminStyleQuery) {
            return $criteria;
        }
        $query = new ChildAdminStyleQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildAdminStyle|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AdminStyleTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AdminStyleTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAdminStyle A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `b_layout`, `c_menu`, `f_header`, `f_sidebar`, `h_bar`, `h_menu`, `t_sidebar`, `custom_style` FROM `admin_style` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildAdminStyle $obj */
            $obj = new ChildAdminStyle();
            $obj->hydrate($row);
            AdminStyleTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildAdminStyle|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AdminStyleTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AdminStyleTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AdminStyleTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AdminStyleTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the b_layout column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowBLayout(true); // WHERE b_layout = true
     * $query->filterByAllowBLayout('yes'); // WHERE b_layout = true
     * </code>
     *
     * @param     boolean|string $allowBLayout The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByAllowBLayout($allowBLayout = null, $comparison = null)
    {
        if (is_string($allowBLayout)) {
            $allowBLayout = in_array(strtolower($allowBLayout), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_B_LAYOUT, $allowBLayout, $comparison);
    }

    /**
     * Filter the query on the c_menu column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowCMenu(true); // WHERE c_menu = true
     * $query->filterByAllowCMenu('yes'); // WHERE c_menu = true
     * </code>
     *
     * @param     boolean|string $allowCMenu The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByAllowCMenu($allowCMenu = null, $comparison = null)
    {
        if (is_string($allowCMenu)) {
            $allowCMenu = in_array(strtolower($allowCMenu), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_C_MENU, $allowCMenu, $comparison);
    }

    /**
     * Filter the query on the f_header column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowFHeader(true); // WHERE f_header = true
     * $query->filterByAllowFHeader('yes'); // WHERE f_header = true
     * </code>
     *
     * @param     boolean|string $allowFHeader The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByAllowFHeader($allowFHeader = null, $comparison = null)
    {
        if (is_string($allowFHeader)) {
            $allowFHeader = in_array(strtolower($allowFHeader), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_F_HEADER, $allowFHeader, $comparison);
    }

    /**
     * Filter the query on the f_sidebar column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowFSidebar(true); // WHERE f_sidebar = true
     * $query->filterByAllowFSidebar('yes'); // WHERE f_sidebar = true
     * </code>
     *
     * @param     boolean|string $allowFSidebar The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByAllowFSidebar($allowFSidebar = null, $comparison = null)
    {
        if (is_string($allowFSidebar)) {
            $allowFSidebar = in_array(strtolower($allowFSidebar), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_F_SIDEBAR, $allowFSidebar, $comparison);
    }

    /**
     * Filter the query on the h_bar column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowHBar(true); // WHERE h_bar = true
     * $query->filterByAllowHBar('yes'); // WHERE h_bar = true
     * </code>
     *
     * @param     boolean|string $allowHBar The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByAllowHBar($allowHBar = null, $comparison = null)
    {
        if (is_string($allowHBar)) {
            $allowHBar = in_array(strtolower($allowHBar), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_H_BAR, $allowHBar, $comparison);
    }

    /**
     * Filter the query on the h_menu column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowHMenu(true); // WHERE h_menu = true
     * $query->filterByAllowHMenu('yes'); // WHERE h_menu = true
     * </code>
     *
     * @param     boolean|string $allowHMenu The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByAllowHMenu($allowHMenu = null, $comparison = null)
    {
        if (is_string($allowHMenu)) {
            $allowHMenu = in_array(strtolower($allowHMenu), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_H_MENU, $allowHMenu, $comparison);
    }

    /**
     * Filter the query on the t_sidebar column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowTSidebar(true); // WHERE t_sidebar = true
     * $query->filterByAllowTSidebar('yes'); // WHERE t_sidebar = true
     * </code>
     *
     * @param     boolean|string $allowTSidebar The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByAllowTSidebar($allowTSidebar = null, $comparison = null)
    {
        if (is_string($allowTSidebar)) {
            $allowTSidebar = in_array(strtolower($allowTSidebar), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_T_SIDEBAR, $allowTSidebar, $comparison);
    }

    /**
     * Filter the query on the custom_style column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomStyle('fooValue');   // WHERE custom_style = 'fooValue'
     * $query->filterByCustomStyle('%fooValue%', Criteria::LIKE); // WHERE custom_style LIKE '%fooValue%'
     * </code>
     *
     * @param     string $customStyle The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByCustomStyle($customStyle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($customStyle)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdminStyleTableMap::COL_CUSTOM_STYLE, $customStyle, $comparison);
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User|ObjectCollection $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminStyleQuery The current query, for fluid interface
     */
    public function filterByCurrentAdminStyleUser($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(AdminStyleTableMap::COL_ID, $user->getCurrentAdminStyleId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useCurrentAdminStyleUserQuery()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentAdminStyleUser() only accepts arguments of type \Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentAdminStyleUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function joinCurrentAdminStyleUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentAdminStyleUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CurrentAdminStyleUser');
        }

        return $this;
    }

    /**
     * Use the CurrentAdminStyleUser relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useCurrentAdminStyleUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentAdminStyleUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentAdminStyleUser', '\Models\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAdminStyle $adminStyle Object to remove from the list of results
     *
     * @return $this|ChildAdminStyleQuery The current query, for fluid interface
     */
    public function prune($adminStyle = null)
    {
        if ($adminStyle) {
            $this->addUsingAlias(AdminStyleTableMap::COL_ID, $adminStyle->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the admin_style table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AdminStyleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AdminStyleTableMap::clearInstancePool();
            AdminStyleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AdminStyleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AdminStyleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AdminStyleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AdminStyleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // query_cache behavior

    public function setQueryKey($key)
    {
        $this->queryKey = $key;

        return $this;
    }

    public function getQueryKey()
    {
        return $this->queryKey;
    }

    public function cacheContains($key)
    {

        return apc_fetch($key);
    }

    public function cacheFetch($key)
    {

        return apc_fetch($key);
    }

    public function cacheStore($key, $value, $lifetime = 3600)
    {
        apc_store($key, $value, $lifetime);
    }

    public function doSelect(ConnectionInterface $con = null)
    {
        // check that the columns of the main class are already added (if this is the primary ModelCriteria)
        if (!$this->hasSelectClause() && !$this->getPrimaryCriteria()) {
            $this->addSelfSelectColumns();
        }
        $this->configureSelectColumns();

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AdminStyleTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(AdminStyleTableMap::DATABASE_NAME);

        $key = $this->getQueryKey();
        if ($key && $this->cacheContains($key)) {
            $params = $this->getParams();
            $sql = $this->cacheFetch($key);
        } else {
            $params = array();
            $sql = $this->createSelectSql($params);
        }

        try {
            $stmt = $con->prepare($sql);
            $db->bindValues($stmt, $params, $dbMap);
            $stmt->execute();
            } catch (Exception $e) {
                Propel::log($e->getMessage(), Propel::LOG_ERR);
                throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
            }

        if ($key && !$this->cacheContains($key)) {
                $this->cacheStore($key, $sql);
        }

        return $con->getDataFetcher($stmt);
    }

    public function doCount(ConnectionInterface $con = null)
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap($this->getDbName());
        $db = Propel::getServiceContainer()->getAdapter($this->getDbName());

        $key = $this->getQueryKey();
        if ($key && $this->cacheContains($key)) {
            $params = $this->getParams();
            $sql = $this->cacheFetch($key);
        } else {
            // check that the columns of the main class are already added (if this is the primary ModelCriteria)
            if (!$this->hasSelectClause() && !$this->getPrimaryCriteria()) {
                $this->addSelfSelectColumns();
            }

            $this->configureSelectColumns();

            $needsComplexCount = $this->getGroupByColumns()
                || $this->getOffset()
                || $this->getLimit() >= 0
                || $this->getHaving()
                || in_array(Criteria::DISTINCT, $this->getSelectModifiers())
                || count($this->selectQueries) > 0
            ;

            $params = array();
            if ($needsComplexCount) {
                if ($this->needsSelectAliases()) {
                    if ($this->getHaving()) {
                        throw new PropelException('Propel cannot create a COUNT query when using HAVING and  duplicate column names in the SELECT part');
                    }
                    $db->turnSelectColumnsToAliases($this);
                }
                $selectSql = $this->createSelectSql($params);
                $sql = 'SELECT COUNT(*) FROM (' . $selectSql . ') propelmatch4cnt';
            } else {
                // Replace SELECT columns with COUNT(*)
                $this->clearSelectColumns()->addSelectColumn('COUNT(*)');
                $sql = $this->createSelectSql($params);
            }
        }

        try {
            $stmt = $con->prepare($sql);
            $db->bindValues($stmt, $params, $dbMap);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute COUNT statement [%s]', $sql), 0, $e);
        }

        if ($key && !$this->cacheContains($key)) {
                $this->cacheStore($key, $sql);
        }


        return $con->getDataFetcher($stmt);
    }

} // AdminStyleQuery
