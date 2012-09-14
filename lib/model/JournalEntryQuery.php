<?php

/**
 * @package propel.generator.model
 * @subpackage rapila-plugin-journal
 */
class JournalEntryQuery extends BaseJournalEntryQuery {

	public function mostRecent($iLimit = null) {
		$this->orderByCreatedAt(Criteria::DESC);
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
	
	public function filterByTagName($sTagName) {
		$aTaggedItems = TagInstanceQuery::create()->filterByTagName($sTagName)->filterByModelName('JournalEntry')->select('TaggedItemId')->find();
		$this->filterById($aTaggedItems, Criteria::IN);
		return $this;
	}
	
	public function findDistinctDates() {
		$this->distinct()->clearSelectColumns();
		$this->withColumn('DAY(created_at)', 'Day');
		$this->withColumn('MONTH(created_at)', 'Month');
		$this->withColumn('YEAR(created_at)', 'Year');
		return $this->orderByYearMonthDay()->select('Year', 'Month', 'Day')->find();
	}
	
	public function findAvailableYearsByJournalId($mJournalId) {
		$this->distinct()->clearSelectColumns();
		if($mJournalId) {
			$this->filterByJournalId($mJournalId);
		} 
		$this->withColumn('YEAR(created_at)', 'Year');
		$this->orderBy('Year');
		return $this->select(array('Year'))->find();
	}
	
	public function findAvailableMonthsByJournalId($mJournalId, $iYear) {
		$this->distinct()->clearSelectColumns();
		if($mJournalId) {
			$this->filterByJournalId($mJournalId);
		} 
		$this->withColumn('MONTH(created_at)', 'Month');
		$oYearInterval = new DateInterval('P1Y');
		$oYear = DateTime::createFromFormat('!Y', $iYear);
		$this->filterByCreatedAt(array('min' => clone $oYear, 'max' => $oYear->add($oYearInterval)));
		$this->orderBy('Month');
		return $this->select(array('Month'))->find();
	}
	
	public function findAvailableDaysByJournalId($mJournalId, $iYear, $iMonth) {
		$this->distinct()->clearSelectColumns();
		if($mJournalId) {
			$this->filterByJournalId($mJournalId);
		} 
		$this->withColumn('DAY(created_at)', 'Day');
		$oMonthInterval = new DateInterval('P1M');
		$oMonth = DateTime::createFromFormat('!Y-m', "$iYear-$iMonth");
		$this->filterByCreatedAt(array('min' => clone $oMonth, 'max' => $oMonth->add($oMonthInterval)));
		$this->orderBy('Day');
		return $this->select(array('Day'))->find();
	}
}

