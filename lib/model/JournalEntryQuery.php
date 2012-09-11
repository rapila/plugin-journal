<?php

/**
 * @package propel.generator.model
 * @subpackage rapila-plugin-journal
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
		if($iYear) {
			$this->add('YEAR(created_at)', (int)$iYear);
		}
		if($iMonth) {
			$this->add('MONTH(created_at)', (int)$iMonth);
		}
		if($iDay) {
			$this->add('DAY(created_at)', (int)$iDay);
		}
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

	public function orderByYearMonthDay() {
		parent::orderByYear(Criteria::DESC)->orderByMonth(Criteria::DESC)->orderByDay(Criteria::DESC);
		return $this;
	}
	
	public function distinctDates() {
		$this->distinct()->clearSelectColumns();
		$this->withColumn('DAY('.JournalEntryPeer::CREATED_AT.')', 'Day');
		$this->withColumn('MONTH('.JournalEntryPeer::CREATED_AT.')', 'Month');
		$this->withColumn('YEAR('.JournalEntryPeer::CREATED_AT.')', 'Year');
		return $this->orderByYearMonthDay()->select('Year', 'Month', 'Day');
	}
	
	public function filterByTagName($sTagName) {
		$aTaggedItems = TagInstanceQuery::create()->filterByTagName($sTagName)->filterByModelName('JournalEntry')->select('TaggedItemId')->find();
		$this->filterById($aTaggedItems, Criteria::IN);
		return $this;
	}

}

