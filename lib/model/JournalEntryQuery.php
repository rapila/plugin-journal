<?php


/**
 * @package    propel.generator.model
 */
class JournalEntryQuery extends BaseJournalEntryQuery {

	public function filterByNavigationItem($oNavigationItem = null) {
		if($oNavigationItem === null) {
			$oNavigationItem = FrontendManager::$CURRENT_NAVIGATION_ITEM;
		}
		$aData = array();
		if(is_array($oNavigationItem)) {
			$aData = $oNavigationItem;
		} else if($oNavigationItem instanceof VirtualNavigationItem) {
			$aData = $oNavigationItem->getData();
		} 
		if(isset($aData['year'])) {
			$this->add('YEAR(CREATED_AT)', $aData['year']);
		} else {
			// get most recent journal entry
			return $this;
		}
		if(isset($aData['month'])) {
			$this->add('MONTH(CREATED_AT)', $aData['month']);
		}
		if(isset($aData['day'])) {
			$this->add('DAY(CREATED_AT)', $aData['day']);
		}
		if(isset($aData['slug'])) {
			$this->add(JournalEntyrPeer::SLUG, $aData['title_normalized']);
		}
		return $this;
	}
	
	public function excludeDraft() {
		return $this->filterByIsPublished(true);
	}
}

