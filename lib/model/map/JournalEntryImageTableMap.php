<?php



/**
 * This class defines the structure of the 'journal_entry_images' table.
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
class JournalEntryImageTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'model.map.JournalEntryImageTableMap';

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
		$this->setName('journal_entry_images');
		$this->setPhpName('JournalEntryImage');
		$this->setClassname('JournalEntryImage');
		$this->setPackage('model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('JOURNAL_ENTRY_ID', 'JournalEntryId', 'INTEGER' , 'journal_entries', 'ID', true, null, null);
		$this->addForeignKey('DOCUMENT_ID', 'DocumentId', 'INTEGER', 'documents', 'ID', true, null, null);
		$this->addColumn('LEGEND', 'Legend', 'VARCHAR', true, 180, null);
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
		$this->addRelation('JournalEntry', 'JournalEntry', RelationMap::MANY_TO_ONE, array('journal_entry_id' => 'id', ), 'CASCADE', null);
		$this->addRelation('Document', 'Document', RelationMap::MANY_TO_ONE, array('document_id' => 'id', ), 'CASCADE', null);
		$this->addRelation('UserRelatedByCreatedBy', 'User', RelationMap::MANY_TO_ONE, array('created_by' => 'id', ), 'SET NULL', null);
		$this->addRelation('UserRelatedByUpdatedBy', 'User', RelationMap::MANY_TO_ONE, array('updated_by' => 'id', ), 'SET NULL', null);
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
			'extended_timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
			'attributable' => array('create_column' => 'created_by', 'update_column' => 'updated_by', ),
		);
	} // getBehaviors()

} // JournalEntryImageTableMap
