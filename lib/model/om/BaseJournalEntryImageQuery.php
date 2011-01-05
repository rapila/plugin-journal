<?php


/**
 * Base class that represents a query for the 'journal_entry_images' table.
 *
 * 
 *
 * @method     JournalEntryImageQuery orderByJournalEntryId($order = Criteria::ASC) Order by the journal_entry_id column
 * @method     JournalEntryImageQuery orderByDocumentId($order = Criteria::ASC) Order by the document_id column
 * @method     JournalEntryImageQuery orderByLegend($order = Criteria::ASC) Order by the legend column
 * @method     JournalEntryImageQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     JournalEntryImageQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     JournalEntryImageQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method     JournalEntryImageQuery orderByUpdatedBy($order = Criteria::ASC) Order by the updated_by column
 *
 * @method     JournalEntryImageQuery groupByJournalEntryId() Group by the journal_entry_id column
 * @method     JournalEntryImageQuery groupByDocumentId() Group by the document_id column
 * @method     JournalEntryImageQuery groupByLegend() Group by the legend column
 * @method     JournalEntryImageQuery groupByCreatedAt() Group by the created_at column
 * @method     JournalEntryImageQuery groupByUpdatedAt() Group by the updated_at column
 * @method     JournalEntryImageQuery groupByCreatedBy() Group by the created_by column
 * @method     JournalEntryImageQuery groupByUpdatedBy() Group by the updated_by column
 *
 * @method     JournalEntryImageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     JournalEntryImageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     JournalEntryImageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     JournalEntryImageQuery leftJoinJournalEntry($relationAlias = '') Adds a LEFT JOIN clause to the query using the JournalEntry relation
 * @method     JournalEntryImageQuery rightJoinJournalEntry($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JournalEntry relation
 * @method     JournalEntryImageQuery innerJoinJournalEntry($relationAlias = '') Adds a INNER JOIN clause to the query using the JournalEntry relation
 *
 * @method     JournalEntryImageQuery leftJoinDocument($relationAlias = '') Adds a LEFT JOIN clause to the query using the Document relation
 * @method     JournalEntryImageQuery rightJoinDocument($relationAlias = '') Adds a RIGHT JOIN clause to the query using the Document relation
 * @method     JournalEntryImageQuery innerJoinDocument($relationAlias = '') Adds a INNER JOIN clause to the query using the Document relation
 *
 * @method     JournalEntryImageQuery leftJoinUserRelatedByCreatedBy($relationAlias = '') Adds a LEFT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     JournalEntryImageQuery rightJoinUserRelatedByCreatedBy($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     JournalEntryImageQuery innerJoinUserRelatedByCreatedBy($relationAlias = '') Adds a INNER JOIN clause to the query using the UserRelatedByCreatedBy relation
 *
 * @method     JournalEntryImageQuery leftJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a LEFT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     JournalEntryImageQuery rightJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     JournalEntryImageQuery innerJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a INNER JOIN clause to the query using the UserRelatedByUpdatedBy relation
 *
 * @method     JournalEntryImage findOne(PropelPDO $con = null) Return the first JournalEntryImage matching the query
 * @method     JournalEntryImage findOneByJournalEntryId(int $journal_entry_id) Return the first JournalEntryImage filtered by the journal_entry_id column
 * @method     JournalEntryImage findOneByDocumentId(int $document_id) Return the first JournalEntryImage filtered by the document_id column
 * @method     JournalEntryImage findOneByLegend(string $legend) Return the first JournalEntryImage filtered by the legend column
 * @method     JournalEntryImage findOneByCreatedAt(string $created_at) Return the first JournalEntryImage filtered by the created_at column
 * @method     JournalEntryImage findOneByUpdatedAt(string $updated_at) Return the first JournalEntryImage filtered by the updated_at column
 * @method     JournalEntryImage findOneByCreatedBy(int $created_by) Return the first JournalEntryImage filtered by the created_by column
 * @method     JournalEntryImage findOneByUpdatedBy(int $updated_by) Return the first JournalEntryImage filtered by the updated_by column
 *
 * @method     array findByJournalEntryId(int $journal_entry_id) Return JournalEntryImage objects filtered by the journal_entry_id column
 * @method     array findByDocumentId(int $document_id) Return JournalEntryImage objects filtered by the document_id column
 * @method     array findByLegend(string $legend) Return JournalEntryImage objects filtered by the legend column
 * @method     array findByCreatedAt(string $created_at) Return JournalEntryImage objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return JournalEntryImage objects filtered by the updated_at column
 * @method     array findByCreatedBy(int $created_by) Return JournalEntryImage objects filtered by the created_by column
 * @method     array findByUpdatedBy(int $updated_by) Return JournalEntryImage objects filtered by the updated_by column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseJournalEntryImageQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseJournalEntryImageQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'mini_cms', $modelName = 'JournalEntryImage', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new JournalEntryImageQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    JournalEntryImageQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof JournalEntryImageQuery) {
			return $criteria;
		}
		$query = new JournalEntryImageQuery();
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
	 * @return    JournalEntryImage|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = JournalEntryImagePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(JournalEntryImagePeer::JOURNAL_ENTRY_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(JournalEntryImagePeer::JOURNAL_ENTRY_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the journal_entry_id column
	 * 
	 * @param     int|array $journalEntryId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByJournalEntryId($journalEntryId = null, $comparison = null)
	{
		if (is_array($journalEntryId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(JournalEntryImagePeer::JOURNAL_ENTRY_ID, $journalEntryId, $comparison);
	}

	/**
	 * Filter the query on the document_id column
	 * 
	 * @param     int|array $documentId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByDocumentId($documentId = null, $comparison = null)
	{
		if (is_array($documentId)) {
			$useMinMax = false;
			if (isset($documentId['min'])) {
				$this->addUsingAlias(JournalEntryImagePeer::DOCUMENT_ID, $documentId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($documentId['max'])) {
				$this->addUsingAlias(JournalEntryImagePeer::DOCUMENT_ID, $documentId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalEntryImagePeer::DOCUMENT_ID, $documentId, $comparison);
	}

	/**
	 * Filter the query on the legend column
	 * 
	 * @param     string $legend The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByLegend($legend = null, $comparison = null)
	{
		if (is_array($legend)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $legend)) {
			$legend = str_replace('*', '%', $legend);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(JournalEntryImagePeer::LEGEND, $legend, $comparison);
	}

	/**
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(JournalEntryImagePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(JournalEntryImagePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalEntryImagePeer::CREATED_AT, $createdAt, $comparison);
	}

	/**
	 * Filter the query on the updated_at column
	 * 
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(JournalEntryImagePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(JournalEntryImagePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalEntryImagePeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Filter the query on the created_by column
	 * 
	 * @param     int|array $createdBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByCreatedBy($createdBy = null, $comparison = null)
	{
		if (is_array($createdBy)) {
			$useMinMax = false;
			if (isset($createdBy['min'])) {
				$this->addUsingAlias(JournalEntryImagePeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdBy['max'])) {
				$this->addUsingAlias(JournalEntryImagePeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalEntryImagePeer::CREATED_BY, $createdBy, $comparison);
	}

	/**
	 * Filter the query on the updated_by column
	 * 
	 * @param     int|array $updatedBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByUpdatedBy($updatedBy = null, $comparison = null)
	{
		if (is_array($updatedBy)) {
			$useMinMax = false;
			if (isset($updatedBy['min'])) {
				$this->addUsingAlias(JournalEntryImagePeer::UPDATED_BY, $updatedBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedBy['max'])) {
				$this->addUsingAlias(JournalEntryImagePeer::UPDATED_BY, $updatedBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(JournalEntryImagePeer::UPDATED_BY, $updatedBy, $comparison);
	}

	/**
	 * Filter the query by a related JournalEntry object
	 *
	 * @param     JournalEntry $journalEntry  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByJournalEntry($journalEntry, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalEntryImagePeer::JOURNAL_ENTRY_ID, $journalEntry->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JournalEntry relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
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
	 * Filter the query by a related Document object
	 *
	 * @param     Document $document  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByDocument($document, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalEntryImagePeer::DOCUMENT_ID, $document->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the Document relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function joinDocument($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Document');
		
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
			$this->addJoinObject($join, 'Document');
		}
		
		return $this;
	}

	/**
	 * Use the Document relation Document object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DocumentQuery A secondary query class using the current class as primary query
	 */
	public function useDocumentQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinDocument($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Document', 'DocumentQuery');
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User $user  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByCreatedBy($user, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalEntryImagePeer::CREATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByCreatedBy relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
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
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByUpdatedBy($user, $comparison = null)
	{
		return $this
			->addUsingAlias(JournalEntryImagePeer::UPDATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByUpdatedBy relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
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
	 * @param     JournalEntryImage $journalEntryImage Object to remove from the list of results
	 *
	 * @return    JournalEntryImageQuery The current query, for fluid interface
	 */
	public function prune($journalEntryImage = null)
	{
		if ($journalEntryImage) {
			$this->addUsingAlias(JournalEntryImagePeer::JOURNAL_ENTRY_ID, $journalEntryImage->getJournalEntryId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

	// extended_timestampable behavior
	
	/**
	 * Filter by the latest updated
	 *
	 * @param      int $nbDays Maximum age of the latest update in days
	 *
	 * @return     JournalEntryImageQuery The current query, for fuid interface
	 */
	public function recentlyUpdated($nbDays = 7)
	{
		return $this->addUsingAlias(JournalEntryImagePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Filter by the latest created
	 *
	 * @param      int $nbDays Maximum age of in days
	 *
	 * @return     JournalEntryImageQuery The current query, for fuid interface
	 */
	public function recentlyCreated($nbDays = 7)
	{
		return $this->addUsingAlias(JournalEntryImagePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Order by update date desc
	 *
	 * @return     JournalEntryImageQuery The current query, for fuid interface
	 */
	public function lastUpdatedFirst()
	{
		return $this->addDescendingOrderByColumn(JournalEntryImagePeer::UPDATED_AT);
	}
	
	/**
	 * Order by update date asc
	 *
	 * @return     JournalEntryImageQuery The current query, for fuid interface
	 */
	public function firstUpdatedFirst()
	{
		return $this->addAscendingOrderByColumn(JournalEntryImagePeer::UPDATED_AT);
	}
	
	/**
	 * Order by create date desc
	 *
	 * @return     JournalEntryImageQuery The current query, for fuid interface
	 */
	public function lastCreatedFirst()
	{
		return $this->addDescendingOrderByColumn(JournalEntryImagePeer::CREATED_AT);
	}
	
	/**
	 * Order by create date asc
	 *
	 * @return     JournalEntryImageQuery The current query, for fuid interface
	 */
	public function firstCreatedFirst()
	{
		return $this->addAscendingOrderByColumn(JournalEntryImagePeer::CREATED_AT);
	}

} // BaseJournalEntryImageQuery
