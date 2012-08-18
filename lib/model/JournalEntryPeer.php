<?php

	// include base peer class
	require_once 'model/om/BaseJournalEntryPeer.php';
	
	// include object class
	include_once 'model/JournalEntry.php';

/**
 * @package model
 * @subpackage rapila-plugin-journal
 */ 
class JournalEntryPeer extends BaseJournalEntryPeer {
	
	public static function addSearchToCriteria($sSearch, $oCriteria) {
		$oSearchCriterion = $oCriteria->getNewCriterion(self::TITLE, "%$sSearch%", Criteria::LIKE);
		$oSearchCriterion->addOr($oCriteria->getNewCriterion(self::TEXT, "%$sSearch%", Criteria::LIKE));
		$oCriteria->add($oSearchCriterion);
	}

}

