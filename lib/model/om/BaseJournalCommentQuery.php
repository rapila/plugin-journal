<?php


/**
 * Base class that represents a query for the 'journal_comments' table.
 *
 * 
 *
 * @method     JournalCommentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     JournalCommentQuery orderByUsername($order = Criteria::ASC) Order by the user column
 * @method     JournalCommentQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     JournalCommentQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     JournalCommentQuery orderByJournalEntryId($order = Criteria::ASC) Order by the journal_entry_id column
 * @method     JournalCommentQuery orderByIsPublished($order = Criteria::ASC) Order by the is_published column
 * @method     JournalCommentQuery orderByActivationHash($order = Criteria::ASC) Order by the activation_hash column
 * @method     JournalCommentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     JournalCommentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     JournalCommentQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method     JournalCommentQuery orderByUpdatedBy($order = Criteria::ASC) Order by the updated_by column
 *
 * @method     JournalCommentQuery groupById() Group by the id column
 * @method     JournalCommentQuery groupByUsername() Group by the user column
 * @method     JournalCommentQuery groupByEmail() Group by the email column
 * @method     JournalCommentQuery groupByText() Group by the text column
 * @method     JournalCommentQuery groupByJournalEntryId() Group by the journal_entry_id column
 * @method     JournalCommentQuery groupByIsPublished() Group by the is_published column
 * @method     JournalCommentQuery groupByActivationHash() Group by the activation_hash column
 * @method     JournalCommentQuery groupByCreatedAt() Group by the created_at column
 * @method     JournalCommentQuery groupByUpdatedAt() Group by the updated_at column
 * @method     JournalCommentQuery groupByCreatedBy() Group by the created_by column
 * @method     JournalCommentQuery groupByUpdatedBy() Group by the updated_by column
 *
 * @method     JournalCommentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     JournalCommentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     JournalCommentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     JournalCommentQuery leftJoinJournalEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the JournalEntry relation
 * @method     JournalCommentQuery rightJoinJournalEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the JournalEntry relation
 * @method     JournalCommentQuery innerJoinJournalEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the JournalEntry relation
 *
 * @method     JournalCommentQuery leftJoinUserRelatedByCreatedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     JournalCommentQuery rightJoinUserRelatedByCreatedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     JournalCommentQuery innerJoinUserRelatedByCreatedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByCreatedBy relation
 *
 * @method     JournalCommentQuery leftJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     JournalCommentQuery rightJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     JournalCommentQuery innerJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByUpdatedBy relation
 *
 * @method     JournalComment findOne(PropelPDO $con = null) Return the first JournalComment matching the query
 * @method     JournalComment findOneOrCreate(PropelPDO $con = null) Return the first JournalComment matching the query, or a new JournalComment object populated from the query conditions when no match is found
 *
 * @method     JournalComment findOneById(int $id) Return the first JournalComment filtered by the id column
 * @method     JournalComment findOneByUsername(string $user) Return the first JournalComment filtered by the user column
 * @method     JournalComment findOneByEmail(string $email) Return the first JournalComment filtered by the email column
 * @method     JournalComment findOneByText(string $text) Return the first JournalComment filtered by the text column
 * @method     JournalComment findOneByJournalEntryId(int $journal_entry_id) Return the first JournalComment filtered by the journal_entry_id column
 * @method     JournalComment findOneByIsPublished(boolean $is_published) Return the first JournalComment filtered by the is_published column
 * @method     JournalComment findOneByActivationHash(string $activation_hash) Return the first JournalComment filtered by the activation_hash column
 * @method     JournalComment findOneByCreatedAt(string $created_at) Return the first JournalComment filtered by the created_at column
 * @method     JournalComment findOneByUpdatedAt(string $updated_at) Return the first JournalComment filtered by the updated_at column
 * @method     JournalComment findOneByCreatedBy(int $created_by) Return the first JournalComment filtered by the created_by column
 * @method     JournalComment findOneByUpdatedBy(int $updated_by) Return the first JournalComment filtered by the updated_by column
 *
 * @method     array findById(int $id) Return JournalComment objects filtered by the id column
 * @method     array findByUsername(string $user) Return JournalComment objects filtered by the user column
 * @method     array findByEmail(string $email) Return JournalComment objects filtered by the email column
 * @method     array findByText(string $text) Return JournalComment objects filtered by the text column
 * @method     array findByJournalEntryId(int $journal_entry_id) Return JournalComment objects filtered by the journal_entry_id column
 * @method     array findByIsPublished(boolean $is_published) Return JournalComment objects filtered by the is_published column
 * @method     array findByActivationHash(string $activation_hash) Return JournalComment objects filtered by the activation_hash column
 * @method     array findByCreatedAt(string $created_at) Return JournalComment objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return JournalComment objects filtered by the updated_at column
 * @method     array findByCreatedBy(int $created_by) Return JournalComment objects filtered by the created_by column
 * @method     array findByUpdatedBy(int $updated_by) Return JournalComment objects filtered by the updated_by column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseJournalCommentQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseJournalCommentQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'rapila', $modelName = 'JournalComment', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new JournalCommentQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    JournalCommentQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof JournalCommentQuery) {
			return $criteria;
		}
		$query = new JournalCommentQuery();
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
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    JournalComment|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = JournalCommentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(JournalCommentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		if ($this->formatter || $this->modelAlias || $this->with || $this->select
		 || $this->selectColumns || $this->asColumns || $this->selectModifiers
		 || $this->map || $this->having || $this->joins) {
			return $this->findPkComplex($key, $con);
		} else {
			return $this->findPkSimple($key, $con);
		}
	}

	/**
	 * Find object by primary key using raw SQL to go fast.
	 * Bypass doSelect() and the object formatter by using generated code.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    JournalComment A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `USER`, `EMAIL`, `TEXT`, `JOURNAL_ENTRY_ID`, `IS_PUBLISHED`, `ACTIVATION_HASH`, `CREATED_AT`, `UPDATED_AT`, `CREATED_BY`, `UPDATED_BY` FROM `journal_comments` WHERE `ID` = :p0';
		try {
			$stmt = $con->prepare($sql);
			$stmt->bindValue(':p0', $key, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
		}
		$obj = null;
		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$obj = new JournalComment();
			$obj->hydrate($row);
			JournalCommentPeer::addInstanceToPool($obj, (string) $row[0]);
		}
		$stmt->closeCursor();

		return $obj;
	}

	/**
	 * Find object by primary key.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    JournalComment|array|mixed the result, formatted by the current formatter
	 */
	protected function findPkComplex($key, $con)
	{
		// As the query uses a PK condition, no limit(1) is necessary.
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKey($key)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKeys($keys)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->format($stmt);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(JournalCommentPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(JournalCommentPeer::ID, $keys, Criteria::IN);
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
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(JournalCommentPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the user column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByUsername('fooValue');   // WHERE user = 'fooValue'
	 * $query->filterByUsername('%fooValue%'); // WHERE user LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $username The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByUsername($username = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($username)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $username)) {
				$username = str_replace('*', '%', $username);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::USER, $username, $comparison);
	}

	/**
	 * Filter the query on the email column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
	 * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $email The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByEmail($email = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($email)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $email)) {
				$email = str_replace('*', '%', $email);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::EMAIL, $email, $comparison);
	}

	/**
	 * Filter the query on the text column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByText('fooValue');   // WHERE text = 'fooValue'
	 * $query->filterByText('%fooValue%'); // WHERE text LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $text The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByText($text = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($text)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $text)) {
				$text = str_replace('*', '%', $text);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::TEXT, $text, $comparison);
	}

	/**
	 * Filter the query on the journal_entry_id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByJournalEntryId(1234); // WHERE journal_entry_id = 1234
	 * $query->filterByJournalEntryId(array(12, 34)); // WHERE journal_entry_id IN (12, 34)
	 * $query->filterByJournalEntryId(array('min' => 12)); // WHERE journal_entry_id > 12
	 * </code>
	 *
	 * @see       filterByJournalEntry()
	 *
	 * @param     mixed $journalEntryId The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByJournalEntryId($journalEntryId = null, $comparison = null)
	{
		if (is_array($journalEntryId)) {
			$useMinMax = false;
			if (isset($journalEntryId['min'])) {
				$this->addUsingAlias(JournalCommentPeer::JOURNAL_ENTRY_ID, $journalEntryId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($journalEntryId['max'])) {
				$this->addUsingAlias(JournalCommentPeer::JOURNAL_ENTRY_ID, $journalEntryId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::JOURNAL_ENTRY_ID, $journalEntryId, $comparison);
	}

	/**
	 * Filter the query on the is_published column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByIsPublished(true); // WHERE is_published = true
	 * $query->filterByIsPublished('yes'); // WHERE is_published = true
	 * </code>
	 *
	 * @param     boolean|string $isPublished The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByIsPublished($isPublished = null, $comparison = null)
	{
		if (is_string($isPublished)) {
			$is_published = in_array(strtolower($isPublished), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(JournalCommentPeer::IS_PUBLISHED, $isPublished, $comparison);
	}

	/**
	 * Filter the query on the activation_hash column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByActivationHash('fooValue');   // WHERE activation_hash = 'fooValue'
	 * $query->filterByActivationHash('%fooValue%'); // WHERE activation_hash LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $activationHash The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByActivationHash($activationHash = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($activationHash)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $activationHash)) {
				$activationHash = str_replace('*', '%', $activationHash);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::ACTIVATION_HASH, $activationHash, $comparison);
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
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(JournalCommentPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(JournalCommentPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::CREATED_AT, $createdAt, $comparison);
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
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(JournalCommentPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(JournalCommentPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Filter the query on the created_by column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByCreatedBy(1234); // WHERE created_by = 1234
	 * $query->filterByCreatedBy(array(12, 34)); // WHERE created_by IN (12, 34)
	 * $query->filterByCreatedBy(array('min' => 12)); // WHERE created_by > 12
	 * </code>
	 *
	 * @see       filterByUserRelatedByCreatedBy()
	 *
	 * @param     mixed $createdBy The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByCreatedBy($createdBy = null, $comparison = null)
	{
		if (is_array($createdBy)) {
			$useMinMax = false;
			if (isset($createdBy['min'])) {
				$this->addUsingAlias(JournalCommentPeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdBy['max'])) {
				$this->addUsingAlias(JournalCommentPeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::CREATED_BY, $createdBy, $comparison);
	}

	/**
	 * Filter the query on the updated_by column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByUpdatedBy(1234); // WHERE updated_by = 1234
	 * $query->filterByUpdatedBy(array(12, 34)); // WHERE updated_by IN (12, 34)
	 * $query->filterByUpdatedBy(array('min' => 12)); // WHERE updated_by > 12
	 * </code>
	 *
	 * @see       filterByUserRelatedByUpdatedBy()
	 *
	 * @param     mixed $updatedBy The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByUpdatedBy($updatedBy = null, $comparison = null)
	{
		if (is_array($updatedBy)) {
			$useMinMax = false;
			if (isset($updatedBy['min'])) {
				$this->addUsingAlias(JournalCommentPeer::UPDATED_BY, $updatedBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedBy['max'])) {
				$this->addUsingAlias(JournalCommentPeer::UPDATED_BY, $updatedBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::UPDATED_BY, $updatedBy, $comparison);
	}

	/**
	 * Filter the query by a related JournalEntry object
	 *
	 * @param     JournalEntry|PropelCollection $journalEntry The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByJournalEntry($journalEntry, $comparison = null)
	{
		if ($journalEntry instanceof JournalEntry) {
			return $this
				->addUsingAlias(JournalCommentPeer::JOURNAL_ENTRY_ID, $journalEntry->getId(), $comparison);
		} elseif ($journalEntry instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(JournalCommentPeer::JOURNAL_ENTRY_ID, $journalEntry->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByJournalEntry() only accepts arguments of type JournalEntry or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the JournalEntry relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function joinJournalEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JournalEntry');

		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}

		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JournalEntry');
		}

		return $this;
	}

	/**
	 * Use the JournalEntry relation JournalEntry object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalEntryQuery A secondary query class using the current class as primary query
	 */
	public function useJournalEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJournalEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JournalEntry', 'JournalEntryQuery');
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User|PropelCollection $user The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByCreatedBy($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(JournalCommentPeer::CREATED_BY, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(JournalCommentPeer::CREATED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByUserRelatedByCreatedBy() only accepts arguments of type User or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByCreatedBy relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function joinUserRelatedByCreatedBy($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('UserRelatedByCreatedBy');

		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}

		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'UserRelatedByCreatedBy');
		}

		return $this;
	}

	/**
	 * Use the UserRelatedByCreatedBy relation User object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery A secondary query class using the current class as primary query
	 */
	public function useUserRelatedByCreatedByQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinUserRelatedByCreatedBy($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'UserRelatedByCreatedBy', 'UserQuery');
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User|PropelCollection $user The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByUpdatedBy($user, $comparison = null)
	{
		if ($user instanceof User) {
			return $this
				->addUsingAlias(JournalCommentPeer::UPDATED_BY, $user->getId(), $comparison);
		} elseif ($user instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(JournalCommentPeer::UPDATED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
		} else {
			throw new PropelException('filterByUserRelatedByUpdatedBy() only accepts arguments of type User or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByUpdatedBy relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function joinUserRelatedByUpdatedBy($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('UserRelatedByUpdatedBy');

		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}

		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'UserRelatedByUpdatedBy');
		}

		return $this;
	}

	/**
	 * Use the UserRelatedByUpdatedBy relation User object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery A secondary query class using the current class as primary query
	 */
	public function useUserRelatedByUpdatedByQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinUserRelatedByUpdatedBy($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUpdatedBy', 'UserQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     JournalComment $journalComment Object to remove from the list of results
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function prune($journalComment = null)
	{
		if ($journalComment) {
			$this->addUsingAlias(JournalCommentPeer::ID, $journalComment->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

	// extended_timestampable behavior
	
	/**
	 * Filter by the latest updated
	 *
	 * @param      int $nbDays Maximum age of the latest update in days
	 *
	 * @return     JournalCommentQuery The current query, for fluid interface
	 */
	public function recentlyUpdated($nbDays = 7)
	{
		return $this->addUsingAlias(JournalCommentPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Filter by the latest created
	 *
	 * @param      int $nbDays Maximum age of in days
	 *
	 * @return     JournalCommentQuery The current query, for fluid interface
	 */
	public function recentlyCreated($nbDays = 7)
	{
		return $this->addUsingAlias(JournalCommentPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Order by update date desc
	 *
	 * @return     JournalCommentQuery The current query, for fluid interface
	 */
	public function lastUpdatedFirst()
	{
		return $this->addDescendingOrderByColumn(JournalCommentPeer::UPDATED_AT);
	}
	
	/**
	 * Order by update date asc
	 *
	 * @return     JournalCommentQuery The current query, for fluid interface
	 */
	public function firstUpdatedFirst()
	{
		return $this->addAscendingOrderByColumn(JournalCommentPeer::UPDATED_AT);
	}
	
	/**
	 * Order by create date desc
	 *
	 * @return     JournalCommentQuery The current query, for fluid interface
	 */
	public function lastCreatedFirst()
	{
		return $this->addDescendingOrderByColumn(JournalCommentPeer::CREATED_AT);
	}
	
	/**
	 * Order by create date asc
	 *
	 * @return     JournalCommentQuery The current query, for fluid interface
	 */
	public function firstCreatedFirst()
	{
		return $this->addAscendingOrderByColumn(JournalCommentPeer::CREATED_AT);
	}

} // BaseJournalCommentQuery