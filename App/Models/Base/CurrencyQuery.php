<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Currency as ChildCurrency;
use Models\CurrencyQuery as ChildCurrencyQuery;
use Models\Map\CurrencyTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'currency' table.
 *
 *
 *
 * @method     ChildCurrencyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCurrencyQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCurrencyQuery orderByISOCode($order = Criteria::ASC) Order by the iso_code column
 * @method     ChildCurrencyQuery orderBySymbol($order = Criteria::ASC) Order by the symbol column
 * @method     ChildCurrencyQuery orderByIsSymbolBefore($order = Criteria::ASC) Order by the is_symbol_before column
 * @method     ChildCurrencyQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method     ChildCurrencyQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method     ChildCurrencyQuery groupById() Group by the id column
 * @method     ChildCurrencyQuery groupByName() Group by the name column
 * @method     ChildCurrencyQuery groupByISOCode() Group by the iso_code column
 * @method     ChildCurrencyQuery groupBySymbol() Group by the symbol column
 * @method     ChildCurrencyQuery groupByIsSymbolBefore() Group by the is_symbol_before column
 * @method     ChildCurrencyQuery groupByNotes() Group by the notes column
 * @method     ChildCurrencyQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method     ChildCurrencyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCurrencyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCurrencyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCurrencyQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCurrencyQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCurrencyQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCurrencyQuery leftJoinCurrentCurrencyUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCurrencyUser relation
 * @method     ChildCurrencyQuery rightJoinCurrentCurrencyUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCurrencyUser relation
 * @method     ChildCurrencyQuery innerJoinCurrentCurrencyUser($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCurrencyUser relation
 *
 * @method     ChildCurrencyQuery joinWithCurrentCurrencyUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCurrencyUser relation
 *
 * @method     ChildCurrencyQuery leftJoinWithCurrentCurrencyUser() Adds a LEFT JOIN clause and with to the query using the CurrentCurrencyUser relation
 * @method     ChildCurrencyQuery rightJoinWithCurrentCurrencyUser() Adds a RIGHT JOIN clause and with to the query using the CurrentCurrencyUser relation
 * @method     ChildCurrencyQuery innerJoinWithCurrentCurrencyUser() Adds a INNER JOIN clause and with to the query using the CurrentCurrencyUser relation
 *
 * @method     ChildCurrencyQuery leftJoinCurrentCurrencyCourseStream($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCurrencyCourseStream relation
 * @method     ChildCurrencyQuery rightJoinCurrentCurrencyCourseStream($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCurrencyCourseStream relation
 * @method     ChildCurrencyQuery innerJoinCurrentCurrencyCourseStream($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCurrencyCourseStream relation
 *
 * @method     ChildCurrencyQuery joinWithCurrentCurrencyCourseStream($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCurrencyCourseStream relation
 *
 * @method     ChildCurrencyQuery leftJoinWithCurrentCurrencyCourseStream() Adds a LEFT JOIN clause and with to the query using the CurrentCurrencyCourseStream relation
 * @method     ChildCurrencyQuery rightJoinWithCurrentCurrencyCourseStream() Adds a RIGHT JOIN clause and with to the query using the CurrentCurrencyCourseStream relation
 * @method     ChildCurrencyQuery innerJoinWithCurrentCurrencyCourseStream() Adds a INNER JOIN clause and with to the query using the CurrentCurrencyCourseStream relation
 *
 * @method     ChildCurrencyQuery leftJoinCurrentDefaultCurrencyRate($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentDefaultCurrencyRate relation
 * @method     ChildCurrencyQuery rightJoinCurrentDefaultCurrencyRate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentDefaultCurrencyRate relation
 * @method     ChildCurrencyQuery innerJoinCurrentDefaultCurrencyRate($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentDefaultCurrencyRate relation
 *
 * @method     ChildCurrencyQuery joinWithCurrentDefaultCurrencyRate($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentDefaultCurrencyRate relation
 *
 * @method     ChildCurrencyQuery leftJoinWithCurrentDefaultCurrencyRate() Adds a LEFT JOIN clause and with to the query using the CurrentDefaultCurrencyRate relation
 * @method     ChildCurrencyQuery rightJoinWithCurrentDefaultCurrencyRate() Adds a RIGHT JOIN clause and with to the query using the CurrentDefaultCurrencyRate relation
 * @method     ChildCurrencyQuery innerJoinWithCurrentDefaultCurrencyRate() Adds a INNER JOIN clause and with to the query using the CurrentDefaultCurrencyRate relation
 *
 * @method     ChildCurrencyQuery leftJoinCurrentToCurrencyRate($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentToCurrencyRate relation
 * @method     ChildCurrencyQuery rightJoinCurrentToCurrencyRate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentToCurrencyRate relation
 * @method     ChildCurrencyQuery innerJoinCurrentToCurrencyRate($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentToCurrencyRate relation
 *
 * @method     ChildCurrencyQuery joinWithCurrentToCurrencyRate($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentToCurrencyRate relation
 *
 * @method     ChildCurrencyQuery leftJoinWithCurrentToCurrencyRate() Adds a LEFT JOIN clause and with to the query using the CurrentToCurrencyRate relation
 * @method     ChildCurrencyQuery rightJoinWithCurrentToCurrencyRate() Adds a RIGHT JOIN clause and with to the query using the CurrentToCurrencyRate relation
 * @method     ChildCurrencyQuery innerJoinWithCurrentToCurrencyRate() Adds a INNER JOIN clause and with to the query using the CurrentToCurrencyRate relation
 *
 * @method     ChildCurrencyQuery leftJoinCurrentCurrencyFeedback($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCurrencyFeedback relation
 * @method     ChildCurrencyQuery rightJoinCurrentCurrencyFeedback($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCurrencyFeedback relation
 * @method     ChildCurrencyQuery innerJoinCurrentCurrencyFeedback($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCurrencyFeedback relation
 *
 * @method     ChildCurrencyQuery joinWithCurrentCurrencyFeedback($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCurrencyFeedback relation
 *
 * @method     ChildCurrencyQuery leftJoinWithCurrentCurrencyFeedback() Adds a LEFT JOIN clause and with to the query using the CurrentCurrencyFeedback relation
 * @method     ChildCurrencyQuery rightJoinWithCurrentCurrencyFeedback() Adds a RIGHT JOIN clause and with to the query using the CurrentCurrencyFeedback relation
 * @method     ChildCurrencyQuery innerJoinWithCurrentCurrencyFeedback() Adds a INNER JOIN clause and with to the query using the CurrentCurrencyFeedback relation
 *
 * @method     \Models\UserQuery|\Models\CourseStreamQuery|\Models\CurrencyRateQuery|\Models\FeedbackQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCurrency findOne(ConnectionInterface $con = null) Return the first ChildCurrency matching the query
 * @method     ChildCurrency findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCurrency matching the query, or a new ChildCurrency object populated from the query conditions when no match is found
 *
 * @method     ChildCurrency findOneById(int $id) Return the first ChildCurrency filtered by the id column
 * @method     ChildCurrency findOneByName(string $name) Return the first ChildCurrency filtered by the name column
 * @method     ChildCurrency findOneByISOCode(string $iso_code) Return the first ChildCurrency filtered by the iso_code column
 * @method     ChildCurrency findOneBySymbol(string $symbol) Return the first ChildCurrency filtered by the symbol column
 * @method     ChildCurrency findOneByIsSymbolBefore(boolean $is_symbol_before) Return the first ChildCurrency filtered by the is_symbol_before column
 * @method     ChildCurrency findOneByNotes(string $notes) Return the first ChildCurrency filtered by the notes column
 * @method     ChildCurrency findOneBySortableRank(int $sortable_rank) Return the first ChildCurrency filtered by the sortable_rank column *

 * @method     ChildCurrency requirePk($key, ConnectionInterface $con = null) Return the ChildCurrency by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOne(ConnectionInterface $con = null) Return the first ChildCurrency matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCurrency requireOneById(int $id) Return the first ChildCurrency filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneByName(string $name) Return the first ChildCurrency filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneByISOCode(string $iso_code) Return the first ChildCurrency filtered by the iso_code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneBySymbol(string $symbol) Return the first ChildCurrency filtered by the symbol column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneByIsSymbolBefore(boolean $is_symbol_before) Return the first ChildCurrency filtered by the is_symbol_before column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneByNotes(string $notes) Return the first ChildCurrency filtered by the notes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrency requireOneBySortableRank(int $sortable_rank) Return the first ChildCurrency filtered by the sortable_rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCurrency[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCurrency objects based on current ModelCriteria
 * @method     ChildCurrency[]|ObjectCollection findById(int $id) Return ChildCurrency objects filtered by the id column
 * @method     ChildCurrency[]|ObjectCollection findByName(string $name) Return ChildCurrency objects filtered by the name column
 * @method     ChildCurrency[]|ObjectCollection findByISOCode(string $iso_code) Return ChildCurrency objects filtered by the iso_code column
 * @method     ChildCurrency[]|ObjectCollection findBySymbol(string $symbol) Return ChildCurrency objects filtered by the symbol column
 * @method     ChildCurrency[]|ObjectCollection findByIsSymbolBefore(boolean $is_symbol_before) Return ChildCurrency objects filtered by the is_symbol_before column
 * @method     ChildCurrency[]|ObjectCollection findByNotes(string $notes) Return ChildCurrency objects filtered by the notes column
 * @method     ChildCurrency[]|ObjectCollection findBySortableRank(int $sortable_rank) Return ChildCurrency objects filtered by the sortable_rank column
 * @method     ChildCurrency[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CurrencyQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\CurrencyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Currency', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCurrencyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCurrencyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCurrencyQuery) {
            return $criteria;
        }
        $query = new ChildCurrencyQuery();
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
     * @return ChildCurrency|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CurrencyTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCurrency A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, iso_code, symbol, is_symbol_before, notes, sortable_rank FROM currency WHERE id = :p0';
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
            /** @var ChildCurrency $obj */
            $obj = new ChildCurrency();
            $obj->hydrate($row);
            CurrencyTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCurrency|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CurrencyTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CurrencyTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the iso_code column
     *
     * Example usage:
     * <code>
     * $query->filterByISOCode('fooValue');   // WHERE iso_code = 'fooValue'
     * $query->filterByISOCode('%fooValue%', Criteria::LIKE); // WHERE iso_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $iSOCode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByISOCode($iSOCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($iSOCode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_ISO_CODE, $iSOCode, $comparison);
    }

    /**
     * Filter the query on the symbol column
     *
     * Example usage:
     * <code>
     * $query->filterBySymbol('fooValue');   // WHERE symbol = 'fooValue'
     * $query->filterBySymbol('%fooValue%', Criteria::LIKE); // WHERE symbol LIKE '%fooValue%'
     * </code>
     *
     * @param     string $symbol The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterBySymbol($symbol = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($symbol)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_SYMBOL, $symbol, $comparison);
    }

    /**
     * Filter the query on the is_symbol_before column
     *
     * Example usage:
     * <code>
     * $query->filterByIsSymbolBefore(true); // WHERE is_symbol_before = true
     * $query->filterByIsSymbolBefore('yes'); // WHERE is_symbol_before = true
     * </code>
     *
     * @param     boolean|string $isSymbolBefore The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByIsSymbolBefore($isSymbolBefore = null, $comparison = null)
    {
        if (is_string($isSymbolBefore)) {
            $isSymbolBefore = in_array(strtolower($isSymbolBefore), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_IS_SYMBOL_BEFORE, $isSymbolBefore, $comparison);
    }

    /**
     * Filter the query on the notes column
     *
     * Example usage:
     * <code>
     * $query->filterByNotes('fooValue');   // WHERE notes = 'fooValue'
     * $query->filterByNotes('%fooValue%', Criteria::LIKE); // WHERE notes LIKE '%fooValue%'
     * </code>
     *
     * @param     string $notes The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByNotes($notes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_NOTES, $notes, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank > 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(CurrencyTableMap::COL_SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyTableMap::COL_SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User|ObjectCollection $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByCurrentCurrencyUser($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(CurrencyTableMap::COL_ID, $user->getCurrentCurrencyId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useCurrentCurrencyUserQuery()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentCurrencyUser() only accepts arguments of type \Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCurrencyUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function joinCurrentCurrencyUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCurrencyUser');

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
            $this->addJoinObject($join, 'CurrentCurrencyUser');
        }

        return $this;
    }

    /**
     * Use the CurrentCurrencyUser relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCurrencyUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentCurrencyUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCurrencyUser', '\Models\UserQuery');
    }

    /**
     * Filter the query by a related \Models\CourseStream object
     *
     * @param \Models\CourseStream|ObjectCollection $courseStream the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByCurrentCurrencyCourseStream($courseStream, $comparison = null)
    {
        if ($courseStream instanceof \Models\CourseStream) {
            return $this
                ->addUsingAlias(CurrencyTableMap::COL_ID, $courseStream->getCurrentCurrencyId(), $comparison);
        } elseif ($courseStream instanceof ObjectCollection) {
            return $this
                ->useCurrentCurrencyCourseStreamQuery()
                ->filterByPrimaryKeys($courseStream->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentCurrencyCourseStream() only accepts arguments of type \Models\CourseStream or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCurrencyCourseStream relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function joinCurrentCurrencyCourseStream($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCurrencyCourseStream');

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
            $this->addJoinObject($join, 'CurrentCurrencyCourseStream');
        }

        return $this;
    }

    /**
     * Use the CurrentCurrencyCourseStream relation CourseStream object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseStreamQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCurrencyCourseStreamQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentCurrencyCourseStream($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCurrencyCourseStream', '\Models\CourseStreamQuery');
    }

    /**
     * Filter the query by a related \Models\CurrencyRate object
     *
     * @param \Models\CurrencyRate|ObjectCollection $currencyRate the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByCurrentDefaultCurrencyRate($currencyRate, $comparison = null)
    {
        if ($currencyRate instanceof \Models\CurrencyRate) {
            return $this
                ->addUsingAlias(CurrencyTableMap::COL_ID, $currencyRate->getCurrentDefaultCurrencyId(), $comparison);
        } elseif ($currencyRate instanceof ObjectCollection) {
            return $this
                ->useCurrentDefaultCurrencyRateQuery()
                ->filterByPrimaryKeys($currencyRate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentDefaultCurrencyRate() only accepts arguments of type \Models\CurrencyRate or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentDefaultCurrencyRate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function joinCurrentDefaultCurrencyRate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentDefaultCurrencyRate');

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
            $this->addJoinObject($join, 'CurrentDefaultCurrencyRate');
        }

        return $this;
    }

    /**
     * Use the CurrentDefaultCurrencyRate relation CurrencyRate object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CurrencyRateQuery A secondary query class using the current class as primary query
     */
    public function useCurrentDefaultCurrencyRateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentDefaultCurrencyRate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentDefaultCurrencyRate', '\Models\CurrencyRateQuery');
    }

    /**
     * Filter the query by a related \Models\CurrencyRate object
     *
     * @param \Models\CurrencyRate|ObjectCollection $currencyRate the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByCurrentToCurrencyRate($currencyRate, $comparison = null)
    {
        if ($currencyRate instanceof \Models\CurrencyRate) {
            return $this
                ->addUsingAlias(CurrencyTableMap::COL_ID, $currencyRate->getCurrentToCurrencyId(), $comparison);
        } elseif ($currencyRate instanceof ObjectCollection) {
            return $this
                ->useCurrentToCurrencyRateQuery()
                ->filterByPrimaryKeys($currencyRate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentToCurrencyRate() only accepts arguments of type \Models\CurrencyRate or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentToCurrencyRate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function joinCurrentToCurrencyRate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentToCurrencyRate');

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
            $this->addJoinObject($join, 'CurrentToCurrencyRate');
        }

        return $this;
    }

    /**
     * Use the CurrentToCurrencyRate relation CurrencyRate object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CurrencyRateQuery A secondary query class using the current class as primary query
     */
    public function useCurrentToCurrencyRateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentToCurrencyRate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentToCurrencyRate', '\Models\CurrencyRateQuery');
    }

    /**
     * Filter the query by a related \Models\Feedback object
     *
     * @param \Models\Feedback|ObjectCollection $feedback the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByCurrentCurrencyFeedback($feedback, $comparison = null)
    {
        if ($feedback instanceof \Models\Feedback) {
            return $this
                ->addUsingAlias(CurrencyTableMap::COL_ID, $feedback->getCurrentCurrencyId(), $comparison);
        } elseif ($feedback instanceof ObjectCollection) {
            return $this
                ->useCurrentCurrencyFeedbackQuery()
                ->filterByPrimaryKeys($feedback->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentCurrencyFeedback() only accepts arguments of type \Models\Feedback or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCurrencyFeedback relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function joinCurrentCurrencyFeedback($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCurrencyFeedback');

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
            $this->addJoinObject($join, 'CurrentCurrencyFeedback');
        }

        return $this;
    }

    /**
     * Use the CurrentCurrencyFeedback relation Feedback object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\FeedbackQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCurrencyFeedbackQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentCurrencyFeedback($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCurrencyFeedback', '\Models\FeedbackQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCurrency $currency Object to remove from the list of results
     *
     * @return $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function prune($currency = null)
    {
        if ($currency) {
            $this->addUsingAlias(CurrencyTableMap::COL_ID, $currency->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the currency table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CurrencyTableMap::clearInstancePool();
            CurrencyTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CurrencyTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CurrencyTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CurrencyTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // sortable behavior

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    ChildCurrencyQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {

        return $this
            ->addUsingAlias(CurrencyTableMap::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    $this|ChildCurrencyQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(CurrencyTableMap::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(CurrencyTableMap::RANK_COL));
                break;
            default:
                throw new \Propel\Runtime\Exception\PropelException('ChildCurrencyQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return    ChildCurrency
     */
    public function findOneByRank($rank, ConnectionInterface $con = null)
    {

        return $this
            ->filterByRank($rank)
            ->findOne($con);
    }

    /**
     * Returns the list of objects
     *
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($con = null)
    {

        return $this
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param     ConnectionInterface optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CurrencyTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get the highest rank by a scope with a array format.
     *
     * @param     ConnectionInterface optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray(ConnectionInterface $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CurrencyTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CurrencyTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return ChildCurrency
     */
    static public function retrieveByRank($rank, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyTableMap::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(CurrencyTableMap::RANK_COL, $rank);

        return static::create(null, $c)->findOne($con);
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     mixed               $order id => rank pairs
     * @param     ConnectionInterface $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder($order, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con, $order) {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
        });

        return true;
    }

    /**
     * Return an array of sortable objects ordered by position
     *
     * @param     Criteria  $criteria  optional criteria object
     * @param     string    $order     sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     ConnectionInterface $con       optional connection
     *
     * @return    array list of sortable objects
     */
    static public function doSelectOrderByRank(Criteria $criteria = null, $order = Criteria::ASC, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyTableMap::DATABASE_NAME);
        }

        if (null === $criteria) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if (Criteria::ASC == $order) {
            $criteria->addAscendingOrderByColumn(CurrencyTableMap::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(CurrencyTableMap::RANK_COL);
        }

        return ChildCurrencyQuery::create(null, $criteria)->find($con);
    }

    /**
     * Adds $delta to all Rank values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta Value to be shifted by, can be negative
     * @param      int $first First node to be shifted
     * @param      int $last  Last node to be shifted
     * @param      ConnectionInterface $con Connection to use.
     */
    static public function sortableShiftRank($delta, $first, $last = null, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyTableMap::DATABASE_NAME);
        }

        $whereCriteria = new Criteria(CurrencyTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(CurrencyTableMap::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(CurrencyTableMap::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(CurrencyTableMap::DATABASE_NAME);
        $valuesCriteria->add(CurrencyTableMap::RANK_COL, array('raw' => CurrencyTableMap::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
        CurrencyTableMap::clearInstancePool();
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CurrencyTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(CurrencyTableMap::DATABASE_NAME);

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

} // CurrencyQuery
