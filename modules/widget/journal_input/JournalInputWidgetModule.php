<?php
/**
 * @package modules.widget
 */
class JournalInputWidgetModule extends WidgetModule {
	
	public function getJournals() {
		$aResult = JournalQuery::create()->distinct()->orderByName()->find()->toKeyValue('Id', 'Name');
		return $aResult;
	}
}