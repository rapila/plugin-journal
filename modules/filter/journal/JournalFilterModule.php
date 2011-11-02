<?php

class JournalFilterModule extends FilterModule {
		
	const ENTRY_YEAR = 'entry_year';
	const ENTRY_MONTH = 'entry_month';
	const ENTRY_DAY = 'entry_day';
	const ENTRY_SLUG = 'entry_slug';
	const ENTRY_REQUEST_KEY = 'journal_entry';

	public function onNavigationItemChildrenRequested(NavigationItem $oNavigationItem) {
		$mIdentifier = $oNavigationItem->getIdentifier();
		if($oNavigationItem instanceof PageNavigationItem && $oNavigationItem->getMe()->isOfType('journal')) {
			

		} else if($oNavigationItem instanceof VirtualNavigationItem) {
			
		}
	}

	public function onPageHasBeenSet($oPage, $bIsNotFound, $oNavigationItem) {
		if($bIsNotFound || !$oPage->isOfType('journal')) {
      return;
    }

		if($oNavigationItem instanceof VirtualNavigationItem && in_array($oNavigationItem->getType(), array(self::ENTRY_YEAR, self::ENTRY_MONTH, self::ENTRY_DAY, self::ENTRY_SLUG))) {
			if(self::selectNames($oNavigationItem->getData()) === 1) {
				// $iJournalId =  get journal(-id) from Language object
				$aId = self::selectNames($oNavigationItem->getData(), JournalEntryPeer::ID, $iJournalId);
				$_REQUEST[self::ENTRY_REQUEST_KEY] = (int) $aId[0];
			}
		}
	}
	
	private static function selectNames($aData, $sColumn = null, $iJournalId = null) {
		$oQuery = JournalEntryQuery::create()->distinct()->filterByNavigationItem($aData);
		if($iJournalId !== null) {
			$oQuery->filterByJournalId($iJournalId);
		}
		if(is_string($sColumn)) {
			$oQuery->clearSelectColumns()->addSelectColumn($sColumn);
			return EventPeer::doSelectStmt($oQuery)->fetchAll(PDO::FETCH_COLUMN);
		} else if(is_array($sColumn)) {
			$oQuery->clearSelectColumns();
			foreach($sColumn as $sColumnName) {
				$oQuery->addSelectColumn($sColumnName);
			}
			return EventPeer::doSelectStmt($oQuery)->fetchAll(PDO::FETCH_CLASS);
		}
		return $oQuery->count();
	}
}