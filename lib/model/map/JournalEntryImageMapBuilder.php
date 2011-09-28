<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'journal_entry_images' table to 'mini_cms' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    model.map
 */
class JournalEntryImageMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'model.map.JournalEntryImageMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('mini_cms');

		$tMap = $this->dbMap->addTable('journal_entry_images');
		$tMap->setPhpName('JournalEntryImage');

		$tMap->setUseIdGenerator(false);

		$tMap->addForeignKey('JOURNAL_ENTRY_ID', 'JournalEntryId', 'int', CreoleTypes::INTEGER, 'journal_entries', 'ID', true, null);

		$tMap->addForeignKey('DOCUMENT_ID', 'DocumentId', 'int', CreoleTypes::INTEGER, 'documents', 'ID', true, null);

		$tMap->addColumn('LEGEND', 'Legend', 'string', CreoleTypes::VARCHAR, true, 180);

	} // doBuild()

} // JournalEntryImageMapBuilder
