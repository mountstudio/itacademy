<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\CourseStreamStatus as ChildCourseStreamStatus;
use Models\CourseStreamStatusQuery as ChildCourseStreamStatusQuery;
use Models\Map\CourseStreamStatusTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'course_stream_status' table.
 *
 *
 *
 * @method     ChildCourseStreamStatusQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCourseStreamStatusQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCourseStreamStatusQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildCourseStreamStatusQuery orderByBackgroundColor($order = Criteria::ASC) Order by the background_color column
 * @method     ChildCourseStreamStatusQuery orderByFontColor($order = Criteria::ASC) Order by the font_color column
 * @method     ChildCourseStreamStatusQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method     ChildCourseStreamStatusQuery groupById() Group by the id column
 * @method     ChildCourseStreamStatusQuery groupByName() Group by the name column
 * @method     ChildCourseStreamStatusQuery groupByDescription() Group by the description column
 * @method     ChildCourseStreamStatusQuery groupByBackgroundColor() Group by the background_color column
 * @method     ChildCourseStreamStatusQuery groupByFontColor() Group by the font_color column
 * @method     ChildCourseStreamStatusQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method     ChildCourseStreamStatusQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCourseStreamStatusQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCourseStreamStatusQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCourseStreamStatusQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCourseStreamStatusQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCourseStreamStatusQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCourseStreamStatusQuery leftJoinCurrentCourseStreamCourseStatus($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentCourseStreamCourseStatus relation
 * @method     ChildCourseStreamStatusQuery rightJoinCurrentCourseStreamCourseStatus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentCourseStreamCourseStatus relation
 * @method     ChildCourseStreamStatusQuery innerJoinCurrentCourseStreamCourseStatus($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentCourseStreamCourseStatus relation
 *
 * @method     ChildCourseStreamStatusQuery joinWithCurrentCourseStreamCourseStatus($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentCourseStreamCourseStatus relation
 *
 * @method     ChildCourseStreamStatusQuery leftJoinWithCurrentCourseStreamCourseStatus() Adds a LEFT JOIN clause and with to the query using the CurrentCourseStreamCourseStatus relation
 * @method     ChildCourseStreamStatusQuery rightJoinWithCurrentCourseStreamCourseStatus() Adds a RIGHT JOIN clause and with to the query using the CurrentCourseStreamCourseStatus relation
 * @method     ChildCourseStreamStatusQuery innerJoinWithCurrentCourseStreamCourseStatus() Adds a INNER JOIN clause and with to the query using the CurrentCourseStreamCourseStatus relation
 *
 * @method     \Models\CourseStreamQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCourseStreamStatus findOne(ConnectionInterface $con = null) Return the first ChildCourseStreamStatus matching the query
 * @method     ChildCourseStreamStatus findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCourseStreamStatus matching the query, or a new ChildCourseStreamStatus object populated from the query conditions when no match is found
 *
 * @method     ChildCourseStreamStatus findOneById(int $id) Return the first ChildCourseStreamStatus filtered by the id column
 * @method     ChildCourseStreamStatus findOneByName(string $name) Return the first ChildCourseStreamStatus filtered by the name column
 * @method     ChildCourseStreamStatus findOneByDescription(string $description) Return the first ChildCourseStreamStatus filtered by the description column
 * @method     ChildCourseStreamStatus findOneByBackgroundColor(string $background_color) Return the first ChildCourseStreamStatus filtered by the background_color column
 * @method     ChildCourseStreamStatus findOneByFontColor(string $font_color) Return the first ChildCourseStreamStatus filtered by the font_color column
 * @method     ChildCourseStreamStatus findOneBySortableRank(int $sortable_rank) Return the first ChildCourseStreamStatus filtered by the sortable_rank column *

 * @method     ChildCourseStreamStatus requirePk($key, ConnectionInterface $con = null) Return the ChildCourseStreamStatus by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStreamStatus requireOne(ConnectionInterface $con = null) Return the first ChildCourseStreamStatus matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCourseStreamStatus requireOneById(int $id) Return the first ChildCourseStreamStatus filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStreamStatus requireOneByName(string $name) Return the first ChildCourseStreamStatus filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStreamStatus requireOneByDescription(string $description) Return the first ChildCourseStreamStatus filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStreamStatus requireOneByBackgroundColor(string $background_color) Return the first ChildCourseStreamStatus filtered by the background_color column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStreamStatus requireOneByFontColor(string $font_color) Return the first ChildCourseStreamStatus filtered by the font_color column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCourseStreamStatus requireOneBySortableRank(int $sortable_rank) Return the first ChildCourseStreamStatus filtered by the sortable_rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCourseStreamStatus[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCourseStreamStatus objects based on current ModelCriteria
 * @method     ChildCourseStreamStatus[]|ObjectCollection findById(int $id) Return ChildCourseStreamStatus objects filtered by the id column
 * @method     ChildCourseStreamStatus[]|ObjectCollection findByName(string $name) Return ChildCourseStreamStatus objects filtered by the name column
 * @method     ChildCourseStreamStatus[]|ObjectCollection findByDescription(string $description) Return ChildCourseStreamStatus objects filtered by the description column
 * @method     ChildCourseStreamStatus[]|ObjectCollection findByBackgroundColor(string $background_color) Return ChildCourseStreamStatus objects filtered by the background_color column
 * @method     ChildCourseStreamStatus[]|ObjectCollection findByFontColor(string $font_color) Return ChildCourseStreamStatus objects filtered by the font_color column
 * @method     ChildCourseStreamStatus[]|ObjectCollection findBySortableRank(int $sortable_rank) Return ChildCourseStreamStatus objects filtered by the sortable_rank column
 * @method     ChildCourseStreamStatus[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CourseStreamStatusQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\CourseStreamStatusQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\CourseStreamStatus', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCourseStreamStatusQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCourseStreamStatusQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCourseStreamStatusQuery) {
            return $criteria;
        }
        $query = new ChildCourseStreamStatusQuery();
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
     * @return ChildCourseStreamStatus|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CourseStreamStatusTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CourseStreamStatusTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCourseStreamStatus A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `description`, `background_color`, `font_color`, `sortable_rank` FROM `course_stream_status` WHERE `id` = :p0';
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
            /** @var ChildCourseStreamStatus $obj */
            $obj = new ChildCourseStreamStatus();
            $obj->hydrate($row);
            CourseStreamStatusTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCourseStreamStatus|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CourseStreamStatusTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CourseStreamStatusTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CourseStreamStatusTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CourseStreamStatusTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamStatusTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamStatusTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamStatusTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the background_color column
     *
     * Example usage:
     * <code>
     * $query->filterByBackgroundColor('fooValue');   // WHERE background_color = 'fooValue'
     * $query->filterByBackgroundColor('%fooValue%', Criteria::LIKE); // WHERE background_color LIKE '%fooValue%'
     * </code>
     *
     * @param     string $backgroundColor The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterByBackgroundColor($backgroundColor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($backgroundColor)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamStatusTableMap::COL_BACKGROUND_COLOR, $backgroundColor, $comparison);
    }

    /**
     * Filter the query on the font_color column
     *
     * Example usage:
     * <code>
     * $query->filterByFontColor('fooValue');   // WHERE font_color = 'fooValue'
     * $query->filterByFontColor('%fooValue%', Criteria::LIKE); // WHERE font_color LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fontColor The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterByFontColor($fontColor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fontColor)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamStatusTableMap::COL_FONT_COLOR, $fontColor, $comparison);
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
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(CourseStreamStatusTableMap::COL_SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(CourseStreamStatusTableMap::COL_SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CourseStreamStatusTableMap::COL_SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related \Models\CourseStream object
     *
     * @param \Models\CourseStream|ObjectCollection $courseStream the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterByCurrentCourseStreamCourseStatus($courseStream, $comparison = null)
    {
        if ($courseStream instanceof \Models\CourseStream) {
            return $this
                ->addUsingAlias(CourseStreamStatusTableMap::COL_ID, $courseStream->getCurrentCourseStreamStatusId(), $comparison);
        } elseif ($courseStream instanceof ObjectCollection) {
            return $this
                ->useCurrentCourseStreamCourseStatusQuery()
                ->filterByPrimaryKeys($courseStream->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentCourseStreamCourseStatus() only accepts arguments of type \Models\CourseStream or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentCourseStreamCourseStatus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function joinCurrentCourseStreamCourseStatus($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentCourseStreamCourseStatus');

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
            $this->addJoinObject($join, 'CurrentCourseStreamCourseStatus');
        }

        return $this;
    }

    /**
     * Use the CurrentCourseStreamCourseStatus relation CourseStream object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseStreamQuery A secondary query class using the current class as primary query
     */
    public function useCurrentCourseStreamCourseStatusQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentCourseStreamCourseStatus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentCourseStreamCourseStatus', '\Models\CourseStreamQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCourseStreamStatus $courseStreamStatus Object to remove from the list of results
     *
     * @return $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function prune($courseStreamStatus = null)
    {
        if ($courseStreamStatus) {
            $this->addUsingAlias(CourseStreamStatusTableMap::COL_ID, $courseStreamStatus->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the course_stream_status table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamStatusTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CourseStreamStatusTableMap::clearInstancePool();
            CourseStreamStatusTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamStatusTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CourseStreamStatusTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CourseStreamStatusTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CourseStreamStatusTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // sortable behavior

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {

        return $this
            ->addUsingAlias(CourseStreamStatusTableMap::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    $this|ChildCourseStreamStatusQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(CourseStreamStatusTableMap::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(CourseStreamStatusTableMap::RANK_COL));
                break;
            default:
                throw new \Propel\Runtime\Exception\PropelException('ChildCourseStreamStatusQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return    ChildCourseStreamStatus
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
            $con = Propel::getServiceContainer()->getReadConnection(CourseStreamStatusTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CourseStreamStatusTableMap::RANK_COL . ')');
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
            $con = Propel::getConnection(CourseStreamStatusTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CourseStreamStatusTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return ChildCourseStreamStatus
     */
    static public function retrieveByRank($rank, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CourseStreamStatusTableMap::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(CourseStreamStatusTableMap::RANK_COL, $rank);

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
            $con = Propel::getServiceContainer()->getReadConnection(CourseStreamStatusTableMap::DATABASE_NAME);
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
            $con = Propel::getServiceContainer()->getReadConnection(CourseStreamStatusTableMap::DATABASE_NAME);
        }

        if (null === $criteria) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if (Criteria::ASC == $order) {
            $criteria->addAscendingOrderByColumn(CourseStreamStatusTableMap::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(CourseStreamStatusTableMap::RANK_COL);
        }

        return ChildCourseStreamStatusQuery::create(null, $criteria)->find($con);
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
            $con = Propel::getServiceContainer()->getWriteConnection(CourseStreamStatusTableMap::DATABASE_NAME);
        }

        $whereCriteria = new Criteria(CourseStreamStatusTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(CourseStreamStatusTableMap::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(CourseStreamStatusTableMap::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(CourseStreamStatusTableMap::DATABASE_NAME);
        $valuesCriteria->add(CourseStreamStatusTableMap::RANK_COL, array('raw' => CourseStreamStatusTableMap::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
        CourseStreamStatusTableMap::clearInstancePool();
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CourseStreamStatusTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(CourseStreamStatusTableMap::DATABASE_NAME);

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

} // CourseStreamStatusQuery
