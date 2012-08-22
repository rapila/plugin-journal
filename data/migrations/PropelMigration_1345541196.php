<?php

require_once($_SERVER['PWD'].'/base/lib/inc.php');

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1345541196.
 * Generated on 2012-08-21 11:26:36 by rafi
 */
class PropelMigration_1345541196
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
		foreach(JournalEntryQuery::create()->find() as $oEntry) {
			$sOldText = $oEntry->getText();
			$sText = RichtextUtil::parseStorageForBackendOutput($sOldText);
			$oUtil = new RichtextUtil();
			$oEntry->setText($oUtil->getTagParser($sText));
			assert($sOldText === $oEntry->getText());
			$oEntry->save();
		}
		// add the post-migration code here
	}

	public function preDown($manager)
	{
		// add the pre-migration code here
	}

	public function postDown($manager)
	{
		// add the post-migration code here
	}

	/**
	 * Get the SQL statements for the Up migration
	 *
	 * @return array list of the SQL strings to execute for the Up migration
	 *               the keys being the datasources
	 */
	public function getUpSQL()
	{
		return array (
  'rapila' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `journal_entries` ADD
(
	`text_short` TEXT NOT NULL
);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

	/**
	 * Get the SQL statements for the Down migration
	 *
	 * @return array list of the SQL strings to execute for the Down migration
	 *               the keys being the datasources
	 */
	public function getDownSQL()
	{
		return array (
  'rapila' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `journal_entries` DROP `text_short`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}