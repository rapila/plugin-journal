<?php

require_once($_SERVER['PWD'].'/base/lib/inc.php');

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1354347903.
 * Generated on 2012-12-01 08:45:03 by jmg
 */
class PropelMigration_1354347903
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
			$aPageProperties = PagePropertyQuery::create()->filterByName('blog_%', Criteria::LIKE)->find();
			foreach($aPageProperties as $oEntry) {
				$oEntry->setName(str_replace('blog_', 'journal:', $oEntry->getName()));
				$oEntry->setUpdatedAt($oEntry->getUpdatedAt());
				$oEntry->setUpdatedBy($oEntry->getUpdatedBy());
				$oEntry->save();
			}
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
			foreach(PagePropertyQuery::create()->filterByName('journal:%', Criteria::LIKE)->find() as $oEntry) {
				$oEntry->setName(str_replace('journal:', 'blog_', $oEntry->getName()));
				$oEntry->setUpdatedAt($oEntry->getUpdatedAt());
				$oEntry->setUpdatedBy($oEntry->getUpdatedBy());
				$oEntry->save();
			}
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

	# This restores the fkey checks, after having unset them earlier
	SET FOREIGN_KEY_CHECKS = 1;
	',
	);
		}

}