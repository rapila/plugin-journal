<?php

/**
 * @package propel.generator.model
 * @subpackage rapila-plugin-journal
 */
class JournalEntryQuery extends BaseJournalEntryQuery {

	public function mostRecentFirst() {
		return $this->orderByPublishAt(Criteria::DESC);
	}
	
	public function filterByDate($iYear, $iMonth, $iDay) {
		if($iYear) {
			$this->add('YEAR(publish_at)', (int)$iYear);
		}
		if($iMonth) {
			$this->add('MONTH(publish_at)', (int)$iMonth);
		}
		if($iDay) {
			$this->add('DAY(publish_at)', (int)$iDay);
		}
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
	
	public function filterByTagId($aTagId) {
		$aTaggedLinkIds = TagInstanceQuery::create()->filterByTagId($aTagId)->filterByModelName('JournalEntry')->select(array('TaggedItemId'))->find();
		return $this->filterById($aTaggedLinkIds, Criteria::IN);
	}
	
	public function findDistinctDates() {
		$this->distinct()->clearSelectColumns();
		$this->withColumn('DAY(publish_at)', 'Day');
		$this->withColumn('MONTH(publish_at)', 'Month');
		$this->withColumn('YEAR(publish_at)', 'Year');
		return $this->orderByYearMonthDay()->select('Year', 'Month', 'Day')->find();
	}
	
	public function findAvailableYearsByJournalId($mJournalId) {
		$this->distinct()->clearSelectColumns();
		if($mJournalId) {
			$this->filterByJournalId($mJournalId);
		} 
		$this->withColumn('YEAR(publish_at)', 'Year');
		$this->orderBy('Year');
		return $this->select(array('Year'))->find();
	}
	
	public function findAvailableMonthsByJournalId($mJournalId, $iYear) {
		$this->distinct()->clearSelectColumns();
		if($mJournalId) {
			$this->filterByJournalId($mJournalId);
		} 
		$this->withColumn('MONTH(publish_at)', 'Month');
		$oYearInterval = new DateInterval('P1Y');
		$oYear = DateTime::createFromFormat('!Y', $iYear);
		$this->filterByPublishAt(array('min' => clone $oYear, 'max' => $oYear->add($oYearInterval)));
		$this->orderBy('Month');
		return $this->select(array('Month'))->find();
	}
	
	public function findAvailableDaysByJournalId($mJournalId, $iYear, $iMonth) {
		$this->distinct()->clearSelectColumns();
		if($mJournalId) {
			$this->filterByJournalId($mJournalId);
		} 
		$this->withColumn('DAY(publish_at)', 'Day');
		$oMonthInterval = new DateInterval('P1M');
		$oMonth = DateTime::createFromFormat('!Y-m', "$iYear-$iMonth");
		$this->filterByPublishAt(array('min' => clone $oMonth, 'max' => $oMonth->add($oMonthInterval)));
		$this->orderBy('Day');
		return $this->select(array('Day'))->find();
	}
}

