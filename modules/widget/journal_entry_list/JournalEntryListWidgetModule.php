<?php
/**
 * @package modules.widget
 */
class JournalEntryListWidgetModule extends WidgetModule {

	private $oListWidget;
	private $oLanguageFilter;
	public $oDelegateProxy;
	
	
	public function __construct() {
		$this->oListWidget = new ListWidgetModule();
		$this->oDelegateProxy = new CriteriaListWidgetDelegate($this, "JournalEntry", "title", "asc");
		$this->oListWidget->setDelegate($this->oDelegateProxy);
	}
	
	public function doWidget() {
		return $this->oListWidget->doWidget('journal_entry_list');
	}

	public function getColumnIdentifiers() {
		return array('id', 'title_truncated', 'created_at_formatted', 'count_comments', 'journal_name', 'delete');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array('is_sortable' => true);
		switch($sColumnIdentifier) {
			case 'title_truncated':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.title');
				$aResult['field_name'] = 'title';
				break;
			case 'created_at_formatted':
				$aResult['heading'] = StringPeer::getString('wns.date');
				break;
			case 'count_comments':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.count_comments');
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
			return CriteriaListWidgetDelegate::FILTER_TYPE_IS;
		}
		return null;
	}

	public function getDatabaseColumnForColumn($sColumnIdentifier) {
		if($sColumnIdentifier === 'created_at_formatted') {
			return JournalEntryPeer::CREATED_AT;
		}		
		if($sColumnIdentifier === 'journal_name') {
			return JournalPeer::NAME;
		}
		return null;
	}
	
	public function getJournalName() {
		$oJournal = JournalPeer::retrieveByPK($this->oDelegateProxy->getJournalId());
		if($oJournal) {
			return $oJournal->getName();
		}
		return $this->oDelegateProxy->getJournalId();
	}
	
	public function getCriteria() {
		$oQuery = JournalEntryQuery::create()->joinJournal();
		return $oQuery;
	}
}