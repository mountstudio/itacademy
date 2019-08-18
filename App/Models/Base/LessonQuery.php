<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Lesson as ChildLesson;
use Models\LessonQuery as ChildLessonQuery;
use Models\Map\LessonTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'stream_lesson' table.
 *
 *
 *
 * @method     ChildLessonQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLessonQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildLessonQuery orderByDateStart($order = Criteria::ASC) Order by the dateStart column
 * @method     ChildLessonQuery orderByDateEnd($order = Criteria::ASC) Order by the dateEnd column
 * @method     ChildLessonQuery orderByDoc($order = Criteria::ASC) Order by the doc column
 * @method     ChildLessonQuery orderByVideoLink($order = Criteria::ASC) Order by the video_link column
 * @method     ChildLessonQuery orderByAllDay($order = Criteria::ASC) Order by the all_day column
 * @method     ChildLessonQuery orderByCurrentStreamId($order = Criteria::ASC) Order by the stream_id column
 * @method     ChildLessonQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildLessonQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildLessonQuery groupById() Group by the id column
 * @method     ChildLessonQuery groupByTitle() Group by the title column
 * @method     ChildLessonQuery groupByDateStart() Group by the dateStart column
 * @method     ChildLessonQuery groupByDateEnd() Group by the dateEnd column
 * @method     ChildLessonQuery groupByDoc() Group by the doc column
 * @method     ChildLessonQuery groupByVideoLink() Group by the video_link column
 * @method     ChildLessonQuery groupByAllDay() Group by the all_day column
 * @method     ChildLessonQuery groupByCurrentStreamId() Group by the stream_id column
 * @method     ChildLessonQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildLessonQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildLessonQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLessonQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLessonQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLessonQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLessonQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLessonQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLessonQuery leftJoinCurrentStreamStreamLesson($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentStreamStreamLesson relation
 * @method     ChildLessonQuery rightJoinCurrentStreamStreamLesson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentStreamStreamLesson relation
 * @method     ChildLessonQuery innerJoinCurrentStreamStreamLesson($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentStreamStreamLesson relation
 *
 * @method     ChildLessonQuery joinWithCurrentStreamStreamLesson($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentStreamStreamLesson relation
 *
 * @method     ChildLessonQuery leftJoinWithCurrentStreamStreamLesson() Adds a LEFT JOIN clause and with to the query using the CurrentStreamStreamLesson relation
 * @method     ChildLessonQuery rightJoinWithCurrentStreamStreamLesson() Adds a RIGHT JOIN clause and with to the query using the CurrentStreamStreamLesson relation
 * @method     ChildLessonQuery innerJoinWithCurrentStreamStreamLesson() Adds a INNER JOIN clause and with to the query using the CurrentStreamStreamLesson relation
 *
 * @method     \Models\CourseStreamQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLesson findOne(ConnectionInterface $con = null) Return the first ChildLesson matching the query
 * @method     ChildLesson findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLesson matching the query, or a new ChildLesson object populated from the query conditions when no match is found
 *
 * @method     ChildLesson findOneById(int $id) Return the first ChildLesson filtered by the id column
 * @method     ChildLesson findOneByTitle(string $title) Return the first ChildLesson filtered by the title column
 * @method     ChildLesson findOneByDateStart(string $dateStart) Return the first ChildLesson filtered by the dateStart column
 * @method     ChildLesson findOneByDateEnd(string $dateEnd) Return the first ChildLesson filtered by the dateEnd column
 * @method     ChildLesson findOneByDoc(string $doc) Return the first ChildLesson filtered by the doc column
 * @method     ChildLesson findOneByVideoLink(string $video_link) Return the first ChildLesson filtered by the video_link column
 * @method     ChildLesson findOneByAllDay(boolean $all_day) Return the first ChildLesson filtered by the all_day column
 * @method     ChildLesson findOneByCurrentStreamId(int $stream_id) Return the first ChildLesson filtered by the stream_id column
 * @method     ChildLesson findOneByCreatedAt(string $created_at) Return the first ChildLesson filtered by the created_at column
 * @method     ChildLesson findOneByUpdatedAt(string $updated_at) Return the first ChildLesson filtered by the updated_at column *

 * @method     ChildLesson requirePk($key, ConnectionInterface $con = null) Return the ChildLesson by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOne(ConnectionInterface $con = null) Return the first ChildLesson matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLesson requireOneById(int $id) Return the first ChildLesson filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByTitle(string $title) Return the first ChildLesson filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByDateStart(string $dateStart) Return the first ChildLesson filtered by the dateStart column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByDateEnd(string $dateEnd) Return the first ChildLesson filtered by the dateEnd column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByDoc(string $doc) Return the first ChildLesson filtered by the doc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByVideoLink(string $video_link) Return the first ChildLesson filtered by the video_link column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByAllDay(boolean $all_day) Return the first ChildLesson filtered by the all_day column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByCurrentStreamId(int $stream_id) Return the first ChildLesson filtered by the stream_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByCreatedAt(string $created_at) Return the first ChildLesson filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLesson requireOneByUpdatedAt(string $updated_at) Return the first ChildLesson filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLesson[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLesson objects based on current ModelCriteria
 * @method     ChildLesson[]|ObjectCollection findById(int $id) Return ChildLesson objects filtered by the id column
 * @method     ChildLesson[]|ObjectCollection findByTitle(string $title) Return ChildLesson objects filtered by the title column
 * @method     ChildLesson[]|ObjectCollection findByDateStart(string $dateStart) Return ChildLesson objects filtered by the dateStart column
 * @method     ChildLesson[]|ObjectCollection findByDateEnd(string $dateEnd) Return ChildLesson objects filtered by the dateEnd column
 * @method     ChildLesson[]|ObjectCollection findByDoc(string $doc) Return ChildLesson objects filtered by the doc column
 * @method     ChildLesson[]|ObjectCollection findByVideoLink(string $video_link) Return ChildLesson objects filtered by the video_link column
 * @method     ChildLesson[]|ObjectCollection findByAllDay(boolean $all_day) Return ChildLesson objects filtered by the all_day column
 * @method     ChildLesson[]|ObjectCollection findByCurrentStreamId(int $stream_id) Return ChildLesson objects filtered by the stream_id column
 * @method     ChildLesson[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildLesson objects filtered by the created_at column
 * @method     ChildLesson[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildLesson objects filtered by the updated_at column
 * @method     ChildLesson[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LessonQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\LessonQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Lesson', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLessonQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLessonQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLessonQuery) {
            return $criteria;
        }
        $query = new ChildLessonQuery();
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
     * @return ChildLesson|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LessonTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LessonTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLesson A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `title`, `dateStart`, `dateEnd`, `doc`, `video_link`, `all_day`, `stream_id`, `created_at`, `updated_at` FROM `stream_lesson` WHERE `id` = :p0';
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
            /** @var ChildLesson $obj */
            $obj = new ChildLesson();
            $obj->hydrate($row);
            LessonTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLesson|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LessonTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LessonTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LessonTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LessonTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the dateStart column
     *
     * Example usage:
     * <code>
     * $query->filterByDateStart('2011-03-14'); // WHERE dateStart = '2011-03-14'
     * $query->filterByDateStart('now'); // WHERE dateStart = '2011-03-14'
     * $query->filterByDateStart(array('max' => 'yesterday')); // WHERE dateStart > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateStart The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByDateStart($dateStart = null, $comparison = null)
    {
        if (is_array($dateStart)) {
            $useMinMax = false;
            if (isset($dateStart['min'])) {
                $this->addUsingAlias(LessonTableMap::COL_DATESTART, $dateStart['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateStart['max'])) {
                $this->addUsingAlias(LessonTableMap::COL_DATESTART, $dateStart['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_DATESTART, $dateStart, $comparison);
    }

    /**
     * Filter the query on the dateEnd column
     *
     * Example usage:
     * <code>
     * $query->filterByDateEnd('2011-03-14'); // WHERE dateEnd = '2011-03-14'
     * $query->filterByDateEnd('now'); // WHERE dateEnd = '2011-03-14'
     * $query->filterByDateEnd(array('max' => 'yesterday')); // WHERE dateEnd > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateEnd The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByDateEnd($dateEnd = null, $comparison = null)
    {
        if (is_array($dateEnd)) {
            $useMinMax = false;
            if (isset($dateEnd['min'])) {
                $this->addUsingAlias(LessonTableMap::COL_DATEEND, $dateEnd['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateEnd['max'])) {
                $this->addUsingAlias(LessonTableMap::COL_DATEEND, $dateEnd['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_DATEEND, $dateEnd, $comparison);
    }

    /**
     * Filter the query on the doc column
     *
     * Example usage:
     * <code>
     * $query->filterByDoc('fooValue');   // WHERE doc = 'fooValue'
     * $query->filterByDoc('%fooValue%', Criteria::LIKE); // WHERE doc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $doc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByDoc($doc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($doc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_DOC, $doc, $comparison);
    }

    /**
     * Filter the query on the video_link column
     *
     * Example usage:
     * <code>
     * $query->filterByVideoLink('fooValue');   // WHERE video_link = 'fooValue'
     * $query->filterByVideoLink('%fooValue%', Criteria::LIKE); // WHERE video_link LIKE '%fooValue%'
     * </code>
     *
     * @param     string $videoLink The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByVideoLink($videoLink = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($videoLink)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_VIDEO_LINK, $videoLink, $comparison);
    }

    /**
     * Filter the query on the all_day column
     *
     * Example usage:
     * <code>
     * $query->filterByAllDay(true); // WHERE all_day = true
     * $query->filterByAllDay('yes'); // WHERE all_day = true
     * </code>
     *
     * @param     boolean|string $allDay The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByAllDay($allDay = null, $comparison = null)
    {
        if (is_string($allDay)) {
            $allDay = in_array(strtolower($allDay), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LessonTableMap::COL_ALL_DAY, $allDay, $comparison);
    }

    /**
     * Filter the query on the stream_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentStreamId(1234); // WHERE stream_id = 1234
     * $query->filterByCurrentStreamId(array(12, 34)); // WHERE stream_id IN (12, 34)
     * $query->filterByCurrentStreamId(array('min' => 12)); // WHERE stream_id > 12
     * </code>
     *
     * @see       filterByCurrentStreamStreamLesson()
     *
     * @param     mixed $currentStreamId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByCurrentStreamId($currentStreamId = null, $comparison = null)
    {
        if (is_array($currentStreamId)) {
            $useMinMax = false;
            if (isset($currentStreamId['min'])) {
                $this->addUsingAlias(LessonTableMap::COL_STREAM_ID, $currentStreamId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentStreamId['max'])) {
                $this->addUsingAlias(LessonTableMap::COL_STREAM_ID, $currentStreamId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_STREAM_ID, $currentStreamId, $comparison);
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
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(LessonTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(LessonTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(LessonTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(LessonTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LessonTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\CourseStream object
     *
     * @param \Models\CourseStream|ObjectCollection $courseStream The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLessonQuery The current query, for fluid interface
     */
    public function filterByCurrentStreamStreamLesson($courseStream, $comparison = null)
    {
        if ($courseStream instanceof \Models\CourseStream) {
            return $this
                ->addUsingAlias(LessonTableMap::COL_STREAM_ID, $courseStream->getId(), $comparison);
        } elseif ($courseStream instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LessonTableMap::COL_STREAM_ID, $courseStream->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentStreamStreamLesson() only accepts arguments of type \Models\CourseStream or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentStreamStreamLesson relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function joinCurrentStreamStreamLesson($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentStreamStreamLesson');

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
            $this->addJoinObject($join, 'CurrentStreamStreamLesson');
        }

        return $this;
    }

    /**
     * Use the CurrentStreamStreamLesson relation CourseStream object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseStreamQuery A secondary query class using the current class as primary query
     */
    public function useCurrentStreamStreamLessonQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentStreamStreamLesson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentStreamStreamLesson', '\Models\CourseStreamQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLesson $lesson Object to remove from the list of results
     *
     * @return $this|ChildLessonQuery The current query, for fluid interface
     */
    public function prune($lesson = null)
    {
        if ($lesson) {
            $this->addUsingAlias(LessonTableMap::COL_ID, $lesson->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the stream_lesson table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LessonTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LessonTableMap::clearInstancePool();
            LessonTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LessonTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LessonTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LessonTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LessonTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildLessonQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(LessonTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildLessonQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(LessonTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildLessonQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(LessonTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildLessonQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(LessonTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildLessonQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(LessonTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildLessonQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(LessonTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LessonTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(LessonTableMap::DATABASE_NAME);

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

} // LessonQuery
