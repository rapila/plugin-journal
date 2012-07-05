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
	
	public function mostRecentByJournalId($mJournalId = null) {
		$this->filterByIsPublished(true)->filterByText('', Criteria::NOT_EQUAL);
		if($mJournalId) {
			$mJournalId = is_array($mJournalId) ? $mJournalId : array($mJournalId);
			$this->filterByJournalId($mJournalId, Criteria::IN);
		}
		return $this->orderByUpdatedAt(Criteria::DESC);
	}
}

