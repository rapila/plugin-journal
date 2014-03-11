<?php


/**
 * Base class that represents a query for the 'journal_entries' table.
 *
 *
 *
 * @method JournalEntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method JournalEntryQuery orderByJournalId($order = Criteria::ASC) Order by the journal_id column
 * @method JournalEntryQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method JournalEntryQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method JournalEntryQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method JournalEntryQuery orderByTextShort($order = Criteria::ASC) Order by the text_short column
 * @method JournalEntryQuery orderByIsPublished($order = Criteria::ASC) Order by the is_published column
 * @method JournalEntryQuery orderByPublishAt($order = Criteria::ASC) Order by the publish_at column
 * @method JournalEntryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method JournalEntryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method JournalEntryQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method JournalEntryQuery orderByUpdatedBy($order = Criteria::ASC) Order by the updated_by column
 *
 * @method JournalEntryQuery groupById() Group by the id column
 * @method JournalEntryQuery groupByJournalId() Group by the journal_id column
 * @method JournalEntryQuery groupByTitle() Group by the title column
 * @method JournalEntryQuery groupBySlug() Group by the slug column
 * @method JournalEntryQuery groupByText() Group by the text column
 * @method JournalEntryQuery groupByTextShort() Group by the text_short column
 * @method JournalEntryQuery groupByIsPublished() Group by the is_published column
 * @method JournalEntryQuery groupByPublishAt() Group by the publish_at column
 * @method JournalEntryQuery groupByCreatedAt() Group by the created_at column
 * @method JournalEntryQuery groupByUpdatedAt() Group by the updated_at column
 * @method JournalEntryQuery groupByCreatedBy() Group by the created_by column
 * @method JournalEntryQuery groupByUpdatedBy() Group by the updated_by column
 *
 * @method JournalEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method JournalEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method JournalEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method JournalEntryQuery leftJoinJournal($relationAlias = null) Adds a LEFT JOIN clause to the query using the Journal relation
 * @method JournalEntryQuery rightJoinJournal($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Journal relation
 * @method JournalEntryQuery innerJoinJournal($relationAlias = null) Adds a INNER JOIN clause to the query using the Journal relation
 *
 * @method JournalEntryQuery leftJoinUserRelatedByCreatedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method JournalEntryQuery rightJoinUserRelatedByCreatedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method JournalEntryQuery innerJoinUserRelatedByCreatedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByCreatedBy relation
 *
 * @method JournalEntryQuery leftJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method JournalEntryQuery rightJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method JournalEntryQuery innerJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByUpdatedBy relation
 *
 * @method JournalEntryQuery leftJoinJournalComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the JournalComment relation
 * @method JournalEntryQuery rightJoinJournalComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the JournalComment relation
 * @method JournalEntryQuery innerJoinJournalComment($relationAlias = null) Adds a INNER JOIN clause to the query using the JournalComment relation
 *
 * @method JournalEntryQuery leftJoinJournalEntryImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the JournalEntryImage relation
 * @method JournalEntryQuery rightJoinJournalEntryImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the JournalEntryImage relation
 * @method JournalEntryQuery innerJoinJournalEntryImage($relationAlias = null) Adds a INNER JOIN clause to the query using the JournalEntryImage relation
 *
 * @method JournalEntry findOne(PropelPDO $con = null) Return the first JournalEntry matching the query
 * @method JournalEntry findOneOrCreate(PropelPDO $con = null) Return the first JournalEntry matching the query, or a new JournalEntry object populated from the query conditions when no match is found
 *
 * @method JournalEntry findOneById(int $id) Return the first JournalEntry filtered by the id column
 * @method JournalEntry findOneByJournalId(int $journal_id) Return the first JournalEntry filtered by the journal_id column
 * @method JournalEntry findOneByTitle(string $title) Return the first JournalEntry filtered by the title column
 * @method JournalEntry findOneBySlug(string $slug) Return the first JournalEntry filtered by the slug column
 * @method JournalEntry findOneByText(string $text) Return the first JournalEntry filtered by the text column
 * @method JournalEntry findOneByTextShort(string $text_short) Return the first JournalEntry filtered by the text_short column
 * @method JournalEntry findOneByIsPublished(boolean $is_published) Return the first JournalEntry filtered by the is_published column
 * @method JournalEntry findOneByPublishAt(string $publish_at) Return the first JournalEntry filtered by the publish_at column
 * @method JournalEntry findOneByCreatedAt(string $created_at) Return the first JournalEntry filtered by the created_at column
 * @method JournalEntry findOneByUpdatedAt(string $updated_at) Return the first JournalEntry filtered by the updated_at column
 * @method JournalEntry findOneByCreatedBy(int $created_by) Return the first JournalEntry filtered by the created_by column
 * @method JournalEntry findOneByUpdatedBy(int $updated_by) Return the first JournalEntry filtered by the updated_by column
 *
 * @method array findById(int $id) Return JournalEntry objects filtered by the id column
 * @method array findByJournalId(int $journal_id) Return JournalEntry objects filtered by the journal_id column
 * @method array findByTitle(string $title) Return JournalEntry objects filtered by the title column
 * @method array findBySlug(string $slug) Return JournalEntry objects filtered by the slug column
 * @method array findByText(string $text) Return JournalEntry objects filtered by the text column
 * @method array findByTextShort(string $text_short) Return JournalEntry objects filtered by the text_short column
 * @method array findByIsPublished(boolean $is_published) Return JournalEntry objects filtered by the is_published column
 * @method array findByPublishAt(string $publish_at) Return JournalEntry objects filtered by the publish_at column
 * @method array findByCreatedAt(string $created_at) Return JournalEntry objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return JournalEntry objects filtered by the updated_at column
 * @method array findByCreatedBy(int $created_by) Return JournalEntry objects filtered by the created_by column
 * @method array findByUpdatedBy(int $updated_by) Return JournalEntry objects filtered by the updated_by column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseJournalEntryQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseJournalEntryQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'rapila', $modelName = 'JournalEntry', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new JournalEntryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     JournalEntryQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return JournalEntryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof JournalEntryQuery) {
            return $criteria;
        }
        $query = new JournalEntryQuery();
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
     * @param     PropelPDO $con an optional connection object
     *
     * @return   JournalEntry|JournalEntry[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = JournalEntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   JournalEntry A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `JOURNAL_ID`, `TITLE`, `SLUG`, `TEXT`, `TEXT_SHORT`, `IS_PUBLISHED`, `PUBLISH_AT`, `CREATED_AT`, `UPDATED_AT`, `CREATED_BY`, `UPDATED_BY` FROM `journal_entries` WHERE `ID` = :p0';
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
            $obj = new JournalEntry();
            $obj->hydrate($row);
            JournalEntryPeer::addInstanceToPool($obj, (string) $key);
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
     * @return JournalEntry|JournalEntry[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|JournalEntry[]|mixed the list of results, formatted by the current formatter
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
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(JournalEntryPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(JournalEntryPeer::ID, $keys, Criteria::IN);
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
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(JournalEntryPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the journal_id column
     *
     * Example usage:
     * <code>
     * $query->filterByJournalId(1234); // WHERE journal_id = 1234
     * $query->filterByJournalId(array(12, 34)); // WHERE journal_id IN (12, 34)
     * $query->filterByJournalId(array('min' => 12)); // WHERE journal_id > 12
     * </code>
     *
     * @see       filterByJournal()
     *
     * @param     mixed $journalId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByJournalId($journalId = null, $comparison = null)
    {
        if (is_array($journalId)) {
            $useMinMax = false;
            if (isset($journalId['min'])) {
                $this->addUsingAlias(JournalEntryPeer::JOURNAL_ID, $journalId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($journalId['max'])) {
                $this->addUsingAlias(JournalEntryPeer::JOURNAL_ID, $journalId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::JOURNAL_ID, $journalId, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%'); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slug)) {
                $slug = str_replace('*', '%', $slug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::SLUG, $slug, $comparison);
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
     * @return JournalEntryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(JournalEntryPeer::TEXT, $text, $comparison);
    }

    /**
     * Filter the query on the text_short column
     *
     * Example usage:
     * <code>
     * $query->filterByTextShort('fooValue');   // WHERE text_short = 'fooValue'
     * $query->filterByTextShort('%fooValue%'); // WHERE text_short LIKE '%fooValue%'
     * </code>
     *
     * @param     string $textShort The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByTextShort($textShort = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($textShort)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $textShort)) {
                $textShort = str_replace('*', '%', $textShort);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::TEXT_SHORT, $textShort, $comparison);
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
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByIsPublished($isPublished = null, $comparison = null)
    {
        if (is_string($isPublished)) {
            $is_published = in_array(strtolower($isPublished), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(JournalEntryPeer::IS_PUBLISHED, $isPublished, $comparison);
    }

    /**
     * Filter the query on the publish_at column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishAt('2011-03-14'); // WHERE publish_at = '2011-03-14'
     * $query->filterByPublishAt('now'); // WHERE publish_at = '2011-03-14'
     * $query->filterByPublishAt(array('max' => 'yesterday')); // WHERE publish_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $publishAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByPublishAt($publishAt = null, $comparison = null)
    {
        if (is_array($publishAt)) {
            $useMinMax = false;
            if (isset($publishAt['min'])) {
                $this->addUsingAlias(JournalEntryPeer::PUBLISH_AT, $publishAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishAt['max'])) {
                $this->addUsingAlias(JournalEntryPeer::PUBLISH_AT, $publishAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::PUBLISH_AT, $publishAt, $comparison);
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
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(JournalEntryPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(JournalEntryPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(JournalEntryPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(JournalEntryPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByCreatedBy($createdBy = null, $comparison = null)
    {
        if (is_array($createdBy)) {
            $useMinMax = false;
            if (isset($createdBy['min'])) {
                $this->addUsingAlias(JournalEntryPeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdBy['max'])) {
                $this->addUsingAlias(JournalEntryPeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::CREATED_BY, $createdBy, $comparison);
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
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function filterByUpdatedBy($updatedBy = null, $comparison = null)
    {
        if (is_array($updatedBy)) {
            $useMinMax = false;
            if (isset($updatedBy['min'])) {
                $this->addUsingAlias(JournalEntryPeer::UPDATED_BY, $updatedBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedBy['max'])) {
                $this->addUsingAlias(JournalEntryPeer::UPDATED_BY, $updatedBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(JournalEntryPeer::UPDATED_BY, $updatedBy, $comparison);
    }

    /**
     * Filter the query by a related Journal object
     *
     * @param   Journal|PropelObjectCollection $journal The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   JournalEntryQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByJournal($journal, $comparison = null)
    {
        if ($journal instanceof Journal) {
            return $this
                ->addUsingAlias(JournalEntryPeer::JOURNAL_ID, $journal->getId(), $comparison);
        } elseif ($journal instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(JournalEntryPeer::JOURNAL_ID, $journal->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByJournal() only accepts arguments of type Journal or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Journal relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function joinJournal($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Journal');

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
            $this->addJoinObject($join, 'Journal');
        }

        return $this;
    }

    /**
     * Use the Journal relation Journal object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   JournalQuery A secondary query class using the current class as primary query
     */
    public function useJournalQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinJournal($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Journal', 'JournalQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   JournalEntryQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserRelatedByCreatedBy($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(JournalEntryPeer::CREATED_BY, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(JournalEntryPeer::CREATED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return JournalEntryQuery The current query, for fluid interface
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
        if ($relationAlias) {
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
     * @return   UserQuery A secondary query class using the current class as primary query
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
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   JournalEntryQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserRelatedByUpdatedBy($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(JournalEntryPeer::UPDATED_BY, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(JournalEntryPeer::UPDATED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return JournalEntryQuery The current query, for fluid interface
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
        if ($relationAlias) {
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
     * @return   UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByUpdatedByQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByUpdatedBy($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUpdatedBy', 'UserQuery');
    }

    /**
     * Filter the query by a related JournalComment object
     *
     * @param   JournalComment|PropelObjectCollection $journalComment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   JournalEntryQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByJournalComment($journalComment, $comparison = null)
    {
        if ($journalComment instanceof JournalComment) {
            return $this
                ->addUsingAlias(JournalEntryPeer::ID, $journalComment->getJournalEntryId(), $comparison);
        } elseif ($journalComment instanceof PropelObjectCollection) {
            return $this
                ->useJournalCommentQuery()
                ->filterByPrimaryKeys($journalComment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByJournalComment() only accepts arguments of type JournalComment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the JournalComment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function joinJournalComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('JournalComment');

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
            $this->addJoinObject($join, 'JournalComment');
        }

        return $this;
    }

    /**
     * Use the JournalComment relation JournalComment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   JournalCommentQuery A secondary query class using the current class as primary query
     */
    public function useJournalCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinJournalComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'JournalComment', 'JournalCommentQuery');
    }

    /**
     * Filter the query by a related JournalEntryImage object
     *
     * @param   JournalEntryImage|PropelObjectCollection $journalEntryImage  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   JournalEntryQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByJournalEntryImage($journalEntryImage, $comparison = null)
    {
        if ($journalEntryImage instanceof JournalEntryImage) {
            return $this
                ->addUsingAlias(JournalEntryPeer::ID, $journalEntryImage->getJournalEntryId(), $comparison);
        } elseif ($journalEntryImage instanceof PropelObjectCollection) {
            return $this
                ->useJournalEntryImageQuery()
                ->filterByPrimaryKeys($journalEntryImage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByJournalEntryImage() only accepts arguments of type JournalEntryImage or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the JournalEntryImage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function joinJournalEntryImage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('JournalEntryImage');

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
            $this->addJoinObject($join, 'JournalEntryImage');
        }

        return $this;
    }

    /**
     * Use the JournalEntryImage relation JournalEntryImage object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   JournalEntryImageQuery A secondary query class using the current class as primary query
     */
    public function useJournalEntryImageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinJournalEntryImage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'JournalEntryImage', 'JournalEntryImageQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   JournalEntry $journalEntry Object to remove from the list of results
     *
     * @return JournalEntryQuery The current query, for fluid interface
     */
    public function prune($journalEntry = null)
    {
        if ($journalEntry) {
            $this->addUsingAlias(JournalEntryPeer::ID, $journalEntry->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // extended_timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     JournalEntryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(JournalEntryPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     JournalEntryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(JournalEntryPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     JournalEntryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(JournalEntryPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     JournalEntryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(JournalEntryPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     JournalEntryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(JournalEntryPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     JournalEntryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(JournalEntryPeer::CREATED_AT);
    }
    // extended_keyable behavior

    public function filterByPKArray($pkArray) {
            return $this->filterByPrimaryKey($pkArray[0]);
    }

    public function filterByPKString($pkString) {
        return $this->filterByPrimaryKey($pkString);
    }

}
