<?php


/**
 * Base class that represents a query for the 'journals' table.
 *
 * 
 *
 * @method     JournalQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     JournalQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     JournalQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     JournalQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     JournalQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     JournalQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method     JournalQuery orderByUpdatedBy($order = Criteria::ASC) Order by the updated_by column
 *
 * @method     JournalQuery groupById() Group by the id column
 * @method     JournalQuery groupByName() Group by the name column
 * @method     JournalQuery groupByDescription() Group by the description column
 * @method     JournalQuery groupByCreatedAt() Group by the created_at column
 * @method     JournalQuery groupByUpdatedAt() Group by the updated_at column
 * @method     JournalQuery groupByCreatedBy() Group by the created_by column
 * @method     JournalQuery groupByUpdatedBy() Group by the updated_by column
 *
 * @method     JournalQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     JournalQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     JournalQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     JournalQuery leftJoinUserRelatedByCreatedBy($relationAlias = '') Adds a LEFT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     JournalQuery rightJoinUserRelatedByCreatedBy($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     JournalQuery innerJoinUserRelatedByCreatedBy($relationAlias = '') Adds a INNER JOIN clause to the query using the UserRelatedByCreatedBy relation
 *
 * @method     JournalQuery leftJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a LEFT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     JournalQuery rightJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     JournalQuery innerJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a INNER JOIN clause to the query using the UserRelatedByUpdatedBy relation
 *
 * @method     JournalQuery leftJoinJournalEntry($relationAlias = '') Adds a LEFT JOIN clause to the query using the JournalEntry relation
 * @method     JournalQuery rightJoinJournalEntry($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JournalEntry relation
 * @method     JournalQuery innerJoinJournalEntry($relationAlias = '') Adds a INNER JOIN clause to the query using the JournalEntry relation
 *
 * @method     Journal findOne(PropelPDO $con = null) Return the first Journal matching the query
 * @method     Journal findOneById(int $id) Return the first Journal filtered by the id column
 * @method     Journal findOneByName(string $name) Return the first Journal filtered by the name column
 * @method     Journal findOneByDescription(string $description) Return the first Journal filtered by the description column
 * @method     Journal findOneByCreatedAt(string $created_at) Return the first Journal filtered by the created_at column
 * @method     Journal findOneByUpdatedAt(string $updated_at) Return the first Journal filtered by the updated_at column
 * @method     Journal findOneByCreatedBy(int $created_by) Return the first Journal filtered by the created_by column
 * @method     Journal findOneByUpdatedBy(int $updated_by) Return the first Journal filtered by the updated_by column
 *
 * @method     array findById(int $id) Return Journal objects filtered by the id column
 * @method     array findByName(string $name) Return Journal objects filtered by the name column
 * @method     array findByDescription(string $description) Return Journal objects filtered by the description column
 * @method     array findByCreatedAt(string $created_at) Return Journal objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return Journal objects filtered by the updated_at column
 * @method     array findByCreatedBy(int $created_by) Return Journal objects filtered by the created_by column
 * @method     array findByUpdatedBy(int $updated_by) Return Journal objects filtered by the updated_by column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseJournalQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseJournalQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'mini_cms', $modelName = 'Journal', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new JournalQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    JournalQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof JournalQuery) {
			return $criteria;
		}
		$query = new JournalQuery();
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
	 * @return    Journal|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = JournalPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(JournalPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(JournalPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(JournalPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the name column
	 * 
	 * @param     string $name The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByName($name = null, $comparison = null)
	{
		if (is_array($name)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $name)) {
			$name = str_replace('*', '%', $name);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the description column
	 * 
	 * @param     string $description The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByDescription($description = null, $comparison = null)
	{
		if (is_array($description)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $description)) {
			$description = str_replace('*', '%', $description);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalPeer::DESCRIPTION, $description, $comparison);
	}

	/**
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(JournalPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(JournalPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalPeer::CREATED_AT, $createdAt, $comparison);
	}

	/**
	 * Filter the query on the updated_at column
	 * 
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(JournalPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(JournalPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalPeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Filter the query on the created_by column
	 * 
	 * @param     int|array $createdBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByCreatedBy($createdBy = null, $comparison = null)
	{
		if (is_array($createdBy)) {
			$useMinMax = false;
			if (isset($createdBy['min'])) {
				$this->addUsingAlias(JournalPeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdBy['max'])) {
				$this->addUsingAlias(JournalPeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalPeer::CREATED_BY, $createdBy, $comparison);
	}

	/**
	 * Filter the query on the updated_by column
	 * 
	 * @param     int|array $updatedBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByUpdatedBy($updatedBy = null, $comparison = null)
	{
		if (is_array($updatedBy)) {
			$useMinMax = false;
			if (isset($updatedBy['min'])) {
				$this->addUsingAlias(JournalPeer::UPDATED_BY, $updatedBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedBy['max'])) {
				$this->addUsingAlias(JournalPeer::UPDATED_BY, $updatedBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalPeer::UPDATED_BY, $updatedBy, $comparison);
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User $user  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByCreatedBy($user, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalPeer::CREATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByCreatedBy relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalQuery The current query, for fluid interface
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
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByUpdatedBy($user, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalPeer::UPDATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByUpdatedBy relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalQuery The current query, for fluid interface
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
	 * Filter the query by a related JournalEntry object
	 *
	 * @param     JournalEntry $journalEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function filterByJournalEntry($journalEntry, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalPeer::ID, $journalEntry->getJournalId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JournalEntry relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function joinJournalEntry($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
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
	public function useJournalEntryQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinJournalEntry($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JournalEntry', 'JournalEntryQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     Journal $journal Object to remove from the list of results
	 *
	 * @return    JournalQuery The current query, for fluid interface
	 */
	public function prune($journal = null)
	{
		if ($journal) {
			$this->addUsingAlias(JournalPeer::ID, $journal->getId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

	// extended_timestampable behavior
	
	/**
	 * Filter by the latest updated
	 *
	 * @param      int $nbDays Maximum age of the latest update in days
	 *
	 * @return     JournalQuery The current query, for fuid interface
	 */
	public function recentlyUpdated($nbDays = 7)
	{
		return $this->addUsingAlias(JournalPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Filter by the latest created
	 *
	 * @param      int $nbDays Maximum age of in days
	 *
	 * @return     JournalQuery The current query, for fuid interface
	 */
	public function recentlyCreated($nbDays = 7)
	{
		return $this->addUsingAlias(JournalPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Order by update date desc
	 *
	 * @return     JournalQuery The current query, for fuid interface
	 */
	public function lastUpdatedFirst()
	{
		return $this->addDescendingOrderByColumn(JournalPeer::UPDATED_AT);
	}
	
	/**
	 * Order by update date asc
	 *
	 * @return     JournalQuery The current query, for fuid interface
	 */
	public function firstUpdatedFirst()
	{
		return $this->addAscendingOrderByColumn(JournalPeer::UPDATED_AT);
	}
	
	/**
	 * Order by create date desc
	 *
	 * @return     JournalQuery The current query, for fuid interface
	 */
	public function lastCreatedFirst()
	{
		return $this->addDescendingOrderByColumn(JournalPeer::CREATED_AT);
	}
	
	/**
	 * Order by create date asc
	 *
	 * @return     JournalQuery The current query, for fuid interface
	 */
	public function firstCreatedFirst()
	{
		return $this->addAscendingOrderByColumn(JournalPeer::CREATED_AT);
	}

} // BaseJournalQuery
