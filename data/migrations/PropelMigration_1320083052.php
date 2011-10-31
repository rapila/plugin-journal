<?php

require_once($_SERVER['PWD'].'/base/lib/inc.php');

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1320083052.
 * Generated on 2011-10-31 18:44:12 by rafi
 */
class PropelMigration_1320083052
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
		foreach(JournalEntryQuery::create()->find() as $oEntry) {
			$oEntry->setSlug(mb_substr($oEntry->getName(), 11));
			$oEntry->setUpdatedAt($oEntry->getUpdatedAt());
			$oEntry->setUpdatedBy($oEntry->getUpdatedBy());
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
	`slug` VARCHAR(50) NOT NULL
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

ALTER TABLE `journal_entries` DROP `slug`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}
