<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\CurrencyRate as ChildCurrencyRate;
use Models\CurrencyRateQuery as ChildCurrencyRateQuery;
use Models\Map\CurrencyRateTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'currency_rate' table.
 *
 *
 *
 * @method     ChildCurrencyRateQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCurrencyRateQuery orderByRate($order = Criteria::ASC) Order by the rate column
 * @method     ChildCurrencyRateQuery orderByCurrentDefaultCurrencyId($order = Criteria::ASC) Order by the default_currency_id column
 * @method     ChildCurrencyRateQuery orderByCurrentToCurrencyId($order = Criteria::ASC) Order by the to_currency_id column
 * @method     ChildCurrencyRateQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildCurrencyRateQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildCurrencyRateQuery groupById() Group by the id column
 * @method     ChildCurrencyRateQuery groupByRate() Group by the rate column
 * @method     ChildCurrencyRateQuery groupByCurrentDefaultCurrencyId() Group by the default_currency_id column
 * @method     ChildCurrencyRateQuery groupByCurrentToCurrencyId() Group by the to_currency_id column
 * @method     ChildCurrencyRateQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildCurrencyRateQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildCurrencyRateQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCurrencyRateQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCurrencyRateQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCurrencyRateQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCurrencyRateQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCurrencyRateQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCurrencyRateQuery leftJoinCurrentDefaultCurrency($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentDefaultCurrency relation
 * @method     ChildCurrencyRateQuery rightJoinCurrentDefaultCurrency($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentDefaultCurrency relation
 * @method     ChildCurrencyRateQuery innerJoinCurrentDefaultCurrency($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentDefaultCurrency relation
 *
 * @method     ChildCurrencyRateQuery joinWithCurrentDefaultCurrency($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentDefaultCurrency relation
 *
 * @method     ChildCurrencyRateQuery leftJoinWithCurrentDefaultCurrency() Adds a LEFT JOIN clause and with to the query using the CurrentDefaultCurrency relation
 * @method     ChildCurrencyRateQuery rightJoinWithCurrentDefaultCurrency() Adds a RIGHT JOIN clause and with to the query using the CurrentDefaultCurrency relation
 * @method     ChildCurrencyRateQuery innerJoinWithCurrentDefaultCurrency() Adds a INNER JOIN clause and with to the query using the CurrentDefaultCurrency relation
 *
 * @method     ChildCurrencyRateQuery leftJoinCurrentToCurrency($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentToCurrency relation
 * @method     ChildCurrencyRateQuery rightJoinCurrentToCurrency($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentToCurrency relation
 * @method     ChildCurrencyRateQuery innerJoinCurrentToCurrency($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentToCurrency relation
 *
 * @method     ChildCurrencyRateQuery joinWithCurrentToCurrency($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentToCurrency relation
 *
 * @method     ChildCurrencyRateQuery leftJoinWithCurrentToCurrency() Adds a LEFT JOIN clause and with to the query using the CurrentToCurrency relation
 * @method     ChildCurrencyRateQuery rightJoinWithCurrentToCurrency() Adds a RIGHT JOIN clause and with to the query using the CurrentToCurrency relation
 * @method     ChildCurrencyRateQuery innerJoinWithCurrentToCurrency() Adds a INNER JOIN clause and with to the query using the CurrentToCurrency relation
 *
 * @method     \Models\CurrencyQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCurrencyRate findOne(ConnectionInterface $con = null) Return the first ChildCurrencyRate matching the query
 * @method     ChildCurrencyRate findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCurrencyRate matching the query, or a new ChildCurrencyRate object populated from the query conditions when no match is found
 *
 * @method     ChildCurrencyRate findOneById(int $id) Return the first ChildCurrencyRate filtered by the id column
 * @method     ChildCurrencyRate findOneByRate(double $rate) Return the first ChildCurrencyRate filtered by the rate column
 * @method     ChildCurrencyRate findOneByCurrentDefaultCurrencyId(int $default_currency_id) Return the first ChildCurrencyRate filtered by the default_currency_id column
 * @method     ChildCurrencyRate findOneByCurrentToCurrencyId(int $to_currency_id) Return the first ChildCurrencyRate filtered by the to_currency_id column
 * @method     ChildCurrencyRate findOneByCreatedAt(string $created_at) Return the first ChildCurrencyRate filtered by the created_at column
 * @method     ChildCurrencyRate findOneByUpdatedAt(string $updated_at) Return the first ChildCurrencyRate filtered by the updated_at column *

 * @method     ChildCurrencyRate requirePk($key, ConnectionInterface $con = null) Return the ChildCurrencyRate by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrencyRate requireOne(ConnectionInterface $con = null) Return the first ChildCurrencyRate matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCurrencyRate requireOneById(int $id) Return the first ChildCurrencyRate filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrencyRate requireOneByRate(double $rate) Return the first ChildCurrencyRate filtered by the rate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrencyRate requireOneByCurrentDefaultCurrencyId(int $default_currency_id) Return the first ChildCurrencyRate filtered by the default_currency_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrencyRate requireOneByCurrentToCurrencyId(int $to_currency_id) Return the first ChildCurrencyRate filtered by the to_currency_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrencyRate requireOneByCreatedAt(string $created_at) Return the first ChildCurrencyRate filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCurrencyRate requireOneByUpdatedAt(string $updated_at) Return the first ChildCurrencyRate filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCurrencyRate[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCurrencyRate objects based on current ModelCriteria
 * @method     ChildCurrencyRate[]|ObjectCollection findById(int $id) Return ChildCurrencyRate objects filtered by the id column
 * @method     ChildCurrencyRate[]|ObjectCollection findByRate(double $rate) Return ChildCurrencyRate objects filtered by the rate column
 * @method     ChildCurrencyRate[]|ObjectCollection findByCurrentDefaultCurrencyId(int $default_currency_id) Return ChildCurrencyRate objects filtered by the default_currency_id column
 * @method     ChildCurrencyRate[]|ObjectCollection findByCurrentToCurrencyId(int $to_currency_id) Return ChildCurrencyRate objects filtered by the to_currency_id column
 * @method     ChildCurrencyRate[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildCurrencyRate objects filtered by the created_at column
 * @method     ChildCurrencyRate[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildCurrencyRate objects filtered by the updated_at column
 * @method     ChildCurrencyRate[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CurrencyRateQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\CurrencyRateQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\CurrencyRate', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCurrencyRateQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCurrencyRateQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCurrencyRateQuery) {
            return $criteria;
        }
        $query = new ChildCurrencyRateQuery();
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
     * @return ChildCurrencyRate|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CurrencyRateTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CurrencyRateTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCurrencyRate A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `rate`, `default_currency_id`, `to_currency_id`, `created_at`, `updated_at` FROM `currency_rate` WHERE `id` = :p0';
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
            /** @var ChildCurrencyRate $obj */
            $obj = new ChildCurrencyRate();
            $obj->hydrate($row);
            CurrencyRateTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCurrencyRate|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CurrencyRateTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CurrencyRateTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyRateTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the rate column
     *
     * Example usage:
     * <code>
     * $query->filterByRate(1234); // WHERE rate = 1234
     * $query->filterByRate(array(12, 34)); // WHERE rate IN (12, 34)
     * $query->filterByRate(array('min' => 12)); // WHERE rate > 12
     * </code>
     *
     * @param     mixed $rate The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByRate($rate = null, $comparison = null)
    {
        if (is_array($rate)) {
            $useMinMax = false;
            if (isset($rate['min'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_RATE, $rate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rate['max'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_RATE, $rate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyRateTableMap::COL_RATE, $rate, $comparison);
    }

    /**
     * Filter the query on the default_currency_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentDefaultCurrencyId(1234); // WHERE default_currency_id = 1234
     * $query->filterByCurrentDefaultCurrencyId(array(12, 34)); // WHERE default_currency_id IN (12, 34)
     * $query->filterByCurrentDefaultCurrencyId(array('min' => 12)); // WHERE default_currency_id > 12
     * </code>
     *
     * @see       filterByCurrentDefaultCurrency()
     *
     * @param     mixed $currentDefaultCurrencyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByCurrentDefaultCurrencyId($currentDefaultCurrencyId = null, $comparison = null)
    {
        if (is_array($currentDefaultCurrencyId)) {
            $useMinMax = false;
            if (isset($currentDefaultCurrencyId['min'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_DEFAULT_CURRENCY_ID, $currentDefaultCurrencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentDefaultCurrencyId['max'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_DEFAULT_CURRENCY_ID, $currentDefaultCurrencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyRateTableMap::COL_DEFAULT_CURRENCY_ID, $currentDefaultCurrencyId, $comparison);
    }

    /**
     * Filter the query on the to_currency_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentToCurrencyId(1234); // WHERE to_currency_id = 1234
     * $query->filterByCurrentToCurrencyId(array(12, 34)); // WHERE to_currency_id IN (12, 34)
     * $query->filterByCurrentToCurrencyId(array('min' => 12)); // WHERE to_currency_id > 12
     * </code>
     *
     * @see       filterByCurrentToCurrency()
     *
     * @param     mixed $currentToCurrencyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByCurrentToCurrencyId($currentToCurrencyId = null, $comparison = null)
    {
        if (is_array($currentToCurrencyId)) {
            $useMinMax = false;
            if (isset($currentToCurrencyId['min'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_TO_CURRENCY_ID, $currentToCurrencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentToCurrencyId['max'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_TO_CURRENCY_ID, $currentToCurrencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyRateTableMap::COL_TO_CURRENCY_ID, $currentToCurrencyId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyRateTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CurrencyRateTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CurrencyRateTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Currency object
     *
     * @param \Models\Currency|ObjectCollection $currency The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByCurrentDefaultCurrency($currency, $comparison = null)
    {
        if ($currency instanceof \Models\Currency) {
            return $this
                ->addUsingAlias(CurrencyRateTableMap::COL_DEFAULT_CURRENCY_ID, $currency->getId(), $comparison);
        } elseif ($currency instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CurrencyRateTableMap::COL_DEFAULT_CURRENCY_ID, $currency->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentDefaultCurrency() only accepts arguments of type \Models\Currency or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentDefaultCurrency relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function joinCurrentDefaultCurrency($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentDefaultCurrency');

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
            $this->addJoinObject($join, 'CurrentDefaultCurrency');
        }

        return $this;
    }

    /**
     * Use the CurrentDefaultCurrency relation Currency object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CurrencyQuery A secondary query class using the current class as primary query
     */
    public function useCurrentDefaultCurrencyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentDefaultCurrency($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentDefaultCurrency', '\Models\CurrencyQuery');
    }

    /**
     * Filter the query by a related \Models\Currency object
     *
     * @param \Models\Currency|ObjectCollection $currency The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function filterByCurrentToCurrency($currency, $comparison = null)
    {
        if ($currency instanceof \Models\Currency) {
            return $this
                ->addUsingAlias(CurrencyRateTableMap::COL_TO_CURRENCY_ID, $currency->getId(), $comparison);
        } elseif ($currency instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CurrencyRateTableMap::COL_TO_CURRENCY_ID, $currency->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentToCurrency() only accepts arguments of type \Models\Currency or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentToCurrency relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function joinCurrentToCurrency($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentToCurrency');

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
            $this->addJoinObject($join, 'CurrentToCurrency');
        }

        return $this;
    }

    /**
     * Use the CurrentToCurrency relation Currency object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CurrencyQuery A secondary query class using the current class as primary query
     */
    public function useCurrentToCurrencyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentToCurrency($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentToCurrency', '\Models\CurrencyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCurrencyRate $currencyRate Object to remove from the list of results
     *
     * @return $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function prune($currencyRate = null)
    {
        if ($currencyRate) {
            $this->addUsingAlias(CurrencyRateTableMap::COL_ID, $currencyRate->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the currency_rate table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyRateTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CurrencyRateTableMap::clearInstancePool();
            CurrencyRateTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CurrencyRateTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CurrencyRateTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CurrencyRateTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CurrencyRateTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CurrencyRateTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CurrencyRateTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CurrencyRateTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CurrencyRateTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CurrencyRateTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildCurrencyRateQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CurrencyRateTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CurrencyRateTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(CurrencyRateTableMap::DATABASE_NAME);

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

} // CurrencyRateQuery
