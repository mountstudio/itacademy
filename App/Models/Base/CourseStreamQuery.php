<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\CourseStream as ChildCourseStream;
use Models\CourseStreamQuery as ChildCourseStreamQuery;
use Models\Map\CourseStreamTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'course_stream' table.
 *
 *
 *
 * @method     ChildCourseStreamQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCourseStreamQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCourseStreamQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildCourseStreamQuery orderByNumberOfPlaces($order = Criteria::ASC) Order by the number_of_places column
 * @method     ChildCourseStreamQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method     ChildCourseStreamQuery orderByStartsAt($order = Criteria::ASC) Order by the starts_at column
 * @method     ChildCourseStreamQuery orderByEndsAt($order = Criteria::ASC) Order by the ends_at column
 * @method     ChildCourseStreamQuery orderByShowOnWebSite($order = Criteria::ASC) Order by the show_on_website column
 * @method     ChildCourseStreamQuery orderByCost($order = Criteria::ASC) Order by the cost column
 * @method     ChildCourseStreamQuery orderByCurrentBranchId($order = Criteria::ASC) Order by the branch_id column
 * @method     ChildCourseStreamQuery orderByCurrentCurrencyId($order = Criteria::ASC) Order by the currency_id column
 * @method     ChildCourseStreamQuery orderByCurrentCourseId($order = Criteria::ASC) Order by the course_id column
 * @method     ChildCourseStreamQuery orderByCurrentCourseStreamStatusId($order = Criteria::ASC) Order by the course_stream_status_id column
 * @method     ChildCourseStreamQuery orderByCurrentCourseStreamInstructorId($order = Criteria::ASC) Order by the instructor_id column
 * @method     ChildCourseStreamQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildCourseStreamQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildCourseStreamQuery groupById() Group by the id column
 * @method     ChildCourseStreamQuery groupByName() Group by the name column
 * @method     ChildCourseStreamQuery groupByDescription() Group by the description column
 * @method     ChildCourseStreamQuery groupByNumberOfPlaces() Group by the number_of_places column
 * @method     ChildCourseStreamQuery groupByNotes() Group by the notes column
 * @method     ChildCourseStreamQuery groupByStartsAt() Group by the starts_at column
 * @method     ChildCourseStreamQuery groupByEndsAt() Group by the ends_at column
 * @method     ChildCourseStreamQuery groupByShowOnWebSite() Group by the show_on_website column
 * @method     ChildCourseStreamQuery groupByCost() Group by the cost column
 * @method     ChildCourseStreamQuery groupByCurrentBranchId() Group by the branch_id column
 * @method     ChildCourseStreamQuery groupByCurrentCurrencyId() Group by the currency_id column
 * @method     ChildCourseStreamQuery groupByCurrentCourseId() Group by the course_id column
 * @method     ChildCourseStreamQuery groupByCurrentCourseStreamStatusId() Group by the course_stream_status_id column
 * @method     ChildCourseStreamQuery groupByCurrentCourseStreamInstructorId() Group by the instructor_id column
 * @method     ChildCourseStreamQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildCourseStreamQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildCourseStreamQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCourseStreamQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCourseStreamQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCourseStreamQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCourseStreamQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCourseStreamQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCourseStreamQuery leftJoinCurrentCourseStreamBranch($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseStreamBranch relation
 * @method     ChildCourseStreamQuery rightJoinCurrentCourseStreamBranch($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseStreamBranch relation
 * @method     ChildCourseStreamQuery innerJoinCurrentCourseStreamBranch($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseStreamBranch relation
 *
 * @method     ChildCourseStreamQuery joinWithCurrentCourseStreamBranch($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseStreamBranch relation
 *
 * @method     ChildCourseStreamQuery leftJoinWithCurrentCourseStreamBranch() Adds a LEFT JOIN clause and with to the query using the CurrentCourseStreamBranch relation
 * @method     ChildCourseStreamQuery rightJoinWithCurrentCourseStreamBranch() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseStreamBranch relation
 * @method     ChildCourseStreamQuery innerJoinWithCurrentCourseStreamBranch() Adds a INNER JOIN clause and with to the query using the CurrentCourseStreamBranch relation
 *
 * @method     ChildCourseStreamQuery leftJoinCurrentCourseStreamCurrency($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseStreamCurrency relation
 * @method     ChildCourseStreamQuery rightJoinCurrentCourseStreamCurrency($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseStreamCurrency relation
 * @method     ChildCourseStreamQuery innerJoinCurrentCourseStreamCurrency($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseStreamCurrency relation
 *
 * @method     ChildCourseStreamQuery joinWithCurrentCourseStreamCurrency($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseStreamCurrency relation
 *
 * @method     ChildCourseStreamQuery leftJoinWithCurrentCourseStreamCurrency() Adds a LEFT JOIN clause and with to the query using the CurrentCourseStreamCurrency relation
 * @method     ChildCourseStreamQuery rightJoinWithCurrentCourseStreamCurrency() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseStreamCurrency relation
 * @method     ChildCourseStreamQuery innerJoinWithCurrentCourseStreamCurrency() Adds a INNER JOIN clause and with to the query using the CurrentCourseStreamCurrency relation
 *
 * @method     ChildCourseStreamQuery leftJoinCurrentCourseCourseStream($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseCourseStream relation
 * @method     ChildCourseStreamQuery rightJoinCurrentCourseCourseStream($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseCourseStream relation
 * @method     ChildCourseStreamQuery innerJoinCurrentCourseCourseStream($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseCourseStream relation
 *
 * @method     ChildCourseStreamQuery joinWithCurrentCourseCourseStream($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseCourseStream relation
 *
 * @method     ChildCourseStreamQuery leftJoinWithCurrentCourseCourseStream() Adds a LEFT JOIN clause and with to the query using the CurrentCourseCourseStream relation
 * @method     ChildCourseStreamQuery rightJoinWithCurrentCourseCourseStream() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseCourseStream relation
 * @method     ChildCourseStreamQuery innerJoinWithCurrentCourseCourseStream() Adds a INNER JOIN clause and with to the query using the CurrentCourseCourseStream relation
 *
 * @method     ChildCourseStreamQuery leftJoinCurrentCourseCourseStreamStatus($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseCourseStreamStatus relation
 * @method     ChildCourseStreamQuery rightJoinCurrentCourseCourseStreamStatus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseCourseStreamStatus relation
 * @method     ChildCourseStreamQuery innerJoinCurrentCourseCourseStreamStatus($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseCourseStreamStatus relation
 *
 * @method     ChildCourseStreamQuery joinWithCurrentCourseCourseStreamStatus($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseCourseStreamStatus relation
 *
 * @method     ChildCourseStreamQuery leftJoinWithCurrentCourseCourseStreamStatus() Adds a LEFT JOIN clause and with to the query using the CurrentCourseCourseStreamStatus relation
 * @method     ChildCourseStreamQuery rightJoinWithCurrentCourseCourseStreamStatus() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseCourseStreamStatus relation
 * @method     ChildCourseStreamQuery innerJoinWithCurrentCourseCourseStreamStatus() Adds a INNER JOIN clause and with to the query using the CurrentCourseCourseStreamStatus relation
 *
 * @method     ChildCourseStreamQuery leftJoinCurrentCourseStreamInstructor($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseStreamInstructor relation
 * @method     ChildCourseStreamQuery rightJoinCurrentCourseStreamInstructor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseStreamInstructor relation
 * @method     ChildCourseStreamQuery innerJoinCurrentCourseStreamInstructor($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseStreamInstructor relation
 *
 * @method     ChildCourseStreamQuery joinWithCurrentCourseStreamInstructor($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseStreamInstructor relation
 *
 * @method     ChildCourseStreamQuery leftJoinWithCurrentCourseStreamInstructor() Adds a LEFT JOIN clause and with to the query using the CurrentCourseStreamInstructor relation
 * @method     ChildCourseStreamQuery rightJoinWithCurrentCourseStreamInstructor() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseStreamInstructor relation
 * @method     ChildCourseStreamQuery innerJoinWithCurrentCourseStreamInstructor() Adds a INNER JOIN clause and with to the query using the CurrentCourseStreamInstructor relation
 *
 * @method     ChildCourseStreamQuery leftJoinCurrentApplicationCourseStream($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentApplicationCourseStream relation
 * @method     ChildCourseStreamQuery rightJoinCurrentApplicationCourseStream($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentApplicationCourseStream relation
 * @method     ChildCourseStreamQuery innerJoinCurrentApplicationCourseStream($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentApplicationCourseStream relation
 *
 * @method     ChildCourseStreamQuery joinWithCurrentApplicationCourseStream($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentApplicationCourseStream relation
 *
 * @method     ChildCourseStreamQuery leftJoinWithCurrentApplicationCourseStream() Adds a LEFT JOIN clause and with to the query using the CurrentApplicationCourseStream relation
 * @method     ChildCourseStreamQuery rightJoinWithCurrentApplicationCourseStream() Adds a RIGHT JOIN clause and with to the query using the CurrentApplicationCourseStream relation
 * @method     ChildCourseStreamQuery innerJoinWithCurrentApplicationCourseStream() Adds a INNER JOIN clause and with to the query using the CurrentApplicationCourseStream relation
 *
 * @method     \Models\BranchQuery|\Models\CurrencyQuery|\Models\CourseQuery|\Models\CourseStreamStatusQuery|\Models\UserQuery|\Models\ApplicationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCourseStream findOne(ConnectionInterface $con = null) Return the first ChildCourseStream matching the query
 * @method     ChildCourseStream findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCourseStream matching the query, or a new ChildCourseStream object populated from the query conditions when no match is found
 *
 * @method     ChildCourseStream findOneById(int $id) Return the first ChildCourseStream filtered by the id column
 * @method     ChildCourseStream findOneByName(string $name) Return the first ChildCourseStream filtered by the name column
 * @method     ChildCourseStream findOneByDescription(string $description) Return the first ChildCourseStream filtered by the description column
 * @method     ChildCourseStream findOneByNumberOfPlaces(int $number_of_places) Return the first ChildCourseStream filtered by the number_of_places column
 * @method     ChildCourseStream findOneByNotes(string $notes) Return the first ChildCourseStream filtered by the notes column
 * @method     ChildCourseStream findOneByStartsAt(string $starts_at) Return the first ChildCourseStream filtered by the starts_at column
 * @method     ChildCourseStream findOneByEndsAt(string $ends_at) Return the first ChildCourseStream filtered by the ends_at column
 * @method     ChildCourseStream findOneByShowOnWebSite(boolean $show_on_website) Return the first ChildCourseStream filtered by the show_on_website column
 * @method     ChildCourseStream findOneByCost(float $cost) Return the first ChildCourseStream filtered by the cost column
 * @method     ChildCourseStream findOneByCurrentBranchId(int $branch_id) Return the first ChildCourseStream filtered by the branch_id column
 * @method     ChildCourseStream findOneByCurrentCurrencyId(int $currency_id) Return the first ChildCourseStream filtered by the currency_id column
 * @method     ChildCourseStream findOneByCurrentCourseId(int $course_id) Return the first ChildCourseStream filtered by the course_id column
 * @method     ChildCourseStream findOneByCurrentCourseStreamStatusId(int $course_stream_status_id) Return the first ChildCourseStream filtered by the course_stream_status_id column
 * @method     ChildCourseStream findOneByCurrentCourseStreamInstructorId(int $instructor_id) Return the first ChildCourseStream filtered by the instructor_id column
 * @method     ChildCourseStream findOneByCreatedAt(string $created_at) Return the first ChildCourseStream filtered by the created_at column
 * @method     ChildCourseStream findOneByUpdatedAt(string $updated_at) Return the first ChildCourseStream filtered by the updated_at column *

 * @method     ChildCourseStream requirePk($key, ConnectionInterface $con = null) Return the ChildCourseStream by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOne(ConnectionInterface $con = null) Return the first ChildCourseStream matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCourseStream requireOneById(int $id) Return the first ChildCourseStream filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByName(string $name) Return the first ChildCourseStream filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByDescription(string $description) Return the first ChildCourseStream filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByNumberOfPlaces(int $number_of_places) Return the first ChildCourseStream filtered by the number_of_places column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByNotes(string $notes) Return the first ChildCourseStream filtered by the notes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByStartsAt(string $starts_at) Return the first ChildCourseStream filtered by the starts_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByEndsAt(string $ends_at) Return the first ChildCourseStream filtered by the ends_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByShowOnWebSite(boolean $show_on_website) Return the first ChildCourseStream filtered by the show_on_website column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByCost(float $cost) Return the first ChildCourseStream filtered by the cost column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByCurrentBranchId(int $branch_id) Return the first ChildCourseStream filtered by the branch_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByCurrentCurrencyId(int $currency_id) Return the first ChildCourseStream filtered by the currency_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByCurrentCourseId(int $course_id) Return the first ChildCourseStream filtered by the course_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByCurrentCourseStreamStatusId(int $course_stream_status_id) Return the first ChildCourseStream filtered by the course_stream_status_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByCurrentCourseStreamInstructorId(int $instructor_id) Return the first ChildCourseStream filtered by the instructor_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByCreatedAt(string $created_at) Return the first ChildCourseStream filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStream requireOneByUpdatedAt(string $updated_at) Return the first ChildCourseStream filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCourseStream[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCourseStream objects based on current ModelCriteria
 * @method     ChildCourseStream[]|ObjectCollection findById(int $id) Return ChildCourseStream objects filtered by the id column
 * @method     ChildCourseStream[]|ObjectCollection findByName(string $name) Return ChildCourseStream objects filtered by the name column
 * @method     ChildCourseStream[]|ObjectCollection findByDescription(string $description) Return ChildCourseStream objects filtered by the description column
 * @method     ChildCourseStream[]|ObjectCollection findByNumberOfPlaces(int $number_of_places) Return ChildCourseStream objects filtered by the number_of_places column
 * @method     ChildCourseStream[]|ObjectCollection findByNotes(string $notes) Return ChildCourseStream objects filtered by the notes column
 * @method     ChildCourseStream[]|ObjectCollection findByStartsAt(string $starts_at) Return ChildCourseStream objects filtered by the starts_at column
 * @method     ChildCourseStream[]|ObjectCollection findByEndsAt(string $ends_at) Return ChildCourseStream objects filtered by the ends_at column
 * @method     ChildCourseStream[]|ObjectCollection findByShowOnWebSite(boolean $show_on_website) Return ChildCourseStream objects filtered by the show_on_website column
 * @method     ChildCourseStream[]|ObjectCollection findByCost(float $cost) Return ChildCourseStream objects filtered by the cost column
 * @method     ChildCourseStream[]|ObjectCollection findByCurrentBranchId(int $branch_id) Return ChildCourseStream objects filtered by the branch_id column
 * @method     ChildCourseStream[]|ObjectCollection findByCurrentCurrencyId(int $currency_id) Return ChildCourseStream objects filtered by the currency_id column
 * @method     ChildCourseStream[]|ObjectCollection findByCurrentCourseId(int $course_id) Return ChildCourseStream objects filtered by the course_id column
 * @method     ChildCourseStream[]|ObjectCollection findByCurrentCourseStreamStatusId(int $course_stream_status_id) Return ChildCourseStream objects filtered by the course_stream_status_id column
 * @method     ChildCourseStream[]|ObjectCollection findByCurrentCourseStreamInstructorId(int $instructor_id) Return ChildCourseStream objects filtered by the instructor_id column
 * @method     ChildCourseStream[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildCourseStream objects filtered by the created_at column
 * @method     ChildCourseStream[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildCourseStream objects filtered by the updated_at column
 * @method     ChildCourseStream[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CourseStreamQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\CourseStreamQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\CourseStream', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCourseStreamQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCourseStreamQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCourseStreamQuery) {
            return $criteria;
        }
        $query = new ChildCourseStreamQuery();
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
     * @return ChildCourseStream|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CourseStreamTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CourseStreamTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCourseStream A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `description`, `number_of_places`, `notes`, `starts_at`, `ends_at`, `show_on_website`, `cost`, `branch_id`, `currency_id`, `course_id`, `course_stream_status_id`, `instructor_id`, `created_at`, `updated_at` FROM `course_stream` WHERE `id` = :p0';
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
            /** @var ChildCourseStream $obj */
            $obj = new ChildCourseStream();
            $obj->hydrate($row);
            CourseStreamTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCourseStream|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CourseStreamTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CourseStreamTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the number_of_places column
     *
     * Example usage:
     * <code>
     * $query->filterByNumberOfPlaces(1234); // WHERE number_of_places = 1234
     * $query->filterByNumberOfPlaces(array(12, 34)); // WHERE number_of_places IN (12, 34)
     * $query->filterByNumberOfPlaces(array('min' => 12)); // WHERE number_of_places > 12
     * </code>
     *
     * @param     mixed $numberOfPlaces The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByNumberOfPlaces($numberOfPlaces = null, $comparison = null)
    {
        if (is_array($numberOfPlaces)) {
            $useMinMax = false;
            if (isset($numberOfPlaces['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_NUMBER_OF_PLACES, $numberOfPlaces['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numberOfPlaces['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_NUMBER_OF_PLACES, $numberOfPlaces['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_NUMBER_OF_PLACES, $numberOfPlaces, $comparison);
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
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByNotes($notes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_NOTES, $notes, $comparison);
    }

    /**
     * Filter the query on the starts_at column
     *
     * Example usage:
     * <code>
     * $query->filterByStartsAt('2011-03-14'); // WHERE starts_at = '2011-03-14'
     * $query->filterByStartsAt('now'); // WHERE starts_at = '2011-03-14'
     * $query->filterByStartsAt(array('max' => 'yesterday')); // WHERE starts_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $startsAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByStartsAt($startsAt = null, $comparison = null)
    {
        if (is_array($startsAt)) {
            $useMinMax = false;
            if (isset($startsAt['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_STARTS_AT, $startsAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startsAt['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_STARTS_AT, $startsAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_STARTS_AT, $startsAt, $comparison);
    }

    /**
     * Filter the query on the ends_at column
     *
     * Example usage:
     * <code>
     * $query->filterByEndsAt('2011-03-14'); // WHERE ends_at = '2011-03-14'
     * $query->filterByEndsAt('now'); // WHERE ends_at = '2011-03-14'
     * $query->filterByEndsAt(array('max' => 'yesterday')); // WHERE ends_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $endsAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByEndsAt($endsAt = null, $comparison = null)
    {
        if (is_array($endsAt)) {
            $useMinMax = false;
            if (isset($endsAt['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_ENDS_AT, $endsAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endsAt['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_ENDS_AT, $endsAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_ENDS_AT, $endsAt, $comparison);
    }

    /**
     * Filter the query on the show_on_website column
     *
     * Example usage:
     * <code>
     * $query->filterByShowOnWebSite(true); // WHERE show_on_website = true
     * $query->filterByShowOnWebSite('yes'); // WHERE show_on_website = true
     * </code>
     *
     * @param     boolean|string $showOnWebSite The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByShowOnWebSite($showOnWebSite = null, $comparison = null)
    {
        if (is_string($showOnWebSite)) {
            $showOnWebSite = in_array(strtolower($showOnWebSite), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_SHOW_ON_WEBSITE, $showOnWebSite, $comparison);
    }

    /**
     * Filter the query on the cost column
     *
     * Example usage:
     * <code>
     * $query->filterByCost(1234); // WHERE cost = 1234
     * $query->filterByCost(array(12, 34)); // WHERE cost IN (12, 34)
     * $query->filterByCost(array('min' => 12)); // WHERE cost > 12
     * </code>
     *
     * @param     mixed $cost The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCost($cost = null, $comparison = null)
    {
        if (is_array($cost)) {
            $useMinMax = false;
            if (isset($cost['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_COST, $cost['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cost['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_COST, $cost['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_COST, $cost, $comparison);
    }

    /**
     * Filter the query on the branch_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentBranchId(1234); // WHERE branch_id = 1234
     * $query->filterByCurrentBranchId(array(12, 34)); // WHERE branch_id IN (12, 34)
     * $query->filterByCurrentBranchId(array('min' => 12)); // WHERE branch_id > 12
     * </code>
     *
     * @see       filterByCurrentCourseStreamBranch()
     *
     * @param     mixed $currentBranchId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentBranchId($currentBranchId = null, $comparison = null)
    {
        if (is_array($currentBranchId)) {
            $useMinMax = false;
            if (isset($currentBranchId['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_BRANCH_ID, $currentBranchId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentBranchId['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_BRANCH_ID, $currentBranchId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_BRANCH_ID, $currentBranchId, $comparison);
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
     * @see       filterByCurrentCourseStreamCurrency()
     *
     * @param     mixed $currentCurrencyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCurrencyId($currentCurrencyId = null, $comparison = null)
    {
        if (is_array($currentCurrencyId)) {
            $useMinMax = false;
            if (isset($currentCurrencyId['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_CURRENCY_ID, $currentCurrencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentCurrencyId['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_CURRENCY_ID, $currentCurrencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_CURRENCY_ID, $currentCurrencyId, $comparison);
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
     * @see       filterByCurrentCourseCourseStream()
     *
     * @param     mixed $currentCourseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseId($currentCourseId = null, $comparison = null)
    {
        if (is_array($currentCourseId)) {
            $useMinMax = false;
            if (isset($currentCourseId['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_COURSE_ID, $currentCourseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentCourseId['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_COURSE_ID, $currentCourseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_COURSE_ID, $currentCourseId, $comparison);
    }

    /**
     * Filter the query on the course_stream_status_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentCourseStreamStatusId(1234); // WHERE course_stream_status_id = 1234
     * $query->filterByCurrentCourseStreamStatusId(array(12, 34)); // WHERE course_stream_status_id IN (12, 34)
     * $query->filterByCurrentCourseStreamStatusId(array('min' => 12)); // WHERE course_stream_status_id > 12
     * </code>
     *
     * @see       filterByCurrentCourseCourseStreamStatus()
     *
     * @param     mixed $currentCourseStreamStatusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamStatusId($currentCourseStreamStatusId = null, $comparison = null)
    {
        if (is_array($currentCourseStreamStatusId)) {
            $useMinMax = false;
            if (isset($currentCourseStreamStatusId['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID, $currentCourseStreamStatusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentCourseStreamStatusId['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID, $currentCourseStreamStatusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID, $currentCourseStreamStatusId, $comparison);
    }

    /**
     * Filter the query on the instructor_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentCourseStreamInstructorId(1234); // WHERE instructor_id = 1234
     * $query->filterByCurrentCourseStreamInstructorId(array(12, 34)); // WHERE instructor_id IN (12, 34)
     * $query->filterByCurrentCourseStreamInstructorId(array('min' => 12)); // WHERE instructor_id > 12
     * </code>
     *
     * @see       filterByCurrentCourseStreamInstructor()
     *
     * @param     mixed $currentCourseStreamInstructorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamInstructorId($currentCourseStreamInstructorId = null, $comparison = null)
    {
        if (is_array($currentCourseStreamInstructorId)) {
            $useMinMax = false;
            if (isset($currentCourseStreamInstructorId['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_INSTRUCTOR_ID, $currentCourseStreamInstructorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentCourseStreamInstructorId['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_INSTRUCTOR_ID, $currentCourseStreamInstructorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_INSTRUCTOR_ID, $currentCourseStreamInstructorId, $comparison);
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
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CourseStreamTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Branch object
     *
     * @param \Models\Branch|ObjectCollection $branch The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamBranch($branch, $comparison = null)
    {
        if ($branch instanceof \Models\Branch) {
            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_BRANCH_ID, $branch->getId(), $comparison);
        } elseif ($branch instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_BRANCH_ID, $branch->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentCourseStreamBranch() only accepts arguments of type \Models\Branch or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseStreamBranch relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function joinCurrentCourseStreamBranch($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseStreamBranch');

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
            $this->addJoinObject($join, 'CurrentCourseStreamBranch');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseStreamBranch relation Branch object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\BranchQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseStreamBranchQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentCourseStreamBranch($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseStreamBranch', '\Models\BranchQuery');
    }

    /**
     * Filter the query by a related \Models\Currency object
     *
     * @param \Models\Currency|ObjectCollection $currency The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamCurrency($currency, $comparison = null)
    {
        if ($currency instanceof \Models\Currency) {
            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_CURRENCY_ID, $currency->getId(), $comparison);
        } elseif ($currency instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_CURRENCY_ID, $currency->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentCourseStreamCurrency() only accepts arguments of type \Models\Currency or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseStreamCurrency relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function joinCurrentCourseStreamCurrency($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseStreamCurrency');

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
            $this->addJoinObject($join, 'CurrentCourseStreamCurrency');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseStreamCurrency relation Currency object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CurrencyQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseStreamCurrencyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentCourseStreamCurrency($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseStreamCurrency', '\Models\CurrencyQuery');
    }

    /**
     * Filter the query by a related \Models\Course object
     *
     * @param \Models\Course|ObjectCollection $course The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseCourseStream($course, $comparison = null)
    {
        if ($course instanceof \Models\Course) {
            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_COURSE_ID, $course->getId(), $comparison);
        } elseif ($course instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_COURSE_ID, $course->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentCourseCourseStream() only accepts arguments of type \Models\Course or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseCourseStream relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function joinCurrentCourseCourseStream($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseCourseStream');

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
            $this->addJoinObject($join, 'CurrentCourseCourseStream');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseCourseStream relation Course object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseCourseStreamQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentCourseCourseStream($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseCourseStream', '\Models\CourseQuery');
    }

    /**
     * Filter the query by a related \Models\CourseStreamStatus object
     *
     * @param \Models\CourseStreamStatus|ObjectCollection $courseStreamStatus The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseCourseStreamStatus($courseStreamStatus, $comparison = null)
    {
        if ($courseStreamStatus instanceof \Models\CourseStreamStatus) {
            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID, $courseStreamStatus->getId(), $comparison);
        } elseif ($courseStreamStatus instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_COURSE_STREAM_STATUS_ID, $courseStreamStatus->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentCourseCourseStreamStatus() only accepts arguments of type \Models\CourseStreamStatus or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseCourseStreamStatus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function joinCurrentCourseCourseStreamStatus($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseCourseStreamStatus');

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
            $this->addJoinObject($join, 'CurrentCourseCourseStreamStatus');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseCourseStreamStatus relation CourseStreamStatus object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseStreamStatusQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseCourseStreamStatusQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentCourseCourseStreamStatus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseCourseStreamStatus', '\Models\CourseStreamStatusQuery');
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamInstructor($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_INSTRUCTOR_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_INSTRUCTOR_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentCourseStreamInstructor() only accepts arguments of type \Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseStreamInstructor relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function joinCurrentCourseStreamInstructor($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseStreamInstructor');

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
            $this->addJoinObject($join, 'CurrentCourseStreamInstructor');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseStreamInstructor relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseStreamInstructorQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentCourseStreamInstructor($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseStreamInstructor', '\Models\UserQuery');
    }

    /**
     * Filter the query by a related \Models\Application object
     *
     * @param \Models\Application|ObjectCollection $application the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseStreamQuery The current query, for fluid interface
     */
    public function filterByCurrentApplicationCourseStream($application, $comparison = null)
    {
        if ($application instanceof \Models\Application) {
            return $this
                ->addUsingAlias(CourseStreamTableMap::COL_ID, $application->getCurrentCourseStreamId(), $comparison);
        } elseif ($application instanceof ObjectCollection) {
            return $this
                ->useCurrentApplicationCourseStreamQuery()
                ->filterByPrimaryKeys($application->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentApplicationCourseStream() only accepts arguments of type \Models\Application or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentApplicationCourseStream relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function joinCurrentApplicationCourseStream($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentApplicationCourseStream');

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
            $this->addJoinObject($join, 'CurrentApplicationCourseStream');
        }

        return $this;
    }

    /**
     * Use the CurrentApplicationCourseStream relation Application object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\ApplicationQuery A secondary query class using the current class as primary query
     */
    public function useCurrentApplicationCourseStreamQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentApplicationCourseStream($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentApplicationCourseStream', '\Models\ApplicationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCourseStream $courseStream Object to remove from the list of results
     *
     * @return $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function prune($courseStream = null)
    {
        if ($courseStream) {
            $this->addUsingAlias(CourseStreamTableMap::COL_ID, $courseStream->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the course_stream table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CourseStreamTableMap::clearInstancePool();
            CourseStreamTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CourseStreamTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CourseStreamTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CourseStreamTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CourseStreamTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CourseStreamTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CourseStreamTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CourseStreamTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CourseStreamTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildCourseStreamQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CourseStreamTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CourseStreamTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(CourseStreamTableMap::DATABASE_NAME);

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

} // CourseStreamQuery
