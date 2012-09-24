<?php
/**
 * @package modules.widget
 */
class JournalInputWidgetModule extends WidgetModule {
	
	public function getJournals($iJournalIdsConfigured = null) {
		$oQuery = JournalQuery::create()->distinct();
		if($iJournalIdsConfigured !== null) {
			$oQuery->filterById($iJournalIdsConfigured);
		}
		$aResult = $oQuery->orderByName()->find()->toKeyValue('Id', 'Name');
		return $aResult;
	}
}