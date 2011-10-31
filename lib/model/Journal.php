<?php

require_once 'model/om/BaseJournal.php';


/**
 * Skeleton subclass for representing a row from the 'journals' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    model
 */
class Journal extends BaseJournal {
	public function getJournalPage() {
		return PageQuery::create()->filterByPageType('journal')->joinPageProperty()->useQuery('PageProperty')->filterByName('journal_id')->filterByValue($this->getId())->endUse()->findOne();
	}

} // Journal
