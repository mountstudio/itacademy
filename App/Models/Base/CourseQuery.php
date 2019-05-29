<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Course as ChildCourse;
use Models\CourseQuery as ChildCourseQuery;
use Models\Map\CourseTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'course' table.
 *
 *
 *
 * @method     ChildCourseQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCourseQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCourseQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildCourseQuery orderByAltUrl($order = Criteria::ASC) Order by the alt_url column
 * @method     ChildCourseQuery orderByLogoName($order = Criteria::ASC) Order by the logo_name column
 * @method     ChildCourseQuery orderByCoverName($order = Criteria::ASC) Order by the cover_name column
 * @method     ChildCourseQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildCourseQuery orderByContext($order = Criteria::ASC) Order by the context column
 * @method     ChildCourseQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method     ChildCourseQuery orderByUseNotes($order = Criteria::ASC) Order by the use_notes column
 * @method     ChildCourseQuery orderByUses($order = Criteria::ASC) Order by the uses column
 * @method     ChildCourseQuery orderByMetaDescription($order = Criteria::ASC) Order by the meta_description column
 * @method     ChildCourseQuery orderByMetaKeywords($order = Criteria::ASC) Order by the meta_keywords column
 * @method     ChildCourseQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildCourseQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildCourseQuery groupById() Group by the id column
 * @method     ChildCourseQuery groupByName() Group by the name column
 * @method     ChildCourseQuery groupByDescription() Group by the description column
 * @method     ChildCourseQuery groupByAltUrl() Group by the alt_url column
 * @method     ChildCourseQuery groupByLogoName() Group by the logo_name column
 * @method     ChildCourseQuery groupByCoverName() Group by the cover_name column
 * @method     ChildCourseQuery groupByTitle() Group by the title column
 * @method     ChildCourseQuery groupByContext() Group by the context column
 * @method     ChildCourseQuery groupByNotes() Group by the notes column
 * @method     ChildCourseQuery groupByUseNotes() Group by the use_notes column
 * @method     ChildCourseQuery groupByUses() Group by the uses column
 * @method     ChildCourseQuery groupByMetaDescription() Group by the meta_description column
 * @method     ChildCourseQuery groupByMetaKeywords() Group by the meta_keywords column
 * @method     ChildCourseQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildCourseQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildCourseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCourseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCourseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCourseQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCourseQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCourseQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCourseQuery leftJoinCurrentApplicationCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentApplicationCourse relation
 * @method     ChildCourseQuery rightJoinCurrentApplicationCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentApplicationCourse relation
 * @method     ChildCourseQuery innerJoinCurrentApplicationCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentApplicationCourse relation
 *
 * @method     ChildCourseQuery joinWithCurrentApplicationCourse($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentApplicationCourse relation
 *
 * @method     ChildCourseQuery leftJoinWithCurrentApplicationCourse() Adds a LEFT JOIN clause and with to the query using the CurrentApplicationCourse relation
 * @method     ChildCourseQuery rightJoinWithCurrentApplicationCourse() Adds a RIGHT JOIN clause and with to the query using the CurrentApplicationCourse relation
 * @method     ChildCourseQuery innerJoinWithCurrentApplicationCourse() Adds a INNER JOIN clause and with to the query using the CurrentApplicationCourse relation
 *
 * @method     ChildCourseQuery leftJoinCurrentCourseStreamCourse($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseStreamCourse relation
 * @method     ChildCourseQuery rightJoinCurrentCourseStreamCourse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseStreamCourse relation
 * @method     ChildCourseQuery innerJoinCurrentCourseStreamCourse($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseStreamCourse relation
 *
 * @method     ChildCourseQuery joinWithCurrentCourseStreamCourse($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseStreamCourse relation
 *
 * @method     ChildCourseQuery leftJoinWithCurrentCourseStreamCourse() Adds a LEFT JOIN clause and with to the query using the CurrentCourseStreamCourse relation
 * @method     ChildCourseQuery rightJoinWithCurrentCourseStreamCourse() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseStreamCourse relation
 * @method     ChildCourseQuery innerJoinWithCurrentCourseStreamCourse() Adds a INNER JOIN clause and with to the query using the CurrentCourseStreamCourse relation
 *
 * @method     ChildCourseQuery leftJoinCurrentCourseCourseSkill($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseCourseSkill relation
 * @method     ChildCourseQuery rightJoinCurrentCourseCourseSkill($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseCourseSkill relation
 * @method     ChildCourseQuery innerJoinCurrentCourseCourseSkill($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseCourseSkill relation
 *
 * @method     ChildCourseQuery joinWithCurrentCourseCourseSkill($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseCourseSkill relation
 *
 * @method     ChildCourseQuery leftJoinWithCurrentCourseCourseSkill() Adds a LEFT JOIN clause and with to the query using the CurrentCourseCourseSkill relation
 * @method     ChildCourseQuery rightJoinWithCurrentCourseCourseSkill() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseCourseSkill relation
 * @method     ChildCourseQuery innerJoinWithCurrentCourseCourseSkill() Adds a INNER JOIN clause and with to the query using the CurrentCourseCourseSkill relation
 *
 * @method     \Models\ApplicationQuery|\Models\CourseStreamQuery|\Models\CourseSkillQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCourse findOne(ConnectionInterface $con = null) Return the first ChildCourse matching the query
 * @method     ChildCourse findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCourse matching the query, or a new ChildCourse object populated from the query conditions when no match is found
 *
 * @method     ChildCourse findOneById(int $id) Return the first ChildCourse filtered by the id column
 * @method     ChildCourse findOneByName(string $name) Return the first ChildCourse filtered by the name column
 * @method     ChildCourse findOneByDescription(string $description) Return the first ChildCourse filtered by the description column
 * @method     ChildCourse findOneByAltUrl(string $alt_url) Return the first ChildCourse filtered by the alt_url column
 * @method     ChildCourse findOneByLogoName(string $logo_name) Return the first ChildCourse filtered by the logo_name column
 * @method     ChildCourse findOneByCoverName(string $cover_name) Return the first ChildCourse filtered by the cover_name column
 * @method     ChildCourse findOneByTitle(string $title) Return the first ChildCourse filtered by the title column
 * @method     ChildCourse findOneByContext(string $context) Return the first ChildCourse filtered by the context column
 * @method     ChildCourse findOneByNotes(string $notes) Return the first ChildCourse filtered by the notes column
 * @method     ChildCourse findOneByUseNotes(string $use_notes) Return the first ChildCourse filtered by the use_notes column
 * @method     ChildCourse findOneByUses(\Core\Course\Uses $uses) Return the first ChildCourse filtered by the uses column
 * @method     ChildCourse findOneByMetaDescription(string $meta_description) Return the first ChildCourse filtered by the meta_description column
 * @method     ChildCourse findOneByMetaKeywords(string $meta_keywords) Return the first ChildCourse filtered by the meta_keywords column
 * @method     ChildCourse findOneByCreatedAt(string $created_at) Return the first ChildCourse filtered by the created_at column
 * @method     ChildCourse findOneByUpdatedAt(string $updated_at) Return the first ChildCourse filtered by the updated_at column *

 * @method     ChildCourse requirePk($key, ConnectionInterface $con = null) Return the ChildCourse by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOne(ConnectionInterface $con = null) Return the first ChildCourse matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCourse requireOneById(int $id) Return the first ChildCourse filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByName(string $name) Return the first ChildCourse filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByDescription(string $description) Return the first ChildCourse filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByAltUrl(string $alt_url) Return the first ChildCourse filtered by the alt_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByLogoName(string $logo_name) Return the first ChildCourse filtered by the logo_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByCoverName(string $cover_name) Return the first ChildCourse filtered by the cover_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByTitle(string $title) Return the first ChildCourse filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByContext(string $context) Return the first ChildCourse filtered by the context column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByNotes(string $notes) Return the first ChildCourse filtered by the notes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByUseNotes(string $use_notes) Return the first ChildCourse filtered by the use_notes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByUses(\Core\Course\Uses $uses) Return the first ChildCourse filtered by the uses column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByMetaDescription(string $meta_description) Return the first ChildCourse filtered by the meta_description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByMetaKeywords(string $meta_keywords) Return the first ChildCourse filtered by the meta_keywords column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByCreatedAt(string $created_at) Return the first ChildCourse filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourse requireOneByUpdatedAt(string $updated_at) Return the first ChildCourse filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCourse[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCourse objects based on current ModelCriteria
 * @method     ChildCourse[]|ObjectCollection findById(int $id) Return ChildCourse objects filtered by the id column
 * @method     ChildCourse[]|ObjectCollection findByName(string $name) Return ChildCourse objects filtered by the name column
 * @method     ChildCourse[]|ObjectCollection findByDescription(string $description) Return ChildCourse objects filtered by the description column
 * @method     ChildCourse[]|ObjectCollection findByAltUrl(string $alt_url) Return ChildCourse objects filtered by the alt_url column
 * @method     ChildCourse[]|ObjectCollection findByLogoName(string $logo_name) Return ChildCourse objects filtered by the logo_name column
 * @method     ChildCourse[]|ObjectCollection findByCoverName(string $cover_name) Return ChildCourse objects filtered by the cover_name column
 * @method     ChildCourse[]|ObjectCollection findByTitle(string $title) Return ChildCourse objects filtered by the title column
 * @method     ChildCourse[]|ObjectCollection findByContext(string $context) Return ChildCourse objects filtered by the context column
 * @method     ChildCourse[]|ObjectCollection findByNotes(string $notes) Return ChildCourse objects filtered by the notes column
 * @method     ChildCourse[]|ObjectCollection findByUseNotes(string $use_notes) Return ChildCourse objects filtered by the use_notes column
 * @method     ChildCourse[]|ObjectCollection findByUses(\Core\Course\Uses $uses) Return ChildCourse objects filtered by the uses column
 * @method     ChildCourse[]|ObjectCollection findByMetaDescription(string $meta_description) Return ChildCourse objects filtered by the meta_description column
 * @method     ChildCourse[]|ObjectCollection findByMetaKeywords(string $meta_keywords) Return ChildCourse objects filtered by the meta_keywords column
 * @method     ChildCourse[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildCourse objects filtered by the created_at column
 * @method     ChildCourse[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildCourse objects filtered by the updated_at column
 * @method     ChildCourse[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CourseQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\CourseQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Course', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCourseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCourseQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCourseQuery) {
            return $criteria;
        }
        $query = new ChildCourseQuery();
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
     * @return ChildCourse|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CourseTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CourseTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCourse A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `description`, `alt_url`, `logo_name`, `cover_name`, `title`, `context`, `notes`, `use_notes`, `uses`, `meta_description`, `meta_keywords`, `created_at`, `updated_at` FROM `course` WHERE `id` = :p0';
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
            /** @var ChildCourse $obj */
            $obj = new ChildCourse();
            $obj->hydrate($row);
            CourseTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCourse|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CourseTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CourseTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CourseTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CourseTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the alt_url column
     *
     * Example usage:
     * <code>
     * $query->filterByAltUrl('fooValue');   // WHERE alt_url = 'fooValue'
     * $query->filterByAltUrl('%fooValue%', Criteria::LIKE); // WHERE alt_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $altUrl The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByAltUrl($altUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($altUrl)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_ALT_URL, $altUrl, $comparison);
    }

    /**
     * Filter the query on the logo_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLogoName('fooValue');   // WHERE logo_name = 'fooValue'
     * $query->filterByLogoName('%fooValue%', Criteria::LIKE); // WHERE logo_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $logoName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByLogoName($logoName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logoName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_LOGO_NAME, $logoName, $comparison);
    }

    /**
     * Filter the query on the cover_name column
     *
     * Example usage:
     * <code>
     * $query->filterByCoverName('fooValue');   // WHERE cover_name = 'fooValue'
     * $query->filterByCoverName('%fooValue%', Criteria::LIKE); // WHERE cover_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $coverName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByCoverName($coverName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($coverName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_COVER_NAME, $coverName, $comparison);
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
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the context column
     *
     * Example usage:
     * <code>
     * $query->filterByContext('fooValue');   // WHERE context = 'fooValue'
     * $query->filterByContext('%fooValue%', Criteria::LIKE); // WHERE context LIKE '%fooValue%'
     * </code>
     *
     * @param     string $context The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByContext($context = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($context)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_CONTEXT, $context, $comparison);
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
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByNotes($notes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_NOTES, $notes, $comparison);
    }

    /**
     * Filter the query on the use_notes column
     *
     * Example usage:
     * <code>
     * $query->filterByUseNotes('fooValue');   // WHERE use_notes = 'fooValue'
     * $query->filterByUseNotes('%fooValue%', Criteria::LIKE); // WHERE use_notes LIKE '%fooValue%'
     * </code>
     *
     * @param     string $useNotes The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByUseNotes($useNotes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($useNotes)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_USE_NOTES, $useNotes, $comparison);
    }

    /**
     * Filter the query on the uses column
     *
     * @param     mixed $uses The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByUses($uses = null, $comparison = null)
    {
        if (is_object($uses)) {
            $uses = serialize($uses);
        }

        return $this->addUsingAlias(CourseTableMap::COL_USES, $uses, $comparison);
    }

    /**
     * Filter the query on the meta_description column
     *
     * Example usage:
     * <code>
     * $query->filterByMetaDescription('fooValue');   // WHERE meta_description = 'fooValue'
     * $query->filterByMetaDescription('%fooValue%', Criteria::LIKE); // WHERE meta_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $metaDescription The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByMetaDescription($metaDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($metaDescription)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_META_DESCRIPTION, $metaDescription, $comparison);
    }

    /**
     * Filter the query on the meta_keywords column
     *
     * Example usage:
     * <code>
     * $query->filterByMetaKeywords('fooValue');   // WHERE meta_keywords = 'fooValue'
     * $query->filterByMetaKeywords('%fooValue%', Criteria::LIKE); // WHERE meta_keywords LIKE '%fooValue%'
     * </code>
     *
     * @param     string $metaKeywords The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByMetaKeywords($metaKeywords = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($metaKeywords)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_META_KEYWORDS, $metaKeywords, $comparison);
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
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CourseTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CourseTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CourseTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CourseTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Application object
     *
     * @param \Models\Application|ObjectCollection $application the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByCurrentApplicationCourse($application, $comparison = null)
    {
        if ($application instanceof \Models\Application) {
            return $this
                ->addUsingAlias(CourseTableMap::COL_ID, $application->getCurrentCourseId(), $comparison);
        } elseif ($application instanceof ObjectCollection) {
            return $this
                ->useCurrentApplicationCourseQuery()
                ->filterByPrimaryKeys($application->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentApplicationCourse() only accepts arguments of type \Models\Application or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentApplicationCourse relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function joinCurrentApplicationCourse($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentApplicationCourse');

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
            $this->addJoinObject($join, 'CurrentApplicationCourse');
        }

        return $this;
    }

    /**
     * Use the CurrentApplicationCourse relation Application object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\ApplicationQuery A secondary query class using the current class as primary query
     */
    public function useCurrentApplicationCourseQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentApplicationCourse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentApplicationCourse', '\Models\ApplicationQuery');
    }

    /**
     * Filter the query by a related \Models\CourseStream object
     *
     * @param \Models\CourseStream|ObjectCollection $courseStream the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamCourse($courseStream, $comparison = null)
    {
        if ($courseStream instanceof \Models\CourseStream) {
            return $this
                ->addUsingAlias(CourseTableMap::COL_ID, $courseStream->getCurrentCourseId(), $comparison);
        } elseif ($courseStream instanceof ObjectCollection) {
            return $this
                ->useCurrentCourseStreamCourseQuery()
                ->filterByPrimaryKeys($courseStream->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentCourseStreamCourse() only accepts arguments of type \Models\CourseStream or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseStreamCourse relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function joinCurrentCourseStreamCourse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseStreamCourse');

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
            $this->addJoinObject($join, 'CurrentCourseStreamCourse');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseStreamCourse relation CourseStream object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseStreamQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseStreamCourseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentCourseStreamCourse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseStreamCourse', '\Models\CourseStreamQuery');
    }

    /**
     * Filter the query by a related \Models\CourseSkill object
     *
     * @param \Models\CourseSkill|ObjectCollection $courseSkill the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseCourseSkill($courseSkill, $comparison = null)
    {
        if ($courseSkill instanceof \Models\CourseSkill) {
            return $this
                ->addUsingAlias(CourseTableMap::COL_ID, $courseSkill->getCurrentStatusId(), $comparison);
        } elseif ($courseSkill instanceof ObjectCollection) {
            return $this
                ->useCurrentCourseCourseSkillQuery()
                ->filterByPrimaryKeys($courseSkill->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentCourseCourseSkill() only accepts arguments of type \Models\CourseSkill or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseCourseSkill relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function joinCurrentCourseCourseSkill($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseCourseSkill');

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
            $this->addJoinObject($join, 'CurrentCourseCourseSkill');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseCourseSkill relation CourseSkill object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseSkillQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseCourseSkillQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentCourseCourseSkill($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseCourseSkill', '\Models\CourseSkillQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCourse $course Object to remove from the list of results
     *
     * @return $this|ChildCourseQuery The current query, for fluid interface
     */
    public function prune($course = null)
    {
        if ($course) {
            $this->addUsingAlias(CourseTableMap::COL_ID, $course->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the course table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CourseTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CourseTableMap::clearInstancePool();
            CourseTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CourseTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CourseTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CourseTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildCourseQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CourseTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildCourseQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CourseTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildCourseQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CourseTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildCourseQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CourseTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildCourseQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CourseTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildCourseQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CourseTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CourseTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(CourseTableMap::DATABASE_NAME);

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

} // CourseQuery
