<?php


/**
 * Base static class for performing query and update operations on the 'journal_entries' table.
 *
 *
 *
 * @package propel.generator.model.om
 */
abstract class BaseJournalEntryPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'rapila';

    /** the table name for this class */
    const TABLE_NAME = 'journal_entries';

    /** the related Propel class for this table */
    const OM_CLASS = 'JournalEntry';

    /** the related TableMap class for this table */
    const TM_CLASS = 'JournalEntryTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 12;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 12;

    /** the column name for the id field */
    const ID = 'journal_entries.id';

    /** the column name for the journal_id field */
    const JOURNAL_ID = 'journal_entries.journal_id';

    /** the column name for the title field */
    const TITLE = 'journal_entries.title';

    /** the column name for the slug field */
    const SLUG = 'journal_entries.slug';

    /** the column name for the text field */
    const TEXT = 'journal_entries.text';

    /** the column name for the text_short field */
    const TEXT_SHORT = 'journal_entries.text_short';

    /** the column name for the is_published field */
    const IS_PUBLISHED = 'journal_entries.is_published';

    /** the column name for the publish_at field */
    const PUBLISH_AT = 'journal_entries.publish_at';

    /** the column name for the created_at field */
    const CREATED_AT = 'journal_entries.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'journal_entries.updated_at';

    /** the column name for the created_by field */
    const CREATED_BY = 'journal_entries.created_by';

    /** the column name for the updated_by field */
    const UPDATED_BY = 'journal_entries.updated_by';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of JournalEntry objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array JournalEntry[]
     */
    public static $instances = array();


    // denyable behavior
    private static $IGNORE_RIGHTS = false;
    private static $RIGHTS_USER = false;

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. JournalEntryPeer::$fieldNames[JournalEntryPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'JournalId', 'Title', 'Slug', 'Text', 'TextShort', 'IsPublished', 'PublishAt', 'CreatedAt', 'UpdatedAt', 'CreatedBy', 'UpdatedBy', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'journalId', 'title', 'slug', 'text', 'textShort', 'isPublished', 'publishAt', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy', ),
        BasePeer::TYPE_COLNAME => array (JournalEntryPeer::ID, JournalEntryPeer::JOURNAL_ID, JournalEntryPeer::TITLE, JournalEntryPeer::SLUG, JournalEntryPeer::TEXT, JournalEntryPeer::TEXT_SHORT, JournalEntryPeer::IS_PUBLISHED, JournalEntryPeer::PUBLISH_AT, JournalEntryPeer::CREATED_AT, JournalEntryPeer::UPDATED_AT, JournalEntryPeer::CREATED_BY, JournalEntryPeer::UPDATED_BY, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'JOURNAL_ID', 'TITLE', 'SLUG', 'TEXT', 'TEXT_SHORT', 'IS_PUBLISHED', 'PUBLISH_AT', 'CREATED_AT', 'UPDATED_AT', 'CREATED_BY', 'UPDATED_BY', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'journal_id', 'title', 'slug', 'text', 'text_short', 'is_published', 'publish_at', 'created_at', 'updated_at', 'created_by', 'updated_by', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. JournalEntryPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'JournalId' => 1, 'Title' => 2, 'Slug' => 3, 'Text' => 4, 'TextShort' => 5, 'IsPublished' => 6, 'PublishAt' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, 'CreatedBy' => 10, 'UpdatedBy' => 11, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'journalId' => 1, 'title' => 2, 'slug' => 3, 'text' => 4, 'textShort' => 5, 'isPublished' => 6, 'publishAt' => 7, 'createdAt' => 8, 'updatedAt' => 9, 'createdBy' => 10, 'updatedBy' => 11, ),
        BasePeer::TYPE_COLNAME => array (JournalEntryPeer::ID => 0, JournalEntryPeer::JOURNAL_ID => 1, JournalEntryPeer::TITLE => 2, JournalEntryPeer::SLUG => 3, JournalEntryPeer::TEXT => 4, JournalEntryPeer::TEXT_SHORT => 5, JournalEntryPeer::IS_PUBLISHED => 6, JournalEntryPeer::PUBLISH_AT => 7, JournalEntryPeer::CREATED_AT => 8, JournalEntryPeer::UPDATED_AT => 9, JournalEntryPeer::CREATED_BY => 10, JournalEntryPeer::UPDATED_BY => 11, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'JOURNAL_ID' => 1, 'TITLE' => 2, 'SLUG' => 3, 'TEXT' => 4, 'TEXT_SHORT' => 5, 'IS_PUBLISHED' => 6, 'PUBLISH_AT' => 7, 'CREATED_AT' => 8, 'UPDATED_AT' => 9, 'CREATED_BY' => 10, 'UPDATED_BY' => 11, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'journal_id' => 1, 'title' => 2, 'slug' => 3, 'text' => 4, 'text_short' => 5, 'is_published' => 6, 'publish_at' => 7, 'created_at' => 8, 'updated_at' => 9, 'created_by' => 10, 'updated_by' => 11, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = JournalEntryPeer::getFieldNames($toType);
        $key = isset(JournalEntryPeer::$fieldKeys[$fromType][$name]) ? JournalEntryPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(JournalEntryPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, JournalEntryPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return JournalEntryPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. JournalEntryPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(JournalEntryPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(JournalEntryPeer::ID);
            $criteria->addSelectColumn(JournalEntryPeer::JOURNAL_ID);
            $criteria->addSelectColumn(JournalEntryPeer::TITLE);
            $criteria->addSelectColumn(JournalEntryPeer::SLUG);
            $criteria->addSelectColumn(JournalEntryPeer::TEXT);
            $criteria->addSelectColumn(JournalEntryPeer::TEXT_SHORT);
            $criteria->addSelectColumn(JournalEntryPeer::IS_PUBLISHED);
            $criteria->addSelectColumn(JournalEntryPeer::PUBLISH_AT);
            $criteria->addSelectColumn(JournalEntryPeer::CREATED_AT);
            $criteria->addSelectColumn(JournalEntryPeer::UPDATED_AT);
            $criteria->addSelectColumn(JournalEntryPeer::CREATED_BY);
            $criteria->addSelectColumn(JournalEntryPeer::UPDATED_BY);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.journal_id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.slug');
            $criteria->addSelectColumn($alias . '.text');
            $criteria->addSelectColumn($alias . '.text_short');
            $criteria->addSelectColumn($alias . '.is_published');
            $criteria->addSelectColumn($alias . '.publish_at');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.created_by');
            $criteria->addSelectColumn($alias . '.updated_by');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            JournalEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return JournalEntry
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = JournalEntryPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return JournalEntryPeer::populateObjects(JournalEntryPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            JournalEntryPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param JournalEntry $obj A JournalEntry object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            JournalEntryPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A JournalEntry object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof JournalEntry) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or JournalEntry object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(JournalEntryPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return JournalEntry Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(JournalEntryPeer::$instances[$key])) {
                return JournalEntryPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (JournalEntryPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        JournalEntryPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to journal_entries
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in JournalCommentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        JournalCommentPeer::clearInstancePool();
        // Invalidate objects in JournalEntryImagePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        JournalEntryImagePeer::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = JournalEntryPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = JournalEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = JournalEntryPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                JournalEntryPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (JournalEntry object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = JournalEntryPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = JournalEntryPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + JournalEntryPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = JournalEntryPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            JournalEntryPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Journal table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinJournal(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            JournalEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(JournalEntryPeer::JOURNAL_ID, JournalPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related UserRelatedByCreatedBy table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUserRelatedByCreatedBy(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            JournalEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(JournalEntryPeer::CREATED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related UserRelatedByUpdatedBy table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUserRelatedByUpdatedBy(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            JournalEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(JournalEntryPeer::UPDATED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of JournalEntry objects pre-filled with their Journal objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of JournalEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinJournal(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);
        }

        JournalEntryPeer::addSelectColumns($criteria);
        $startcol = JournalEntryPeer::NUM_HYDRATE_COLUMNS;
        JournalPeer::addSelectColumns($criteria);

        $criteria->addJoin(JournalEntryPeer::JOURNAL_ID, JournalPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = JournalEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = JournalEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = JournalEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                JournalEntryPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = JournalPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = JournalPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = JournalPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    JournalPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (JournalEntry) to $obj2 (Journal)
                $obj2->addJournalEntry($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of JournalEntry objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of JournalEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUserRelatedByCreatedBy(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);
        }

        JournalEntryPeer::addSelectColumns($criteria);
        $startcol = JournalEntryPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(JournalEntryPeer::CREATED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = JournalEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = JournalEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = JournalEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                JournalEntryPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (JournalEntry) to $obj2 (User)
                $obj2->addJournalEntryRelatedByCreatedBy($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of JournalEntry objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of JournalEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUserRelatedByUpdatedBy(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);
        }

        JournalEntryPeer::addSelectColumns($criteria);
        $startcol = JournalEntryPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(JournalEntryPeer::UPDATED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = JournalEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = JournalEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = JournalEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                JournalEntryPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (JournalEntry) to $obj2 (User)
                $obj2->addJournalEntryRelatedByUpdatedBy($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            JournalEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(JournalEntryPeer::JOURNAL_ID, JournalPeer::ID, $join_behavior);

        $criteria->addJoin(JournalEntryPeer::CREATED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(JournalEntryPeer::UPDATED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of JournalEntry objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of JournalEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);
        }

        JournalEntryPeer::addSelectColumns($criteria);
        $startcol2 = JournalEntryPeer::NUM_HYDRATE_COLUMNS;

        JournalPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + JournalPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(JournalEntryPeer::JOURNAL_ID, JournalPeer::ID, $join_behavior);

        $criteria->addJoin(JournalEntryPeer::CREATED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(JournalEntryPeer::UPDATED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = JournalEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = JournalEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = JournalEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                JournalEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Journal rows

            $key2 = JournalPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = JournalPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = JournalPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    JournalPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (JournalEntry) to the collection in $obj2 (Journal)
                $obj2->addJournalEntry($obj1);
            } // if joined row not null

            // Add objects for joined User rows

            $key3 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = UserPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = UserPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (JournalEntry) to the collection in $obj3 (User)
                $obj3->addJournalEntryRelatedByCreatedBy($obj1);
            } // if joined row not null

            // Add objects for joined User rows

            $key4 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = UserPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = UserPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    UserPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (JournalEntry) to the collection in $obj4 (User)
                $obj4->addJournalEntryRelatedByUpdatedBy($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Journal table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptJournal(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            JournalEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(JournalEntryPeer::CREATED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(JournalEntryPeer::UPDATED_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related UserRelatedByCreatedBy table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUserRelatedByCreatedBy(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            JournalEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(JournalEntryPeer::JOURNAL_ID, JournalPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related UserRelatedByUpdatedBy table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUserRelatedByUpdatedBy(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            JournalEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(JournalEntryPeer::JOURNAL_ID, JournalPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of JournalEntry objects pre-filled with all related objects except Journal.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of JournalEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptJournal(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);
        }

        JournalEntryPeer::addSelectColumns($criteria);
        $startcol2 = JournalEntryPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(JournalEntryPeer::CREATED_BY, UserPeer::ID, $join_behavior);

        $criteria->addJoin(JournalEntryPeer::UPDATED_BY, UserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = JournalEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = JournalEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = JournalEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                JournalEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (JournalEntry) to the collection in $obj2 (User)
                $obj2->addJournalEntryRelatedByCreatedBy($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key3 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = UserPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = UserPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (JournalEntry) to the collection in $obj3 (User)
                $obj3->addJournalEntryRelatedByUpdatedBy($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of JournalEntry objects pre-filled with all related objects except UserRelatedByCreatedBy.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of JournalEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUserRelatedByCreatedBy(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);
        }

        JournalEntryPeer::addSelectColumns($criteria);
        $startcol2 = JournalEntryPeer::NUM_HYDRATE_COLUMNS;

        JournalPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + JournalPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(JournalEntryPeer::JOURNAL_ID, JournalPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = JournalEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = JournalEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = JournalEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                JournalEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Journal rows

                $key2 = JournalPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = JournalPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = JournalPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    JournalPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (JournalEntry) to the collection in $obj2 (Journal)
                $obj2->addJournalEntry($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of JournalEntry objects pre-filled with all related objects except UserRelatedByUpdatedBy.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of JournalEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUserRelatedByUpdatedBy(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);
        }

        JournalEntryPeer::addSelectColumns($criteria);
        $startcol2 = JournalEntryPeer::NUM_HYDRATE_COLUMNS;

        JournalPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + JournalPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(JournalEntryPeer::JOURNAL_ID, JournalPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = JournalEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = JournalEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = JournalEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                JournalEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Journal rows

                $key2 = JournalPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = JournalPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = JournalPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    JournalPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (JournalEntry) to the collection in $obj2 (Journal)
                $obj2->addJournalEntry($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(JournalEntryPeer::DATABASE_NAME)->getTable(JournalEntryPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseJournalEntryPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseJournalEntryPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \JournalEntryTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return JournalEntryPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a JournalEntry or Criteria object.
     *
     * @param      mixed $values Criteria or JournalEntry object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from JournalEntry object
        }

        if ($criteria->containsKey(JournalEntryPeer::ID) && $criteria->keyContainsValue(JournalEntryPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.JournalEntryPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a JournalEntry or Criteria object.
     *
     * @param      mixed $values Criteria or JournalEntry object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(JournalEntryPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(JournalEntryPeer::ID);
            $value = $criteria->remove(JournalEntryPeer::ID);
            if ($value) {
                $selectCriteria->add(JournalEntryPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(JournalEntryPeer::TABLE_NAME);
            }

        } else { // $values is JournalEntry object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the journal_entries table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += JournalEntryPeer::doOnDeleteCascade(new Criteria(JournalEntryPeer::DATABASE_NAME), $con);
            $affectedRows += BasePeer::doDeleteAll(JournalEntryPeer::TABLE_NAME, $con, JournalEntryPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            JournalEntryPeer::clearInstancePool();
            JournalEntryPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a JournalEntry or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or JournalEntry object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     private static function doDeleteBeforeReferencing($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof JournalEntry) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(JournalEntryPeer::DATABASE_NAME);
            $criteria->add(JournalEntryPeer::ID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(JournalEntryPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            $affectedRows += JournalEntryPeer::doOnDeleteCascade($c, $con);

            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            if ($values instanceof Criteria) {
                JournalEntryPeer::clearInstancePool();
            } elseif ($values instanceof JournalEntry) { // it's a model object
                JournalEntryPeer::removeInstanceFromPool($values);
            } else { // it's a primary key, or an array of pks
                foreach ((array) $values as $singleval) {
                    JournalEntryPeer::removeInstanceFromPool($singleval);
                }
            }

            $affectedRows += BasePeer::doDelete($criteria, $con);
            JournalEntryPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * This is a method for emulating ON DELETE CASCADE for DBs that don't support this
     * feature (like MySQL or SQLite).
     *
     * This method is not very speedy because it must perform a query first to get
     * the implicated records and then perform the deletes by calling those Peer classes.
     *
     * This method should be used within a transaction if possible.
     *
     * @param      Criteria $criteria
     * @param      PropelPDO $con
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    protected static function doOnDeleteCascade(Criteria $criteria, PropelPDO $con)
    {
        // initialize var to track total num of affected rows
        $affectedRows = 0;

        // first find the objects that are implicated by the $criteria
        $objects = JournalEntryPeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {


            // delete related JournalComment objects
            $criteria = new Criteria(JournalCommentPeer::DATABASE_NAME);

            $criteria->add(JournalCommentPeer::JOURNAL_ENTRY_ID, $obj->getId());
            $affectedRows += JournalCommentPeer::doDelete($criteria, $con);

            // delete related JournalEntryImage objects
            $criteria = new Criteria(JournalEntryImagePeer::DATABASE_NAME);

            $criteria->add(JournalEntryImagePeer::JOURNAL_ENTRY_ID, $obj->getId());
            $affectedRows += JournalEntryImagePeer::doDelete($criteria, $con);
        }

        return $affectedRows;
    }

    /**
     * Validates all modified columns of given JournalEntry object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param JournalEntry $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(JournalEntryPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(JournalEntryPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(JournalEntryPeer::DATABASE_NAME, JournalEntryPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return JournalEntry
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = JournalEntryPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(JournalEntryPeer::DATABASE_NAME);
        $criteria->add(JournalEntryPeer::ID, $pk);

        $v = JournalEntryPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return JournalEntry[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(JournalEntryPeer::DATABASE_NAME);
            $criteria->add(JournalEntryPeer::ID, $pks, Criteria::IN);
            $objs = JournalEntryPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // referencing behavior
    private static function doDeleteBeforeTaggable($values, PropelPDO $con = null) {
            if ($con === null) {
                $con = Propel::getConnection(PagePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
            }

            if($values instanceof Criteria) {
                // rename for clarity
                $criteria = clone $values;
            } elseif ($values instanceof JournalEntry) { // it's a model object
                // create criteria based on pk values
                $criteria = $values->buildPkeyCriteria();
            } else { // it's a primary key, or an array of pks
                $criteria = new Criteria(self::DATABASE_NAME);
                $criteria->add(PagePeer::ID, (array) $values, Criteria::IN);
            }

            foreach(JournalEntryPeer::doSelect(clone $criteria, $con) as $object) {
                ReferencePeer::removeReferences($object);
            }

            return self::doDeleteBeforeReferencing($criteria, $con);
    }
    // taggable behavior
    public static function doDelete($values, PropelPDO $con = null) {
            if ($con === null) {
                $con = Propel::getConnection(PagePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
            }

            if($values instanceof Criteria) {
                // rename for clarity
                $criteria = clone $values;
            } elseif ($values instanceof JournalEntry) { // it's a model object
                // create criteria based on pk values
                $criteria = $values->buildPkeyCriteria();
            } else { // it's a primary key, or an array of pks
                $criteria = new Criteria(self::DATABASE_NAME);
                $criteria->add(PagePeer::ID, (array) $values, Criteria::IN);
            }

            foreach(JournalEntryPeer::doSelect(clone $criteria, $con) as $object) {
                TagPeer::deleteTagsForObject($object);
            }

            return self::doDeleteBeforeTaggable($criteria, $con);
    }
    // denyable behavior
    public static function ignoreRights($bIgnore = true) {
        self::$IGNORE_RIGHTS = $bIgnore;
    }
    public static function isIgnoringRights() {
        return self::$IGNORE_RIGHTS || PHP_SAPI === "cli";
    }
    public static function setRightsUser($oUser = false) {
        self::$RIGHTS_USER = $oUser;
    }
    public static function getRightsUser($oUser = false) {
        if($oUser === false) {
            $oUser = self::$RIGHTS_USER;
        }
        if($oUser === false) {
            $oUser = Session::getSession()->getUser();
        }
        return $oUser;
    }
    public static function mayOperateOn($oUser, $mObject, $sOperation) {
        if($oUser === null) {
            return false;
        }
        if($oUser->getIsAdmin()) {
            return true;
        }
        return $oUser->hasRole("journal_entries");
    }
    public static function mayOperateOnOwn($oUser, $mObject, $sOperation) {
        return $oUser->hasRole("journal_entries-own");
    }

} // BaseJournalEntryPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseJournalEntryPeer::buildTableMap();

