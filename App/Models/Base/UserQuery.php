<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildUserQuery orderByUserName($order = Criteria::ASC) Order by the user_name column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByAbout($order = Criteria::ASC) Order by the about column
 * @method     ChildUserQuery orderByBirthDate($order = Criteria::ASC) Order by the birth_date column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method     ChildUserQuery orderByLogoName($order = Criteria::ASC) Order by the logo_name column
 * @method     ChildUserQuery orderByCoverName($order = Criteria::ASC) Order by the cover_name column
 * @method     ChildUserQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method     ChildUserQuery orderByAddressCoordinates($order = Criteria::ASC) Order by the address_coordinates column
 * @method     ChildUserQuery orderByActivated($order = Criteria::ASC) Order by the is_activated column
 * @method     ChildUserQuery orderBySocialId($order = Criteria::ASC) Order by the social_id column
 * @method     ChildUserQuery orderBySocialToken($order = Criteria::ASC) Order by the social_token column
 * @method     ChildUserQuery orderByCurrentGroupId($order = Criteria::ASC) Order by the group_id column
 * @method     ChildUserQuery orderByCurrentCurrencyId($order = Criteria::ASC) Order by the currency_id column
 * @method     ChildUserQuery orderByCurrentAdminStyleId($order = Criteria::ASC) Order by the admin_style_id column
 * @method     ChildUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByName() Group by the name column
 * @method     ChildUserQuery groupByUserName() Group by the user_name column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByAbout() Group by the about column
 * @method     ChildUserQuery groupByBirthDate() Group by the birth_date column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupByPhone() Group by the phone column
 * @method     ChildUserQuery groupByLogoName() Group by the logo_name column
 * @method     ChildUserQuery groupByCoverName() Group by the cover_name column
 * @method     ChildUserQuery groupByAddress() Group by the address column
 * @method     ChildUserQuery groupByAddressCoordinates() Group by the address_coordinates column
 * @method     ChildUserQuery groupByActivated() Group by the is_activated column
 * @method     ChildUserQuery groupBySocialId() Group by the social_id column
 * @method     ChildUserQuery groupBySocialToken() Group by the social_token column
 * @method     ChildUserQuery groupByCurrentGroupId() Group by the group_id column
 * @method     ChildUserQuery groupByCurrentCurrencyId() Group by the currency_id column
 * @method     ChildUserQuery groupByCurrentAdminStyleId() Group by the admin_style_id column
 * @method     ChildUserQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildUserQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinCurrentGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentGroup relation
 * @method     ChildUserQuery rightJoinCurrentGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentGroup relation
 * @method     ChildUserQuery innerJoinCurrentGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentGroup relation
 *
 * @method     ChildUserQuery joinWithCurrentGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentGroup relation
 *
 * @method     ChildUserQuery leftJoinWithCurrentGroup() Adds a LEFT JOIN clause and with to the query using the CurrentGroup relation
 * @method     ChildUserQuery rightJoinWithCurrentGroup() Adds a RIGHT JOIN clause and with to the query using the CurrentGroup relation
 * @method     ChildUserQuery innerJoinWithCurrentGroup() Adds a INNER JOIN clause and with to the query using the CurrentGroup relation
 *
 * @method     ChildUserQuery leftJoinCurrentUserCurrency($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentUserCurrency relation
 * @method     ChildUserQuery rightJoinCurrentUserCurrency($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentUserCurrency relation
 * @method     ChildUserQuery innerJoinCurrentUserCurrency($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentUserCurrency relation
 *
 * @method     ChildUserQuery joinWithCurrentUserCurrency($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentUserCurrency relation
 *
 * @method     ChildUserQuery leftJoinWithCurrentUserCurrency() Adds a LEFT JOIN clause and with to the query using the CurrentUserCurrency relation
 * @method     ChildUserQuery rightJoinWithCurrentUserCurrency() Adds a RIGHT JOIN clause and with to the query using the CurrentUserCurrency relation
 * @method     ChildUserQuery innerJoinWithCurrentUserCurrency() Adds a INNER JOIN clause and with to the query using the CurrentUserCurrency relation
 *
 * @method     ChildUserQuery leftJoinCurrentAdminStyle($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentAdminStyle relation
 * @method     ChildUserQuery rightJoinCurrentAdminStyle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentAdminStyle relation
 * @method     ChildUserQuery innerJoinCurrentAdminStyle($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentAdminStyle relation
 *
 * @method     ChildUserQuery joinWithCurrentAdminStyle($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentAdminStyle relation
 *
 * @method     ChildUserQuery leftJoinWithCurrentAdminStyle() Adds a LEFT JOIN clause and with to the query using the CurrentAdminStyle relation
 * @method     ChildUserQuery rightJoinWithCurrentAdminStyle() Adds a RIGHT JOIN clause and with to the query using the CurrentAdminStyle relation
 * @method     ChildUserQuery innerJoinWithCurrentAdminStyle() Adds a INNER JOIN clause and with to the query using the CurrentAdminStyle relation
 *
 * @method     ChildUserQuery leftJoinCurrentUserVerificationToken($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentUserVerificationToken relation
 * @method     ChildUserQuery rightJoinCurrentUserVerificationToken($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentUserVerificationToken relation
 * @method     ChildUserQuery innerJoinCurrentUserVerificationToken($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentUserVerificationToken relation
 *
 * @method     ChildUserQuery joinWithCurrentUserVerificationToken($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentUserVerificationToken relation
 *
 * @method     ChildUserQuery leftJoinWithCurrentUserVerificationToken() Adds a LEFT JOIN clause and with to the query using the CurrentUserVerificationToken relation
 * @method     ChildUserQuery rightJoinWithCurrentUserVerificationToken() Adds a RIGHT JOIN clause and with to the query using the CurrentUserVerificationToken relation
 * @method     ChildUserQuery innerJoinWithCurrentUserVerificationToken() Adds a INNER JOIN clause and with to the query using the CurrentUserVerificationToken relation
 *
 * @method     ChildUserQuery leftJoinCurrentInstructorCourseStream($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentInstructorCourseStream relation
 * @method     ChildUserQuery rightJoinCurrentInstructorCourseStream($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentInstructorCourseStream relation
 * @method     ChildUserQuery innerJoinCurrentInstructorCourseStream($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentInstructorCourseStream relation
 *
 * @method     ChildUserQuery joinWithCurrentInstructorCourseStream($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentInstructorCourseStream relation
 *
 * @method     ChildUserQuery leftJoinWithCurrentInstructorCourseStream() Adds a LEFT JOIN clause and with to the query using the CurrentInstructorCourseStream relation
 * @method     ChildUserQuery rightJoinWithCurrentInstructorCourseStream() Adds a RIGHT JOIN clause and with to the query using the CurrentInstructorCourseStream relation
 * @method     ChildUserQuery innerJoinWithCurrentInstructorCourseStream() Adds a INNER JOIN clause and with to the query using the CurrentInstructorCourseStream relation
 *
 * @method     ChildUserQuery leftJoinPassport($relationAlias = null) Adds a LEFT JOIN clause to the query using the Passport relation
 * @method     ChildUserQuery rightJoinPassport($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Passport relation
 * @method     ChildUserQuery innerJoinPassport($relationAlias = null) Adds a INNER JOIN clause to the query using the Passport relation
 *
 * @method     ChildUserQuery joinWithPassport($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Passport relation
 *
 * @method     ChildUserQuery leftJoinWithPassport() Adds a LEFT JOIN clause and with to the query using the Passport relation
 * @method     ChildUserQuery rightJoinWithPassport() Adds a RIGHT JOIN clause and with to the query using the Passport relation
 * @method     ChildUserQuery innerJoinWithPassport() Adds a INNER JOIN clause and with to the query using the Passport relation
 *
 * @method     ChildUserQuery leftJoinStreamUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the StreamUser relation
 * @method     ChildUserQuery rightJoinStreamUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StreamUser relation
 * @method     ChildUserQuery innerJoinStreamUser($relationAlias = null) Adds a INNER JOIN clause to the query using the StreamUser relation
 *
 * @method     ChildUserQuery joinWithStreamUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the StreamUser relation
 *
 * @method     ChildUserQuery leftJoinWithStreamUser() Adds a LEFT JOIN clause and with to the query using the StreamUser relation
 * @method     ChildUserQuery rightJoinWithStreamUser() Adds a RIGHT JOIN clause and with to the query using the StreamUser relation
 * @method     ChildUserQuery innerJoinWithStreamUser() Adds a INNER JOIN clause and with to the query using the StreamUser relation
 *
 * @method     ChildUserQuery leftJoinToUserNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the ToUserNotification relation
 * @method     ChildUserQuery rightJoinToUserNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ToUserNotification relation
 * @method     ChildUserQuery innerJoinToUserNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the ToUserNotification relation
 *
 * @method     ChildUserQuery joinWithToUserNotification($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ToUserNotification relation
 *
 * @method     ChildUserQuery leftJoinWithToUserNotification() Adds a LEFT JOIN clause and with to the query using the ToUserNotification relation
 * @method     ChildUserQuery rightJoinWithToUserNotification() Adds a RIGHT JOIN clause and with to the query using the ToUserNotification relation
 * @method     ChildUserQuery innerJoinWithToUserNotification() Adds a INNER JOIN clause and with to the query using the ToUserNotification relation
 *
 * @method     ChildUserQuery leftJoinFromUserNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the FromUserNotification relation
 * @method     ChildUserQuery rightJoinFromUserNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FromUserNotification relation
 * @method     ChildUserQuery innerJoinFromUserNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the FromUserNotification relation
 *
 * @method     ChildUserQuery joinWithFromUserNotification($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the FromUserNotification relation
 *
 * @method     ChildUserQuery leftJoinWithFromUserNotification() Adds a LEFT JOIN clause and with to the query using the FromUserNotification relation
 * @method     ChildUserQuery rightJoinWithFromUserNotification() Adds a RIGHT JOIN clause and with to the query using the FromUserNotification relation
 * @method     ChildUserQuery innerJoinWithFromUserNotification() Adds a INNER JOIN clause and with to the query using the FromUserNotification relation
 *
 * @method     ChildUserQuery leftJoinCurrentUserFeedback($relationAlias = null) Adds a LEFT JOIN clause to the query using the CurrentUserFeedback relation
 * @method     ChildUserQuery rightJoinCurrentUserFeedback($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CurrentUserFeedback relation
 * @method     ChildUserQuery innerJoinCurrentUserFeedback($relationAlias = null) Adds a INNER JOIN clause to the query using the CurrentUserFeedback relation
 *
 * @method     ChildUserQuery joinWithCurrentUserFeedback($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CurrentUserFeedback relation
 *
 * @method     ChildUserQuery leftJoinWithCurrentUserFeedback() Adds a LEFT JOIN clause and with to the query using the CurrentUserFeedback relation
 * @method     ChildUserQuery rightJoinWithCurrentUserFeedback() Adds a RIGHT JOIN clause and with to the query using the CurrentUserFeedback relation
 * @method     ChildUserQuery innerJoinWithCurrentUserFeedback() Adds a INNER JOIN clause and with to the query using the CurrentUserFeedback relation
 *
 * @method     \Models\GroupQuery|\Models\CurrencyQuery|\Models\AdminStyleQuery|\Models\VerificationTokenQuery|\Models\CourseStreamQuery|\Models\PassportQuery|\Models\StreamUserQuery|\Models\NotificationQuery|\Models\FeedbackQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByName(string $name) Return the first ChildUser filtered by the name column
 * @method     ChildUser findOneByUserName(string $user_name) Return the first ChildUser filtered by the user_name column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByAbout(string $about) Return the first ChildUser filtered by the about column
 * @method     ChildUser findOneByBirthDate(string $birth_date) Return the first ChildUser filtered by the birth_date column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneByPhone(string $phone) Return the first ChildUser filtered by the phone column
 * @method     ChildUser findOneByLogoName(string $logo_name) Return the first ChildUser filtered by the logo_name column
 * @method     ChildUser findOneByCoverName(string $cover_name) Return the first ChildUser filtered by the cover_name column
 * @method     ChildUser findOneByAddress(string $address) Return the first ChildUser filtered by the address column
 * @method     ChildUser findOneByAddressCoordinates(array $address_coordinates) Return the first ChildUser filtered by the address_coordinates column
 * @method     ChildUser findOneByActivated(boolean $is_activated) Return the first ChildUser filtered by the is_activated column
 * @method     ChildUser findOneBySocialId(string $social_id) Return the first ChildUser filtered by the social_id column
 * @method     ChildUser findOneBySocialToken(string $social_token) Return the first ChildUser filtered by the social_token column
 * @method     ChildUser findOneByCurrentGroupId(int $group_id) Return the first ChildUser filtered by the group_id column
 * @method     ChildUser findOneByCurrentCurrencyId(int $currency_id) Return the first ChildUser filtered by the currency_id column
 * @method     ChildUser findOneByCurrentAdminStyleId(int $admin_style_id) Return the first ChildUser filtered by the admin_style_id column
 * @method     ChildUser findOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column
 * @method     ChildUser findOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByName(string $name) Return the first ChildUser filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUserName(string $user_name) Return the first ChildUser filtered by the user_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAbout(string $about) Return the first ChildUser filtered by the about column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByBirthDate(string $birth_date) Return the first ChildUser filtered by the birth_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPhone(string $phone) Return the first ChildUser filtered by the phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLogoName(string $logo_name) Return the first ChildUser filtered by the logo_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCoverName(string $cover_name) Return the first ChildUser filtered by the cover_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAddress(string $address) Return the first ChildUser filtered by the address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAddressCoordinates(array $address_coordinates) Return the first ChildUser filtered by the address_coordinates column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByActivated(boolean $is_activated) Return the first ChildUser filtered by the is_activated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySocialId(string $social_id) Return the first ChildUser filtered by the social_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySocialToken(string $social_token) Return the first ChildUser filtered by the social_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCurrentGroupId(int $group_id) Return the first ChildUser filtered by the group_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCurrentCurrencyId(int $currency_id) Return the first ChildUser filtered by the currency_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCurrentAdminStyleId(int $admin_style_id) Return the first ChildUser filtered by the admin_style_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByName(string $name) Return ChildUser objects filtered by the name column
 * @method     ChildUser[]|ObjectCollection findByUserName(string $user_name) Return ChildUser objects filtered by the user_name column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByAbout(string $about) Return ChildUser objects filtered by the about column
 * @method     ChildUser[]|ObjectCollection findByBirthDate(string $birth_date) Return ChildUser objects filtered by the birth_date column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findByPhone(string $phone) Return ChildUser objects filtered by the phone column
 * @method     ChildUser[]|ObjectCollection findByLogoName(string $logo_name) Return ChildUser objects filtered by the logo_name column
 * @method     ChildUser[]|ObjectCollection findByCoverName(string $cover_name) Return ChildUser objects filtered by the cover_name column
 * @method     ChildUser[]|ObjectCollection findByAddress(string $address) Return ChildUser objects filtered by the address column
 * @method     ChildUser[]|ObjectCollection findByAddressCoordinates(array $address_coordinates) Return ChildUser objects filtered by the address_coordinates column
 * @method     ChildUser[]|ObjectCollection findByActivated(boolean $is_activated) Return ChildUser objects filtered by the is_activated column
 * @method     ChildUser[]|ObjectCollection findBySocialId(string $social_id) Return ChildUser objects filtered by the social_id column
 * @method     ChildUser[]|ObjectCollection findBySocialToken(string $social_token) Return ChildUser objects filtered by the social_token column
 * @method     ChildUser[]|ObjectCollection findByCurrentGroupId(int $group_id) Return ChildUser objects filtered by the group_id column
 * @method     ChildUser[]|ObjectCollection findByCurrentCurrencyId(int $currency_id) Return ChildUser objects filtered by the currency_id column
 * @method     ChildUser[]|ObjectCollection findByCurrentAdminStyleId(int $admin_style_id) Return ChildUser objects filtered by the admin_style_id column
 * @method     ChildUser[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildUser objects filtered by the created_at column
 * @method     ChildUser[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildUser objects filtered by the updated_at column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{

    // query_cache behavior
    protected $queryKey = '';
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `user_name`, `email`, `about`, `birth_date`, `password`, `phone`, `logo_name`, `cover_name`, `address`, `address_coordinates`, `is_activated`, `social_id`, `social_token`, `group_id`, `currency_id`, `admin_style_id`, `created_at`, `updated_at` FROM `user` WHERE `id` = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the user_name column
     *
     * Example usage:
     * <code>
     * $query->filterByUserName('fooValue');   // WHERE user_name = 'fooValue'
     * $query->filterByUserName('%fooValue%', Criteria::LIKE); // WHERE user_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserName($userName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USER_NAME, $userName, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the about column
     *
     * Example usage:
     * <code>
     * $query->filterByAbout('fooValue');   // WHERE about = 'fooValue'
     * $query->filterByAbout('%fooValue%', Criteria::LIKE); // WHERE about LIKE '%fooValue%'
     * </code>
     *
     * @param     string $about The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAbout($about = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($about)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ABOUT, $about, $comparison);
    }

    /**
     * Filter the query on the birth_date column
     *
     * Example usage:
     * <code>
     * $query->filterByBirthDate('2011-03-14'); // WHERE birth_date = '2011-03-14'
     * $query->filterByBirthDate('now'); // WHERE birth_date = '2011-03-14'
     * $query->filterByBirthDate(array('max' => 'yesterday')); // WHERE birth_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $birthDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByBirthDate($birthDate = null, $comparison = null)
    {
        if (is_array($birthDate)) {
            $useMinMax = false;
            if (isset($birthDate['min'])) {
                $this->addUsingAlias(UserTableMap::COL_BIRTH_DATE, $birthDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($birthDate['max'])) {
                $this->addUsingAlias(UserTableMap::COL_BIRTH_DATE, $birthDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_BIRTH_DATE, $birthDate, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PHONE, $phone, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLogoName($logoName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logoName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LOGO_NAME, $logoName, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCoverName($coverName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($coverName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_COVER_NAME, $coverName, $comparison);
    }

    /**
     * Filter the query on the address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the address_coordinates column
     *
     * @param     array $addressCoordinates The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAddressCoordinates($addressCoordinates = null, $comparison = null)
    {
        $key = $this->getAliasedColName(UserTableMap::COL_ADDRESS_COORDINATES);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($addressCoordinates as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($addressCoordinates as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($addressCoordinates as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(UserTableMap::COL_ADDRESS_COORDINATES, $addressCoordinates, $comparison);
    }

    /**
     * Filter the query on the address_coordinates column
     * @param     mixed $addressCoordinates The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAddressCoordinate($addressCoordinates = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($addressCoordinates)) {
                $addressCoordinates = '%| ' . $addressCoordinates . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $addressCoordinates = '%| ' . $addressCoordinates . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(UserTableMap::COL_ADDRESS_COORDINATES);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $addressCoordinates, $comparison);
            } else {
                $this->addAnd($key, $addressCoordinates, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(UserTableMap::COL_ADDRESS_COORDINATES, $addressCoordinates, $comparison);
    }

    /**
     * Filter the query on the is_activated column
     *
     * Example usage:
     * <code>
     * $query->filterByActivated(true); // WHERE is_activated = true
     * $query->filterByActivated('yes'); // WHERE is_activated = true
     * </code>
     *
     * @param     boolean|string $activated The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByActivated($activated = null, $comparison = null)
    {
        if (is_string($activated)) {
            $activated = in_array(strtolower($activated), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_IS_ACTIVATED, $activated, $comparison);
    }

    /**
     * Filter the query on the social_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySocialId('fooValue');   // WHERE social_id = 'fooValue'
     * $query->filterBySocialId('%fooValue%', Criteria::LIKE); // WHERE social_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $socialId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterBySocialId($socialId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($socialId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_SOCIAL_ID, $socialId, $comparison);
    }

    /**
     * Filter the query on the social_token column
     *
     * Example usage:
     * <code>
     * $query->filterBySocialToken('fooValue');   // WHERE social_token = 'fooValue'
     * $query->filterBySocialToken('%fooValue%', Criteria::LIKE); // WHERE social_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $socialToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterBySocialToken($socialToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($socialToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_SOCIAL_TOKEN, $socialToken, $comparison);
    }

    /**
     * Filter the query on the group_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentGroupId(1234); // WHERE group_id = 1234
     * $query->filterByCurrentGroupId(array(12, 34)); // WHERE group_id IN (12, 34)
     * $query->filterByCurrentGroupId(array('min' => 12)); // WHERE group_id > 12
     * </code>
     *
     * @see       filterByCurrentGroup()
     *
     * @param     mixed $currentGroupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentGroupId($currentGroupId = null, $comparison = null)
    {
        if (is_array($currentGroupId)) {
            $useMinMax = false;
            if (isset($currentGroupId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_GROUP_ID, $currentGroupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentGroupId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_GROUP_ID, $currentGroupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_GROUP_ID, $currentGroupId, $comparison);
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
     * @see       filterByCurrentUserCurrency()
     *
     * @param     mixed $currentCurrencyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentCurrencyId($currentCurrencyId = null, $comparison = null)
    {
        if (is_array($currentCurrencyId)) {
            $useMinMax = false;
            if (isset($currentCurrencyId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_CURRENCY_ID, $currentCurrencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentCurrencyId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_CURRENCY_ID, $currentCurrencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_CURRENCY_ID, $currentCurrencyId, $comparison);
    }

    /**
     * Filter the query on the admin_style_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrentAdminStyleId(1234); // WHERE admin_style_id = 1234
     * $query->filterByCurrentAdminStyleId(array(12, 34)); // WHERE admin_style_id IN (12, 34)
     * $query->filterByCurrentAdminStyleId(array('min' => 12)); // WHERE admin_style_id > 12
     * </code>
     *
     * @see       filterByCurrentAdminStyle()
     *
     * @param     mixed $currentAdminStyleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentAdminStyleId($currentAdminStyleId = null, $comparison = null)
    {
        if (is_array($currentAdminStyleId)) {
            $useMinMax = false;
            if (isset($currentAdminStyleId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ADMIN_STYLE_ID, $currentAdminStyleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currentAdminStyleId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ADMIN_STYLE_ID, $currentAdminStyleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ADMIN_STYLE_ID, $currentAdminStyleId, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Group object
     *
     * @param \Models\Group|ObjectCollection $group The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentGroup($group, $comparison = null)
    {
        if ($group instanceof \Models\Group) {
            return $this
                ->addUsingAlias(UserTableMap::COL_GROUP_ID, $group->getId(), $comparison);
        } elseif ($group instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTableMap::COL_GROUP_ID, $group->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentGroup() only accepts arguments of type \Models\Group or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCurrentGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentGroup');

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
            $this->addJoinObject($join, 'CurrentGroup');
        }

        return $this;
    }

    /**
     * Use the CurrentGroup relation Group object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\GroupQuery A secondary query class using the current class as primary query
     */
    public function useCurrentGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentGroup', '\Models\GroupQuery');
    }

    /**
     * Filter the query by a related \Models\Currency object
     *
     * @param \Models\Currency|ObjectCollection $currency The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentUserCurrency($currency, $comparison = null)
    {
        if ($currency instanceof \Models\Currency) {
            return $this
                ->addUsingAlias(UserTableMap::COL_CURRENCY_ID, $currency->getId(), $comparison);
        } elseif ($currency instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTableMap::COL_CURRENCY_ID, $currency->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentUserCurrency() only accepts arguments of type \Models\Currency or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentUserCurrency relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCurrentUserCurrency($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentUserCurrency');

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
            $this->addJoinObject($join, 'CurrentUserCurrency');
        }

        return $this;
    }

    /**
     * Use the CurrentUserCurrency relation Currency object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CurrencyQuery A secondary query class using the current class as primary query
     */
    public function useCurrentUserCurrencyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentUserCurrency($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentUserCurrency', '\Models\CurrencyQuery');
    }

    /**
     * Filter the query by a related \Models\AdminStyle object
     *
     * @param \Models\AdminStyle|ObjectCollection $adminStyle The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentAdminStyle($adminStyle, $comparison = null)
    {
        if ($adminStyle instanceof \Models\AdminStyle) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ADMIN_STYLE_ID, $adminStyle->getId(), $comparison);
        } elseif ($adminStyle instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTableMap::COL_ADMIN_STYLE_ID, $adminStyle->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrentAdminStyle() only accepts arguments of type \Models\AdminStyle or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentAdminStyle relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCurrentAdminStyle($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentAdminStyle');

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
            $this->addJoinObject($join, 'CurrentAdminStyle');
        }

        return $this;
    }

    /**
     * Use the CurrentAdminStyle relation AdminStyle object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\AdminStyleQuery A secondary query class using the current class as primary query
     */
    public function useCurrentAdminStyleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentAdminStyle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentAdminStyle', '\Models\AdminStyleQuery');
    }

    /**
     * Filter the query by a related \Models\VerificationToken object
     *
     * @param \Models\VerificationToken|ObjectCollection $verificationToken the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentUserVerificationToken($verificationToken, $comparison = null)
    {
        if ($verificationToken instanceof \Models\VerificationToken) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $verificationToken->getCurrentUserId(), $comparison);
        } elseif ($verificationToken instanceof ObjectCollection) {
            return $this
                ->useCurrentUserVerificationTokenQuery()
                ->filterByPrimaryKeys($verificationToken->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentUserVerificationToken() only accepts arguments of type \Models\VerificationToken or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentUserVerificationToken relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCurrentUserVerificationToken($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentUserVerificationToken');

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
            $this->addJoinObject($join, 'CurrentUserVerificationToken');
        }

        return $this;
    }

    /**
     * Use the CurrentUserVerificationToken relation VerificationToken object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\VerificationTokenQuery A secondary query class using the current class as primary query
     */
    public function useCurrentUserVerificationTokenQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentUserVerificationToken($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentUserVerificationToken', '\Models\VerificationTokenQuery');
    }

    /**
     * Filter the query by a related \Models\CourseStream object
     *
     * @param \Models\CourseStream|ObjectCollection $courseStream the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentInstructorCourseStream($courseStream, $comparison = null)
    {
        if ($courseStream instanceof \Models\CourseStream) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $courseStream->getCurrentCourseStreamInstructorId(), $comparison);
        } elseif ($courseStream instanceof ObjectCollection) {
            return $this
                ->useCurrentInstructorCourseStreamQuery()
                ->filterByPrimaryKeys($courseStream->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentInstructorCourseStream() only accepts arguments of type \Models\CourseStream or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentInstructorCourseStream relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCurrentInstructorCourseStream($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentInstructorCourseStream');

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
            $this->addJoinObject($join, 'CurrentInstructorCourseStream');
        }

        return $this;
    }

    /**
     * Use the CurrentInstructorCourseStream relation CourseStream object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CourseStreamQuery A secondary query class using the current class as primary query
     */
    public function useCurrentInstructorCourseStreamQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCurrentInstructorCourseStream($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentInstructorCourseStream', '\Models\CourseStreamQuery');
    }

    /**
     * Filter the query by a related \Models\Passport object
     *
     * @param \Models\Passport|ObjectCollection $passport the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassport($passport, $comparison = null)
    {
        if ($passport instanceof \Models\Passport) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $passport->getUserId(), $comparison);
        } elseif ($passport instanceof ObjectCollection) {
            return $this
                ->usePassportQuery()
                ->filterByPrimaryKeys($passport->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPassport() only accepts arguments of type \Models\Passport or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Passport relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinPassport($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Passport');

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
            $this->addJoinObject($join, 'Passport');
        }

        return $this;
    }

    /**
     * Use the Passport relation Passport object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\PassportQuery A secondary query class using the current class as primary query
     */
    public function usePassportQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPassport($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Passport', '\Models\PassportQuery');
    }

    /**
     * Filter the query by a related \Models\StreamUser object
     *
     * @param \Models\StreamUser|ObjectCollection $streamUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByStreamUser($streamUser, $comparison = null)
    {
        if ($streamUser instanceof \Models\StreamUser) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $streamUser->getUserId(), $comparison);
        } elseif ($streamUser instanceof ObjectCollection) {
            return $this
                ->useStreamUserQuery()
                ->filterByPrimaryKeys($streamUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStreamUser() only accepts arguments of type \Models\StreamUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StreamUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinStreamUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StreamUser');

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
            $this->addJoinObject($join, 'StreamUser');
        }

        return $this;
    }

    /**
     * Use the StreamUser relation StreamUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\StreamUserQuery A secondary query class using the current class as primary query
     */
    public function useStreamUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStreamUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StreamUser', '\Models\StreamUserQuery');
    }

    /**
     * Filter the query by a related \Models\Notification object
     *
     * @param \Models\Notification|ObjectCollection $notification the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByToUserNotification($notification, $comparison = null)
    {
        if ($notification instanceof \Models\Notification) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $notification->getToUserId(), $comparison);
        } elseif ($notification instanceof ObjectCollection) {
            return $this
                ->useToUserNotificationQuery()
                ->filterByPrimaryKeys($notification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByToUserNotification() only accepts arguments of type \Models\Notification or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ToUserNotification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinToUserNotification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ToUserNotification');

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
            $this->addJoinObject($join, 'ToUserNotification');
        }

        return $this;
    }

    /**
     * Use the ToUserNotification relation Notification object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\NotificationQuery A secondary query class using the current class as primary query
     */
    public function useToUserNotificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinToUserNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ToUserNotification', '\Models\NotificationQuery');
    }

    /**
     * Filter the query by a related \Models\Notification object
     *
     * @param \Models\Notification|ObjectCollection $notification the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByFromUserNotification($notification, $comparison = null)
    {
        if ($notification instanceof \Models\Notification) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $notification->getFromUserId(), $comparison);
        } elseif ($notification instanceof ObjectCollection) {
            return $this
                ->useFromUserNotificationQuery()
                ->filterByPrimaryKeys($notification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFromUserNotification() only accepts arguments of type \Models\Notification or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FromUserNotification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinFromUserNotification($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FromUserNotification');

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
            $this->addJoinObject($join, 'FromUserNotification');
        }

        return $this;
    }

    /**
     * Use the FromUserNotification relation Notification object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\NotificationQuery A secondary query class using the current class as primary query
     */
    public function useFromUserNotificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFromUserNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FromUserNotification', '\Models\NotificationQuery');
    }

    /**
     * Filter the query by a related \Models\Feedback object
     *
     * @param \Models\Feedback|ObjectCollection $feedback the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCurrentUserFeedback($feedback, $comparison = null)
    {
        if ($feedback instanceof \Models\Feedback) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $feedback->getCurrentUserId(), $comparison);
        } elseif ($feedback instanceof ObjectCollection) {
            return $this
                ->useCurrentUserFeedbackQuery()
                ->filterByPrimaryKeys($feedback->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCurrentUserFeedback() only accepts arguments of type \Models\Feedback or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CurrentUserFeedback relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCurrentUserFeedback($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CurrentUserFeedback');

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
            $this->addJoinObject($join, 'CurrentUserFeedback');
        }

        return $this;
    }

    /**
     * Use the CurrentUserFeedback relation Feedback object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\FeedbackQuery A secondary query class using the current class as primary query
     */
    public function useCurrentUserFeedbackQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrentUserFeedback($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CurrentUserFeedback', '\Models\FeedbackQuery');
    }

    /**
     * Filter the query by a related CourseStream object
     * using the stream_user table as cross reference
     *
     * @param CourseStream $courseStream the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCourseStream($courseStream, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStreamUserQuery()
            ->filterByCourseStream($courseStream, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_CREATED_AT);
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

        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        $db = Propel::getServiceContainer()->getAdapter(UserTableMap::DATABASE_NAME);

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

} // UserQuery
