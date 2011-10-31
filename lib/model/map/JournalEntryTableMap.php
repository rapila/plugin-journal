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
	 * @return     void
	 * @throws     PropelException
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
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('JOURNAL_ID', 'JournalId', 'INTEGER', 'journals', 'ID', false, null, null);
		$this->addColumn('TITLE', 'Title', 'VARCHAR', true, 180, null);
		$this->addColumn('SLUG', 'Slug', 'VARCHAR', true, 50, null);
		$this->addColumn('TEXT', 'Text', 'LONGVARCHAR', true, null, null);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		$this->addForeignKey('CREATED_BY', 'CreatedBy', 'INTEGER', 'users', 'ID', false, null, null);
		$this->addForeignKey('UPDATED_BY', 'UpdatedBy', 'INTEGER', 'users', 'ID', false, null, null);
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
		$this->addRelation('JournalEntryImage', 'JournalEntryImage', RelationMap::ONE_TO_ONE, array('id' => 'journal_entry_id', ), 'CASCADE', null);
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
			'taggable' => array(),
			'denyable' => array('mode' => 'by_role', 'role_key' => '', 'owner_allowed' => '', ),
			'extended_timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
			'attributable' => array('create_column' => 'created_by', 'update_column' => 'updated_by', ),
		);
	} // getBehaviors()

} // JournalEntryTableMap
