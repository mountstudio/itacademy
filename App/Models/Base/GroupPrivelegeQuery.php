<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\GroupPrivelege as ChildGroupPrivelege;
use Models\GroupPrivelegeQuery as ChildGroupPrivelegeQuery;
use Models\Map\GroupPrivelegeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'group_privilege' table.
 *
 *
 *
 * @method     ChildGroupPrivelegeQuery orderByGroupId($order = Criteria::ASC) Order by the group_id column
 * @method     ChildGroupPrivelegeQuery orderByPrivilegeId($order = Criteria::ASC) Order by the privilege_id column
 * @method     ChildGroupPrivelegeQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method     ChildGroupPrivelegeQuery groupByGroupId() Group by the group_id column
 * @method     ChildGroupPrivelegeQuery groupByPrivilegeId() Group by the privilege_id column
 * @method     ChildGroupPrivelegeQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method     ChildGroupPrivelegeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGroupPrivelegeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGroupPrivelegeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGroupPrivelegeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildGroupPrivelegeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildGroupPrivelegeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildGroupPrivelegeQuery leftJoinCurrentGroupGroupPrivelege($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentGroupGroupPrivelege relation
 * @method     ChildGroupPrivelegeQuery rightJoinCurrentGroupGroupPrivelege($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentGroupGroupPrivelege relation
 * @method     ChildGroupPrivelegeQuery innerJoinCurrentGroupGroupPrivelege($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentGroupGroupPrivelege relation
 *
 * @method     ChildGroupPrivelegeQuery joinWithCurrentGroupGroupPrivelege($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentGroupGroupPrivelege relation
 *
 * @method     ChildGroupPrivelegeQuery leftJoinWithCurrentGroupGroupPrivelege() Adds a LEFT JOIN clause and with to the query using the CurrentGroupGroupPrivelege relation
 * @method     ChildGroupPrivelegeQuery rightJoinWithCurrentGroupGroupPrivelege() Adds a RIGHT JOIN clause and with to the query using the CurrentGroupGroupPrivelege relation
 * @method     ChildGroupPrivelegeQuery innerJoinWithCurrentGroupGroupPrivelege() Adds a INNER JOIN clause and with to the query using the CurrentGroupGroupPrivelege relation
 *
 * @method     ChildGroupPrivelegeQuery leftJoinCurrentPrivilegeGroupPrivelege($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentPrivilegeGroupPrivelege relation
 * @method     ChildGroupPrivelegeQuery rightJoinCurrentPrivilegeGroupPrivelege($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentPrivilegeGroupPrivelege relation
 * @method     ChildGroupPrivelegeQuery innerJoinCurrentPrivilegeGroupPrivelege($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentPrivilegeGroupPrivelege relation
 *
 * @method     ChildGroupPrivelegeQuery joinWithCurrentPrivilegeGroupPrivelege($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentPrivilegeGroupPrivelege relation
 *
 * @method     ChildGroupPrivelegeQuery leftJoinWithCurrentPrivilegeGroupPrivelege() Adds a LEFT JOIN clause and with to the query using the CurrentPrivilegeGroupPrivelege relation
 * @method     ChildGroupPrivelegeQuery rightJoinWithCurrentPrivilegeGroupPrivelege() Adds a RIGHT JOIN clause and with to the query using the CurrentPrivilegeGroupPrivelege relation
 * @method     ChildGroupPrivelegeQuery innerJoinWithCurrentPrivilegeGroupPrivelege() Adds a INNER JOIN clause and with to the query using the CurrentPrivilegeGroupPrivelege relation
 *
 * @method     \Models\GroupQuery|\Models\PrivilegeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildGroupPrivelege findOne(ConnectionInterface $con = null) Return the first ChildGroupPrivelege matching the query
 * @method     ChildGroupPrivelege findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGroupPrivelege matching the query, or a new ChildGroupPrivelege object populated from the query conditions when no match is found
 *
 * @method     ChildGroupPrivelege findOneByGroupId(int $group_id) Return the first ChildGroupPrivelege filtered by the group_id column
 * @method     ChildGroupPrivelege findOneByPrivilegeId(int $privilege_id) Return the first ChildGroupPrivelege filtered by the privilege_id column
 * @method     ChildGroupPrivelege findOneBySortableRank(int $sortable_rank) Return the first ChildGroupPrivelege filtered by the sortable_rank column *

 * @method     ChildGroupPrivelege requirePk($key, ConnectionInterface $con = null) Return the ChildGroupPrivelege by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroupPrivelege requireOne(ConnectionInterface $con = null) Return the first ChildGroupPrivelege matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGroupPrivelege requireOneByGroupId(int $group_id) Return the first ChildGroupPrivelege filtered by the group_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroupPrivelege requireOneByPrivilegeId(int $privilege_id) Return the first ChildGroupPrivelege filtered by the privilege_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGroupPrivelege requireOneBySortableRank(int $sortable_rank) Return the first ChildGroupPrivelege filtered by the sortable_rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGroupPrivelege[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGroupPrivelege objects based on current ModelCriteria
 * @method     ChildGroupPrivelege[]|ObjectCollection findByGroupId(int $group_id) Return ChildGroupPrivelege objects filtered by the group_id column
 * @method     ChildGroupPrivelege[]|ObjectCollection findByPrivilegeId(int $privilege_id) Return ChildGroupPrivelege objects filtered by the privilege_id column
 * @method     ChildGroupPrivelege[]|ObjectCollection findBySortableRank(int $sortable_rank) Return ChildGroupPrivelege objects filtered by the sortable_rank column
 * @method     ChildGroupPrivelege[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GroupPrivelegeQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\GroupPrivelegeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\GroupPrivelege', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGroupPrivelegeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGroupPrivelegeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGroupPrivelegeQuery) {
            return $criteria;
        }
        $query = new ChildGroupPrivelegeQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$group_id, $privilege_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildGroupPrivelege|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GroupPrivelegeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = GroupPrivelegeTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildGroupPrivelege A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT group_id, privilege_id, sortable_rank FROM group_privilege WHERE group_id = :p0 AND privilege_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildGroupPrivelege $obj */
            $obj = new ChildGroupPrivelege();
            $obj->hydrate($row);
            GroupPrivelegeTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildGroupPrivelege|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(GroupPrivelegeTableMap::COL_GROUP_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(GroupPrivelegeTableMap::COL_PRIVILEGE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(GroupPrivelegeTableMap::COL_GROUP_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(GroupPrivelegeTableMap::COL_PRIVILEGE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the group_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupId(1234); // WHERE group_id = 1234
     * $query->filterByGroupId(array(12, 34)); // WHERE group_id IN (12, 34)
     * $query->filterByGroupId(array('min' => 12)); // WHERE group_id > 12
     * </code>
     *
     * @see       filterByCurrentGroupGroupPrivelege()
     *
     * @param     mixed $groupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function filterByGroupId($groupId = null, $comparison = null)
    {
        if (is_array($groupId)) {
            $useMinMax = false;
            if (isset($groupId['min'])) {
                $this->addUsingAlias(GroupPrivelegeTableMap::COL_GROUP_ID, $groupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupId['max'])) {
                $this->addUsingAlias(GroupPrivelegeTableMap::COL_GROUP_ID, $groupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupPrivelegeTableMap::COL_GROUP_ID, $groupId, $comparison);
    }

    /**
     * Filter the query on the privilege_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPrivilegeId(1234); // WHERE privilege_id = 1234
     * $query->filterByPrivilegeId(array(12, 34)); // WHERE privilege_id IN (12, 34)
     * $query->filterByPrivilegeId(array('min' => 12)); // WHERE privilege_id > 12
     * </code>
     *
     * @see       filterByCurrentPrivilegeGroupPrivelege()
     *
     * @param     mixed $privilegeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function filterByPrivilegeId($privilegeId = null, $comparison = null)
    {
        if (is_array($privilegeId)) {
            $useMinMax = false;
            if (isset($privilegeId['min'])) {
                $this->addUsingAlias(GroupPrivelegeTableMap::COL_PRIVILEGE_ID, $privilegeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($privilegeId['max'])) {
                $this->addUsingAlias(GroupPrivelegeTableMap::COL_PRIVILEGE_ID, $privilegeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupPrivelegeTableMap::COL_PRIVILEGE_ID, $privilegeId, $comparison);
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
     * @return $this|ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(GroupPrivelegeTableMap::COL_SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(GroupPrivelegeTableMap::COL_SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupPrivelegeTableMap::COL_SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related \Models\Group object
     *
     * @param \Models\Group|ObjectCollection $group The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function filterByCurrentGroupGroupPrivelege($group, $comparison = null)
    {
        if ($group instanceof \Models\Group) {
            return $this
                ->addUsingAlias(GroupPrivelegeTableMap::COL_GROUP_ID, $group->getId(), $comparison);
        } elseif ($group instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GroupPrivelegeTableMap::COL_GROUP_ID, $group->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentGroupGroupPrivelege() only accepts arguments of type \Models\Group or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentGroupGroupPrivelege relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function joinCurrentGroupGroupPrivelege($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentGroupGroupPrivelege');

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
            $this->addJoinObject($join, 'CurrentGroupGroupPrivelege');
        }

        return $this;
    }

    /**
     * Use the CurrentGroupGroupPrivelege relation Group object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\GroupQuery A secondary query class using the current class as primary query
     */
    public function useCurrentGroupGroupPrivelegeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentGroupGroupPrivelege($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentGroupGroupPrivelege', '\Models\GroupQuery');
    }

    /**
     * Filter the query by a related \Models\Privilege object
     *
     * @param \Models\Privilege|ObjectCollection $privilege The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function filterByCurrentPrivilegeGroupPrivelege($privilege, $comparison = null)
    {
        if ($privilege instanceof \Models\Privilege) {
            return $this
                ->addUsingAlias(GroupPrivelegeTableMap::COL_PRIVILEGE_ID, $privilege->getId(), $comparison);
        } elseif ($privilege instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GroupPrivelegeTableMap::COL_PRIVILEGE_ID, $privilege->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentPrivilegeGroupPrivelege() only accepts arguments of type \Models\Privilege or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentPrivilegeGroupPrivelege relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGroupPrivelegeQuery The current query, for fluid interface
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
     * Use the CurrentPrivilegeGroupPrivelege relation Privilege object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\PrivilegeQuery A secondary query class using the current class as primary query
     */
    public function useCurrentPrivilegeGroupPrivelegeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentPrivilegeGroupPrivelege($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentPrivilegeGroupPrivelege', '\Models\PrivilegeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGroupPrivelege $groupPrivelege Object to remove from the list of results
     *
     * @return $this|ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function prune($groupPrivelege = null)
    {
        if ($groupPrivelege) {
            $this->addCond('pruneCond0', $this->getAliasedColName(GroupPrivelegeTableMap::COL_GROUP_ID), $groupPrivelege->getGroupId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(GroupPrivelegeTableMap::COL_PRIVILEGE_ID), $groupPrivelege->getPrivilegeId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the group_privilege table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupPrivelegeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GroupPrivelegeTableMap::clearInstancePool();
            GroupPrivelegeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupPrivelegeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GroupPrivelegeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            GroupPrivelegeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GroupPrivelegeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // sortable behavior

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {

        return $this
            ->addUsingAlias(GroupPrivelegeTableMap::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    $this|ChildGroupPrivelegeQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(GroupPrivelegeTableMap::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(GroupPrivelegeTableMap::RANK_COL));
                break;
            default:
                throw new \Propel\Runtime\Exception\PropelException('ChildGroupPrivelegeQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return    ChildGroupPrivelege
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
            $con = Propel::getServiceContainer()->getReadConnection(GroupPrivelegeTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . GroupPrivelegeTableMap::RANK_COL . ')');
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
            $con = Propel::getConnection(GroupPrivelegeTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . GroupPrivelegeTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return ChildGroupPrivelege
     */
    static public function retrieveByRank($rank, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(GroupPrivelegeTableMap::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(GroupPrivelegeTableMap::RANK_COL, $rank);

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
            $con = Propel::getServiceContainer()->getReadConnection(GroupPrivelegeTableMap::DATABASE_NAME);
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
            $con = Propel::getServiceContainer()->getReadConnection(GroupPrivelegeTableMap::DATABASE_NAME);
        }

        if (null === $criteria) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if (Criteria::ASC == $order) {
            $criteria->addAscendingOrderByColumn(GroupPrivelegeTableMap::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(GroupPrivelegeTableMap::RANK_COL);
        }

        return ChildGroupPrivelegeQuery::create(null, $criteria)->find($con);
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupPrivelegeTableMap::DATABASE_NAME);
        }

        $whereCriteria = new Criteria(GroupPrivelegeTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(GroupPrivelegeTableMap::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(GroupPrivelegeTableMap::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(GroupPrivelegeTableMap::DATABASE_NAME);
        $valuesCriteria->add(GroupPrivelegeTableMap::RANK_COL, array('raw' => GroupPrivelegeTableMap::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
        GroupPrivelegeTableMap::clearInstancePool();
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(GroupPrivelegeTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(GroupPrivelegeTableMap::DATABASE_NAME);

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

} // GroupPrivelegeQuery
