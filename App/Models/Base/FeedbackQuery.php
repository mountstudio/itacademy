<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Feedback as ChildFeedback;
use Models\FeedbackQuery as ChildFeedbackQuery;
use Models\Map\FeedbackTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'feedback' table.
 *
 *
 *
 * @method     ChildFeedbackQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildFeedbackQuery orderByWorkPlace($order = Criteria::ASC) Order by the work_place column
 * @method     ChildFeedbackQuery orderBySalary($order = Criteria::ASC) Order by the salary column
 * @method     ChildFeedbackQuery orderByCurrentCurrencyId($order = Criteria::ASC) Order by the currency_id column
 * @method     ChildFeedbackQuery orderByCurrentUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildFeedbackQuery orderByAvailable($order = Criteria::ASC) Order by the is_available column
 * @method     ChildFeedbackQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     ChildFeedbackQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method     ChildFeedbackQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildFeedbackQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildFeedbackQuery groupById() Group by the id column
 * @method     ChildFeedbackQuery groupByWorkPlace() Group by the work_place column
 * @method     ChildFeedbackQuery groupBySalary() Group by the salary column
 * @method     ChildFeedbackQuery groupByCurrentCurrencyId() Group by the currency_id column
 * @method     ChildFeedbackQuery groupByCurrentUserId() Group by the user_id column
 * @method     ChildFeedbackQuery groupByAvailable() Group by the is_available column
 * @method     ChildFeedbackQuery groupByContent() Group by the content column
 * @method     ChildFeedbackQuery groupByNotes() Group by the notes column
 * @method     ChildFeedbackQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildFeedbackQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildFeedbackQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFeedbackQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFeedbackQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFeedbackQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildFeedbackQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildFeedbackQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildFeedbackQuery leftJoinCurrentFeedbackCurrency($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentFeedbackCurrency relation
 * @method     ChildFeedbackQuery rightJoinCurrentFeedbackCurrency($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentFeedbackCurrency relation
 * @method     ChildFeedbackQuery innerJoinCurrentFeedbackCurrency($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentFeedbackCurrency relation
 *
 * @method     ChildFeedbackQuery joinWithCurrentFeedbackCurrency($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentFeedbackCurrency relation
 *
 * @method     ChildFeedbackQuery leftJoinWithCurrentFeedbackCurrency() Adds a LEFT JOIN clause and with to the query using the CurrentFeedbackCurrency relation
 * @method     ChildFeedbackQuery rightJoinWithCurrentFeedbackCurrency() Adds a RIGHT JOIN clause and with to the query using the CurrentFeedbackCurrency relation
 * @method     ChildFeedbackQuery innerJoinWithCurrentFeedbackCurrency() Adds a INNER JOIN clause and with to the query using the CurrentFeedbackCurrency relation
 *
 * @method     ChildFeedbackQuery leftJoinCurrentFeedbackUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentFeedbackUser relation
 * @method     ChildFeedbackQuery rightJoinCurrentFeedbackUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentFeedbackUser relation
 * @method     ChildFeedbackQuery innerJoinCurrentFeedbackUser($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentFeedbackUser relation
 *
 * @method     ChildFeedbackQuery joinWithCurrentFeedbackUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentFeedbackUser relation
 *
 * @method     ChildFeedbackQuery leftJoinWithCurrentFeedbackUser() Adds a LEFT JOIN clause and with to the query using the CurrentFeedbackUser relation
 * @method     ChildFeedbackQuery rightJoinWithCurrentFeedbackUser() Adds a RIGHT JOIN clause and with to the query using the CurrentFeedbackUser relation
 * @method     ChildFeedbackQuery innerJoinWithCurrentFeedbackUser() Adds a INNER JOIN clause and with to the query using the CurrentFeedbackUser relation
 *
 * @method     \Models\CurrencyQuery|\Models\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFeedback findOne(ConnectionInterface $con = null) Return the first ChildFeedback matching the query
 * @method     ChildFeedback findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFeedback matching the query, or a new ChildFeedback object populated from the query conditions when no match is found
 *
 * @method     ChildFeedback findOneById(int $id) Return the first ChildFeedback filtered by the id column
 * @method     ChildFeedback findOneByWorkPlace(string $work_place) Return the first ChildFeedback filtered by the work_place column
 * @method     ChildFeedback findOneBySalary(float $salary) Return the first ChildFeedback filtered by the salary column
 * @method     ChildFeedback findOneByCurrentCurrencyId(int $currency_id) Return the first ChildFeedback filtered by the currency_id column
 * @method     ChildFeedback findOneByCurrentUserId(int $user_id) Return the first ChildFeedback filtered by the user_id column
 * @method     ChildFeedback findOneByAvailable(boolean $is_available) Return the first ChildFeedback filtered by the is_available column
 * @method     ChildFeedback findOneByContent(string $content) Return the first ChildFeedback filtered by the content column
 * @method     ChildFeedback findOneByNotes(string $notes) Return the first ChildFeedback filtered by the notes column
 * @method     ChildFeedback findOneByCreatedAt(string $created_at) Return the first ChildFeedback filtered by the created_at column
 * @method     ChildFeedback findOneByUpdatedAt(string $updated_at) Return the first ChildFeedback filtered by the updated_at column *

 * @method     ChildFeedback requirePk($key, ConnectionInterface $con = null) Return the ChildFeedback by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOne(ConnectionInterface $con = null) Return the first ChildFeedback matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFeedback requireOneById(int $id) Return the first ChildFeedback filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneByWorkPlace(string $work_place) Return the first ChildFeedback filtered by the work_place column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneBySalary(float $salary) Return the first ChildFeedback filtered by the salary column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneByCurrentCurrencyId(int $currency_id) Return the first ChildFeedback filtered by the currency_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneByCurrentUserId(int $user_id) Return the first ChildFeedback filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneByAvailable(boolean $is_available) Return the first ChildFeedback filtered by the is_available column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneByContent(string $content) Return the first ChildFeedback filtered by the content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneByNotes(string $notes) Return the first ChildFeedback filtered by the notes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneByCreatedAt(string $created_at) Return the first ChildFeedback filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedback requireOneByUpdatedAt(string $updated_at) Return the first ChildFeedback filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFeedback[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFeedback objects based on current ModelCriteria
 * @method     ChildFeedback[]|ObjectCollection findById(int $id) Return ChildFeedback objects filtered by the id column
 * @method     ChildFeedback[]|ObjectCollection findByWorkPlace(string $work_place) Return ChildFeedback objects filtered by the work_place column
 * @method     ChildFeedback[]|ObjectCollection findBySalary(float $salary) Return ChildFeedback objects filtered by the salary column
 * @method     ChildFeedback[]|ObjectCollection findByCurrentCurrencyId(int $currency_id) Return ChildFeedback objects filtered by the currency_id column
 * @method     ChildFeedback[]|ObjectCollection findByCurrentUserId(int $user_id) Return ChildFeedback objects filtered by the user_id column
 * @method     ChildFeedback[]|ObjectCollection findByAvailable(boolean $is_available) Return ChildFeedback objects filtered by the is_available column
 * @method     ChildFeedback[]|ObjectCollection findByContent(string $content) Return ChildFeedback objects filtered by the content column
 * @method     ChildFeedback[]|ObjectCollection findByNotes(string $notes) Return ChildFeedback objects filtered by the notes column
 * @method     ChildFeedback[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildFeedback objects filtered by the created_at column
 * @method     ChildFeedback[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildFeedback objects filtered by the updated_at column
 * @method     ChildFeedback[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FeedbackQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\FeedbackQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Feedback', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFeedbackQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFeedbackQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFeedbackQuery) {
            return $criteria;
        }
        $query = new ChildFeedbackQuery();
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
     * @return ChildFeedback|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FeedbackTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = FeedbackTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildFeedback A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `work_place`, `salary`, `currency_id`, `user_id`, `is_available`, `content`, `notes`, `created_at`, `updated_at` FROM `feedback` WHERE `id` = :p0';
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
            /** @var ChildFeedback $obj */
            $obj = new ChildFeedback();
            $obj->hydrate($row);
            FeedbackTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildFeedback|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FeedbackTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FeedbackTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the work_place column
     *
     * Example usage:
     * <code>
     * $query->filterByWorkPlace('fooValue');   // WHERE work_place = 'fooValue'
     * $query->filterByWorkPlace('%fooValue%', Criteria::LIKE); // WHERE work_place LIKE '%fooValue%'
     * </code>
     *
     * @param     string $workPlace The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByWorkPlace($workPlace = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($workPlace)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_WORK_PLACE, $workPlace, $comparison);
    }

    /**
     * Filter the query on the salary column
     *
     * Example usage:
     * <code>
     * $query->filterBySalary(1234); // WHERE salary = 1234
     * $query->filterBySalary(array(12, 34)); // WHERE salary IN (12, 34)
     * $query->filterBySalary(array('min' => 12)); // WHERE salary > 12
     * </code>
     *
     * @param     mixed $salary The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterBySalary($salary = null, $comparison = null)
    {
        if (is_array($salary)) {
            $useMinMax = false;
            if (isset($salary['min'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_SALARY, $salary['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($salary['max'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_SALARY, $salary['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_SALARY, $salary, $comparison);
    }

    /**
     * Filter the query on the currency_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentCurrencyId(1234); // WHERE currency_id = 1234
     * $query->filterByCurrentCurrencyId(array(12, 34)); // WHERE currency_id IN (12, 34)
     * $query->filterByCurrentCurrencyId(array('min' => 12)); // WHERE currency_id > 12
     * </code>
     *
     * @see       filterByCurrentFeedbackCurrency()
     *
     * @param     mixed $currentCurrencyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByCurrentCurrencyId($currentCurrencyId = null, $comparison = null)
    {
        if (is_array($currentCurrencyId)) {
            $useMinMax = false;
            if (isset($currentCurrencyId['min'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_CURRENCY_ID, $currentCurrencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentCurrencyId['max'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_CURRENCY_ID, $currentCurrencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_CURRENCY_ID, $currentCurrencyId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentUserId(1234); // WHERE user_id = 1234
     * $query->filterByCurrentUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByCurrentUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByCurrentFeedbackUser()
     *
     * @param     mixed $currentUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByCurrentUserId($currentUserId = null, $comparison = null)
    {
        if (is_array($currentUserId)) {
            $useMinMax = false;
            if (isset($currentUserId['min'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_USER_ID, $currentUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentUserId['max'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_USER_ID, $currentUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_USER_ID, $currentUserId, $comparison);
    }

    /**
     * Filter the query on the is_available column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailable(true); // WHERE is_available = true
     * $query->filterByAvailable('yes'); // WHERE is_available = true
     * </code>
     *
     * @param     boolean|string $available The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByAvailable($available = null, $comparison = null)
    {
        if (is_string($available)) {
            $available = in_array(strtolower($available), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_IS_AVAILABLE, $available, $comparison);
    }

    /**
     * Filter the query on the content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE content = 'fooValue'
     * $query->filterByContent('%fooValue%', Criteria::LIKE); // WHERE content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_CONTENT, $content, $comparison);
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
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByNotes($notes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_NOTES, $notes, $comparison);
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
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FeedbackTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedbackTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Currency object
     *
     * @param \Models\Currency|ObjectCollection $currency The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByCurrentFeedbackCurrency($currency, $comparison = null)
    {
        if ($currency instanceof \Models\Currency) {
            return $this
                ->addUsingAlias(FeedbackTableMap::COL_CURRENCY_ID, $currency->getId(), $comparison);
        } elseif ($currency instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FeedbackTableMap::COL_CURRENCY_ID, $currency->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentFeedbackCurrency() only accepts arguments of type \Models\Currency or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentFeedbackCurrency relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function joinCurrentFeedbackCurrency($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentFeedbackCurrency');

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
            $this->addJoinObject($join, 'CurrentFeedbackCurrency');
        }

        return $this;
    }

    /**
     * Use the CurrentFeedbackCurrency relation Currency object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CurrencyQuery A secondary query class using the current class as primary query
     */
    public function useCurrentFeedbackCurrencyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentFeedbackCurrency($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentFeedbackCurrency', '\Models\CurrencyQuery');
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFeedbackQuery The current query, for fluid interface
     */
    public function filterByCurrentFeedbackUser($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(FeedbackTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FeedbackTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentFeedbackUser() only accepts arguments of type \Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentFeedbackUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function joinCurrentFeedbackUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentFeedbackUser');

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
            $this->addJoinObject($join, 'CurrentFeedbackUser');
        }

        return $this;
    }

    /**
     * Use the CurrentFeedbackUser relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useCurrentFeedbackUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentFeedbackUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentFeedbackUser', '\Models\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFeedback $feedback Object to remove from the list of results
     *
     * @return $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function prune($feedback = null)
    {
        if ($feedback) {
            $this->addUsingAlias(FeedbackTableMap::COL_ID, $feedback->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the feedback table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FeedbackTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FeedbackTableMap::clearInstancePool();
            FeedbackTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(FeedbackTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FeedbackTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FeedbackTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FeedbackTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(FeedbackTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(FeedbackTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(FeedbackTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FeedbackTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FeedbackTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildFeedbackQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FeedbackTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(FeedbackTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(FeedbackTableMap::DATABASE_NAME);

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

} // FeedbackQuery
