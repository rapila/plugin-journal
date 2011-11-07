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

	public function possibleYears() {
		$oQuery = JournalEntryQuery::create()->distinct();
		$oQuery->clearSelectColumns()->addSelectColumn('YEAR('.JournalEntryPeer::CREATED_AT.')');
		$oQuery->addAsColumn('YEAR', 'YEAR('.JournalEntryPeer::CREATED_AT.')');
		$oQuery->orderBy('YEAR', Criteria::ASC);
		return JournalEntryPeer::doSelectStmt($oQuery)->fetchAll(PDO::FETCH_COLUMN);
	}

	public function possibleMonths($iYear) {
		$oQuery = JournalEntryQuery::create()->distinct();
		$oQuery->clearSelectColumns()->addSelectColumn('MONTH('.JournalEntryPeer::CREATED_AT.')');
		$oQuery->addAsColumn('MONTH', 'MONTH('.JournalEntryPeer::CREATED_AT.')');
		$oYearInterval = new DateInterval('P1Y');
		$oYear = DateTime::createFromFormat('!Y', $iYear);
		$oQuery->filterByCreatedAt(array('min' => clone $oYear, 'max' => $oYear->add($oYearInterval)));
		$oQuery->orderBy('MONTH', Criteria::ASC);
		return JournalEntryPeer::doSelectStmt($oQuery)->fetchAll(PDO::FETCH_COLUMN);
	}

	public function possibleDays($iYear, $iMonth) {
		$oQuery = JournalEntryQuery::create()->distinct();
		$oQuery->clearSelectColumns()->addSelectColumn('DAY('.JournalEntryPeer::CREATED_AT.')');
		$oQuery->addAsColumn('DAY', 'DAY('.JournalEntryPeer::CREATED_AT.')');
		$oMonthInterval = new DateInterval('P1M');
		$oMonth = DateTime::createFromFormat('!Y-m', "$iYear-$iMonth");
		$oQuery->filterByCreatedAt(array('min' => clone $oMonth, 'max' => $oMonth->add($oMonthInterval)));
		$oQuery->orderBy('DAY', Criteria::ASC);
		return JournalEntryPeer::doSelectStmt($oQuery)->fetchAll(PDO::FETCH_COLUMN);
	}
	
} // Journal
