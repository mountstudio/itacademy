<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Privilege as ChildPrivilege;
use Models\PrivilegeQuery as ChildPrivilegeQuery;
use Models\Map\PrivilegeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'privilege' table.
 *
 *
 *
 * @method     ChildPrivilegeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPrivilegeQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildPrivilegeQuery orderByAlt($order = Criteria::ASC) Order by the alt column
 *
 * @method     ChildPrivilegeQuery groupById() Group by the id column
 * @method     ChildPrivilegeQuery groupByName() Group by the name column
 * @method     ChildPrivilegeQuery groupByAlt() Group by the alt column
 *
 * @method     ChildPrivilegeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPrivilegeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPrivilegeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPrivilegeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPrivilegeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPrivilegeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPrivilegeQuery leftJoinCurrentPrivilegeGroupPrivelege($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentPrivilegeGroupPrivelege relation
 * @method     ChildPrivilegeQuery rightJoinCurrentPrivilegeGroupPrivelege($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentPrivilegeGroupPrivelege relation
 * @method     ChildPrivilegeQuery innerJoinCurrentPrivilegeGroupPrivelege($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentPrivilegeGroupPrivelege relation
 *
 * @method     ChildPrivilegeQuery joinWithCurrentPrivilegeGroupPrivelege($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentPrivilegeGroupPrivelege relation
 *
 * @method     ChildPrivilegeQuery leftJoinWithCurrentPrivilegeGroupPrivelege() Adds a LEFT JOIN clause and with to the query using the CurrentPrivilegeGroupPrivelege relation
 * @method     ChildPrivilegeQuery rightJoinWithCurrentPrivilegeGroupPrivelege() Adds a RIGHT JOIN clause and with to the query using the CurrentPrivilegeGroupPrivelege relation
 * @method     ChildPrivilegeQuery innerJoinWithCurrentPrivilegeGroupPrivelege() Adds a INNER JOIN clause and with to the query using the CurrentPrivilegeGroupPrivelege relation
 *
 * @method     \Models\GroupPrivelegeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPrivilege findOne(ConnectionInterface $con = null) Return the first ChildPrivilege matching the query
 * @method     ChildPrivilege findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPrivilege matching the query, or a new ChildPrivilege object populated from the query conditions when no match is found
 *
 * @method     ChildPrivilege findOneById(int $id) Return the first ChildPrivilege filtered by the id column
 * @method     ChildPrivilege findOneByName(string $name) Return the first ChildPrivilege filtered by the name column
 * @method     ChildPrivilege findOneByAlt(string $alt) Return the first ChildPrivilege filtered by the alt column *

 * @method     ChildPrivilege requirePk($key, ConnectionInterface $con = null) Return the ChildPrivilege by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrivilege requireOne(ConnectionInterface $con = null) Return the first ChildPrivilege matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPrivilege requireOneById(int $id) Return the first ChildPrivilege filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrivilege requireOneByName(string $name) Return the first ChildPrivilege filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrivilege requireOneByAlt(string $alt) Return the first ChildPrivilege filtered by the alt column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPrivilege[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPrivilege objects based on current ModelCriteria
 * @method     ChildPrivilege[]|ObjectCollection findById(int $id) Return ChildPrivilege objects filtered by the id column
 * @method     ChildPrivilege[]|ObjectCollection findByName(string $name) Return ChildPrivilege objects filtered by the name column
 * @method     ChildPrivilege[]|ObjectCollection findByAlt(string $alt) Return ChildPrivilege objects filtered by the alt column
 * @method     ChildPrivilege[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PrivilegeQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\PrivilegeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Privilege', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPrivilegeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPrivilegeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPrivilegeQuery) {
            return $criteria;
        }
        $query = new ChildPrivilegeQuery();
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
     * @return ChildPrivilege|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PrivilegeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PrivilegeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPrivilege A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, alt FROM privilege WHERE id = :p0';
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
            /** @var ChildPrivilege $obj */
            $obj = new ChildPrivilege();
            $obj->hydrate($row);
            PrivilegeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPrivilege|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPrivilegeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PrivilegeTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPrivilegeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PrivilegeTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPrivilegeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PrivilegeTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PrivilegeTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrivilegeTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPrivilegeQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrivilegeTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the alt column
     *
     * Example usage:
     * <code>
     * $query->filterByAlt('fooValue');   // WHERE alt = 'fooValue'
     * $query->filterByAlt('%fooValue%', Criteria::LIKE); // WHERE alt LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alt The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPrivilegeQuery The current query, for fluid interface
     */
    public function filterByAlt($alt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alt)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PrivilegeTableMap::COL_ALT, $alt, $comparison);
    }

    /**
     * Filter the query by a related \Models\GroupPrivelege object
     *
     * @param \Models\GroupPrivelege|ObjectCollection $groupPrivelege the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPrivilegeQuery The current query, for fluid interface
     */
    public function filterByCurrentPrivilegeGroupPrivelege($groupPrivelege, $comparison = null)
    {
        if ($groupPrivelege instanceof \Models\GroupPrivelege) {
            return $this
                ->addUsingAlias(PrivilegeTableMap::COL_ID, $groupPrivelege->getPrivilegeId(), $comparison);
        } elseif ($groupPrivelege instanceof ObjectCollection) {
            return $this
                ->useCurrentPrivilegeGroupPrivelegeQuery()
                ->filterByPrimaryKeys($groupPrivelege->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentPrivilegeGroupPrivelege() only accepts arguments of type \Models\GroupPrivelege or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentPrivilegeGroupPrivelege relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPrivilegeQuery The current query, for fluid interface
     */
    public function joinCurrentPrivilegeGroupPrivelege($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentPrivilegeGroupPrivelege');

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
            $this->addJoinObject($join, 'CurrentPrivilegeGroupPrivelege');
        }

        return $this;
    }

    /**
     * Use the CurrentPrivilegeGroupPrivelege relation GroupPrivelege object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\GroupPrivelegeQuery A secondary query class using the current class as primary query
     */
    public function useCurrentPrivilegeGroupPrivelegeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentPrivilegeGroupPrivelege($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentPrivilegeGroupPrivelege', '\Models\GroupPrivelegeQuery');
    }

    /**
     * Filter the query by a related Group object
     * using the group_privilege table as cross reference
     *
     * @param Group $group the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPrivilegeQuery The current query, for fluid interface
     */
    public function filterByCurrentGroupGroupPrivelege($group, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCurrentPrivilegeGroupPrivelegeQuery()
            ->filterByCurrentGroupGroupPrivelege($group, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPrivilege $privilege Object to remove from the list of results
     *
     * @return $this|ChildPrivilegeQuery The current query, for fluid interface
     */
    public function prune($privilege = null)
    {
        if ($privilege) {
            $this->addUsingAlias(PrivilegeTableMap::COL_ID, $privilege->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the privilege table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PrivilegeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PrivilegeTableMap::clearInstancePool();
            PrivilegeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PrivilegeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PrivilegeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PrivilegeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PrivilegeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PrivilegeTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(PrivilegeTableMap::DATABASE_NAME);

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

} // PrivilegeQuery
