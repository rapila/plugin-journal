<?php



/**
 * This class defines the structure of the 'journal_entries' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.model.map
 */
class JournalEntryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'model.map.JournalEntryTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('journal_entries');
        $this->setPhpName('JournalEntry');
        $this->setClassname('JournalEntry');
        $this->setPackage('model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('journal_id', 'JournalId', 'INTEGER', 'journals', 'id', false, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 180, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', true, 50, null);
        $this->addColumn('text', 'Text', 'LONGVARCHAR', true, null, null);
        $this->addColumn('text_short', 'TextShort', 'LONGVARCHAR', true, null, null);
        $this->addColumn('is_published', 'IsPublished', 'BOOLEAN', false, 1, false);
        $this->addColumn('publish_at', 'PublishAt', 'DATE', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('created_by', 'CreatedBy', 'INTEGER', 'users', 'id', false, null, null);
        $this->addForeignKey('updated_by', 'UpdatedBy', 'INTEGER', 'users', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Journal', 'Journal', RelationMap::MANY_TO_ONE, array('journal_id' => 'id', ), null, null);
        $this->addRelation('UserRelatedByCreatedBy', 'User', RelationMap::MANY_TO_ONE, array('created_by' => 'id', ), 'SET NULL', null);
        $this->addRelation('UserRelatedByUpdatedBy', 'User', RelationMap::MANY_TO_ONE, array('updated_by' => 'id', ), 'SET NULL', null);
        $this->addRelation('JournalComment', 'JournalComment', RelationMap::ONE_TO_MANY, array('id' => 'journal_entry_id', ), 'CASCADE', null, 'JournalComments');
        $this->addRelation('JournalEntryImage', 'JournalEntryImage', RelationMap::ONE_TO_MANY, array('id' => 'journal_entry_id', ), 'CASCADE', null, 'JournalEntryImages');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'referencing' =>  array (
),
            'taggable' =>  array (
),
            'denyable' =>  array (
  'mode' => '',
  'role_key' => 'journal_entries',
  'owner_allowed' => '',
),
            'extended_timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'attributable' =>  array (
  'create_column' => 'created_by',
  'update_column' => 'updated_by',
),
            'extended_keyable' =>  array (
  'key_separator' => '_',
),
        );
    } // getBehaviors()

} // JournalEntryTableMap
