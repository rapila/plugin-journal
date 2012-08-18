<?php

/**
 * @package model
 * @subpackage rapila-plugin-journal
 */	
class FrontendJournalEntryQuery extends JournalEntryQuery {
	
	public static function create($sModelAlias = null, $oCriteria = null) {
		if ($oCriteria instanceof FrontendJournalEntryQuery) {
			return $oCriteria;
		}
		$oQuery = new FrontendJournalEntryQuery();
		if (null !== $sModelAlias) {
			$oQuery->setModelAlias($sModelAlias);
		}
		if ($oCriteria instanceof Criteria) {
			$oQuery->mergeWith($oCriteria);
		}
		$oQuery->filterByIsPublished(true);
		return $oQuery;
	}
}

