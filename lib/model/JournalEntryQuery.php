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
	
	public function orderByYearMonthDay() {
		parent::orderByYear(Criteria::DESC)->orderByMonth(Criteria::DESC)->orderByDay(Criteria::DESC);
		return $this;
	}
	
	public function filterByTagName($sTagName) {
		$aTaggedItems = TagInstanceQuery::create()->filterByTagName($sTagName)->filterByModelName('JournalEntry')->select('TaggedItemId')->find();
		$this->filterById($aTaggedItems, Criteria::IN);
		return $this;
	}
	
}

