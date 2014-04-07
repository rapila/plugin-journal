<?php
/**
 * @package modules.widget
 */
class JournalEntryListWidgetModule extends WidgetModule {

	private $oListWidget;
	private $oDelegateProxy;
	private $oTagFilter;
	
	public function __construct() {
		$this->oListWidget = new ListWidgetModule();
		$this->oDelegateProxy = new CriteriaListWidgetDelegate($this, "JournalEntry", "publish_at", "desc");
		$this->oListWidget->setDelegate($this->oDelegateProxy);
		$this->oListWidget->setSetting('row_model_drag_and_drop_identifier', "id");
		$this->oTagFilter = WidgetModule::getWidget('tag_input', null, true);
		$this->oTagFilter->setSetting('model_name', 'JournalEntry');
	}
	
	public function getDelegate() {
		return $this->oDelegateProxy;
	}
	
	public function getList() {
		return $this->oListWidget;
	}
	
	public function doWidget() {
		return $this->oListWidget->doWidget('journal_entry_list');
	}

	public function getColumnIdentifiers() {
		return array('id', 'title_truncated', 'publish_at_formatted', 'count_comments', 'is_published', 'has_tags', 'journal_name', 'delete');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array('is_sortable' => true);
		switch($sColumnIdentifier) {
			case 'title_truncated':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.title');
				$aResult['field_name'] = 'title';
				break;
			case 'publish_at_formatted':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.publish_at');
				break;
			case 'is_published':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.is_published');
				break;
			case 'count_comments':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.count_comments');
				$aResult['is_sortable'] = false;
				break;
			case 'has_tags':
				$aResult['heading'] = '';
				$aResult['heading_filter'] = array('tag_input', $this->oTagFilter->getSessionKey());
				$aResult['is_sortable'] = false;
				break;
			case 'journal_name':
				$aResult['heading'] = StringPeer::getString('wns.journal.name');
				break;
			case 'delete':
				$aResult['heading'] = ' ';
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_ICON;
				$aResult['field_name'] = 'trash';
				$aResult['is_sortable'] = false;
				break;
		}
		return $aResult;
	}

	public function getFilterTypeForColumn($sColumnIdentifier) {
		if($sColumnIdentifier === 'journal_id') {
			return CriteriaListWidgetDelegate::FILTER_TYPE_IN;
		}
		if($sColumnIdentifier === 'has_tags') {
			return CriteriaListWidgetDelegate::FILTER_TYPE_MANUAL;
		}
		return null;
	}

	public function getDatabaseColumnForColumn($sColumnIdentifier) {
		if($sColumnIdentifier === 'publish_at_formatted') {
			return JournalEntryPeer::PUBLISH_AT;
		}
		if($sColumnIdentifier === 'journal_name') {
			return JournalPeer::NAME;
		}
		return null;
	}
	
	public function toggleIsPublished($aRowData) {
		$oJournalEntry = JournalEntryQuery::create()->findPk($aRowData['id']);
		if($oJournalEntry) {
			$oJournalEntry->setIsPublished(!$oJournalEntry->getIsPublished());
			$oJournalEntry->save();
		}
	}
	
	public function getJournalId() {
		return $this->oDelegateProxy->getJournalId();
	}

	public function getJournalName() {
		$iId = $this->oDelegateProxy->getJournalId();
		if(is_array($iId)) {
			$iId = @$iId[0];
		}
		$oJournal = JournalQuery::create()->findPk($iId);
		if($oJournal) {
			return $oJournal->getName();
		}
		return $this->oDelegateProxy->getJournalId();
	}
	
	public function getJournalHasEntries($iJournalId) {
		return JournalEntryQuery::create()->filterByJournalId($iJournalId)->count() > 0;
	}
	
	public function getRemoveJournalIntriesByJournalId($iJournalId) {
		return JournalEntryQuery::create()->filterByJournalId($iJournalId)->delete();
	}
	
	
	public function getCriteria() {
		$oQuery = JournalEntryQuery::create()->joinJournal();
		if($this->oTagFilter && $this->oDelegateProxy->getListSettings()->getFilterColumnValue('has_tags') !== CriteriaListWidgetDelegate::SELECT_ALL) {
			$oQuery->filterByTagId($this->oDelegateProxy->getListSettings()->getFilterColumnValue('has_tags'));
		}
		return $oQuery;
	}
}
