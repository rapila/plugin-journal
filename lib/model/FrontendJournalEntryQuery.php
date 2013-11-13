<?php

/**
 * @package model
 * @subpackage rapila-plugin-journal
 */	
class FrontendJournalEntryQuery extends JournalEntryQuery {
	
	public static function create($sModelAlias = null, $oCriteria = null) {
		$oQuery = JournalEntryQuery::create($sModelAlias, $oCriteria);
		if(Manager::getCurrentPrefix() !== 'preview') {
			return $oQuery->excludeDraft();
		}
		return $oQuery;
	}
}

