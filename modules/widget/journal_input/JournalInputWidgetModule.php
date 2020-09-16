<?php
/**
 * @package modules.widget
 */
class JournalInputWidgetModule extends WidgetModule {

	public function listJournals($iJournalIdsConfigured = null) {
		$oQuery = JournalQuery::create()->distinct();
		if($iJournalIdsConfigured !== null) {
			$oQuery->filterById($iJournalIdsConfigured);
		}
		return WidgetJsonFileModule::jsonBaseObjects($oQuery->orderByName()->find(), array('id', 'name'));
	}
}