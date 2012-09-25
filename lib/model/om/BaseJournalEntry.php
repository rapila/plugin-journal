<?php


/**
 * Base class that represents a row from the 'journal_entries' table.
 *
 *
 *
 * @package    propel.generator.model.om
 */
abstract class BaseJournalEntry extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'JournalEntryPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        JournalEntryPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the journal_id field.
     * @var        int
     */
    protected $journal_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * The value for the text field.
     * @var        string
     */
    protected $text;

    /**
     * The value for the text_short field.
     * @var        string
     */
    protected $text_short;

    /**
     * The value for the is_published field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_published;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * The value for the created_by field.
     * @var        int
     */
    protected $created_by;

    /**
     * The value for the updated_by field.
     * @var        int
     */
    protected $updated_by;

    /**
     * @var        Journal
     */
    protected $aJournal;

    /**
     * @var        User
     */
    protected $aUserRelatedByCreatedBy;

    /**
     * @var        User
     */
    protected $aUserRelatedByUpdatedBy;

    /**
     * @var        PropelObjectCollection|JournalComment[] Collection to store aggregation of JournalComment objects.
     */
    protected $collJournalComments;
    protected $collJournalCommentsPartial;

    /**
     * @var        PropelObjectCollection|JournalEntryImage[] Collection to store aggregation of JournalEntryImage objects.
     */
    protected $collJournalEntryImages;
    protected $collJournalEntryImagesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $journalCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $journalEntryImagesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_published = false;
    }

    /**
     * Initializes internal state of BaseJournalEntry object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [journal_id] column value.
     *
     * @return int
     */
    public function getJournalId()
    {
        return $this->journal_id;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the [text] column value.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the [text_short] column value.
     *
     * @return string
     */
    public function getTextShort()
    {
        return $this->text_short;
    }

    /**
     * Get the [is_published] column value.
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = 'Y-m-d H:i:s')
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->created_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = 'Y-m-d H:i:s')
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->updated_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [created_by] column value.
     *
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get the [updated_by] column value.
     *
     * @return int
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = JournalEntryPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [journal_id] column.
     *
     * @param int $v new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setJournalId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->journal_id !== $v) {
            $this->journal_id = $v;
            $this->modifiedColumns[] = JournalEntryPeer::JOURNAL_ID;
        }

        if ($this->aJournal !== null && $this->aJournal->getId() !== $v) {
            $this->aJournal = null;
        }


        return $this;
    } // setJournalId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = JournalEntryPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [slug] column.
     *
     * @param string $v new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = JournalEntryPeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Set the value of [text] column.
     *
     * @param string $v new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setText($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->text !== $v) {
            $this->text = $v;
            $this->modifiedColumns[] = JournalEntryPeer::TEXT;
        }


        return $this;
    } // setText()

    /**
     * Set the value of [text_short] column.
     *
     * @param string $v new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setTextShort($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->text_short !== $v) {
            $this->text_short = $v;
            $this->modifiedColumns[] = JournalEntryPeer::TEXT_SHORT;
        }


        return $this;
    } // setTextShort()

    /**
     * Sets the value of the [is_published] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setIsPublished($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_published !== $v) {
            $this->is_published = $v;
            $this->modifiedColumns[] = JournalEntryPeer::IS_PUBLISHED;
        }


        return $this;
    } // setIsPublished()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = JournalEntryPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = JournalEntryPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [created_by] column.
     *
     * @param int $v new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setCreatedBy($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->created_by !== $v) {
            $this->created_by = $v;
            $this->modifiedColumns[] = JournalEntryPeer::CREATED_BY;
        }

        if ($this->aUserRelatedByCreatedBy !== null && $this->aUserRelatedByCreatedBy->getId() !== $v) {
            $this->aUserRelatedByCreatedBy = null;
        }


        return $this;
    } // setCreatedBy()

    /**
     * Set the value of [updated_by] column.
     *
     * @param int $v new value
     * @return JournalEntry The current object (for fluent API support)
     */
    public function setUpdatedBy($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->updated_by !== $v) {
            $this->updated_by = $v;
            $this->modifiedColumns[] = JournalEntryPeer::UPDATED_BY;
        }

        if ($this->aUserRelatedByUpdatedBy !== null && $this->aUserRelatedByUpdatedBy->getId() !== $v) {
            $this->aUserRelatedByUpdatedBy = null;
        }


        return $this;
    } // setUpdatedBy()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->is_published !== false) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->journal_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->slug = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->text = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->text_short = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->is_published = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->created_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->updated_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->created_by = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->updated_by = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = JournalEntryPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating JournalEntry object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aJournal !== null && $this->journal_id !== $this->aJournal->getId()) {
            $this->aJournal = null;
        }
        if ($this->aUserRelatedByCreatedBy !== null && $this->created_by !== $this->aUserRelatedByCreatedBy->getId()) {
            $this->aUserRelatedByCreatedBy = null;
        }
        if ($this->aUserRelatedByUpdatedBy !== null && $this->updated_by !== $this->aUserRelatedByUpdatedBy->getId()) {
            $this->aUserRelatedByUpdatedBy = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = JournalEntryPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aJournal = null;
            $this->aUserRelatedByCreatedBy = null;
            $this->aUserRelatedByUpdatedBy = null;
            $this->collJournalComments = null;

            $this->collJournalEntryImages = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = JournalEntryQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // denyable behavior
            if(!(JournalEntryPeer::isIgnoringRights() || $this->mayOperate("delete"))) {
                throw new PropelException(new NotPermittedException("delete.by_role", array("role_key" => "journal_entries")));
            }

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(JournalEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // denyable behavior
                if(!(JournalEntryPeer::isIgnoringRights() || $this->mayOperate("insert"))) {
                    throw new PropelException(new NotPermittedException("insert.by_role", array("role_key" => "journal_entries")));
                }

                // extended_timestampable behavior
                if (!$this->isColumnModified(JournalEntryPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(JournalEntryPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // attributable behavior

                if(Session::getSession()->isAuthenticated()) {
                    if (!$this->isColumnModified(JournalEntryPeer::CREATED_BY)) {
                        $this->setCreatedBy(Session::getSession()->getUser()->getId());
                    }
                    if (!$this->isColumnModified(JournalEntryPeer::UPDATED_BY)) {
                        $this->setUpdatedBy(Session::getSession()->getUser()->getId());
                    }
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // denyable behavior
                if(!(JournalEntryPeer::isIgnoringRights() || $this->mayOperate("update"))) {
                    throw new PropelException(new NotPermittedException("update.by_role", array("role_key" => "journal_entries")));
                }

                // extended_timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(JournalEntryPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // attributable behavior

                if(Session::getSession()->isAuthenticated()) {
                    if ($this->isModified() && !$this->isColumnModified(JournalEntryPeer::UPDATED_BY)) {
                        $this->setUpdatedBy(Session::getSession()->getUser()->getId());
                    }
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                JournalEntryPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aJournal !== null) {
                if ($this->aJournal->isModified() || $this->aJournal->isNew()) {
                    $affectedRows += $this->aJournal->save($con);
                }
                $this->setJournal($this->aJournal);
            }

            if ($this->aUserRelatedByCreatedBy !== null) {
                if ($this->aUserRelatedByCreatedBy->isModified() || $this->aUserRelatedByCreatedBy->isNew()) {
                    $affectedRows += $this->aUserRelatedByCreatedBy->save($con);
                }
                $this->setUserRelatedByCreatedBy($this->aUserRelatedByCreatedBy);
            }

            if ($this->aUserRelatedByUpdatedBy !== null) {
                if ($this->aUserRelatedByUpdatedBy->isModified() || $this->aUserRelatedByUpdatedBy->isNew()) {
                    $affectedRows += $this->aUserRelatedByUpdatedBy->save($con);
                }
                $this->setUserRelatedByUpdatedBy($this->aUserRelatedByUpdatedBy);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->journalCommentsScheduledForDeletion !== null) {
                if (!$this->journalCommentsScheduledForDeletion->isEmpty()) {
                    JournalCommentQuery::create()
                        ->filterByPrimaryKeys($this->journalCommentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->journalCommentsScheduledForDeletion = null;
                }
            }

            if ($this->collJournalComments !== null) {
                foreach ($this->collJournalComments as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->journalEntryImagesScheduledForDeletion !== null) {
                if (!$this->journalEntryImagesScheduledForDeletion->isEmpty()) {
                    JournalEntryImageQuery::create()
                        ->filterByPrimaryKeys($this->journalEntryImagesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->journalEntryImagesScheduledForDeletion = null;
                }
            }

            if ($this->collJournalEntryImages !== null) {
                foreach ($this->collJournalEntryImages as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = JournalEntryPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . JournalEntryPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(JournalEntryPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(JournalEntryPeer::JOURNAL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`JOURNAL_ID`';
        }
        if ($this->isColumnModified(JournalEntryPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE`';
        }
        if ($this->isColumnModified(JournalEntryPeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`SLUG`';
        }
        if ($this->isColumnModified(JournalEntryPeer::TEXT)) {
            $modifiedColumns[':p' . $index++]  = '`TEXT`';
        }
        if ($this->isColumnModified(JournalEntryPeer::TEXT_SHORT)) {
            $modifiedColumns[':p' . $index++]  = '`TEXT_SHORT`';
        }
        if ($this->isColumnModified(JournalEntryPeer::IS_PUBLISHED)) {
            $modifiedColumns[':p' . $index++]  = '`IS_PUBLISHED`';
        }
        if ($this->isColumnModified(JournalEntryPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_AT`';
        }
        if ($this->isColumnModified(JournalEntryPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }
        if ($this->isColumnModified(JournalEntryPeer::CREATED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_BY`';
        }
        if ($this->isColumnModified(JournalEntryPeer::UPDATED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_BY`';
        }

        $sql = sprintf(
            'INSERT INTO `journal_entries` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`ID`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`JOURNAL_ID`':
                        $stmt->bindValue($identifier, $this->journal_id, PDO::PARAM_INT);
                        break;
                    case '`TITLE`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`SLUG`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                    case '`TEXT`':
                        $stmt->bindValue($identifier, $this->text, PDO::PARAM_STR);
                        break;
                    case '`TEXT_SHORT`':
                        $stmt->bindValue($identifier, $this->text_short, PDO::PARAM_STR);
                        break;
                    case '`IS_PUBLISHED`':
                        $stmt->bindValue($identifier, (int) $this->is_published, PDO::PARAM_INT);
                        break;
                    case '`CREATED_AT`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`UPDATED_AT`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                    case '`CREATED_BY`':
                        $stmt->bindValue($identifier, $this->created_by, PDO::PARAM_INT);
                        break;
                    case '`UPDATED_BY`':
                        $stmt->bindValue($identifier, $this->updated_by, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        } else {
            $this->validationFailures = $res;

            return false;
        }
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aJournal !== null) {
                if (!$this->aJournal->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aJournal->getValidationFailures());
                }
            }

            if ($this->aUserRelatedByCreatedBy !== null) {
                if (!$this->aUserRelatedByCreatedBy->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUserRelatedByCreatedBy->getValidationFailures());
                }
            }

            if ($this->aUserRelatedByUpdatedBy !== null) {
                if (!$this->aUserRelatedByUpdatedBy->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUserRelatedByUpdatedBy->getValidationFailures());
                }
            }


            if (($retval = JournalEntryPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collJournalComments !== null) {
                    foreach ($this->collJournalComments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collJournalEntryImages !== null) {
                    foreach ($this->collJournalEntryImages as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = JournalEntryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getJournalId();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getSlug();
                break;
            case 4:
                return $this->getText();
                break;
            case 5:
                return $this->getTextShort();
                break;
            case 6:
                return $this->getIsPublished();
                break;
            case 7:
                return $this->getCreatedAt();
                break;
            case 8:
                return $this->getUpdatedAt();
                break;
            case 9:
                return $this->getCreatedBy();
                break;
            case 10:
                return $this->getUpdatedBy();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['JournalEntry'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['JournalEntry'][$this->getPrimaryKey()] = true;
        $keys = JournalEntryPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getJournalId(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getSlug(),
            $keys[4] => $this->getText(),
            $keys[5] => $this->getTextShort(),
            $keys[6] => $this->getIsPublished(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
            $keys[9] => $this->getCreatedBy(),
            $keys[10] => $this->getUpdatedBy(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aJournal) {
                $result['Journal'] = $this->aJournal->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserRelatedByCreatedBy) {
                $result['UserRelatedByCreatedBy'] = $this->aUserRelatedByCreatedBy->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserRelatedByUpdatedBy) {
                $result['UserRelatedByUpdatedBy'] = $this->aUserRelatedByUpdatedBy->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collJournalComments) {
                $result['JournalComments'] = $this->collJournalComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collJournalEntryImages) {
                $result['JournalEntryImages'] = $this->collJournalEntryImages->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = JournalEntryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setJournalId($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setSlug($value);
                break;
            case 4:
                $this->setText($value);
                break;
            case 5:
                $this->setTextShort($value);
                break;
            case 6:
                $this->setIsPublished($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
                $this->setUpdatedAt($value);
                break;
            case 9:
                $this->setCreatedBy($value);
                break;
            case 10:
                $this->setUpdatedBy($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = JournalEntryPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setJournalId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSlug($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setText($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTextShort($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setIsPublished($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUpdatedAt($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreatedBy($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdatedBy($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(JournalEntryPeer::DATABASE_NAME);

        if ($this->isColumnModified(JournalEntryPeer::ID)) $criteria->add(JournalEntryPeer::ID, $this->id);
        if ($this->isColumnModified(JournalEntryPeer::JOURNAL_ID)) $criteria->add(JournalEntryPeer::JOURNAL_ID, $this->journal_id);
        if ($this->isColumnModified(JournalEntryPeer::TITLE)) $criteria->add(JournalEntryPeer::TITLE, $this->title);
        if ($this->isColumnModified(JournalEntryPeer::SLUG)) $criteria->add(JournalEntryPeer::SLUG, $this->slug);
        if ($this->isColumnModified(JournalEntryPeer::TEXT)) $criteria->add(JournalEntryPeer::TEXT, $this->text);
        if ($this->isColumnModified(JournalEntryPeer::TEXT_SHORT)) $criteria->add(JournalEntryPeer::TEXT_SHORT, $this->text_short);
        if ($this->isColumnModified(JournalEntryPeer::IS_PUBLISHED)) $criteria->add(JournalEntryPeer::IS_PUBLISHED, $this->is_published);
        if ($this->isColumnModified(JournalEntryPeer::CREATED_AT)) $criteria->add(JournalEntryPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(JournalEntryPeer::UPDATED_AT)) $criteria->add(JournalEntryPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(JournalEntryPeer::CREATED_BY)) $criteria->add(JournalEntryPeer::CREATED_BY, $this->created_by);
        if ($this->isColumnModified(JournalEntryPeer::UPDATED_BY)) $criteria->add(JournalEntryPeer::UPDATED_BY, $this->updated_by);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(JournalEntryPeer::DATABASE_NAME);
        $criteria->add(JournalEntryPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of JournalEntry (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setJournalId($this->getJournalId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setText($this->getText());
        $copyObj->setTextShort($this->getTextShort());
        $copyObj->setIsPublished($this->getIsPublished());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setCreatedBy($this->getCreatedBy());
        $copyObj->setUpdatedBy($this->getUpdatedBy());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getJournalComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addJournalComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getJournalEntryImages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addJournalEntryImage($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return JournalEntry Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return JournalEntryPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new JournalEntryPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Journal object.
     *
     * @param             Journal $v
     * @return JournalEntry The current object (for fluent API support)
     * @throws PropelException
     */
    public function setJournal(Journal $v = null)
    {
        if ($v === null) {
            $this->setJournalId(NULL);
        } else {
            $this->setJournalId($v->getId());
        }

        $this->aJournal = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Journal object, it will not be re-added.
        if ($v !== null) {
            $v->addJournalEntry($this);
        }


        return $this;
    }


    /**
     * Get the associated Journal object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Journal The associated Journal object.
     * @throws PropelException
     */
    public function getJournal(PropelPDO $con = null)
    {
        if ($this->aJournal === null && ($this->journal_id !== null)) {
            $this->aJournal = JournalQuery::create()->findPk($this->journal_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aJournal->addJournalEntrys($this);
             */
        }

        return $this->aJournal;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return JournalEntry The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByCreatedBy(User $v = null)
    {
        if ($v === null) {
            $this->setCreatedBy(NULL);
        } else {
            $this->setCreatedBy($v->getId());
        }

        $this->aUserRelatedByCreatedBy = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addJournalEntryRelatedByCreatedBy($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUserRelatedByCreatedBy(PropelPDO $con = null)
    {
        if ($this->aUserRelatedByCreatedBy === null && ($this->created_by !== null)) {
            $this->aUserRelatedByCreatedBy = UserQuery::create()->findPk($this->created_by, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByCreatedBy->addJournalEntrysRelatedByCreatedBy($this);
             */
        }

        return $this->aUserRelatedByCreatedBy;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return JournalEntry The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByUpdatedBy(User $v = null)
    {
        if ($v === null) {
            $this->setUpdatedBy(NULL);
        } else {
            $this->setUpdatedBy($v->getId());
        }

        $this->aUserRelatedByUpdatedBy = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addJournalEntryRelatedByUpdatedBy($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUserRelatedByUpdatedBy(PropelPDO $con = null)
    {
        if ($this->aUserRelatedByUpdatedBy === null && ($this->updated_by !== null)) {
            $this->aUserRelatedByUpdatedBy = UserQuery::create()->findPk($this->updated_by, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByUpdatedBy->addJournalEntrysRelatedByUpdatedBy($this);
             */
        }

        return $this->aUserRelatedByUpdatedBy;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('JournalComment' == $relationName) {
            $this->initJournalComments();
        }
        if ('JournalEntryImage' == $relationName) {
            $this->initJournalEntryImages();
        }
    }

    /**
     * Clears out the collJournalComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addJournalComments()
     */
    public function clearJournalComments()
    {
        $this->collJournalComments = null; // important to set this to null since that means it is uninitialized
        $this->collJournalCommentsPartial = null;
    }

    /**
     * reset is the collJournalComments collection loaded partially
     *
     * @return void
     */
    public function resetPartialJournalComments($v = true)
    {
        $this->collJournalCommentsPartial = $v;
    }

    /**
     * Initializes the collJournalComments collection.
     *
     * By default this just sets the collJournalComments collection to an empty array (like clearcollJournalComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initJournalComments($overrideExisting = true)
    {
        if (null !== $this->collJournalComments && !$overrideExisting) {
            return;
        }
        $this->collJournalComments = new PropelObjectCollection();
        $this->collJournalComments->setModel('JournalComment');
    }

    /**
     * Gets an array of JournalComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this JournalEntry is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|JournalComment[] List of JournalComment objects
     * @throws PropelException
     */
    public function getJournalComments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collJournalCommentsPartial && !$this->isNew();
        if (null === $this->collJournalComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collJournalComments) {
                // return empty collection
                $this->initJournalComments();
            } else {
                $collJournalComments = JournalCommentQuery::create(null, $criteria)
                    ->filterByJournalEntry($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collJournalCommentsPartial && count($collJournalComments)) {
                      $this->initJournalComments(false);

                      foreach($collJournalComments as $obj) {
                        if (false == $this->collJournalComments->contains($obj)) {
                          $this->collJournalComments->append($obj);
                        }
                      }

                      $this->collJournalCommentsPartial = true;
                    }

                    return $collJournalComments;
                }

                if($partial && $this->collJournalComments) {
                    foreach($this->collJournalComments as $obj) {
                        if($obj->isNew()) {
                            $collJournalComments[] = $obj;
                        }
                    }
                }

                $this->collJournalComments = $collJournalComments;
                $this->collJournalCommentsPartial = false;
            }
        }

        return $this->collJournalComments;
    }

    /**
     * Sets a collection of JournalComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $journalComments A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setJournalComments(PropelCollection $journalComments, PropelPDO $con = null)
    {
        $this->journalCommentsScheduledForDeletion = $this->getJournalComments(new Criteria(), $con)->diff($journalComments);

        foreach ($this->journalCommentsScheduledForDeletion as $journalCommentRemoved) {
            $journalCommentRemoved->setJournalEntry(null);
        }

        $this->collJournalComments = null;
        foreach ($journalComments as $journalComment) {
            $this->addJournalComment($journalComment);
        }

        $this->collJournalComments = $journalComments;
        $this->collJournalCommentsPartial = false;
    }

    /**
     * Returns the number of related JournalComment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related JournalComment objects.
     * @throws PropelException
     */
    public function countJournalComments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collJournalCommentsPartial && !$this->isNew();
        if (null === $this->collJournalComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collJournalComments) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getJournalComments());
                }
                $query = JournalCommentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByJournalEntry($this)
                    ->count($con);
            }
        } else {
            return count($this->collJournalComments);
        }
    }

    /**
     * Method called to associate a JournalComment object to this object
     * through the JournalComment foreign key attribute.
     *
     * @param    JournalComment $l JournalComment
     * @return JournalEntry The current object (for fluent API support)
     */
    public function addJournalComment(JournalComment $l)
    {
        if ($this->collJournalComments === null) {
            $this->initJournalComments();
            $this->collJournalCommentsPartial = true;
        }
        if (!$this->collJournalComments->contains($l)) { // only add it if the **same** object is not already associated
            $this->doAddJournalComment($l);
        }

        return $this;
    }

    /**
     * @param	JournalComment $journalComment The journalComment object to add.
     */
    protected function doAddJournalComment($journalComment)
    {
        $this->collJournalComments[]= $journalComment;
        $journalComment->setJournalEntry($this);
    }

    /**
     * @param	JournalComment $journalComment The journalComment object to remove.
     */
    public function removeJournalComment($journalComment)
    {
        if ($this->getJournalComments()->contains($journalComment)) {
            $this->collJournalComments->remove($this->collJournalComments->search($journalComment));
            if (null === $this->journalCommentsScheduledForDeletion) {
                $this->journalCommentsScheduledForDeletion = clone $this->collJournalComments;
                $this->journalCommentsScheduledForDeletion->clear();
            }
            $this->journalCommentsScheduledForDeletion[]= $journalComment;
            $journalComment->setJournalEntry(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this JournalEntry is new, it will return
     * an empty collection; or if this JournalEntry has previously
     * been saved, it will retrieve related JournalComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in JournalEntry.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|JournalComment[] List of JournalComment objects
     */
    public function getJournalCommentsJoinUserRelatedByCreatedBy($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = JournalCommentQuery::create(null, $criteria);
        $query->joinWith('UserRelatedByCreatedBy', $join_behavior);

        return $this->getJournalComments($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this JournalEntry is new, it will return
     * an empty collection; or if this JournalEntry has previously
     * been saved, it will retrieve related JournalComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in JournalEntry.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|JournalComment[] List of JournalComment objects
     */
    public function getJournalCommentsJoinUserRelatedByUpdatedBy($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = JournalCommentQuery::create(null, $criteria);
        $query->joinWith('UserRelatedByUpdatedBy', $join_behavior);

        return $this->getJournalComments($query, $con);
    }

    /**
     * Clears out the collJournalEntryImages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addJournalEntryImages()
     */
    public function clearJournalEntryImages()
    {
        $this->collJournalEntryImages = null; // important to set this to null since that means it is uninitialized
        $this->collJournalEntryImagesPartial = null;
    }

    /**
     * reset is the collJournalEntryImages collection loaded partially
     *
     * @return void
     */
    public function resetPartialJournalEntryImages($v = true)
    {
        $this->collJournalEntryImagesPartial = $v;
    }

    /**
     * Initializes the collJournalEntryImages collection.
     *
     * By default this just sets the collJournalEntryImages collection to an empty array (like clearcollJournalEntryImages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initJournalEntryImages($overrideExisting = true)
    {
        if (null !== $this->collJournalEntryImages && !$overrideExisting) {
            return;
        }
        $this->collJournalEntryImages = new PropelObjectCollection();
        $this->collJournalEntryImages->setModel('JournalEntryImage');
    }

    /**
     * Gets an array of JournalEntryImage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this JournalEntry is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|JournalEntryImage[] List of JournalEntryImage objects
     * @throws PropelException
     */
    public function getJournalEntryImages($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collJournalEntryImagesPartial && !$this->isNew();
        if (null === $this->collJournalEntryImages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collJournalEntryImages) {
                // return empty collection
                $this->initJournalEntryImages();
            } else {
                $collJournalEntryImages = JournalEntryImageQuery::create(null, $criteria)
                    ->filterByJournalEntry($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collJournalEntryImagesPartial && count($collJournalEntryImages)) {
                      $this->initJournalEntryImages(false);

                      foreach($collJournalEntryImages as $obj) {
                        if (false == $this->collJournalEntryImages->contains($obj)) {
                          $this->collJournalEntryImages->append($obj);
                        }
                      }

                      $this->collJournalEntryImagesPartial = true;
                    }

                    return $collJournalEntryImages;
                }

                if($partial && $this->collJournalEntryImages) {
                    foreach($this->collJournalEntryImages as $obj) {
                        if($obj->isNew()) {
                            $collJournalEntryImages[] = $obj;
                        }
                    }
                }

                $this->collJournalEntryImages = $collJournalEntryImages;
                $this->collJournalEntryImagesPartial = false;
            }
        }

        return $this->collJournalEntryImages;
    }

    /**
     * Sets a collection of JournalEntryImage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $journalEntryImages A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setJournalEntryImages(PropelCollection $journalEntryImages, PropelPDO $con = null)
    {
        $this->journalEntryImagesScheduledForDeletion = $this->getJournalEntryImages(new Criteria(), $con)->diff($journalEntryImages);

        foreach ($this->journalEntryImagesScheduledForDeletion as $journalEntryImageRemoved) {
            $journalEntryImageRemoved->setJournalEntry(null);
        }

        $this->collJournalEntryImages = null;
        foreach ($journalEntryImages as $journalEntryImage) {
            $this->addJournalEntryImage($journalEntryImage);
        }

        $this->collJournalEntryImages = $journalEntryImages;
        $this->collJournalEntryImagesPartial = false;
    }

    /**
     * Returns the number of related JournalEntryImage objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related JournalEntryImage objects.
     * @throws PropelException
     */
    public function countJournalEntryImages(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collJournalEntryImagesPartial && !$this->isNew();
        if (null === $this->collJournalEntryImages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collJournalEntryImages) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getJournalEntryImages());
                }
                $query = JournalEntryImageQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByJournalEntry($this)
                    ->count($con);
            }
        } else {
            return count($this->collJournalEntryImages);
        }
    }

    /**
     * Method called to associate a JournalEntryImage object to this object
     * through the JournalEntryImage foreign key attribute.
     *
     * @param    JournalEntryImage $l JournalEntryImage
     * @return JournalEntry The current object (for fluent API support)
     */
    public function addJournalEntryImage(JournalEntryImage $l)
    {
        if ($this->collJournalEntryImages === null) {
            $this->initJournalEntryImages();
            $this->collJournalEntryImagesPartial = true;
        }
        if (!$this->collJournalEntryImages->contains($l)) { // only add it if the **same** object is not already associated
            $this->doAddJournalEntryImage($l);
        }

        return $this;
    }

    /**
     * @param	JournalEntryImage $journalEntryImage The journalEntryImage object to add.
     */
    protected function doAddJournalEntryImage($journalEntryImage)
    {
        $this->collJournalEntryImages[]= $journalEntryImage;
        $journalEntryImage->setJournalEntry($this);
    }

    /**
     * @param	JournalEntryImage $journalEntryImage The journalEntryImage object to remove.
     */
    public function removeJournalEntryImage($journalEntryImage)
    {
        if ($this->getJournalEntryImages()->contains($journalEntryImage)) {
            $this->collJournalEntryImages->remove($this->collJournalEntryImages->search($journalEntryImage));
            if (null === $this->journalEntryImagesScheduledForDeletion) {
                $this->journalEntryImagesScheduledForDeletion = clone $this->collJournalEntryImages;
                $this->journalEntryImagesScheduledForDeletion->clear();
            }
            $this->journalEntryImagesScheduledForDeletion[]= $journalEntryImage;
            $journalEntryImage->setJournalEntry(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this JournalEntry is new, it will return
     * an empty collection; or if this JournalEntry has previously
     * been saved, it will retrieve related JournalEntryImages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in JournalEntry.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|JournalEntryImage[] List of JournalEntryImage objects
     */
    public function getJournalEntryImagesJoinDocument($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = JournalEntryImageQuery::create(null, $criteria);
        $query->joinWith('Document', $join_behavior);

        return $this->getJournalEntryImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this JournalEntry is new, it will return
     * an empty collection; or if this JournalEntry has previously
     * been saved, it will retrieve related JournalEntryImages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in JournalEntry.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|JournalEntryImage[] List of JournalEntryImage objects
     */
    public function getJournalEntryImagesJoinUserRelatedByCreatedBy($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = JournalEntryImageQuery::create(null, $criteria);
        $query->joinWith('UserRelatedByCreatedBy', $join_behavior);

        return $this->getJournalEntryImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this JournalEntry is new, it will return
     * an empty collection; or if this JournalEntry has previously
     * been saved, it will retrieve related JournalEntryImages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in JournalEntry.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|JournalEntryImage[] List of JournalEntryImage objects
     */
    public function getJournalEntryImagesJoinUserRelatedByUpdatedBy($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = JournalEntryImageQuery::create(null, $criteria);
        $query->joinWith('UserRelatedByUpdatedBy', $join_behavior);

        return $this->getJournalEntryImages($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->journal_id = null;
        $this->title = null;
        $this->slug = null;
        $this->text = null;
        $this->text_short = null;
        $this->is_published = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->created_by = null;
        $this->updated_by = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collJournalComments) {
                foreach ($this->collJournalComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collJournalEntryImages) {
                foreach ($this->collJournalEntryImages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collJournalComments instanceof PropelCollection) {
            $this->collJournalComments->clearIterator();
        }
        $this->collJournalComments = null;
        if ($this->collJournalEntryImages instanceof PropelCollection) {
            $this->collJournalEntryImages->clearIterator();
        }
        $this->collJournalEntryImages = null;
        $this->aJournal = null;
        $this->aUserRelatedByCreatedBy = null;
        $this->aUserRelatedByUpdatedBy = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(JournalEntryPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // taggable behavior

    /**
     * @return A list of TagInstances (not Tags) which reference this JournalEntry
     */
    public function getTags()
    {
        return TagPeer::tagInstancesForObject($this);
    }
    // denyable behavior
    public function mayOperate($sOperation, $oUser = false) {
        if($oUser === false) {
            $oUser = Session::getSession()->getUser();
        }
        $bIsAllowed = false;
        if($oUser && ($this->isNew() || $this->getCreatedBy() === $oUser->getId()) && JournalEntryPeer::mayOperateOnOwn($oUser, $this, $sOperation)) {
            $bIsAllowed = true;
        } else if(JournalEntryPeer::mayOperateOn($oUser, $this, $sOperation)) {
            $bIsAllowed = true;
        }
        FilterModule::getFilters()->handleJournalEntryOperationCheck($sOperation, $this, $oUser, array(&$bIsAllowed));
        return $bIsAllowed;
    }
    public function mayBeInserted($oUser = false) {
        return $this->mayOperate("insert", $oUser);
    }
    public function mayBeUpdated($oUser = false) {
        return $this->mayOperate("update", $oUser);
    }
    public function mayBeDeleted($oUser = false) {
        return $this->mayOperate("delete", $oUser);
    }

    // extended_timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     JournalEntry The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = JournalEntryPeer::UPDATED_AT;

        return $this;
    }

    /**
     * @return created_at as int (timestamp)
     */
    public function getCreatedAtTimestamp()
    {
        return (int)$this->getCreatedAt('U');
    }

    /**
     * @return created_at formatted to the current locale
     */
    public function getCreatedAtFormatted($sLanguageId = null, $sFormatString = 'x')
    {
        if($this->created_at === null) {
            return null;
        }
        return LocaleUtil::localizeDate($this->created_at, $sLanguageId, $sFormatString);
    }

    /**
     * @return updated_at as int (timestamp)
     */
    public function getUpdatedAtTimestamp()
    {
        return (int)$this->getUpdatedAt('U');
    }

    /**
     * @return updated_at formatted to the current locale
     */
    public function getUpdatedAtFormatted($sLanguageId = null, $sFormatString = 'x')
    {
        if($this->updated_at === null) {
            return null;
        }
        return LocaleUtil::localizeDate($this->updated_at, $sLanguageId, $sFormatString);
    }

    // attributable behavior

    /**
     * Mark the current object so that the updated user doesn't get updated during next save
     *
     * @return     JournalEntry The current object (for fluent API support)
     */
    public function keepUpdateUserUnchanged()
    {
        $this->modifiedColumns[] = JournalEntryPeer::UPDATED_BY;
        return $this;
    }

}
