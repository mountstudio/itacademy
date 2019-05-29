<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\StaticPage as ChildStaticPage;
use Models\StaticPageQuery as ChildStaticPageQuery;
use Models\Map\StaticPageTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'static_page' table.
 *
 *
 *
 * @method     ChildStaticPageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildStaticPageQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildStaticPageQuery orderByLogoName($order = Criteria::ASC) Order by the logo_name column
 * @method     ChildStaticPageQuery orderByCoverName($order = Criteria::ASC) Order by the cover_name column
 * @method     ChildStaticPageQuery orderByAltUrl($order = Criteria::ASC) Order by the alt_url column
 * @method     ChildStaticPageQuery orderByAvailable($order = Criteria::ASC) Order by the is_available column
 * @method     ChildStaticPageQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     ChildStaticPageQuery orderByContext($order = Criteria::ASC) Order by the context column
 * @method     ChildStaticPageQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method     ChildStaticPageQuery orderByMetaDescription($order = Criteria::ASC) Order by the meta_description column
 * @method     ChildStaticPageQuery orderByMetaKeywords($order = Criteria::ASC) Order by the meta_keywords column
 * @method     ChildStaticPageQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildStaticPageQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildStaticPageQuery groupById() Group by the id column
 * @method     ChildStaticPageQuery groupByTitle() Group by the title column
 * @method     ChildStaticPageQuery groupByLogoName() Group by the logo_name column
 * @method     ChildStaticPageQuery groupByCoverName() Group by the cover_name column
 * @method     ChildStaticPageQuery groupByAltUrl() Group by the alt_url column
 * @method     ChildStaticPageQuery groupByAvailable() Group by the is_available column
 * @method     ChildStaticPageQuery groupByContent() Group by the content column
 * @method     ChildStaticPageQuery groupByContext() Group by the context column
 * @method     ChildStaticPageQuery groupByNotes() Group by the notes column
 * @method     ChildStaticPageQuery groupByMetaDescription() Group by the meta_description column
 * @method     ChildStaticPageQuery groupByMetaKeywords() Group by the meta_keywords column
 * @method     ChildStaticPageQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildStaticPageQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildStaticPageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildStaticPageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildStaticPageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildStaticPageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildStaticPageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildStaticPageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildStaticPage findOne(ConnectionInterface $con = null) Return the first ChildStaticPage matching the query
 * @method     ChildStaticPage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildStaticPage matching the query, or a new ChildStaticPage object populated from the query conditions when no match is found
 *
 * @method     ChildStaticPage findOneById(int $id) Return the first ChildStaticPage filtered by the id column
 * @method     ChildStaticPage findOneByTitle(string $title) Return the first ChildStaticPage filtered by the title column
 * @method     ChildStaticPage findOneByLogoName(string $logo_name) Return the first ChildStaticPage filtered by the logo_name column
 * @method     ChildStaticPage findOneByCoverName(string $cover_name) Return the first ChildStaticPage filtered by the cover_name column
 * @method     ChildStaticPage findOneByAltUrl(string $alt_url) Return the first ChildStaticPage filtered by the alt_url column
 * @method     ChildStaticPage findOneByAvailable(boolean $is_available) Return the first ChildStaticPage filtered by the is_available column
 * @method     ChildStaticPage findOneByContent(string $content) Return the first ChildStaticPage filtered by the content column
 * @method     ChildStaticPage findOneByContext(string $context) Return the first ChildStaticPage filtered by the context column
 * @method     ChildStaticPage findOneByNotes(string $notes) Return the first ChildStaticPage filtered by the notes column
 * @method     ChildStaticPage findOneByMetaDescription(string $meta_description) Return the first ChildStaticPage filtered by the meta_description column
 * @method     ChildStaticPage findOneByMetaKeywords(string $meta_keywords) Return the first ChildStaticPage filtered by the meta_keywords column
 * @method     ChildStaticPage findOneByCreatedAt(string $created_at) Return the first ChildStaticPage filtered by the created_at column
 * @method     ChildStaticPage findOneByUpdatedAt(string $updated_at) Return the first ChildStaticPage filtered by the updated_at column *

 * @method     ChildStaticPage requirePk($key, ConnectionInterface $con = null) Return the ChildStaticPage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOne(ConnectionInterface $con = null) Return the first ChildStaticPage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStaticPage requireOneById(int $id) Return the first ChildStaticPage filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByTitle(string $title) Return the first ChildStaticPage filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByLogoName(string $logo_name) Return the first ChildStaticPage filtered by the logo_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByCoverName(string $cover_name) Return the first ChildStaticPage filtered by the cover_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByAltUrl(string $alt_url) Return the first ChildStaticPage filtered by the alt_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByAvailable(boolean $is_available) Return the first ChildStaticPage filtered by the is_available column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByContent(string $content) Return the first ChildStaticPage filtered by the content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByContext(string $context) Return the first ChildStaticPage filtered by the context column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByNotes(string $notes) Return the first ChildStaticPage filtered by the notes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByMetaDescription(string $meta_description) Return the first ChildStaticPage filtered by the meta_description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByMetaKeywords(string $meta_keywords) Return the first ChildStaticPage filtered by the meta_keywords column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByCreatedAt(string $created_at) Return the first ChildStaticPage filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStaticPage requireOneByUpdatedAt(string $updated_at) Return the first ChildStaticPage filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStaticPage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildStaticPage objects based on current ModelCriteria
 * @method     ChildStaticPage[]|ObjectCollection findById(int $id) Return ChildStaticPage objects filtered by the id column
 * @method     ChildStaticPage[]|ObjectCollection findByTitle(string $title) Return ChildStaticPage objects filtered by the title column
 * @method     ChildStaticPage[]|ObjectCollection findByLogoName(string $logo_name) Return ChildStaticPage objects filtered by the logo_name column
 * @method     ChildStaticPage[]|ObjectCollection findByCoverName(string $cover_name) Return ChildStaticPage objects filtered by the cover_name column
 * @method     ChildStaticPage[]|ObjectCollection findByAltUrl(string $alt_url) Return ChildStaticPage objects filtered by the alt_url column
 * @method     ChildStaticPage[]|ObjectCollection findByAvailable(boolean $is_available) Return ChildStaticPage objects filtered by the is_available column
 * @method     ChildStaticPage[]|ObjectCollection findByContent(string $content) Return ChildStaticPage objects filtered by the content column
 * @method     ChildStaticPage[]|ObjectCollection findByContext(string $context) Return ChildStaticPage objects filtered by the context column
 * @method     ChildStaticPage[]|ObjectCollection findByNotes(string $notes) Return ChildStaticPage objects filtered by the notes column
 * @method     ChildStaticPage[]|ObjectCollection findByMetaDescription(string $meta_description) Return ChildStaticPage objects filtered by the meta_description column
 * @method     ChildStaticPage[]|ObjectCollection findByMetaKeywords(string $meta_keywords) Return ChildStaticPage objects filtered by the meta_keywords column
 * @method     ChildStaticPage[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildStaticPage objects filtered by the created_at column
 * @method     ChildStaticPage[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildStaticPage objects filtered by the updated_at column
 * @method     ChildStaticPage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class StaticPageQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\StaticPageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\StaticPage', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildStaticPageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildStaticPageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildStaticPageQuery) {
            return $criteria;
        }
        $query = new ChildStaticPageQuery();
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
     * @return ChildStaticPage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(StaticPageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = StaticPageTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildStaticPage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `title`, `logo_name`, `cover_name`, `alt_url`, `is_available`, `content`, `context`, `notes`, `meta_description`, `meta_keywords`, `created_at`, `updated_at` FROM `static_page` WHERE `id` = :p0';
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
            /** @var ChildStaticPage $obj */
            $obj = new ChildStaticPage();
            $obj->hydrate($row);
            StaticPageTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildStaticPage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StaticPageTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StaticPageTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StaticPageTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StaticPageTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByLogoName($logoName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logoName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_LOGO_NAME, $logoName, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByCoverName($coverName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($coverName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_COVER_NAME, $coverName, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByAltUrl($altUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($altUrl)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_ALT_URL, $altUrl, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByAvailable($available = null, $comparison = null)
    {
        if (is_string($available)) {
            $available = in_array(strtolower($available), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_IS_AVAILABLE, $available, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_CONTENT, $content, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByContext($context = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($context)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_CONTEXT, $context, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByNotes($notes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_NOTES, $notes, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByMetaDescription($metaDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($metaDescription)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_META_DESCRIPTION, $metaDescription, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByMetaKeywords($metaKeywords = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($metaKeywords)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_META_KEYWORDS, $metaKeywords, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StaticPageTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StaticPageTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StaticPageTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StaticPageTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StaticPageTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildStaticPage $staticPage Object to remove from the list of results
     *
     * @return $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function prune($staticPage = null)
    {
        if ($staticPage) {
            $this->addUsingAlias(StaticPageTableMap::COL_ID, $staticPage->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the static_page table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StaticPageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StaticPageTableMap::clearInstancePool();
            StaticPageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(StaticPageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(StaticPageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            StaticPageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            StaticPageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StaticPageTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StaticPageTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StaticPageTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StaticPageTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StaticPageTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildStaticPageQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StaticPageTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(StaticPageTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(StaticPageTableMap::DATABASE_NAME);

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

} // StaticPageQuery
