<?php


/**
 * @package    propel.generator.model
 */
class JournalEntryQuery extends BaseJournalEntryQuery {

	public function mostRecent($iNum) {
    $this->addDescendingOrderByColumn(JournalEntryPeer::CREATED_AT);
		$this->setLimit($iNum);
		return $this;
	}
	
	public function filterByDate($iYear, $iMonth, $iDay) {
		$this->add('YEAR(created_at)', $iYear);
		$this->add('MONTH(created_at)', $iMonth);
		$this->add('DAY(created_at)', $iDay);
		return $this;
	}
	
	public function excludeDraft() {
		return $this->filterByIsPublished(true);
	}
}

