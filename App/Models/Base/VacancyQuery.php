<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Vacancy as ChildVacancy;
use Models\VacancyQuery as ChildVacancyQuery;
use Models\Map\VacancyTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'vacancy' table.
 *
 *
 *
 * @method     ChildVacancyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildVacancyQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildVacancyQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildVacancyQuery orderByContext($order = Criteria::ASC) Order by the context column
 * @method     ChildVacancyQuery orderByAltUrl($order = Criteria::ASC) Order by the alt_url column
 * @method     ChildVacancyQuery orderByLogoName($order = Criteria::ASC) Order by the logo_name column
 * @method     ChildVacancyQuery orderByCurrentVacancySalaryId($order = Criteria::ASC) Order by the vacancy_salary_id column
 * @method     ChildVacancyQuery orderByMetaDescription($order = Criteria::ASC) Order by the meta_description column
 * @method     ChildVacancyQuery orderByMetaKeywords($order = Criteria::ASC) Order by the meta_keywords column
 * @method     ChildVacancyQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method     ChildVacancyQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildVacancyQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildVacancyQuery groupById() Group by the id column
 * @method     ChildVacancyQuery groupByName() Group by the name column
 * @method     ChildVacancyQuery groupByDescription() Group by the description column
 * @method     ChildVacancyQuery groupByContext() Group by the context column
 * @method     ChildVacancyQuery groupByAltUrl() Group by the alt_url column
 * @method     ChildVacancyQuery groupByLogoName() Group by the logo_name column
 * @method     ChildVacancyQuery groupByCurrentVacancySalaryId() Group by the vacancy_salary_id column
 * @method     ChildVacancyQuery groupByMetaDescription() Group by the meta_description column
 * @method     ChildVacancyQuery groupByMetaKeywords() Group by the meta_keywords column
 * @method     ChildVacancyQuery groupBySortableRank() Group by the sortable_rank column
 * @method     ChildVacancyQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildVacancyQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildVacancyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildVacancyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildVacancyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildVacancyQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildVacancyQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildVacancyQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildVacancyQuery leftJoinCurrentVacancyVacancySalary($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentVacancyVacancySalary relation
 * @method     ChildVacancyQuery rightJoinCurrentVacancyVacancySalary($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentVacancyVacancySalary relation
 * @method     ChildVacancyQuery innerJoinCurrentVacancyVacancySalary($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentVacancyVacancySalary relation
 *
 * @method     ChildVacancyQuery joinWithCurrentVacancyVacancySalary($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentVacancyVacancySalary relation
 *
 * @method     ChildVacancyQuery leftJoinWithCurrentVacancyVacancySalary() Adds a LEFT JOIN clause and with to the query using the CurrentVacancyVacancySalary relation
 * @method     ChildVacancyQuery rightJoinWithCurrentVacancyVacancySalary() Adds a RIGHT JOIN clause and with to the query using the CurrentVacancyVacancySalary relation
 * @method     ChildVacancyQuery innerJoinWithCurrentVacancyVacancySalary() Adds a INNER JOIN clause and with to the query using the CurrentVacancyVacancySalary relation
 *
 * @method     \Models\VacancySalaryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildVacancy findOne(ConnectionInterface $con = null) Return the first ChildVacancy matching the query
 * @method     ChildVacancy findOneOrCreate(ConnectionInterface $con = null) Return the first ChildVacancy matching the query, or a new ChildVacancy object populated from the query conditions when no match is found
 *
 * @method     ChildVacancy findOneById(int $id) Return the first ChildVacancy filtered by the id column
 * @method     ChildVacancy findOneByName(string $name) Return the first ChildVacancy filtered by the name column
 * @method     ChildVacancy findOneByDescription(string $description) Return the first ChildVacancy filtered by the description column
 * @method     ChildVacancy findOneByContext(string $context) Return the first ChildVacancy filtered by the context column
 * @method     ChildVacancy findOneByAltUrl(string $alt_url) Return the first ChildVacancy filtered by the alt_url column
 * @method     ChildVacancy findOneByLogoName(string $logo_name) Return the first ChildVacancy filtered by the logo_name column
 * @method     ChildVacancy findOneByCurrentVacancySalaryId(int $vacancy_salary_id) Return the first ChildVacancy filtered by the vacancy_salary_id column
 * @method     ChildVacancy findOneByMetaDescription(string $meta_description) Return the first ChildVacancy filtered by the meta_description column
 * @method     ChildVacancy findOneByMetaKeywords(string $meta_keywords) Return the first ChildVacancy filtered by the meta_keywords column
 * @method     ChildVacancy findOneBySortableRank(int $sortable_rank) Return the first ChildVacancy filtered by the sortable_rank column
 * @method     ChildVacancy findOneByCreatedAt(string $created_at) Return the first ChildVacancy filtered by the created_at column
 * @method     ChildVacancy findOneByUpdatedAt(string $updated_at) Return the first ChildVacancy filtered by the updated_at column *

 * @method     ChildVacancy requirePk($key, ConnectionInterface $con = null) Return the ChildVacancy by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOne(ConnectionInterface $con = null) Return the first ChildVacancy matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildVacancy requireOneById(int $id) Return the first ChildVacancy filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByName(string $name) Return the first ChildVacancy filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByDescription(string $description) Return the first ChildVacancy filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByContext(string $context) Return the first ChildVacancy filtered by the context column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByAltUrl(string $alt_url) Return the first ChildVacancy filtered by the alt_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByLogoName(string $logo_name) Return the first ChildVacancy filtered by the logo_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByCurrentVacancySalaryId(int $vacancy_salary_id) Return the first ChildVacancy filtered by the vacancy_salary_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByMetaDescription(string $meta_description) Return the first ChildVacancy filtered by the meta_description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByMetaKeywords(string $meta_keywords) Return the first ChildVacancy filtered by the meta_keywords column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneBySortableRank(int $sortable_rank) Return the first ChildVacancy filtered by the sortable_rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByCreatedAt(string $created_at) Return the first ChildVacancy filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVacancy requireOneByUpdatedAt(string $updated_at) Return the first ChildVacancy filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildVacancy[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildVacancy objects based on current ModelCriteria
 * @method     ChildVacancy[]|ObjectCollection findById(int $id) Return ChildVacancy objects filtered by the id column
 * @method     ChildVacancy[]|ObjectCollection findByName(string $name) Return ChildVacancy objects filtered by the name column
 * @method     ChildVacancy[]|ObjectCollection findByDescription(string $description) Return ChildVacancy objects filtered by the description column
 * @method     ChildVacancy[]|ObjectCollection findByContext(string $context) Return ChildVacancy objects filtered by the context column
 * @method     ChildVacancy[]|ObjectCollection findByAltUrl(string $alt_url) Return ChildVacancy objects filtered by the alt_url column
 * @method     ChildVacancy[]|ObjectCollection findByLogoName(string $logo_name) Return ChildVacancy objects filtered by the logo_name column
 * @method     ChildVacancy[]|ObjectCollection findByCurrentVacancySalaryId(int $vacancy_salary_id) Return ChildVacancy objects filtered by the vacancy_salary_id column
 * @method     ChildVacancy[]|ObjectCollection findByMetaDescription(string $meta_description) Return ChildVacancy objects filtered by the meta_description column
 * @method     ChildVacancy[]|ObjectCollection findByMetaKeywords(string $meta_keywords) Return ChildVacancy objects filtered by the meta_keywords column
 * @method     ChildVacancy[]|ObjectCollection findBySortableRank(int $sortable_rank) Return ChildVacancy objects filtered by the sortable_rank column
 * @method     ChildVacancy[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildVacancy objects filtered by the created_at column
 * @method     ChildVacancy[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildVacancy objects filtered by the updated_at column
 * @method     ChildVacancy[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class VacancyQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\VacancyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Vacancy', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildVacancyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildVacancyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildVacancyQuery) {
            return $criteria;
        }
        $query = new ChildVacancyQuery();
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
     * @return ChildVacancy|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(VacancyTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = VacancyTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildVacancy A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, description, context, alt_url, logo_name, vacancy_salary_id, meta_description, meta_keywords, sortable_rank, created_at, updated_at FROM vacancy WHERE id = :p0';
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
            /** @var ChildVacancy $obj */
            $obj = new ChildVacancy();
            $obj->hydrate($row);
            VacancyTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildVacancy|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(VacancyTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(VacancyTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(VacancyTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(VacancyTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByContext($context = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($context)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_CONTEXT, $context, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByAltUrl($altUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($altUrl)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_ALT_URL, $altUrl, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByLogoName($logoName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logoName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_LOGO_NAME, $logoName, $comparison);
    }

    /**
     * Filter the query on the vacancy_salary_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentVacancySalaryId(1234); // WHERE vacancy_salary_id = 1234
     * $query->filterByCurrentVacancySalaryId(array(12, 34)); // WHERE vacancy_salary_id IN (12, 34)
     * $query->filterByCurrentVacancySalaryId(array('min' => 12)); // WHERE vacancy_salary_id > 12
     * </code>
     *
     * @see       filterByCurrentVacancyVacancySalary()
     *
     * @param     mixed $currentVacancySalaryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByCurrentVacancySalaryId($currentVacancySalaryId = null, $comparison = null)
    {
        if (is_array($currentVacancySalaryId)) {
            $useMinMax = false;
            if (isset($currentVacancySalaryId['min'])) {
                $this->addUsingAlias(VacancyTableMap::COL_VACANCY_SALARY_ID, $currentVacancySalaryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentVacancySalaryId['max'])) {
                $this->addUsingAlias(VacancyTableMap::COL_VACANCY_SALARY_ID, $currentVacancySalaryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_VACANCY_SALARY_ID, $currentVacancySalaryId, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByMetaDescription($metaDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($metaDescription)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_META_DESCRIPTION, $metaDescription, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByMetaKeywords($metaKeywords = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($metaKeywords)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_META_KEYWORDS, $metaKeywords, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(VacancyTableMap::COL_SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(VacancyTableMap::COL_SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_SORTABLE_RANK, $sortableRank, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(VacancyTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(VacancyTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(VacancyTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(VacancyTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VacancyTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\VacancySalary object
     *
     * @param \Models\VacancySalary|ObjectCollection $vacancySalary The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByCurrentVacancyVacancySalary($vacancySalary, $comparison = null)
    {
        if ($vacancySalary instanceof \Models\VacancySalary) {
            return $this
                ->addUsingAlias(VacancyTableMap::COL_VACANCY_SALARY_ID, $vacancySalary->getId(), $comparison);
        } elseif ($vacancySalary instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(VacancyTableMap::COL_VACANCY_SALARY_ID, $vacancySalary->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentVacancyVacancySalary() only accepts arguments of type \Models\VacancySalary or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentVacancyVacancySalary relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function joinCurrentVacancyVacancySalary($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentVacancyVacancySalary');

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
            $this->addJoinObject($join, 'CurrentVacancyVacancySalary');
        }

        return $this;
    }

    /**
     * Use the CurrentVacancyVacancySalary relation VacancySalary object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\VacancySalaryQuery A secondary query class using the current class as primary query
     */
    public function useCurrentVacancyVacancySalaryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentVacancyVacancySalary($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentVacancyVacancySalary', '\Models\VacancySalaryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildVacancy $vacancy Object to remove from the list of results
     *
     * @return $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function prune($vacancy = null)
    {
        if ($vacancy) {
            $this->addUsingAlias(VacancyTableMap::COL_ID, $vacancy->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the vacancy table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(VacancyTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            VacancyTableMap::clearInstancePool();
            VacancyTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(VacancyTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(VacancyTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            VacancyTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            VacancyTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // sortable behavior

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    ChildVacancyQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {

        return $this
            ->addUsingAlias(VacancyTableMap::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(VacancyTableMap::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(VacancyTableMap::RANK_COL));
                break;
            default:
                throw new \Propel\Runtime\Exception\PropelException('ChildVacancyQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return    ChildVacancy
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
            $con = Propel::getServiceContainer()->getReadConnection(VacancyTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . VacancyTableMap::RANK_COL . ')');
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
            $con = Propel::getConnection(VacancyTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . VacancyTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return ChildVacancy
     */
    static public function retrieveByRank($rank, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(VacancyTableMap::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(VacancyTableMap::RANK_COL, $rank);

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
            $con = Propel::getServiceContainer()->getReadConnection(VacancyTableMap::DATABASE_NAME);
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
            $con = Propel::getServiceContainer()->getReadConnection(VacancyTableMap::DATABASE_NAME);
        }

        if (null === $criteria) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if (Criteria::ASC == $order) {
            $criteria->addAscendingOrderByColumn(VacancyTableMap::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(VacancyTableMap::RANK_COL);
        }

        return ChildVacancyQuery::create(null, $criteria)->find($con);
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
            $con = Propel::getServiceContainer()->getWriteConnection(VacancyTableMap::DATABASE_NAME);
        }

        $whereCriteria = new Criteria(VacancyTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(VacancyTableMap::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(VacancyTableMap::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(VacancyTableMap::DATABASE_NAME);
        $valuesCriteria->add(VacancyTableMap::RANK_COL, array('raw' => VacancyTableMap::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
        VacancyTableMap::clearInstancePool();
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(VacancyTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(VacancyTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(VacancyTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(VacancyTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(VacancyTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildVacancyQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(VacancyTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(VacancyTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(VacancyTableMap::DATABASE_NAME);

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

} // VacancyQuery
