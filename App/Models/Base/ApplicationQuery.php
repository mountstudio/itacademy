<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Application as ChildApplication;
use Models\ApplicationQuery as ChildApplicationQuery;
use Models\Map\ApplicationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'application' table.
 *
 *
 *
 * @method     ChildApplicationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildApplicationQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildApplicationQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method     ChildApplicationQuery orderByCurrentApplicationStatusId($order = Criteria::ASC) Order by the application_status_id column
 * @method     ChildApplicationQuery orderByCurrentCourseId($order = Criteria::ASC) Order by the course_id column
 * @method     ChildApplicationQuery orderByCurrentCourseStreamId($order = Criteria::ASC) Order by the course_stream_id column
 * @method     ChildApplicationQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildApplicationQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method     ChildApplicationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildApplicationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildApplicationQuery groupById() Group by the id column
 * @method     ChildApplicationQuery groupByName() Group by the name column
 * @method     ChildApplicationQuery groupByPhone() Group by the phone column
 * @method     ChildApplicationQuery groupByCurrentApplicationStatusId() Group by the application_status_id column
 * @method     ChildApplicationQuery groupByCurrentCourseId() Group by the course_id column
 * @method     ChildApplicationQuery groupByCurrentCourseStreamId() Group by the course_stream_id column
 * @method     ChildApplicationQuery groupByDescription() Group by the description column
 * @method     ChildApplicationQuery groupByNotes() Group by the notes column
 * @method     ChildApplicationQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildApplicationQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildApplicationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildApplicationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildApplicationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildApplicationQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildApplicationQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildApplicationQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildApplicationQuery leftJoinCurrentApplicationStatus($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentApplicationStatus relation
 * @method     ChildApplicationQuery rightJoinCurrentApplicationStatus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentApplicationStatus relation
 * @method     ChildApplicationQuery innerJoinCurrentApplicationStatus($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentApplicationStatus relation
 *
 * @method     ChildApplicationQuery joinWithCurrentApplicationStatus($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentApplicationStatus relation
 *
 * @method     ChildApplicationQuery leftJoinWithCurrentApplicationStatus() Adds a LEFT JOIN clause and with to the query using the CurrentApplicationStatus relation
 * @method     ChildApplicationQuery rightJoinWithCurrentApplicationStatus() Adds a RIGHT JOIN clause and with to the query using the CurrentApplicationStatus relation
 * @method     ChildApplicationQuery innerJoinWithCurrentApplicationStatus() Adds a INNER JOIN clause and with to the query using the CurrentApplicationStatus relation
 *
 * @method     ChildApplicationQuery leftJoinCurrentCourseApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseApplication relation
 * @method     ChildApplicationQuery rightJoinCurrentCourseApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseApplication relation
 * @method     ChildApplicationQuery innerJoinCurrentCourseApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseApplication relation
 *
 * @method     ChildApplicationQuery joinWithCurrentCourseApplication($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseApplication relation
 *
 * @method     ChildApplicationQuery leftJoinWithCurrentCourseApplication() Adds a LEFT JOIN clause and with to the query using the CurrentCourseApplication relation
 * @method     ChildApplicationQuery rightJoinWithCurrentCourseApplication() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseApplication relation
 * @method     ChildApplicationQuery innerJoinWithCurrentCourseApplication() Adds a INNER JOIN clause and with to the query using the CurrentCourseApplication relation
 *
 * @method     ChildApplicationQuery leftJoinCurrentCourseStreamApplication($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseStreamApplication relation
 * @method     ChildApplicationQuery rightJoinCurrentCourseStreamApplication($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseStreamApplication relation
 * @method     ChildApplicationQuery innerJoinCurrentCourseStreamApplication($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseStreamApplication relation
 *
 * @method     ChildApplicationQuery joinWithCurrentCourseStreamApplication($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseStreamApplication relation
 *
 * @method     ChildApplicationQuery leftJoinWithCurrentCourseStreamApplication() Adds a LEFT JOIN clause and with to the query using the CurrentCourseStreamApplication relation
 * @method     ChildApplicationQuery rightJoinWithCurrentCourseStreamApplication() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseStreamApplication relation
 * @method     ChildApplicationQuery innerJoinWithCurrentCourseStreamApplication() Adds a INNER JOIN clause and with to the query using the CurrentCourseStreamApplication relation
 *
 * @method     \Models\ApplicationStatusQuery|\Models\CourseQuery|\Models\CourseStreamQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildApplication findOne(ConnectionInterface $con = null) Return the first ChildApplication matching the query
 * @method     ChildApplication findOneOrCreate(ConnectionInterface $con = null) Return the first ChildApplication matching the query, or a new ChildApplication object populated from the query conditions when no match is found
 *
 * @method     ChildApplication findOneById(int $id) Return the first ChildApplication filtered by the id column
 * @method     ChildApplication findOneByName(string $name) Return the first ChildApplication filtered by the name column
 * @method     ChildApplication findOneByPhone(string $phone) Return the first ChildApplication filtered by the phone column
 * @method     ChildApplication findOneByCurrentApplicationStatusId(int $application_status_id) Return the first ChildApplication filtered by the application_status_id column
 * @method     ChildApplication findOneByCurrentCourseId(int $course_id) Return the first ChildApplication filtered by the course_id column
 * @method     ChildApplication findOneByCurrentCourseStreamId(int $course_stream_id) Return the first ChildApplication filtered by the course_stream_id column
 * @method     ChildApplication findOneByDescription(string $description) Return the first ChildApplication filtered by the description column
 * @method     ChildApplication findOneByNotes(string $notes) Return the first ChildApplication filtered by the notes column
 * @method     ChildApplication findOneByCreatedAt(string $created_at) Return the first ChildApplication filtered by the created_at column
 * @method     ChildApplication findOneByUpdatedAt(string $updated_at) Return the first ChildApplication filtered by the updated_at column *

 * @method     ChildApplication requirePk($key, ConnectionInterface $con = null) Return the ChildApplication by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOne(ConnectionInterface $con = null) Return the first ChildApplication matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildApplication requireOneById(int $id) Return the first ChildApplication filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByName(string $name) Return the first ChildApplication filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByPhone(string $phone) Return the first ChildApplication filtered by the phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByCurrentApplicationStatusId(int $application_status_id) Return the first ChildApplication filtered by the application_status_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByCurrentCourseId(int $course_id) Return the first ChildApplication filtered by the course_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByCurrentCourseStreamId(int $course_stream_id) Return the first ChildApplication filtered by the course_stream_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByDescription(string $description) Return the first ChildApplication filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByNotes(string $notes) Return the first ChildApplication filtered by the notes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByCreatedAt(string $created_at) Return the first ChildApplication filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildApplication requireOneByUpdatedAt(string $updated_at) Return the first ChildApplication filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildApplication[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildApplication objects based on current ModelCriteria
 * @method     ChildApplication[]|ObjectCollection findById(int $id) Return ChildApplication objects filtered by the id column
 * @method     ChildApplication[]|ObjectCollection findByName(string $name) Return ChildApplication objects filtered by the name column
 * @method     ChildApplication[]|ObjectCollection findByPhone(string $phone) Return ChildApplication objects filtered by the phone column
 * @method     ChildApplication[]|ObjectCollection findByCurrentApplicationStatusId(int $application_status_id) Return ChildApplication objects filtered by the application_status_id column
 * @method     ChildApplication[]|ObjectCollection findByCurrentCourseId(int $course_id) Return ChildApplication objects filtered by the course_id column
 * @method     ChildApplication[]|ObjectCollection findByCurrentCourseStreamId(int $course_stream_id) Return ChildApplication objects filtered by the course_stream_id column
 * @method     ChildApplication[]|ObjectCollection findByDescription(string $description) Return ChildApplication objects filtered by the description column
 * @method     ChildApplication[]|ObjectCollection findByNotes(string $notes) Return ChildApplication objects filtered by the notes column
 * @method     ChildApplication[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildApplication objects filtered by the created_at column
 * @method     ChildApplication[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildApplication objects filtered by the updated_at column
 * @method     ChildApplication[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ApplicationQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\ApplicationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Application', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildApplicationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildApplicationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildApplicationQuery) {
            return $criteria;
        }
        $query = new ChildApplicationQuery();
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
     * @return ChildApplication|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ApplicationTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ApplicationTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildApplication A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `phone`, `application_status_id`, `course_id`, `course_stream_id`, `description`, `notes`, `created_at`, `updated_at` FROM `application` WHERE `id` = :p0';
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
            /** @var ChildApplication $obj */
            $obj = new ChildApplication();
            $obj->hydrate($row);
            ApplicationTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildApplication|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ApplicationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ApplicationTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the application_status_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentApplicationStatusId(1234); // WHERE application_status_id = 1234
     * $query->filterByCurrentApplicationStatusId(array(12, 34)); // WHERE application_status_id IN (12, 34)
     * $query->filterByCurrentApplicationStatusId(array('min' => 12)); // WHERE application_status_id > 12
     * </code>
     *
     * @see       filterByCurrentApplicationStatus()
     *
     * @param     mixed $currentApplicationStatusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByCurrentApplicationStatusId($currentApplicationStatusId = null, $comparison = null)
    {
        if (is_array($currentApplicationStatusId)) {
            $useMinMax = false;
            if (isset($currentApplicationStatusId['min'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_APPLICATION_STATUS_ID, $currentApplicationStatusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentApplicationStatusId['max'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_APPLICATION_STATUS_ID, $currentApplicationStatusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_APPLICATION_STATUS_ID, $currentApplicationStatusId, $comparison);
    }

    /**
     * Filter the query on the course_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentCourseId(1234); // WHERE course_id = 1234
     * $query->filterByCurrentCourseId(array(12, 34)); // WHERE course_id IN (12, 34)
     * $query->filterByCurrentCourseId(array('min' => 12)); // WHERE course_id > 12
     * </code>
     *
     * @see       filterByCurrentCourseApplication()
     *
     * @param     mixed $currentCourseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseId($currentCourseId = null, $comparison = null)
    {
        if (is_array($currentCourseId)) {
            $useMinMax = false;
            if (isset($currentCourseId['min'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_COURSE_ID, $currentCourseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentCourseId['max'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_COURSE_ID, $currentCourseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_COURSE_ID, $currentCourseId, $comparison);
    }

    /**
     * Filter the query on the course_stream_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentCourseStreamId(1234); // WHERE course_stream_id = 1234
     * $query->filterByCurrentCourseStreamId(array(12, 34)); // WHERE course_stream_id IN (12, 34)
     * $query->filterByCurrentCourseStreamId(array('min' => 12)); // WHERE course_stream_id > 12
     * </code>
     *
     * @see       filterByCurrentCourseStreamApplication()
     *
     * @param     mixed $currentCourseStreamId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamId($currentCourseStreamId = null, $comparison = null)
    {
        if (is_array($currentCourseStreamId)) {
            $useMinMax = false;
            if (isset($currentCourseStreamId['min'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_COURSE_STREAM_ID, $currentCourseStreamId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentCourseStreamId['max'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_COURSE_STREAM_ID, $currentCourseStreamId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_COURSE_STREAM_ID, $currentCourseStreamId, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByNotes($notes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_NOTES, $notes, $comparison);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ApplicationTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ApplicationTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\ApplicationStatus object
     *
     * @param \Models\ApplicationStatus|ObjectCollection $applicationStatus The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByCurrentApplicationStatus($applicationStatus, $comparison = null)
    {
        if ($applicationStatus instanceof \Models\ApplicationStatus) {
            return $this
                ->addUsingAlias(ApplicationTableMap::COL_APPLICATION_STATUS_ID, $applicationStatus->getId(), $comparison);
        } elseif ($applicationStatus instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationTableMap::COL_APPLICATION_STATUS_ID, $applicationStatus->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentApplicationStatus() only accepts arguments of type \Models\ApplicationStatus or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentApplicationStatus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function joinCurrentApplicationStatus($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentApplicationStatus');

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
            $this->addJoinObject($join, 'CurrentApplicationStatus');
        }

        return $this;
    }

    /**
     * Use the CurrentApplicationStatus relation ApplicationStatus object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\ApplicationStatusQuery A secondary query class using the current class as primary query
     */
    public function useCurrentApplicationStatusQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentApplicationStatus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentApplicationStatus', '\Models\ApplicationStatusQuery');
    }

    /**
     * Filter the query by a related \Models\Course object
     *
     * @param \Models\Course|ObjectCollection $course The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseApplication($course, $comparison = null)
    {
        if ($course instanceof \Models\Course) {
            return $this
                ->addUsingAlias(ApplicationTableMap::COL_COURSE_ID, $course->getId(), $comparison);
        } elseif ($course instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationTableMap::COL_COURSE_ID, $course->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentCourseApplication() only accepts arguments of type \Models\Course or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseApplication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function joinCurrentCourseApplication($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseApplication');

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
            $this->addJoinObject($join, 'CurrentCourseApplication');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseApplication relation Course object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseApplicationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentCourseApplication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseApplication', '\Models\CourseQuery');
    }

    /**
     * Filter the query by a related \Models\CourseStream object
     *
     * @param \Models\CourseStream|ObjectCollection $courseStream The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildApplicationQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamApplication($courseStream, $comparison = null)
    {
        if ($courseStream instanceof \Models\CourseStream) {
            return $this
                ->addUsingAlias(ApplicationTableMap::COL_COURSE_STREAM_ID, $courseStream->getId(), $comparison);
        } elseif ($courseStream instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ApplicationTableMap::COL_COURSE_STREAM_ID, $courseStream->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentCourseStreamApplication() only accepts arguments of type \Models\CourseStream or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseStreamApplication relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function joinCurrentCourseStreamApplication($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseStreamApplication');

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
            $this->addJoinObject($join, 'CurrentCourseStreamApplication');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseStreamApplication relation CourseStream object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseStreamQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseStreamApplicationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentCourseStreamApplication($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseStreamApplication', '\Models\CourseStreamQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildApplication $application Object to remove from the list of results
     *
     * @return $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function prune($application = null)
    {
        if ($application) {
            $this->addUsingAlias(ApplicationTableMap::COL_ID, $application->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the application table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ApplicationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ApplicationTableMap::clearInstancePool();
            ApplicationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ApplicationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ApplicationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ApplicationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ApplicationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ApplicationTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ApplicationTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ApplicationTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ApplicationTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ApplicationTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildApplicationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ApplicationTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ApplicationTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(ApplicationTableMap::DATABASE_NAME);

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

} // ApplicationQuery
