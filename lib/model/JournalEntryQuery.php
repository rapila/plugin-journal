<?php


/**
 * @package    propel.generator.model
 */
class JournalEntryQuery extends BaseJournalEntryQuery {

	public function mostRecent($iLimit = null) {
		$this->addDescendingOrderByColumn(JournalEntryPeer::CREATED_AT);
		if($iLimit) {
			$this->setLimit($iLimit);
		}
		return $this;
	}
	
	public function filterByDate($iYear, $iMonth, $iDay) {
		$this->add('YEAR(created_at)', (int)$iYear);
		$this->add('MONTH(created_at)', (int)$iMonth);
		$this->add('DAY(created_at)', (int)$iDay);
		return $this;
	}
	
	public function excludeDraft() {
		return $this->filterByIsPublished(true);
	}
}

