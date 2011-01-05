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
 * @method     JournalCommentQuery groupByCreatedAt() Group by the created_at column
 * @method     JournalCommentQuery groupByUpdatedAt() Group by the updated_at column
 * @method     JournalCommentQuery groupByCreatedBy() Group by the created_by column
 * @method     JournalCommentQuery groupByUpdatedBy() Group by the updated_by column
 *
 * @method     JournalCommentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     JournalCommentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     JournalCommentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     JournalCommentQuery leftJoinJournalEntry($relationAlias = '') Adds a LEFT JOIN clause to the query using the JournalEntry relation
 * @method     JournalCommentQuery rightJoinJournalEntry($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JournalEntry relation
 * @method     JournalCommentQuery innerJoinJournalEntry($relationAlias = '') Adds a INNER JOIN clause to the query using the JournalEntry relation
 *
 * @method     JournalCommentQuery leftJoinUserRelatedByCreatedBy($relationAlias = '') Adds a LEFT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     JournalCommentQuery rightJoinUserRelatedByCreatedBy($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     JournalCommentQuery innerJoinUserRelatedByCreatedBy($relationAlias = '') Adds a INNER JOIN clause to the query using the UserRelatedByCreatedBy relation
 *
 * @method     JournalCommentQuery leftJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a LEFT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     JournalCommentQuery rightJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     JournalCommentQuery innerJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a INNER JOIN clause to the query using the UserRelatedByUpdatedBy relation
 *
 * @method     JournalComment findOne(PropelPDO $con = null) Return the first JournalComment matching the query
 * @method     JournalComment findOneById(int $id) Return the first JournalComment filtered by the id column
 * @method     JournalComment findOneByUsername(string $user) Return the first JournalComment filtered by the user column
 * @method     JournalComment findOneByEmail(string $email) Return the first JournalComment filtered by the email column
 * @method     JournalComment findOneByText(string $text) Return the first JournalComment filtered by the text column
 * @method     JournalComment findOneByJournalEntryId(int $journal_entry_id) Return the first JournalComment filtered by the journal_entry_id column
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
	public function __construct($dbName = 'mini_cms', $modelName = 'JournalComment', $modelAlias = null)
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
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    JournalComment|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = JournalCommentPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$criteria = $this->isKeepQuery() ? clone $this : $this;
			$stmt = $criteria
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
		}
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
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
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
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     string $username The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByUsername($username = null, $comparison = null)
	{
		if (is_array($username)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $username)) {
			$username = str_replace('*', '%', $username);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::USER, $username, $comparison);
	}

	/**
	 * Filter the query on the email column
	 * 
	 * @param     string $email The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByEmail($email = null, $comparison = null)
	{
		if (is_array($email)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $email)) {
			$email = str_replace('*', '%', $email);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::EMAIL, $email, $comparison);
	}

	/**
	 * Filter the query on the text column
	 * 
	 * @param     string $text The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByText($text = null, $comparison = null)
	{
		if (is_array($text)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $text)) {
			$text = str_replace('*', '%', $text);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalCommentPeer::TEXT, $text, $comparison);
	}

	/**
	 * Filter the query on the journal_entry_id column
	 * 
	 * @param     int|array $journalEntryId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     int|array $createdBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     int|array $updatedBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
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
	 * @param     JournalEntry $journalEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByJournalEntry($journalEntry, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalCommentPeer::JOURNAL_ENTRY_ID, $journalEntry->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JournalEntry relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function joinJournalEntry($relationAlias = '', $joinType = Criteria::INNER_JOIN)
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
	public function useJournalEntryQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJournalEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JournalEntry', 'JournalEntryQuery');
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User $user  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByCreatedBy($user, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalCommentPeer::CREATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByCreatedBy relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function joinUserRelatedByCreatedBy($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
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
	public function useUserRelatedByCreatedByQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinUserRelatedByCreatedBy($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'UserRelatedByCreatedBy', 'UserQuery');
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User $user  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByUpdatedBy($user, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalCommentPeer::UPDATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByUpdatedBy relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalCommentQuery The current query, for fluid interface
	 */
	public function joinUserRelatedByUpdatedBy($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
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
	public function useUserRelatedByUpdatedByQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
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
	 * @return     JournalCommentQuery The current query, for fuid interface
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
	 * @return     JournalCommentQuery The current query, for fuid interface
	 */
	public function recentlyCreated($nbDays = 7)
	{
		return $this->addUsingAlias(JournalCommentPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Order by update date desc
	 *
	 * @return     JournalCommentQuery The current query, for fuid interface
	 */
	public function lastUpdatedFirst()
	{
		return $this->addDescendingOrderByColumn(JournalCommentPeer::UPDATED_AT);
	}
	
	/**
	 * Order by update date asc
	 *
	 * @return     JournalCommentQuery The current query, for fuid interface
	 */
	public function firstUpdatedFirst()
	{
		return $this->addAscendingOrderByColumn(JournalCommentPeer::UPDATED_AT);
	}
	
	/**
	 * Order by create date desc
	 *
	 * @return     JournalCommentQuery The current query, for fuid interface
	 */
	public function lastCreatedFirst()
	{
		return $this->addDescendingOrderByColumn(JournalCommentPeer::CREATED_AT);
	}
	
	/**
	 * Order by create date asc
	 *
	 * @return     JournalCommentQuery The current query, for fuid interface
	 */
	public function firstCreatedFirst()
	{
		return $this->addAscendingOrderByColumn(JournalCommentPeer::CREATED_AT);
	}

} // BaseJournalCommentQuery
