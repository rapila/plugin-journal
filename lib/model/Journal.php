<?php

require_once 'model/om/BaseJournal.php';

/**
 * @package model
 * @subpackage rapila-plugin-journal
 */	
class Journal extends BaseJournal {
	
	public function getJournalPage() {
		return PageQuery::create()->filterByPageType('journal')->joinPageProperty()->useQuery('PageProperty')->filterByName('blog_journal_id')->filterBySplitValue($this->getId())->endUse()->findOne();
	}
}

