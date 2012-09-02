<?php

require_once 'model/om/BaseJournal.php';

/**
 * @package model
 * @subpackage rapila-plugin-journal
 */	
class Journal extends BaseJournal {
	
	public function getJournalPage() {
		return PageQuery::create()->filterByPageType('journal')->joinPageProperty()->useQuery('PageProperty')->filterByName('blog-journal_id')->filterByValue($this->getId())->endUse()->findOne();
	}

	public function possibleYears($bExcludeDraft = true) {
		$oQuery = JournalEntryQuery::create()->distinct()->filterByJournal($this);
		if($bExcludeDraft) {
			$oQuery->excludeDraft();
		}
		$oQuery->clearSelectColumns()->addSelectColumn('YEAR('.JournalEntryPeer::CREATED_AT.')');
		$oQuery->addAsColumn('YEAR', 'YEAR('.JournalEntryPeer::CREATED_AT.')');
		$oQuery->orderBy('YEAR', Criteria::ASC);
		return JournalEntryPeer::doSelectStmt($oQuery)->fetchAll(PDO::FETCH_COLUMN);
	}

	public function possibleMonths($iYear, $bExcludeDraft = true) {
		$oQuery = JournalEntryQuery::create()->distinct()->filterByJournal($this);
		if($bExcludeDraft) {
			$oQuery->excludeDraft();
		}
		$oQuery->clearSelectColumns()->addSelectColumn('MONTH('.JournalEntryPeer::CREATED_AT.')');
		$oQuery->addAsColumn('MONTH', 'MONTH('.JournalEntryPeer::CREATED_AT.')');
		$oYearInterval = new DateInterval('P1Y');
		$oYear = DateTime::createFromFormat('!Y', $iYear);
		$oQuery->filterByCreatedAt(array('min' => clone $oYear, 'max' => $oYear->add($oYearInterval)));
		$oQuery->orderBy('MONTH', Criteria::ASC);
		return JournalEntryPeer::doSelectStmt($oQuery)->fetchAll(PDO::FETCH_COLUMN);
	}

	public function possibleDays($iYear, $iMonth, $bExcludeDraft = true) {
		$oQuery = JournalEntryQuery::create()->distinct()->filterByJournal($this);
		if($bExcludeDraft) {
			$oQuery->excludeDraft();
		}
		$oQuery->clearSelectColumns()->addSelectColumn('DAY('.JournalEntryPeer::CREATED_AT.')');
		$oQuery->addAsColumn('DAY', 'DAY('.JournalEntryPeer::CREATED_AT.')');
		$oMonthInterval = new DateInterval('P1M');
		$oMonth = DateTime::createFromFormat('!Y-m', "$iYear-$iMonth");
		$oQuery->filterByCreatedAt(array('min' => clone $oMonth, 'max' => $oMonth->add($oMonthInterval)));
		$oQuery->orderBy('DAY', Criteria::ASC);
		return JournalEntryPeer::doSelectStmt($oQuery)->fetchAll(PDO::FETCH_COLUMN);
	}
	
}

